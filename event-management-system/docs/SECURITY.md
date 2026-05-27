# Security Implementation

## CSRF Protection

CSRF is enabled globally in `app/Config/Filters.php`:

```php
'before' => [
    'invalidchars',
    'csrf' => ['except' => ['uploads/*']],
],
```

All POST forms include:

```php
<?= csrf_field() ?>
```

If a POST request is submitted without a valid token, CodeIgniter returns a 403 response. This can be demonstrated by submitting a form from another origin or removing the hidden CSRF field in the browser inspector.

## XSS Protection

All user-facing output uses:

```php
<?= esc($value) ?>
```

Examples are in event titles, descriptions, user names, notifications, email templates, table rows, and form values.

## Input Validation

Controllers validate all form submissions with CodeIgniter validation rules:

- `AuthController` validates login and registration.
- `EventController` validates event CRUD data.
- `RegistrationController` validates status updates.
- `UserController` validates profile updates.

## Secure Sessions

On successful login:

```php
session()->regenerate(true);
```

Production session behavior is configured in `.env.production`, including `session.regenerateDestroy = true`.

## Protected Routes

Routes use filters:

```php
['filter' => 'auth']
['filter' => 'role:admin']
['filter' => 'role:admin,organizer']
```

Unauthorized role access returns the custom 403 page.

## SQL Injection Prevention

The application uses CodeIgniter Models and Query Builder:

```php
$this->where('email', $email)->first();
```

No controller builds SQL by concatenating user input.

## File Upload Security

`SecureUploader` validates:

- extension: `jpg`, `jpeg`, `png`, `webp`
- MIME type: `image/jpeg`, `image/png`, `image/webp`
- file size
- random file names
- storage under `writable/uploads`

Uploaded files are served through `UploadController`, which checks folder allowlists and MIME types before responding.
