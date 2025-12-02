#!/usr/bin/env bash
set -euo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REMOTE_NAME="${REMOTE_NAME:-origin}"
BRANCH_NAME="${BRANCH_NAME:-main}"
DEPLOY_SCRIPT="$PROJECT_ROOT/deploy.sh"

log() {
  printf "[%s] %s\n" "$(date '+%Y-%m-%d %H:%M:%S')" "$*"
}

main() {
  if [ ! -x "$DEPLOY_SCRIPT" ]; then
    echo "Deployment script not found or not executable at $DEPLOY_SCRIPT" >&2
    exit 1
  fi

  log "Fetching latest code from $REMOTE_NAME/$BRANCH_NAME"
  git -C "$PROJECT_ROOT" fetch "$REMOTE_NAME"
  log "Updating local branch $BRANCH_NAME"
  git -C "$PROJECT_ROOT" checkout "$BRANCH_NAME"
  git -C "$PROJECT_ROOT" pull --ff-only "$REMOTE_NAME" "$BRANCH_NAME"

  log "Running deployment workflow"
  "$DEPLOY_SCRIPT"
}

main "$@"
