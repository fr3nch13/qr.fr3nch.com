#!/bin/sh
set -eu

# Docker container healthcheck for the production CakePHP image.
#
# Why this exists:
# - Verifies the app can serve an HTTP request from inside the container, not
#   just that the Apache process is running.
# - Uses `/` because it is unauthenticated and expected to return
#   either 200 (ok) or 302 (redirect), both of which indicate the app stack is
#   responsive.
#
# Why inline PHP instead of curl/wget:
# - Keeps the check dependency-free for the production image (no extra packages
#   required).
# - Uses the same runtime already required by the application.

php -r '
$ctx = stream_context_create([
    "http" => [
        "method" => "GET",
        "ignore_errors" => true,
        "timeout" => 3,
    ],
]);

$headers = @get_headers("http://127.0.0.1:8080/", false, $ctx);
if (!is_array($headers) || !isset($headers[0])) {
    exit(1);
}

if (preg_match("#^HTTP/[0-9.]+\\s+(200|302)\\b#", $headers[0])) {
    exit(0);
}

exit(1);
'