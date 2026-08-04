<?php
declare(strict_types=1);

namespace App\Test\TestCase\Cache\Engine;

use App\Cache\Engine\S3CacheEngine;
use Aws\History;
use Aws\Middleware;
use Aws\MockHandler;
use Aws\Result;
use Aws\S3\S3Client;
use Cake\TestSuite\TestCase;
use DateInterval;
use GuzzleHttp\Psr7\Utils;
use LogicException;
use RuntimeException;
use stdClass as PhpStdClass;

class S3CacheEngineTest extends TestCase
{
    /**
     * @param array<int, mixed> $queue
     * @param \Aws\History $history
     * @param array<string, mixed> $overrides
     * @return \App\Cache\Engine\S3CacheEngine
     */
    private function buildEngine(array $queue, History $history, array $overrides = []): S3CacheEngine
    {
        $mock = new MockHandler($queue);
        $client = new S3Client([
            'version' => 'latest',
            'region' => 'us-east-1',
            'credentials' => [
                'key' => 'test-key',
                'secret' => 'test-secret',
            ],
            'handler' => $mock,
        ]);

        $history->clear();
        $client->getHandlerList()->appendSign(Middleware::history($history), 'history');

        $engine = new S3CacheEngine();
        $config = array_merge([
            'client' => $client,
            'bucket' => 'unit-test-bucket',
            'region' => 'us-east-1',
            'prefix' => 'cache/',
            'duration' => 60,
        ], $overrides);

        $this->assertTrue($engine->init($config));

        return $engine;
    }

    private function encodePayload(mixed $value, int $expires): string
    {
        return json_encode([
            'expires' => $expires,
            'value' => base64_encode(serialize($value)),
        ], JSON_THROW_ON_ERROR);
    }

    public function testInitReturnsFalseWithoutRequiredConfig(): void
    {
        $missingBucket = new S3CacheEngine();
        $this->assertFalse($missingBucket->init(['region' => 'us-east-1']));

        $missingRegion = new S3CacheEngine();
        $this->assertFalse($missingRegion->init(['bucket' => 'missing-region']));
    }

    public function testSetStoresEncodedPayloadAndKeyPrefix(): void
    {
        $history = new History();
        $before = time();

        $engine = $this->buildEngine([new Result([])], $history);

        $this->assertTrue($engine->set('my-key', ['status' => 'ok'], 30));

        $entries = $history->toArray();
        $this->assertCount(1, $entries);
        $command = $entries[0]['command'];
        $this->assertSame('PutObject', $command->getName());
        $params = $command->toArray();
        $this->assertSame('unit-test-bucket', $params['Bucket']);
        $this->assertSame('cache/my-key', $params['Key']);

        $payload = json_decode((string)$params['Body'], true);
        $this->assertIsArray($payload);
        $this->assertIsInt($payload['expires'] ?? null);
        $this->assertIsString($payload['value'] ?? null);

        $after = time();
        $this->assertGreaterThanOrEqual($before + 30, $payload['expires']);
        $this->assertLessThanOrEqual($after + 30, $payload['expires']);
    }

    public function testGetReturnsDecodedValue(): void
    {
        $history = new History();
        $engine = $this->buildEngine([
            new Result([
                'Body' => Utils::streamFor($this->encodePayload(['value' => 42], time() + 300)),
            ]),
        ], $history);

        $this->assertSame(['value' => 42], $engine->get('cache-key'));
        $entries = $history->toArray();
        $this->assertCount(1, $entries);
        $this->assertSame('GetObject', $entries[0]['command']->getName());
    }

    public function testGetReturnsDefaultWhenPayloadCannotBeDecoded(): void
    {
        $history = new History();
        $engine = $this->buildEngine([
            new Result([
                'Body' => Utils::streamFor('{"bad":"payload"}'),
            ]),
        ], $history);

        $this->assertSame('fallback', $engine->get('cache-key', 'fallback'));
    }

    public function testGetDeletesExpiredValueAndReturnsDefault(): void
    {
        $history = new History();
        $engine = $this->buildEngine([
            new Result([
                'Body' => Utils::streamFor($this->encodePayload('old', time() - 1)),
            ]),
            new Result([]),
        ], $history);

        $this->assertSame('fallback', $engine->get('expired-key', 'fallback'));
        $entries = $history->toArray();
        $this->assertCount(2, $entries);
        $this->assertSame('GetObject', $entries[0]['command']->getName());
        $this->assertSame('DeleteObject', $entries[1]['command']->getName());
        $this->assertSame('cache/expired-key', $entries[1]['command']->toArray()['Key']);
    }

    public function testDeleteReturnsFalseOnClientFailure(): void
    {
        $history = new History();
        $engine = $this->buildEngine([new RuntimeException('delete failed')], $history);

        $this->assertFalse($engine->delete('broken-key'));
    }

    public function testClearDeletesAllObjectsUnderPrefix(): void
    {
        $history = new History();
        $engine = $this->buildEngine([
            new Result([
                'IsTruncated' => false,
                'Contents' => [
                    ['Key' => 'cache/one'],
                    ['Key' => 'cache/two'],
                ],
            ]),
            new Result([]),
        ], $history);

        $this->assertTrue($engine->clear());

        $entries = $history->toArray();
        $this->assertCount(2, $entries);
        $this->assertSame('ListObjectsV2', $entries[0]['command']->getName());
        $this->assertSame('DeleteObjects', $entries[1]['command']->getName());

        $params = $entries[1]['command']->toArray();
        $keys = array_map(
            static fn(array $obj): string => (string)$obj['Key'],
            (array)($params['Delete']['Objects'] ?? []),
        );
        sort($keys);
        $this->assertSame(['cache/one', 'cache/two'], $keys);
    }

    public function testClearGroupRequiresConfiguredGroup(): void
    {
        $history = new History();
        $engine = $this->buildEngine([
            new Result([
                'IsTruncated' => false,
                'Contents' => [],
            ]),
        ], $history, ['groups' => ['alpha']]);

        $this->assertFalse($engine->clearGroup('missing'));
        $this->assertTrue($engine->clearGroup('alpha'));
        $entries = $history->toArray();
        $this->assertCount(1, $entries);
        $this->assertSame('ListObjectsV2', $entries[0]['command']->getName());
    }

    public function testIncrementAndDecrementThrowLogicException(): void
    {
        $history = new History();
        $engine = $this->buildEngine([], $history);

        $this->expectException(LogicException::class);
        $engine->increment('counter');
    }

    public function testDecrementThrowsLogicException(): void
    {
        $history = new History();
        $engine = $this->buildEngine([], $history);

        $this->expectException(LogicException::class);
        $engine->decrement('counter');
    }

    public function testSetAcceptsDateIntervalTtl(): void
    {
        $history = new History();
        $engine = $this->buildEngine([new Result([])], $history);

        $this->assertTrue($engine->set('interval-key', 123, new DateInterval('PT5M')));
        $entries = $history->toArray();
        $this->assertCount(1, $entries);

        $payload = json_decode((string)$entries[0]['command']->toArray()['Body'], true);
        $this->assertIsArray($payload);
        $this->assertGreaterThan(time(), (int)$payload['expires']);
    }

    public function testSetReturnsFalseWhenPutObjectFails(): void
    {
        $history = new History();
        $engine = $this->buildEngine([new RuntimeException('put failed')], $history);

        $this->assertFalse($engine->set('write-fail', 'value'));
    }

    public function testGetReturnsDefaultWhenGetObjectFails(): void
    {
        $history = new History();
        $engine = $this->buildEngine([new RuntimeException('get failed')], $history);

        $this->assertSame('fallback', $engine->get('read-fail', 'fallback'));
    }

    public function testGetReturnsStoredBooleanFalseValue(): void
    {
        $history = new History();
        $engine = $this->buildEngine([
            new Result([
                'Body' => Utils::streamFor($this->encodePayload(false, time() + 300)),
            ]),
        ], $history);

        $this->assertFalse($engine->get('false-value', 'fallback'));
    }

    public function testGetRestoresObjectValues(): void
    {
        $history = new History();
        $object = new PhpStdClass();
        $object->name = 'translator-like-object';

        $engine = $this->buildEngine([
            new Result([
                'Body' => Utils::streamFor($this->encodePayload($object, time() + 300)),
            ]),
        ], $history);

        $result = $engine->get('object-value');
        $this->assertInstanceOf(PhpStdClass::class, $result);
        $this->assertSame('translator-like-object', $result->name);
    }

    public function testGetReturnsDefaultWhenPayloadJsonIsInvalid(): void
    {
        $history = new History();
        $engine = $this->buildEngine([
            new Result([
                'Body' => Utils::streamFor('{not-json'),
            ]),
        ], $history);

        $this->assertSame('fallback', $engine->get('bad-json', 'fallback'));
    }

    public function testGetReturnsDefaultWhenPayloadBase64IsInvalid(): void
    {
        $history = new History();
        $payload = json_encode([
            'expires' => time() + 300,
            'value' => '***not-base64***',
        ], JSON_THROW_ON_ERROR);

        $engine = $this->buildEngine([
            new Result([
                'Body' => Utils::streamFor($payload),
            ]),
        ], $history);

        $this->assertSame('fallback', $engine->get('bad-base64', 'fallback'));
    }

    public function testGetReturnsDefaultWhenPayloadUnserializeFails(): void
    {
        $history = new History();
        $payload = json_encode([
            'expires' => time() + 300,
            'value' => base64_encode('not-a-serialized-value'),
        ], JSON_THROW_ON_ERROR);

        $engine = $this->buildEngine([
            new Result([
                'Body' => Utils::streamFor($payload),
            ]),
        ], $history);

        $this->assertSame('fallback', $engine->get('bad-unserialize', 'fallback'));
    }

    public function testClearReturnsFalseWhenListFails(): void
    {
        $history = new History();
        $engine = $this->buildEngine([new RuntimeException('list failed')], $history);

        $this->assertFalse($engine->clear());
    }

    public function testClearHandlesTruncatedResultsWithContinuationToken(): void
    {
        $history = new History();
        $engine = $this->buildEngine([
            new Result([
                'IsTruncated' => true,
                'NextContinuationToken' => 'token-1',
                'Contents' => [
                    ['Key' => 'cache/one'],
                ],
            ]),
            new Result([]),
            new Result([
                'IsTruncated' => false,
                'Contents' => [
                    ['Key' => 'cache/two'],
                ],
            ]),
            new Result([]),
        ], $history);

        $this->assertTrue($engine->clear());

        $entries = $history->toArray();
        $this->assertCount(4, $entries);
        $this->assertSame('ListObjectsV2', $entries[0]['command']->getName());
        $this->assertSame('DeleteObjects', $entries[1]['command']->getName());
        $this->assertSame('ListObjectsV2', $entries[2]['command']->getName());
        $this->assertSame('DeleteObjects', $entries[3]['command']->getName());

        $secondListParams = $entries[2]['command']->toArray();
        $this->assertSame('token-1', $secondListParams['ContinuationToken']);
    }

    public function testInitBuildsClientWhenOnlyRequiredFieldsExist(): void
    {
        $engine = new S3CacheEngine();

        $this->assertTrue($engine->init([
            'bucket' => 'unit-test-bucket',
            'region' => 'us-east-1',
            'prefix' => 'cache/',
        ]));
    }

    public function testInitBuildsClientWhenStaticCredentialsExist(): void
    {
        $engine = new S3CacheEngine();

        $this->assertTrue($engine->init([
            'bucket' => 'unit-test-bucket',
            'region' => 'us-east-1',
            'prefix' => 'cache/',
            'key' => 'abc',
            'secret' => 'def',
        ]));
    }
}
