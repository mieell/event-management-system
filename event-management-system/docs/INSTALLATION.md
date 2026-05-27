# Step-by-Step Installation Guide

## Local Requirements

- PHP 8.1 or newer
- Composer
- MySQL 8 or MariaDB 10.5+
- PHP extensions: `intl`, `mbstring`, `mysqli`, `openssl`, `json`
- Apache with `mod_rewrite` or the CI4 built-in server

## Steps

1. Enter the project folder:

```bash
cd event-management-system
```

2. Install dependencies:

```bash
composer install
```

3. Create the MySQL database and tables:

```bash
mysql -u root -p < database/schema.sql
```

4. Create the environment file:

```bash
cp .env.example .env
```

5. Edit `.env`:

```text
app.baseURL = 'http://127.0.0.1:8080/'
database.default.hostname = localhost
database.default.database = event_management_system
database.default.username = root
database.default.password =
encryption.key = your-32-character-random-secret
```

6. Configure SMTP in `.env`:

```text
email.SMTPHost = smtp.gmail.com
email.SMTPUser = your-email@example.com
email.SMTPPass = your-app-password
email.SMTPPort = 587
email.SMTPCrypto = tls
```

7. Verify upload folders exist:

```text
writable/uploads/events
writable/uploads/profiles
writable/uploads/payments
```

8. Run the app:

```bash
php spark serve
```

9. Visit:

```text
http://127.0.0.1:8080
```

10. Sign in with a seeded account:

```text
admin@Evenira.test / Admin123!
organizer@Evenira.test / Admin123!
attendee@Evenira.test / Admin123!
```

## Alternative Migration Setup

Instead of importing `database/schema.sql`, create an empty database and run:

```bash
php spark migrate
php spark db:seed InitialSeeder
```

## Common Installation Issues

- If routes return 404, confirm the document root is `public/`.
- If forms return 403, confirm sessions and CSRF cookies are working.
- If uploads fail, confirm `writable/` is writable.
- If emails do not send, confirm SMTP credentials and app password settings.
