#!/usr/bin/env bash
set -euo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
BACKEND_DIR="$PROJECT_ROOT/backend"
FRONTEND_DIR="$PROJECT_ROOT/frontend"
QUEUE_GROUP="${QUEUE_GROUP:-laravel-worker}"
SUPERVISORCTL_BIN="${SUPERVISORCTL_BIN:-supervisorctl}"

log() {
  printf "[%s] %s\n" "$(date '+%Y-%m-%d %H:%M:%S')" "$*"
}

require_command() {
  if ! command -v "$1" >/dev/null 2>&1; then
    echo "Missing required command: $1" >&2
    exit 1
  fi
}

artisan() {
  (cd "$BACKEND_DIR" && php artisan "$@")
}

stop_queue_workers() {
  if command -v "$SUPERVISORCTL_BIN" >/dev/null 2>&1; then
    log "Stopping supervisor-managed queue workers ($QUEUE_GROUP)"
    "$SUPERVISORCTL_BIN" stop "${QUEUE_GROUP}:*" || log "Queue workers were not running; continuing"
  else
    log "supervisorctl not found; skipping queue worker stop"
  fi
}

start_queue_workers() {
  if command -v "$SUPERVISORCTL_BIN" >/dev/null 2>&1; then
    log "Reloading supervisor configuration"
    "$SUPERVISORCTL_BIN" reread || log "Supervisor reread reported an issue; continuing"
    "$SUPERVISORCTL_BIN" update || log "Supervisor update reported an issue; continuing"
    log "Starting supervisor-managed queue workers ($QUEUE_GROUP)"
    "$SUPERVISORCTL_BIN" start "${QUEUE_GROUP}:*" || log "Queue workers could not be started via supervisor"
  else
    log "supervisorctl not found; skipping queue worker start"
  fi
}

build_frontend() {
  if [ -d "$FRONTEND_DIR" ]; then
    log "Installing frontend dependencies"
    (cd "$FRONTEND_DIR" && npm ci)
    log "Building frontend assets"
    (cd "$FRONTEND_DIR" && npm run build)
  else
    log "Frontend directory not found; skipping asset build"
  fi
}

main() {
  require_command php
  require_command composer
  require_command npm

  if [ ! -d "$BACKEND_DIR" ]; then
    echo "Backend directory not found at $BACKEND_DIR" >&2
    exit 1
  fi

  log "Starting deployment"
  stop_queue_workers

  log "Enabling Laravel maintenance mode"
  artisan down || log "Application already in maintenance mode"

  log "Installing PHP dependencies"
  (cd "$BACKEND_DIR" && composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader)

  log "Running database migrations"
  artisan migrate --force

  log "Caching Laravel configuration"
  artisan config:clear
  artisan config:cache
  artisan route:cache
  artisan view:cache
  artisan event:cache

  build_frontend

  log "Restarting Laravel queue workers after code change"
  artisan queue:restart || log "Queue restart reported an issue; continuing"

  log "Disabling maintenance mode"
  artisan up

  start_queue_workers
  log "Deployment completed"
}

main "$@"
