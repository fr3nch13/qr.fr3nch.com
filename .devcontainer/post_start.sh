#!/usr/bin/env bash
set -euo pipefail

# This script is run after the container is created and started.

# find the root of this project.
ROOT_DIR=$(git rev-parse --show-toplevel)

# symlink the hermes files.
if test -f ${HOME}/.hermes/SOUL.md; then
    rm -f ${HOME}/.hermes/SOUL.md && ln -s ${ROOT_DIR}/.devcontainer/hermes/SOUL.md ${HOME}/.hermes/SOUL.md
fi

if test -f ${HOME}/.hermes/config.yaml; then
    rm -f ${HOME}/.hermes/config.yaml && ln -s ${ROOT_DIR}/.devcontainer/hermes/config.yaml ${HOME}/.hermes/config.yaml
fi
