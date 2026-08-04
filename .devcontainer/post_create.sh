#!/usr/bin/env bash
set -euo pipefail

# Runs when the devcontainer is created.

# Install the Hermes agent if it is not already available.
install_hermes() {
    if command -v curl >/dev/null 2>&1; then
        curl -fsSL https://hermes-agent.nousresearch.com/install.sh | bash
    else
        echo "No supported installer found for Hermes." >&2
        return 1
    fi
}

if command -v hermes >/dev/null 2>&1; then
    echo "Hermes is already installed."
    hermes update || echo "Hermes update failed. You may need to update it manually."
else
    echo "Installing Hermes agent..."
    if install_hermes; then
        echo "Hermes installed successfully."
    else
        echo "Hermes installation failed."
        echo "If your Hermes package name differs, update .devcontainer/post_create.sh accordingly." >&2
        exit 1
    fi
fi
