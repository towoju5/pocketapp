#!/usr/bin/env bash
#
# Sets up (if needed) and runs the IQCent WebSocket relay (ws.py) inside a
# dedicated Python virtual environment. Safe to re-run - it only installs
# what's missing and then starts the script.
#
# Usage:
#   chmod +x start_ws.sh
#   ./start_ws.sh
#
# Intended to be pointed to by Supervisor's `command=` directive, e.g.:
#   command=/root/start_ws.sh

set -euo pipefail

APP_DIR="/root"
VENV_DIR="$APP_DIR/ws_venv"
SCRIPT_PATH="$APP_DIR/ws.py"
REQUIRED_PACKAGES=("playwright" "redis")

log() {
    echo "[start_ws] $*"
}

# ---------------------------------------------------------------------------
# 1. Make sure the target script actually exists before doing anything else
# ---------------------------------------------------------------------------
if [ ! -f "$SCRIPT_PATH" ]; then
    log "ERROR: $SCRIPT_PATH not found. Aborting."
    exit 1
fi

# ---------------------------------------------------------------------------
# 2. Ensure python3-venv is available (needed to create the venv at all)
# ---------------------------------------------------------------------------
if ! python3 -c "import venv" 2>/dev/null; then
    log "python3-venv not found, installing..."
    apt-get update -y
    apt-get install -y python3-venv
fi

# ---------------------------------------------------------------------------
# 3. Create the virtual environment if it doesn't exist yet
# ---------------------------------------------------------------------------
if [ ! -d "$VENV_DIR" ]; then
    log "Creating virtual environment at $VENV_DIR..."
    python3 -m venv "$VENV_DIR"
else
    log "Virtual environment already exists at $VENV_DIR."
fi

# Activate the venv for the rest of this script
# shellcheck disable=SC1091
source "$VENV_DIR/bin/activate"

# ---------------------------------------------------------------------------
# 4. Check for missing Python packages inside the venv, install only if needed
# ---------------------------------------------------------------------------
log "Checking required Python packages..."
MISSING_PACKAGES=()
for pkg in "${REQUIRED_PACKAGES[@]}"; do
    if ! python -c "import ${pkg}" 2>/dev/null; then
        MISSING_PACKAGES+=("$pkg")
    fi
done

if [ ${#MISSING_PACKAGES[@]} -gt 0 ]; then
    log "Installing missing packages: ${MISSING_PACKAGES[*]}"
    pip install --upgrade pip
    pip install "${MISSING_PACKAGES[@]}"
else
    log "All required Python packages already installed."
fi

# ---------------------------------------------------------------------------
# 5. Ensure Playwright's Chromium browser (and required OS libs) are installed
#    --with-deps pulls in the system-level shared libraries Chromium needs
#    (fonts, NSS, GTK, etc.) that a bare VPS won't have by default.
#    This is idempotent - Playwright skips anything already installed.
# ---------------------------------------------------------------------------
log "Ensuring Playwright Chromium + system dependencies are installed..."
python -m playwright install --with-deps chromium

# ---------------------------------------------------------------------------
# 6. Run the relay script
# ---------------------------------------------------------------------------
log "Starting ws.py..."
exec python "$SCRIPT_PATH"