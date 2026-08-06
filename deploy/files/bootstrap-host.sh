#!/bin/sh
set -eu

require_var() {
    name="$1"
    eval "value=\${$name-}"
    if [ -z "$value" ]; then
        echo "Missing required environment variable: $name" >&2
        exit 1
    fi
}

stage_dir="${1-}"
if [ -z "$stage_dir" ]; then
    echo "Usage: $0 STAGE_DIR" >&2
    exit 1
fi

require_var APP_ROOT
require_var PROJECT_NAME
require_var DOCKER_HUB_REPO
require_var IMAGE_TAG
require_var DEPLOY_TAG
require_var ROLLBACK_TAG
require_var LOCK_PATH
require_var INTERNAL_HEALTHCHECK_URL

if [ ! -d "$stage_dir" ]; then
    echo "Missing staging directory: $stage_dir" >&2
    exit 1
fi

if ! printf '%s' "$IMAGE_TAG" | grep -Eq '^[A-Za-z0-9_][A-Za-z0-9_.-]{0,127}$'; then
    echo "IMAGE_TAG must be a valid Docker tag" >&2
    exit 1
fi

if ! printf '%s' "$APP_ROOT" | grep -Eq '^/opt/[A-Za-z0-9][A-Za-z0-9._-]*$'; then
    echo "APP_ROOT must stay under /opt" >&2
    exit 1
fi

if ! printf '%s' "$PROJECT_NAME" | grep -Eq '^[A-Za-z0-9][A-Za-z0-9._-]*$'; then
    echo "PROJECT_NAME contains invalid characters" >&2
    exit 1
fi

if ! printf '%s' "$INTERNAL_HEALTHCHECK_URL" | grep -Eq '^http://127\.0\.0\.1:[0-9]+/.*$'; then
    echo "INTERNAL_HEALTHCHECK_URL must target 127.0.0.1" >&2
    exit 1
fi

stage_prefix="/tmp/${PROJECT_NAME}-deploy-"
case "$stage_dir" in
    "$stage_prefix"*) stage_suffix=${stage_dir#"$stage_prefix"} ;;
    *) stage_suffix="" ;;
esac
case "$stage_suffix" in
    ""|*[!A-Za-z0-9._-]*)
        echo "STAGE_DIR must be a project deployment directory under /tmp" >&2
        exit 1
        ;;
esac

docker_config_dir=""
cleanup() {
    if [ -n "$docker_config_dir" ] && [ -d "$docker_config_dir" ]; then
        rm -rf "$docker_config_dir"
    fi
    rm -rf "$stage_dir"
}
trap cleanup EXIT HUP INT TERM

snapshot_file() {
    source_file="$1"
    backup_file="$2"
    if [ -f "$source_file" ]; then
        cp -p "$source_file" "$backup_file"
        return 0
    fi
    return 1
}

restore_file() {
    existed="$1"
    backup_file="$2"
    target_file="$3"
    if [ "$existed" = true ]; then
        cp -p "$backup_file" "$target_file"
    else
        rm -f "$target_file"
    fi
}

wait_for_internal_health() {
    attempt=1
    max_attempts=10
    while [ "$attempt" -le "$max_attempts" ]; do
        if curl --silent --show-error --location --fail --max-time 8 "$INTERNAL_HEALTHCHECK_URL" >/dev/null 2>&1; then
            return 0
        fi
        attempt=$((attempt + 1))
        if [ "$attempt" -le "$max_attempts" ]; then
            sleep 6
        fi
    done
    return 1
}

for required_file in compose.yaml application.env nginx-server.conf deploy-container.sh; do
    if [ ! -f "$stage_dir/$required_file" ]; then
        echo "Missing staged file: $stage_dir/$required_file" >&2
        exit 1
    fi
done

lock_dir=$(dirname "$LOCK_PATH")
mkdir -p "$lock_dir"
umask 077
exec 9>"$LOCK_PATH"
chmod 600 "$LOCK_PATH"
flock 9

dnf install -y \
    nginx \
    docker \
    docker-compose-plugin \
    python3 \
    util-linux \
    curl

systemctl enable --now docker
if ! systemctl enable --now nginx; then
    echo "::warning title=Nginx startup failed::The application will still be deployed and checked on its host-loopback port."
fi

install -d -m 700 -o root -g root "$APP_ROOT"
install -d -m 700 -o root -g root "$(dirname "$LOCK_PATH")"

nginx_config="/etc/nginx/conf.d/$PROJECT_NAME.conf"
compose_config="$APP_ROOT/compose.yaml"
application_env="$APP_ROOT/.env"
deploy_script="$APP_ROOT/deploy-container.sh"
rollback_dir="$stage_dir/rollback"
install -d -m 700 -o root -g root "$rollback_dir"

had_nginx_config=false
had_compose_config=false
had_application_env=false
had_deploy_script=false
snapshot_file "$nginx_config" "$rollback_dir/nginx-server.conf" && had_nginx_config=true
snapshot_file "$compose_config" "$rollback_dir/compose.yaml" && had_compose_config=true
snapshot_file "$application_env" "$rollback_dir/application.env" && had_application_env=true
snapshot_file "$deploy_script" "$rollback_dir/deploy-container.sh" && had_deploy_script=true

previous_image_id=""
if [ "$had_compose_config" = true ]; then
    previous_container_id=$(docker compose -p "$PROJECT_NAME" -f "$compose_config" ps -q app || true)
    if [ -n "$previous_container_id" ]; then
        previous_image_id=$(docker inspect --format '{{.Image}}' "$previous_container_id")
    fi
fi

install -m 600 -o root -g root "$stage_dir/nginx-server.conf" "$nginx_config"
install -m 600 -o root -g root "$stage_dir/compose.yaml" "$compose_config"
install -m 600 -o root -g root "$stage_dir/application.env" "$application_env"
install -m 700 -o root -g root "$stage_dir/deploy-container.sh" "$deploy_script"

if nginx -t; then
    if ! systemctl reload nginx; then
        echo "::warning title=Nginx reload failed::The application will still be deployed and checked on its host-loopback port."
    fi
else
    restore_file "$had_nginx_config" "$rollback_dir/nginx-server.conf" "$nginx_config"
    echo "::warning title=Nginx configuration unavailable::The previous configuration was restored. The application will still be deployed and checked on its host-loopback port."
fi

if [ -n "${DOCKERHUB_USERNAME-}" ] && [ -n "${DOCKERHUB_TOKEN-}" ]; then
    docker_config_dir=$(mktemp -d)
    export DOCKER_CONFIG="$docker_config_dir"
    umask 077
    printf '%s' "$DOCKERHUB_TOKEN" | docker login --username "$DOCKERHUB_USERNAME" --password-stdin
fi

if APP_ROOT="$APP_ROOT" \
    PROJECT_NAME="$PROJECT_NAME" \
    DOCKER_HUB_REPO="$DOCKER_HUB_REPO" \
    IMAGE_TAG="$IMAGE_TAG" \
    DEPLOY_TAG="$DEPLOY_TAG" \
    ROLLBACK_TAG="$ROLLBACK_TAG" \
    LOCK_PATH="$LOCK_PATH" \
    INTERNAL_HEALTHCHECK_URL="$INTERNAL_HEALTHCHECK_URL" \
    DEPLOY_LOCK_HELD=true \
    TRANSACTIONAL_ROLLBACK=true \
    "$deploy_script"; then
    exit 0
else
    deployment_status=$?
fi

echo "Restoring deployment artifacts" >&2
restore_file "$had_compose_config" "$rollback_dir/compose.yaml" "$compose_config"
restore_file "$had_application_env" "$rollback_dir/application.env" "$application_env"
restore_file "$had_deploy_script" "$rollback_dir/deploy-container.sh" "$deploy_script"
restore_file "$had_nginx_config" "$rollback_dir/nginx-server.conf" "$nginx_config"

if nginx -t; then
    if ! systemctl reload nginx; then
        echo "Rollback warning: restored Nginx configuration could not be reloaded" >&2
    fi
else
    echo "Rollback warning: restored Nginx configuration is invalid" >&2
fi

rollback_succeeded=false
if [ "$had_compose_config" = true ] && [ -n "$previous_image_id" ] && docker image inspect "$previous_image_id" >/dev/null 2>&1; then
    docker tag "$previous_image_id" "$DEPLOY_TAG"
    if docker compose -p "$PROJECT_NAME" -f "$compose_config" up -d --no-deps --force-recreate --pull never --wait app; then
        if wait_for_internal_health; then
            rollback_succeeded=true
        fi
    fi
else
    docker compose -p "$PROJECT_NAME" -f "$stage_dir/compose.yaml" rm -sf app >/dev/null 2>&1 || true
fi

if [ "$rollback_succeeded" = true ]; then
    echo "Rollback restored the previous artifacts and healthy application image" >&2
else
    echo "Rollback failed to restore a healthy previous deployment" >&2
fi

exit "$deployment_status"
