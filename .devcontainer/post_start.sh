#!/usr/bin/env bash
set -euo pipefail

# This script is run after the container is created and started.

# find the root of this project.
ROOT_DIR=$(git rev-parse --show-toplevel)

# Bind mounts replace the ownership set while building the image.
mkdir -p "${ROOT_DIR}/tmp/uploads" "${ROOT_DIR}/logs"
chown -R www-data:www-data "${ROOT_DIR}/tmp" "${ROOT_DIR}/logs"

# symlink the hermes files.
if test -f "${HOME}/.hermes/SOUL.md"; then
    rm -f "${HOME}/.hermes/SOUL.md" && ln -s "${ROOT_DIR}/.devcontainer/hermes/SOUL.md" "${HOME}/.hermes/SOUL.md"
fi

if test -f "${HOME}/.hermes/config.yaml"; then
    rm -f "${HOME}/.hermes/config.yaml" && ln -s "${ROOT_DIR}/.devcontainer/hermes/config.yaml" "${HOME}/.hermes/config.yaml"
fi
