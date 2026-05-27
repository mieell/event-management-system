<header class="topbar glass-panel">
    <button class="icon-btn sidebar-toggle" type="button" data-sidebar-toggle aria-label="Toggle navigation">
        <i class="bi bi-list"></i>
    </button>
    <div>
        <p class="eyebrow mb-0"><?= esc(ucfirst((string) session()->get('role'))) ?> workspace</p>
        <h1><?= esc($title ?? 'Dashboard') ?></h1>
    </div>
    <div class="topbar-actions">
        <a class="user-chip" href="<?= site_url('profile') ?>" aria-label="Open profile">
            <span><?= esc(strtoupper(substr((string) session()->get('fullname'), 0, 1))) ?></span>
            <strong><?= esc(session()->get('fullname')) ?></strong>
        </a>
        <a class="icon-btn logout-btn" href="<?= site_url('logout') ?>" aria-label="Logout">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </div>
</header>
