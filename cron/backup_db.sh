
#!/usr/bin/env bash
set -euo pipefail
TS=$(date +"%Y%m%d-%H%M%S")
DEST="$HOME/Sites/membership-portal/backups"
mkdir -p "$DEST"
DB_NAME="membership_portal"
DB_USER="portal_user"
DB_PASS="P0rtal!User#2025"
mysqldump -u --single-transaction --quick --lock-tables=false -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" | gzip > "$DEST/${DB_NAME}-${TS}.sql.gz"
# keep last 14
ls -1t "$DEST"/*.sql.gz | tail -n +15 | xargs -I {} rm -f "{}" || true
