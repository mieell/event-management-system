# Evenira EMS - Mobile Legends Event Management System

Advanced Web Development Final Project built with CodeIgniter 4, PHP 8+, MySQL, HTML5, CSS3, JavaScript, and Bootstrap 5 for Mobile Legends tournaments, scrims, watch parties, and esports registrations.

Theme: Modern Futuristic Glassmorphism Blue UI.

## Features

- Authentication: login, registration, logout, session regeneration, secure remember-me tokens.
- Roles: admin, organizer, attendee.
- Admin dashboard: total users, events, registrations, estimated revenue, charts, activity logs, notifications.
- Organizer dashboard: event overview, registration queue, upcoming events.
- User dashboard: browse events, register, upload payment proof, view history, manage profile.
- Event CRUD: create, edit, delete, details page, categories, capacity limits, statuses, event banners.
- Registration system: duplicate prevention, capacity enforcement, status approval workflow.
- File uploads: event banners, profile images, payment proof images, drag-and-drop preview.
- Search and pagination: event filters, category/date/status filters, user search, paginated lists.
- Email notifications: HTML confirmation, status updates, reminders, admin notification helper.
- Security: global CSRF, `csrf_field()` on forms, XSS escaping with `esc()`, validation, secure sessions, protected routes, Query Builder, file MIME/extension/size checks.
- Testing: PHPUnit feature tests for homepage, login, event creation, and validation.
- Deployment: production env template, Apache config, Railway, Render, VPS, SSL, GitHub Actions.

## Project Structure

```text
event-management-system/
  app/
    Config/
      App.php
      Autoload.php
      Cache.php
      Database.php
      Email.php
      Filters.php
      Paths.php
      Routes.php
      Validation.php
    Controllers/
      AuthController.php
      BaseController.php
      DashboardController.php
      EventController.php
      Home.php
      RegistrationController.php
      UploadController.php
      UserController.php
    Database/
      Migrations/2026-05-23-000001_CreateEventManagementTables.php
      Seeds/InitialSeeder.php
    Filters/
      AuthFilter.php
      GuestFilter.php
      RoleFilter.php
    Libraries/
      EventMailer.php
      SecureUploader.php
    Models/
      ActivityLogModel.php
      EventModel.php
      NotificationModel.php
      RegistrationModel.php
      RememberTokenModel.php
      UserModel.php
    Views/
      auth/
      dashboard/
      emails/
      errors/
      events/
      layouts/
      partials/
      registrations/
      users/
  database/schema.sql
  deployment/
  docs/
  public/
    assets/css/app.css
    assets/js/app.js
    .htaccess
    index.php
  tests/
  writable/uploads/
  .env.example
  .env.production
  composer.json
  phpunit.xml.dist
  spark
```

## Database

The complete MySQL schema is in `database/schema.sql`.

Required tables:

- `users`
- `events`
- `registrations`
- `notifications`
- `activity_logs`

Additional support table:

- `remember_tokens` for secure remember-me sessions.

## Installation

1. Install PHP 8.1+, Composer, MySQL, and required PHP extensions: `intl`, `mbstring`, `mysqli`.
2. Open a terminal inside this project.
3. Install dependencies:

```bash
composer install
```

4. Create the database:

```bash
mysql -u root -p < database/schema.sql
```

5. Copy the local environment template:

```bash
cp .env.example .env
```

6. Update `.env` with your MySQL credentials, base URL, encryption key, and SMTP settings.
7. Confirm writable permissions:

```bash
chmod -R 775 writable
```

8. Run migrations and seeders if you prefer CI4 migrations instead of raw SQL:

```bash
php spark migrate
php spark db:seed InitialSeeder
```

9. Start the development server:

```bash
php spark serve
```

10. Open `http://127.0.0.1:8080`.

## Demo Accounts

All seed accounts use this initial password:

```text
Admin123!
```

- Admin: `admin@Evenira.test`
- Organizer: `organizer@Evenira.test`
- Attendee: `attendee@Evenira.test`

Change these credentials before any real deployment.

## Main Routes

```text
GET    /                         Public event listing
GET    /login                    Login page
POST   /login                    Login submit
GET    /register                 Registration page
POST   /register                 Registration submit
GET    /logout                   Logout
GET    /dashboard                Role-aware dashboard redirect
GET    /admin/dashboard          Admin dashboard
GET    /organizer/dashboard      Organizer dashboard
GET    /events                   Event listing
GET    /events/create            Create event form
POST   /events                   Store event
GET    /events/{id}              Event details
GET    /events/{id}/edit         Edit event
POST   /events/{id}              Update event
POST   /events/{id}/delete       Delete event
POST   /events/{id}/register     Register for event
GET    /registrations            Admin/organizer registration queue
POST   /registrations/{id}/status Update registration status
GET    /my-registrations         Attendee registration history
POST   /registrations/{id}/payment Upload payment proof
GET    /admin/users              User directory
GET    /admin/analytics          Analytics page
GET    /profile                  Profile page
POST   /profile                  Update profile
GET    /uploads/{folder}/{file}  Secure uploaded media response
```

## Testing

Run the PHPUnit test suite:

```bash
composer test
```

Included tests:

- Homepage status test with `assertStatus(200)`.
- Login page and invalid validation test.
- Event creation test with `assertEquals()` and `assertNotNull()`.
- Validation failure test.

## Security Notes

- CSRF is enabled globally in `app/Config/Filters.php`.
- Every form includes `csrf_field()`.
- Output is escaped in views using `esc()`.
- SQL queries use CI4 Models and Query Builder instead of raw interpolated SQL.
- Uploads are stored under `writable/uploads`, not directly under public web root.
- Uploaded files are validated by extension, MIME type, size, and random file names.
- Admin and organizer routes are protected by `RoleFilter`.
- Sessions regenerate on login and use secure config values in production.

## Deployment

Read `docs/DEPLOYMENT.md` for GitHub, Railway, Render, VPS, Apache, `.htaccess`, SSL, and environment variable instructions.

## Debugging

Read `docs/DEBUGGING.md` for `dd()` examples, stack trace debugging, and `log_message()` examples.
