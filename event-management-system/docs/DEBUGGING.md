# Unit Testing and Debugging Guide

## PHPUnit

Run:

```bash
composer test
```

Feature tests are in `tests/Feature`.

## `dd()` Example

Use `dd()` only in development:

```php
public function show(int $id)
{
    $event = (new EventModel())->find($id);
    dd($event);
}
```

Do not commit active `dd()` calls to production branches.

## Stack Trace Debugging Example

In development, CodeIgniter shows a stack trace when an exception is thrown:

```php
throw new \RuntimeException('Testing stack trace output.');
```

Use the trace to inspect:

- controller method
- model call
- view file
- line number
- request path

## Logging Examples

Login failure logging:

```php
log_message('warning', 'Failed login attempt for email: {email}', ['email' => $email]);
```

Email failure logging:

```php
log_message('error', 'Email send failed: {debug}', ['debug' => $email->printDebugger(['headers'])]);
```

Check logs in:

```text
writable/logs/
```

## Query Debugging

Enable the CI4 Debug Toolbar in development. It is configured in `app/Config/Filters.php`:

```php
'after' => ['toolbar', 'secureheaders', 'performance']
```

Use it to inspect:

- request timing
- database queries
- session data
- route matching
- loaded views

## Common Fixes

- 403 after form submit: check `csrf_field()` and session cookies.
- 404 after deployment: confirm Apache/Nginx document root points to `public/`.
- Upload fails: confirm `writable/uploads` exists and is writable.
- Email fails: verify SMTP host, port, crypto, username, password, and app password settings.
