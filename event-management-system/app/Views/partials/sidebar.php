<?php
$role = session()->get('role') ?: 'guest';
$active = static fn (string $path): string => uri_string() === $path || str_starts_with(uri_string(), $path . '/') ? 'active' : '';
?>
<aside class="sidebar glass-panel" id="sidebar">
    <a class="brand" href="<?= site_url('/') ?>">
        <span class="brand-mark"><i class="bi bi-gem"></i></span>
        <span>
            <strong>Evenira</strong>
            <small>MLBB Event Suite</small>
        </span>
    </a>

    <nav class="sidebar-nav">
        <a class="<?= esc($active('dashboard')) ?>" href="<?= site_url('dashboard') ?>"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
        <a class="<?= esc($active('events')) ?>" href="<?= site_url('events') ?>"><i class="bi bi-calendar2-event"></i><span>Events</span></a>

        <?php if (in_array($role, ['admin', 'organizer'], true)): ?>
            <a class="<?= esc($active('registrations')) ?>" href="<?= site_url('registrations') ?>"><i class="bi bi-ticket-perforated"></i><span>Registrations</span></a>
        <?php endif; ?>

        <?php if ($role === 'admin'): ?>
            <a class="<?= esc($active('admin/users')) ?>" href="<?= site_url('admin/users') ?>"><i class="bi bi-people"></i><span>Users</span></a>
            <a class="<?= esc($active('admin/analytics')) ?>" href="<?= site_url('admin/analytics') ?>"><i class="bi bi-bar-chart-line"></i><span>Analytics</span></a>
        <?php endif; ?>

        <?php if ($role === 'attendee'): ?>
            <a class="<?= esc($active('my-registrations')) ?>" href="<?= site_url('my-registrations') ?>"><i class="bi bi-bookmark-check"></i><span>My Registrations</span></a>
        <?php endif; ?>

    </nav>

    <?php if (in_array($role, ['admin', 'organizer'], true)): ?>
        <div class="sidebar-footer">
            <a href="<?= site_url('events/create') ?>" class="sidebar-cta">
                <i class="bi bi-plus-circle"></i>
                <span>Create MLBB Event</span>
            </a>
        </div>
    <?php endif; ?>
</aside>
