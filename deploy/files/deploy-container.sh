#!/bin/sh
# Rolls out a tagged application image on an already-bootstrapped host.
# Pulls and starts the image, verifies the internal health endpoint, records
# deployment metadata, and restores the previous image if the rollout fails.
set -eu

require_var() {
    name="$1"
    eval "value=\${$name-}"
    if [ -z "$value" ]; then
        echo "Missing required environment variable: $name" >&2
        exit 1
    fi
}

require_var APP_ROOT
require_var PROJECT_NAME
require_var DOCKER_HUB_REPO
require_var IMAGE_TAG
require_var DEPLOY_TAG
require_var ROLLBACK_TAG
require_var LOCK_PATH
require_var INTERNAL_HEALTHCHECK_URL

if ! printf '%s' "$IMAGE_TAG" | grep -Eq '^[A-Za-z0-9_][A-Za-z0-9_.-]{0,127}$'; then
    echo "IMAGE_TAG must be a valid Docker tag" >&2
    exit 1
fi

if [ ! -f "$APP_ROOT/compose.yaml" ]; then
    echo "Missing compose file at $APP_ROOT/compose.yaml" >&2
    exit 1
fi

if [ "${DEPLOY_LOCK_HELD-}" != true ]; then
    lock_dir=$(dirname "$LOCK_PATH")
    mkdir -p "$lock_dir"
    umask 077
    exec 9>"$LOCK_PATH"
    chmod 600 "$LOCK_PATH"
    flock 9
fi

new_ref="$DOCKER_HUB_REPO:$IMAGE_TAG"
remove_source_tag() {
    docker image rm "$new_ref" >/dev/null 2>&1 || true
}

write_deployed_image() {
    deployed_image_file="$APP_ROOT/deployed-image.env"
    deployed_image_tmp="$APP_ROOT/.deployed-image.env.tmp"
    container_id=$(docker compose -p "$PROJECT_NAME" -f "$APP_ROOT/compose.yaml" ps -q app)

    {
        printf 'IMAGE_TAG=%s\n' "$IMAGE_TAG"
        printf 'IMAGE_REF=%s\n' "$new_ref"
        printf 'IMAGE_ID=%s\n' "$new_image_id"
        printf 'IMAGE_DIGEST=%s\n' "$new_image_digest"
        printf 'CONTAINER_ID=%s\n' "$container_id"
    } > "$deployed_image_tmp"
    chmod 600 "$deployed_image_tmp"
    mv "$deployed_image_tmp" "$deployed_image_file"
}

echo "Pulling image $new_ref"
docker pull "$new_ref"
new_image_id=$(docker image inspect --format '{{.Id}}' "$new_ref")
new_image_digest=$(docker image inspect --format '{{range .RepoDigests}}{{println .}}{{end}}' "$new_ref" | sed -n '1p')

current_container_id=$(docker compose -p "$PROJECT_NAME" -f "$APP_ROOT/compose.yaml" ps -q app || true)
current_image_id=""

if [ -n "$current_container_id" ]; then
    current_image_id=$(docker inspect --format '{{.Image}}' "$current_container_id")
fi

if [ -n "$current_image_id" ] && [ "$current_image_id" = "$new_image_id" ]; then
    if curl --silent --show-error --location --fail --max-time 8 "$INTERNAL_HEALTHCHECK_URL" >/dev/null 2>&1; then
        write_deployed_image
        remove_source_tag
        echo "ALREADY_CURRENT: $new_ref"
        exit 0
    fi
    echo "Current image failed its internal health check; recreating the service" >&2
fi

if [ -n "$current_image_id" ]; then
    docker image rm "$ROLLBACK_TAG" >/dev/null 2>&1 || true
    docker tag "$current_image_id" "$ROLLBACK_TAG"
fi

docker tag "$new_image_id" "$DEPLOY_TAG"

if docker compose -p "$PROJECT_NAME" -f "$APP_ROOT/compose.yaml" up -d --no-deps --force-recreate --pull never --wait app; then
    deployment_healthy=true
    if [ -n "${INTERNAL_HEALTHCHECK_URL-}" ]; then
        deployment_healthy=false
        attempt=1
        max_attempts=10
        while [ "$attempt" -le "$max_attempts" ]; do
            if curl --silent --show-error --location --fail --max-time 8 "$INTERNAL_HEALTHCHECK_URL" >/dev/null 2>&1; then
                deployment_healthy=true
                break
            fi
            if [ "$attempt" -eq "$max_attempts" ]; then
                echo "Internal health check failed after deployment: $INTERNAL_HEALTHCHECK_URL" >&2
                break
            fi
            attempt=$((attempt + 1))
            sleep 6
        done
    fi

    if [ "$deployment_healthy" = true ]; then
        write_deployed_image
        remove_source_tag
        docker image prune --force >/dev/null 2>&1 || true
        echo "DEPLOYED: $new_ref"
        exit 0
    fi
fi

echo "Deployment failed, attempting rollback" >&2
if docker image inspect "$ROLLBACK_TAG" >/dev/null 2>&1; then
    docker tag "$ROLLBACK_TAG" "$DEPLOY_TAG"
    if [ "${TRANSACTIONAL_ROLLBACK-}" != true ]; then
        if docker compose -p "$PROJECT_NAME" -f "$APP_ROOT/compose.yaml" up -d --no-deps --force-recreate --pull never --wait app; then
            if curl --silent --show-error --location --fail --max-time 8 "$INTERNAL_HEALTHCHECK_URL" >/dev/null 2>&1; then
                echo "Rollback restored a healthy previous application image" >&2
            else
                echo "Rollback restored the previous image, but its internal health check failed" >&2
            fi
        else
            echo "Rollback failed to recreate the previous application image" >&2
        fi
    fi
fi

remove_source_tag
docker image prune --force >/dev/null 2>&1 || true
exit 1
