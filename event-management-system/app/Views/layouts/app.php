<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="<?= esc(csrf_header()) ?>" content="<?= esc(csrf_hash()) ?>">
    <title><?= esc($title ?? 'Evenira EMS') ?> | Evenira EMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css?v=' . time()) ?>" rel="stylesheet">
</head>
<body>
    <div class="preloader" id="preloader">
        <div class="loader-ring"></div>
        <span>Loading Evenira</span>
    </div>

    <div class="app-bg"></div>
    <div class="app-shell">
        <?= $this->include('partials/sidebar') ?>
        <main class="app-main">
            <?= $this->include('partials/topbar') ?>
            <div class="content-wrap">
                <?= $this->include('partials/alerts') ?>
                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/vendor/chartjs/chart.umd.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/app.js?v=' . time()) ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
