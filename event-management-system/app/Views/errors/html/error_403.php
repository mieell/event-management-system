<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 Forbidden | Evenira EMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="auth-body">
    <div class="app-bg"></div>
    <main class="auth-stage">
        <section class="auth-card glass-panel text-center">
            <span class="brand-mark mx-auto mb-3"><i class="bi bi-shield-lock"></i></span>
            <p class="eyebrow">403 Forbidden</p>
            <h1>Access blocked</h1>
            <p class="text-soft"><?= esc($message ?? 'The request was blocked by the application security layer.') ?></p>
            <a href="<?= site_url('dashboard') ?>" class="btn btn-neon">Back to dashboard</a>
        </section>
    </main>
</body>
</html>
