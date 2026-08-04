<?php
declare(strict_types=1);

namespace App\Cache\Engine;

use Aws\S3\S3Client;
use Aws\S3\S3ClientInterface;
use Cake\Cache\CacheEngine;
use DateInterval;
use LogicException;
use Throwable;

class S3CacheEngine extends CacheEngine
{
    /**
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        'duration' => 3600,
        'groups' => [],
        'prefix' => 'cache/',
        'bucket' => null,
        'region' => null,
        'key' => null,
        'secret' => null,
        's3ClientConfig' => [],
    ];

    protected S3ClientInterface $client;
    protected string $bucket;

    /**
     * @param array<string, mixed> $config Configuration options.
     */
    public function init(array $config = []): bool
    {
        parent::init($config);

        $bucket = (string)($this->getConfig('bucket') ?? '');
        $region = (string)($this->getConfig('region') ?? '');
        if ($bucket === '' || $region === '') {
            return false;
        }
        $this->bucket = $bucket;

        $configuredClient = $this->getConfig('client');
        if ($configuredClient instanceof S3ClientInterface) {
            $this->client = $configuredClient;

            return true;
        }

        $clientConfig = array_replace(
            [
                'version' => 'latest',
                'region' => $region,
            ],
            (array)$this->getConfig('s3ClientConfig'),
        );

        $key = (string)($this->getConfig('key') ?? '');
        $secret = (string)($this->getConfig('secret') ?? '');
        if ($key !== '' && $secret !== '') {
            $clientConfig['credentials'] = [
                'key' => $key,
                'secret' => $secret,
            ];
        }

        $this->client = new S3Client($clientConfig);

        return true;
    }

    /**
     * Store a value in S3.
     *
     * @param string $key Cache key.
     * @param mixed $value Cache value.
     * @param \DateInterval|int|null $ttl Optional TTL.
     * @return bool
     */
    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        try {
            $payload = $this->encodePayload($value, $ttl);
            $this->client->putObject([
                'Bucket' => $this->bucket,
                'Key' => $this->_key($key),
                'Body' => $payload,
            ]);

            return true;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Retrieve a value from S3.
     *
     * @param string $key Cache key.
     * @param mixed $default Default value.
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        try {
            $result = $this->client->getObject([
                'Bucket' => $this->bucket,
                'Key' => $this->_key($key),
            ]);

            $body = (string)$result['Body']->getContents();
            $decoded = $this->decodePayload($body);
            if ($decoded === null) {
                return $default;
            }

            if ($decoded['expires'] < time()) {
                $this->delete($key);

                return $default;
            }

            return $decoded['value'];
        } catch (Throwable) {
            return $default;
        }
    }

    /**
     * Delete a single cache key from S3.
     *
     * @param string $key Cache key.
     * @return bool
     */
    public function delete(string $key): bool
    {
        try {
            $this->client->deleteObject([
                'Bucket' => $this->bucket,
                'Key' => $this->_key($key),
            ]);

            return true;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Remove all cache keys managed by this cache prefix.
     *
     * @return bool
     */
    public function clear(): bool
    {
        $params = [
            'Bucket' => $this->bucket,
            'Prefix' => (string)$this->getConfig('prefix'),
        ];

        try {
            do {
                $result = $this->client->listObjectsV2($params);
                $contents = (array)($result['Contents'] ?? []);

                if ($contents !== []) {
                    $objects = array_map(
                        static fn(array $object): array => ['Key' => (string)$object['Key']],
                        $contents,
                    );
                    $this->client->deleteObjects([
                        'Bucket' => $this->bucket,
                        'Delete' => ['Objects' => $objects],
                    ]);
                }

                $isTruncated = (bool)($result['IsTruncated'] ?? false);
                $nextToken = $result['NextContinuationToken'] ?? null;
                if ($isTruncated && is_string($nextToken) && $nextToken !== '') {
                    $params['ContinuationToken'] = $nextToken;
                } else {
                    unset($params['ContinuationToken']);
                }
            } while (!empty($isTruncated));

            return true;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Clear a configured cache group.
     *
     * @param string $group Group name.
     * @return bool
     */
    public function clearGroup(string $group): bool
    {
        if (!in_array($group, $this->groups(), true)) {
            return false;
        }

        return $this->clear();
    }

    /**
     * Increment is not atomic on S3.
     *
     * @param string $key Cache key.
     * @param int $offset Increment amount.
     * @return int|false
     */
    public function increment(string $key, int $offset = 1): int|false
    {
        throw new LogicException('S3 cache values cannot be atomically incremented.');
    }

    /**
     * Decrement is not atomic on S3.
     *
     * @param string $key Cache key.
     * @param int $offset Decrement amount.
     * @return int|false
     */
    public function decrement(string $key, int $offset = 1): int|false
    {
        throw new LogicException('S3 cache values cannot be atomically decremented.');
    }

    /**
     * Encode a cache payload for S3 storage.
     *
     * @param mixed $value Value to store.
     * @param \DateInterval|int|null $ttl Optional TTL.
     * @return string
     */
    protected function encodePayload(mixed $value, DateInterval|int|null $ttl): string
    {
        $encodedValue = base64_encode(serialize($value));
        $expires = time() + $this->duration($ttl);

        return json_encode([
            'expires' => $expires,
            'value' => $encodedValue,
        ], JSON_THROW_ON_ERROR);
    }

    /**
     * Decode an S3 payload into cache metadata.
     *
     * @param string $body Raw object body.
     * @return array{expires:int, value:mixed}|null
     */
    protected function decodePayload(string $body): ?array
    {
        try {
            $payload = json_decode($body, true, flags: JSON_THROW_ON_ERROR);
        } catch (Throwable) {
            return null;
        }

        if (
            !is_array($payload) ||
            !isset($payload['expires'], $payload['value']) ||
            !is_int($payload['expires']) ||
            !is_string($payload['value'])
        ) {
            return null;
        }

        $serializedValue = base64_decode($payload['value'], true);
        if ($serializedValue === false) {
            return null;
        }

        set_error_handler(static fn(): bool => true);
        try {
            // Cache payloads can contain framework objects (for example translators).
            // Restricting allowed classes breaks normal Cake cache usage.
            $value = unserialize($serializedValue, ['allowed_classes' => true]);
        } finally {
            restore_error_handler();
        }

        if ($value === false && $serializedValue !== 'b:0;') {
            return null;
        }

        return [
            'expires' => $payload['expires'],
            'value' => $value,
        ];
    }
}
