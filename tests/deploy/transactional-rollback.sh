#!/bin/sh
set -eu

repo_root=$(CDPATH= cd -- "$(dirname "$0")/../.." && pwd)
test_root=$(mktemp -d)
stage_dir="$test_root/tmp/qr-fr3nch-com-deploy-test"
app_root="$test_root/opt/qr-fr3nch-com"
nginx_root="$test_root/etc/nginx/conf.d"
bin_dir="$test_root/bin"
state_dir="$test_root/state"

cleanup() {
    rm -rf "$test_root"
}
trap cleanup EXIT HUP INT TERM

mkdir -p "$stage_dir" "$app_root" "$nginx_root" "$bin_dir" "$state_dir"

printf '%s\n' old-compose > "$app_root/compose.yaml"
printf '%s\n' old-env > "$app_root/.env"
printf '%s\n' old-script > "$app_root/deploy-container.sh"
printf '%s\n' old-nginx > "$nginx_root/qr-fr3nch-com.conf"
printf '%s\n' new-compose > "$stage_dir/compose.yaml"
printf '%s\n' new-env > "$stage_dir/application.env"
printf '%s\n' new-nginx > "$stage_dir/nginx-server.conf"
sed 's/max_attempts=10/max_attempts=1/g' \
    "$repo_root/deploy/files/deploy-container.sh" > "$stage_dir/deploy-container.sh"

cat > "$bin_dir/dnf" <<'EOF'
#!/bin/sh
exit 0
EOF

cat > "$bin_dir/systemctl" <<'EOF'
#!/bin/sh
exit 0
EOF

cat > "$bin_dir/nginx" <<'EOF'
#!/bin/sh
exit 0
EOF

cat > "$bin_dir/flock" <<'EOF'
#!/bin/sh
printf '%s\n' locked > "$TEST_STATE_DIR/lock-held"
exit 0
EOF

cat > "$bin_dir/curl" <<'EOF'
#!/bin/sh
if [ "${TRANSACTIONAL_ROLLBACK-}" = true ]; then
    exit 1
fi
test -f "$TEST_STATE_DIR/rollback-started"
EOF

cat > "$bin_dir/docker" <<'EOF'
#!/bin/sh
if [ "$1" = login ]; then
    exit 0
fi
if [ "$1" = image ] && [ "$2" = inspect ]; then
    exit 0
fi
if [ "$1" = inspect ]; then
    printf '%s\n' sha256:previous
    exit 0
fi
if [ "$1" = tag ]; then
    printf '%s\n' "$2 $3" >> "$TEST_STATE_DIR/image-tags"
    exit 0
fi
if [ "$1" = compose ]; then
    case "$*" in
        *" ps -q app")
            printf '%s\n' previous-container
            exit 0
            ;;
        *" up -d "*)
            test -f "$TEST_STATE_DIR/lock-held"
            printf '%s\n' rollback > "$TEST_STATE_DIR/rollback-started"
            exit 0
            ;;
        *" rm -sf app")
            exit 0
            ;;
    esac
fi
exit 0
EOF

chmod 755 "$bin_dir"/*

sed \
    -e "s|/etc/nginx/conf.d/|$nginx_root/|" \
    -e "s|\^/opt/|^$test_root/opt/|" \
    -e "s|stage_prefix=\"/tmp/\${PROJECT_NAME}-deploy-\"|stage_prefix=\"$test_root/tmp/\${PROJECT_NAME}-deploy-\"|" \
    -e 's|max_attempts=10|max_attempts=1|g' \
    "$repo_root/deploy/files/bootstrap-host.sh" > "$test_root/bootstrap-host.sh"
chmod 755 "$test_root/bootstrap-host.sh"

if env \
    PATH="$bin_dir:$PATH" \
    TEST_STATE_DIR="$state_dir" \
    APP_ROOT="$app_root" \
    PROJECT_NAME=qr-fr3nch-com \
    DOCKER_HUB_REPO=example/qr.fr3nch.com \
    IMAGE_TAG=2608.06.1 \
    DEPLOY_TAG=qr-fr3nch-com:deploy-current \
    ROLLBACK_TAG=qr-fr3nch-com:rollback-local \
    LOCK_PATH="$test_root/var/lock/fr3nch-deploy.lock" \
    INTERNAL_HEALTHCHECK_URL=http://127.0.0.1:8081/ \
    sh "$test_root/bootstrap-host.sh" "$stage_dir"; then
    echo "Expected deployment failure after successful rollback" >&2
    exit 1
fi

test "$(cat "$app_root/compose.yaml")" = old-compose
test "$(cat "$app_root/.env")" = old-env
test "$(cat "$app_root/deploy-container.sh")" = old-script
test "$(cat "$nginx_root/qr-fr3nch-com.conf")" = old-nginx
test -f "$state_dir/lock-held"
test -f "$state_dir/rollback-started"
grep -Fx 'sha256:previous qr-fr3nch-com:deploy-current' "$state_dir/image-tags" >/dev/null