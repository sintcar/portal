#!/usr/bin/env bash
set -euo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
BACKEND_DIR="$PROJECT_ROOT/backend"
BACKUP_DIR="${BACKUP_DIR:-$PROJECT_ROOT/backups}"
TIMESTAMP="$(date '+%Y%m%d_%H%M%S')"
ENV_FILE="$BACKEND_DIR/.env"

log() {
  printf "[%s] %s\n" "$(date '+%Y-%m-%d %H:%M:%S')" "$*"
}

load_env() {
  if [ -f "$ENV_FILE" ]; then
    # shellcheck disable=SC2046
    export $(grep -E "^(DB_CONNECTION|DB_HOST|DB_PORT|DB_DATABASE|DB_USERNAME|DB_PASSWORD)=" "$ENV_FILE" | xargs)
  fi
}

backup_database() {
  if [ ! -f "$ENV_FILE" ]; then
    log "No .env file found; skipping database backup"
    return
  fi

  load_env

  case "${DB_CONNECTION:-}" in
    mysql)
      if command -v mysqldump >/dev/null 2>&1; then
        log "Creating MySQL database backup for $DB_DATABASE"
        mysqldump -h "${DB_HOST:-127.0.0.1}" -P "${DB_PORT:-3306}" -u "${DB_USERNAME:-root}" ${DB_PASSWORD:+-p"$DB_PASSWORD"} "$DB_DATABASE" > "$BACKUP_DIR/${TIMESTAMP}_database.sql"
      else
        log "mysqldump not available; skipping MySQL backup"
      fi
      ;;
    pgsql)
      if command -v pg_dump >/dev/null 2>&1; then
        log "Creating PostgreSQL database backup for $DB_DATABASE"
        PGPASSWORD="${DB_PASSWORD:-}" pg_dump -h "${DB_HOST:-127.0.0.1}" -p "${DB_PORT:-5432}" -U "${DB_USERNAME:-postgres}" "$DB_DATABASE" > "$BACKUP_DIR/${TIMESTAMP}_database.sql"
      else
        log "pg_dump not available; skipping PostgreSQL backup"
      fi
      ;;
    *)
      log "DB_CONNECTION not set or unsupported; skipping database backup"
      ;;
  esac
}

backup_files() {
  mkdir -p "$BACKUP_DIR"
  log "Creating application file backup archive"
  local paths=(backend/config backend/database backend/resources backend/routes frontend)
  if [ -f "$BACKEND_DIR/.env" ]; then
    paths=(backend/.env "${paths[@]}")
  fi

  tar -czf "$BACKUP_DIR/${TIMESTAMP}_app.tar.gz" -C "$PROJECT_ROOT" "${paths[@]}" || log "File backup completed with warnings"
}

main() {
  log "Starting backup"
  backup_files
  backup_database
  log "Backup completed; files stored in $BACKUP_DIR"
}

main "$@"
