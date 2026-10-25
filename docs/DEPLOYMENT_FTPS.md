# GitHub Actions → FTPS → PHP Hosting

## Status and constraints

User confirms FTPS deployment from GitHub is available. This document specifies the deployment implementation; no active workflow or credentials are committed, and no hosting deployment has been performed. Add `.github/workflows/deploy.yml` after the Laravel scaffold, lockfiles and real hosting layout are established. Do not add a workflow that currently cannot build or that deploys a documentation-only repo.

FTPS = FTP over TLS; it is different from SFTP/SSH. Require TLS with valid certificate and passive mode according to host configuration. Never fall back to unencrypted FTP or disable certificate verification to make deployment pass.

## Hosting preflight

- Confirm PHP version/extensions, memory limits, MySQL version, Composer/Artisan terminal or host terminal, cron availability, writable paths, domain docroot and FTPS root mapping.
- Node.js presence on hosting is optional: compiled Vite output is served by PHP/web server. No production npm dev server needed.
- Prefer app root outside public web directory and domain document root pointing at `<app-root>/public`. If hosting cannot change docroot, keep Laravel core outside public_html and deploy only public files there with explicitly adjusted index paths. Never expose the application root, .env, vendor, storage logs, docs or .git publicly.
- Check Laravel rewrite support (.htaccess for Apache/LiteSpeed as applicable), SSL and storage serving. Prefer storage:link when supported; if symlinks unavailable configure an intentional supported upload path outside deploy replacement and block executable uploads.

## Secrets and settings

GitHub Environment `production`, limited to intended branch. Required deploy secrets: `FTPS_HOST`, `FTPS_USERNAME`, `FTPS_PASSWORD`. Repository/environment variables: `FTPS_PORT`, `FTPS_APP_PATH`, `SITE_URL`; verify exact server path through a staging test, not by guessing public_html. Scope FTPS user to the application where possible.

Server-only `.env`: APP_ENV=production, APP_DEBUG=false, APP_KEY (generate once, preserve), APP_URL, DB_HOST/PORT/DATABASE/USERNAME/PASSWORD, MAIL_MAILER=resend, RESEND_API_KEY, MAIL_FROM_ADDRESS/NAME, INQUIRY_TO_EMAIL. `SESSION_SECURE_COOKIE=true` for HTTPS. Commit `.env.example` placeholders only. Do not expose secrets in VITE_ variables, build logs, workflow artifacts or docs.

No hosting/Resend secrets supplied yet. Do not ask users to paste them into code, issues or chat; owner enters them through GitHub Secrets and hosting configuration.

## Build and deploy job design

1. Trigger workflow_dispatch first. Add push to main after staging validation and host setup. PRs run checks only with no production credentials.
2. Minimal permissions `contents: read`; production concurrency group; do not cancel a running upload halfway to start another. Pin third-party actions to reviewed commit SHAs; use supported actions/runtime versions at implementation time.
3. Checkout, set PHP matching host/extensions and Node supported by chosen Vite version. Install from lockfiles (`composer install` for tests, `npm ci`); create temporary CI-only .env, APP_KEY and MySQL test database.
4. Run meaningful tests, PHP formatting checks as appropriate, `npm run build`; stop on any failure. CI uses mock mail, not real provider credentials.
5. Build clean production artifact with `composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction`, compatible PHP/extensions, and `public/build` including manifest. No platform requirement bypass. Never build environment-specific Laravel config/route cache on CI. Do not copy generated test .env, test database, public/hot or cached CI paths.
6. Upload artifact through FTPS client/action supporting certificate verification. Include app, bootstrap source, config, routes, resources views/lang, vendor, public assets, artisan, composer files and required scaffold files. Exclude `.git`, `.github`, node_modules, tests, docs, `.env*`, logs, user uploads and generated caches. Do not overwrite/delete persistent `storage` or `.env`; ensure empty storage/cache directory structure during first server setup separately.
7. Post-deploy server commands are run through host terminal/SSH if independently available; FTPS cannot execute Artisan. If only FTPS is available, document manual host-panel steps and leave deployment status as “files uploaded; server finalization pending” until completed. Do not create a public web shell, deploy.php or unauthenticated migrate/cache endpoint.
8. HTTP smoke check public Home/Contact, CSS/JS and asset manifest behavior. Upload success alone does not prove website health. A read-only health route may verify boot without exposing config; do not expose database credentials/errors.

## First installation and each release

First setup: create MySQL database/user; upload artifact; set server .env; `php artisan key:generate` once only; create writable storage/framework/{cache,sessions,views}, storage/logs and bootstrap/cache using host owner-appropriate permissions (no blanket 777); `php artisan migrate --force`; create admin securely; configure media disk/storage link; verify Resend sender domain and DNS; send controlled test to owner-designated inbox after integration is ready.

On releases: backup database and persistent uploads before schema changes; use host terminal to enable maintenance before non-atomic in-place upload if required, then upload, `php artisan optimize:clear`, `php artisan migrate --force`, `php artisan config:cache`, `php artisan view:cache`; route cache only if routes support it. Remove maintenance after smoke checks. Do not run Artisan against build-time env or regenerate APP_KEY on deploy. Document exact supported commands for chosen Laravel version.

If FTPS-only and no ability to execute CLI on server, establish hosting-supported manual Artisan/cron execution or a supported alternative before launch. A workflow cannot truthfully automate migrations or cache refresh using FTPS alone.

## Persistence, failure and rollback

Keep .env, uploads, logs and server sessions outside destructive synchronization. Avoid delete-all/clean-slate options. Never indiscriminately mirror-delete remote files; clean obsolete application files with an explicit release manifest while excluding persistent directories.

FTPS in-place uploads are not atomic and can briefly mix old/new code. For MVP, use a short controlled maintenance window with backup/retry and deployment concurrency. Prefer host-supported release directories/switching only if available, not custom infrastructure for its own sake.

Retain previous known-good build artifact and commit SHA. On failure stop uploads, keep maintenance as appropriate, restore previous application artifact, clear/rebuild server caches, smoke-test then reopen. Database rollback requires reviewed compatibility/backup restoration, not automatic migrate:rollback (can destroy data). Favor additive migrations. Test rollback on staging before first launch.

## Launch acceptance

- TLS verified; FTPS target points only at intended app; no secret values in logs/artifacts.
- .env and Laravel internals return denied/not found over HTTP; only public/ exposed.
- Built assets load without Node process; MySQL persistence works; TH/EN routes work.
- Contact inquiry persists and provider notification accepted; simulate provider error and verify stored inquiry remains.
- Uploads survive redeployment; PHP error details disabled; cache is server-local.
- Backups and manual/automated finalization path documented; controlled rollback verified.

References: https://laravel.com/docs/deployment ; https://laravel.com/docs/vite ; https://resend.com/docs/send-with-laravel ; https://docs.github.com/en/actions/security-for-github-actions/security-guides/using-secrets-in-github-actions
