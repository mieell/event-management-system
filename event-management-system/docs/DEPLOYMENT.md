# Deployment Guide

## Production Checklist

1. Run tests:

```bash
composer test
```

2. Install optimized dependencies:

```bash
composer install --no-dev --optimize-autoloader
```

3. Copy production env:

```bash
cp .env.production .env
```

4. Replace all placeholders in `.env`.
5. Set `CI_ENVIRONMENT = production`.
6. Point the web server document root to `public/`.
7. Make `writable/` writable by the web server.
8. Import `database/schema.sql` or run migrations and seeders.
9. Configure SSL.
10. Change seeded demo account passwords.

## Required Environment Variables

```text
CI_ENVIRONMENT=production
app.baseURL=https://your-domain.com/
encryption.key=32-plus-character-secret
DB_HOST=your-mysql-host
DB_DATABASE=event_management_system
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password
SMTP_HOST=smtp.example.com
SMTP_USER=no-reply@example.com
SMTP_PASS=your-smtp-password
```

## GitHub Deployment Workflow

The project includes `.github/workflows/ci.yml`.

Recommended GitHub flow:

```bash
git checkout -b feature/Evenira-final
git add .
git commit -m "Build Evenira EMS final project"
git push origin feature/Evenira-final
```

Create a pull request and require the CI test workflow before merge.

## Railway Deployment

1. Push this repository to GitHub.
2. Create a Railway project from the repository.
3. Add a MySQL service.
4. Configure the variables from the production list above.
5. Set the public root to `public`.
6. Run:

```bash
php spark migrate --all
php spark db:seed InitialSeeder
```

7. Generate a Railway domain or attach your custom domain.

## Render Deployment

1. Create a new Render Web Service from the GitHub repository.
2. Use the included `render.yaml` as a blueprint or configure manually.
3. Add a managed MySQL provider or external MySQL database.
4. Set environment variables.
5. Build command:

```bash
composer install --no-dev --optimize-autoloader
```

6. Start command:

```bash
php -S 0.0.0.0:$PORT -t public
```

## VPS Deployment with Apache

Install packages:

```bash
sudo apt update
sudo apt install apache2 mysql-server php php-cli php-mysql php-intl php-mbstring php-xml php-curl unzip certbot python3-certbot-apache
```

Clone and install:

```bash
cd /var/www
sudo git clone https://github.com/your-user/event-management-system.git Evenira
cd Evenira
composer install --no-dev --optimize-autoloader
sudo chown -R www-data:www-data writable
sudo chmod -R 775 writable
```

Create database:

```bash
mysql -u root -p < database/schema.sql
```

Apache virtual host:

```bash
sudo cp deployment/apache-vhost.conf /etc/apache2/sites-available/Evenira.conf
sudo a2enmod rewrite headers ssl
sudo a2ensite Evenira.conf
sudo systemctl reload apache2
```

SSL:

```bash
sudo certbot --apache -d your-domain.com -d www.your-domain.com
```

## Apache `.htaccess`

The public rewrite rules are in `public/.htaccess`. They route all non-file requests through `public/index.php` and block hidden files.

## Render/Railway Notes

For platforms that use ephemeral disks, profile images, event banners, and payment proofs should be moved to object storage such as S3-compatible storage for production. The local `writable/uploads` flow is appropriate for coursework, local hosting, and VPS deployment.
