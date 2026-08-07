#!/bin/sh
# Renders the environment-specific deployment bundle used by GitHub Actions.
# Produces Compose, Nginx, and protected runtime configuration files for a
# release image; the resulting directory is uploaded to the target host.
set -eu
umask 077

require_var() {
    name="$1"
    eval "value=\${$name-}"
    if [ -z "$value" ]; then
        echo "Missing required environment variable: $name" >&2
        exit 1
    fi
}

quote_sh() {
    printf "'%s'" "$(printf '%s' "$1" | sed "s/'/'\\\\''/g")"
}

write_shell_var() {
    key="$1"
    value="$2"
    printf '%s=%s\n' "$key" "$(quote_sh "$value")" >> "$runtime_env"
}

quote_dotenv() {
    printf '"%s"' "$(printf '%s' "$1" | sed -e 's/\\/\\\\/g' -e 's/"/\\"/g' -e 's/\$/$$/g')"
}

write_application_var() {
    key="$1"
    value="$2"
    printf '%s=%s\n' "$key" "$(quote_dotenv "$value")" >> "$application_env"
}

output_dir="${1-}"
if [ -z "$output_dir" ]; then
    echo "Usage: $0 OUTPUT_DIR" >&2
    exit 1
fi

require_var DOMAIN
require_var PROJECT_NAME
require_var DOCKER_HUB_REPO
require_var IMAGE_TAG
require_var APP_HOST_PORT
require_var APP_CONTAINER_PORT
require_var LOCK_PATH
require_var DEBUG
require_var DATABASE_URL
require_var SECURITY_SALT
require_var CERTBOT_EMAIL

APP_ROOT="${APP_ROOT:-/opt/${PROJECT_NAME}}"
CERTBOT_WEBROOT="${CERTBOT_WEBROOT:-/opt/certbot/webroot}"
TLS_CERTIFICATE_FILE="${TLS_CERTIFICATE_FILE:-/etc/letsencrypt/live/${DOMAIN}/fullchain.pem}"
TLS_CERTIFICATE_KEY_FILE="${TLS_CERTIFICATE_KEY_FILE:-/etc/letsencrypt/live/${DOMAIN}/privkey.pem}"
healthcheck_path="${HEALTHCHECK_PATH:-/}"
deploy_tag="${PROJECT_NAME}:deploy-current"
rollback_tag="${PROJECT_NAME}:rollback-local"
internal_healthcheck_url="http://127.0.0.1:${APP_HOST_PORT}${healthcheck_path}"
application_env="$output_dir/application.env"
runtime_env="$output_dir/runtime.env"

if ! printf '%s' "$IMAGE_TAG" | grep -Eq '^[A-Za-z0-9_][A-Za-z0-9_.-]{0,127}$'; then
    echo "IMAGE_TAG must be a valid Docker tag" >&2
    exit 1
fi

if ! printf '%s' "$APP_ROOT" | grep -Eq '^/opt/[A-Za-z0-9][A-Za-z0-9._-]*$'; then
    echo "APP_ROOT must stay under /opt" >&2
    exit 1
fi

mkdir -p "$output_dir"
chmod 700 "$output_dir"

cat > "$output_dir/nginx-server.conf" <<EOF
server {
    listen 80;
    listen [::]:80;
    server_name ${DOMAIN};

    location /.well-known/acme-challenge/ {
        root ${CERTBOT_WEBROOT};
    }

    location / {
        return 301 https://\$host\$request_uri;
    }
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name ${DOMAIN};

    ssl_certificate ${TLS_CERTIFICATE_FILE};
    ssl_certificate_key ${TLS_CERTIFICATE_KEY_FILE};
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;

    location / {
        proxy_pass http://127.0.0.1:${APP_HOST_PORT};
        proxy_http_version 1.1;
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto https;
    }
}
EOF

cat > "$output_dir/nginx-bootstrap.conf" <<EOF
server {
    listen 80;
    listen [::]:80;
    server_name ${DOMAIN};

    location /.well-known/acme-challenge/ {
        root ${CERTBOT_WEBROOT};
    }

    location / {
        proxy_pass http://127.0.0.1:${APP_HOST_PORT};
        proxy_http_version 1.1;
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto http;
    }
}
EOF

{
        printf '%s\n' 'services:'
        printf '%s\n' '  app:'
        printf '    image: %s\n' "$deploy_tag"
        printf '%s\n' '    restart: unless-stopped'
        printf '%s\n' '    env_file:'
        printf '%s\n' '      - .env'
        printf '    ports: ["127.0.0.1:%s:%s"]\n' "$APP_HOST_PORT" "$APP_CONTAINER_PORT"
        printf '%s\n' "    volumes: [\"./tmp:${APP_ROOT}/tmp\"]"
} > "$output_dir/compose.yaml"

: > "$application_env"
write_application_var DEBUG "$DEBUG"
write_application_var DATABASE_URL "$DATABASE_URL"
write_application_var SECURITY_SALT "$SECURITY_SALT"
if [ -n "${EMAIL_TRANSPORT_DEFAULT_URL-}" ]; then
    write_application_var EMAIL_TRANSPORT_DEFAULT_URL "$EMAIL_TRANSPORT_DEFAULT_URL"
fi

: > "$runtime_env"
write_shell_var APP_ROOT "$APP_ROOT"
write_shell_var DOMAIN "$DOMAIN"
write_shell_var CERTBOT_EMAIL "$CERTBOT_EMAIL"
write_shell_var PROJECT_NAME "$PROJECT_NAME"
write_shell_var DOCKER_HUB_REPO "$DOCKER_HUB_REPO"
write_shell_var IMAGE_TAG "$IMAGE_TAG"
write_shell_var DEPLOY_TAG "$deploy_tag"
write_shell_var ROLLBACK_TAG "$rollback_tag"
write_shell_var LOCK_PATH "$LOCK_PATH"
write_shell_var INTERNAL_HEALTHCHECK_URL "$internal_healthcheck_url"
write_shell_var DOCKERHUB_USERNAME "${DOCKERHUB_USERNAME-}"
write_shell_var DOCKERHUB_TOKEN "${DOCKERHUB_TOKEN-}"

chmod 600 "$application_env" "$runtime_env"
