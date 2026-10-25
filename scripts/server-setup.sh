#!/usr/bin/env bash
# One-time server setup for d51cons.com. Run over SSH after the first Deploy upload:
#   bash <(curl -fsSL https://raw.githubusercontent.com/upskilltrade/d51cons/main/scripts/server-setup.sh)
# Safe to run again: it keeps an existing .env, APP_KEY, public_html symlink and cron line.
set -euo pipefail

PHP="${PHP:-/opt/alt/php84/usr/bin/php}"
DOMAIN_DIR="${DOMAIN_DIR:-$HOME/domains/d51cons.com}"
APP="$DOMAIN_DIR/laravel"

[ -x "$PHP" ] || { echo "PHP 8.4 not found at $PHP"; exit 1; }
[ -f "$APP/artisan" ] && [ -f "$APP/bootstrap/deploy-id" ] || { echo "Upload not finished yet: run the Deploy workflow first."; exit 1; }
cd "$APP"

if [ ! -f .env ]; then
  echo "== Database (from the panel) and inquiry inbox =="
  read -rp "DB name: " DB_DATABASE
  read -rp "DB user: " DB_USERNAME
  read -rsp "DB password: " DB_PASSWORD; echo
  read -rp "Email that receives website inquiries: " INQUIRY_TO
  case "$DB_PASSWORD" in *"'"*) echo "The DB password cannot contain a single quote ('). Change it in the panel and run again."; exit 1;; esac

  umask 077
  cat > .env <<EOF
APP_NAME="D51 Construction"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://d51cons.com
APP_LOCALE=th
APP_FALLBACK_LOCALE=en

LOG_CHANNEL=daily
LOG_LEVEL=warning

DB_CONNECTION=mariadb
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE='${DB_DATABASE}'
DB_USERNAME='${DB_USERNAME}'
DB_PASSWORD='${DB_PASSWORD}'

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
CACHE_STORE=database
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local

# Switch to MAIL_MAILER=resend and set RESEND_API_KEY once the domain is verified in Resend.
MAIL_MAILER=log
RESEND_API_KEY=
MAIL_FROM_ADDRESS=no-reply@d51cons.com
MAIL_FROM_NAME="D51 Construction"
INQUIRY_TO_EMAIL='${INQUIRY_TO}'

SITE_NOINDEX=false
EOF
  umask 022
  "$PHP" artisan key:generate --force
  echo "Created .env"
else
  echo "Keeping existing .env"
fi

"$PHP" artisan migrate --force
"$PHP" artisan db:seed --force
[ -e public/storage ] || "$PHP" artisan storage:link

admins=$("$PHP" artisan tinker --execute 'echo App\Models\User::query()->count();' 2>/dev/null | tr -dc '0-9')
if [ "${admins:-0}" = "0" ]; then
  echo "== Create the admin account for /admin =="
  "$PHP" artisan admin:create
fi

cd "$DOMAIN_DIR"
if [ ! -L public_html ]; then
  backup="public_html.bak-$(date +%Y%m%d%H%M)"
  mv public_html "$backup"
  ln -s laravel/public public_html
  echo "Old public_html kept as $backup; public_html now points to laravel/public"
fi

line="* * * * * $PHP $APP/artisan schedule:run >> /dev/null 2>&1"
if ! crontab -l 2>/dev/null | grep -Fq "$APP/artisan schedule:run"; then
  if (crontab -l 2>/dev/null; echo "$line") | crontab -; then
    echo "Added cron job"
  else
    echo "Could not add the cron job automatically. Add this in the panel (Cron Jobs):"
    echo "  $line"
  fi
fi

"$PHP" "$APP/artisan" deploy:finish
echo
echo "Done. Check https://d51cons.com/th and https://d51cons.com/admin/login"
