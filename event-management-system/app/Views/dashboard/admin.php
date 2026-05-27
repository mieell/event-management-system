<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<section class="stats-grid">
    <article class="stat-card glass-panel">
        <span class="stat-icon cyan"><i class="bi bi-people"></i></span>
        <div><p>Total Users</p><h2 data-counter="<?= esc($stats['users']) ?>">0</h2></div>
    </article>
    <article class="stat-card glass-panel">
        <span class="stat-icon violet"><i class="bi bi-calendar2-event"></i></span>
        <div><p>Total Events</p><h2 data-counter="<?= esc($stats['events']) ?>">0</h2></div>
    </article>
    <article class="stat-card glass-panel">
        <span class="stat-icon emerald"><i class="bi bi-ticket-perforated"></i></span>
        <div><p>Registrations</p><h2 data-counter="<?= esc($stats['registrations']) ?>">0</h2></div>
    </article>
    <article class="stat-card glass-panel">
        <span class="stat-icon amber"><i class="bi bi-cash-coin"></i></span>
        <div><p>Estimated Revenue</p><h2>PHP <span data-counter="<?= esc($stats['revenue']) ?>">0</span></h2></div>
    </article>
</section>

<section class="dashboard-grid">
    <article class="glass-panel panel-xl">
        <div class="panel-head">
            <div><p class="eyebrow">Analytics</p><h2>Registration trend</h2></div>
            <a href="<?= site_url('admin/analytics') ?>" class="btn btn-ghost btn-sm">View analytics</a>
        </div>
        <canvas id="monthlyChart" height="120"></canvas>
        <script type="application/json" id="monthlyChartData"><?= json_encode($monthlyRegistrations, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?></script>
    </article>

    <article class="glass-panel">
        <div class="panel-head">
            <div><p class="eyebrow">Event mix</p><h2>Categories</h2></div>
        </div>
        <canvas id="categoryChart" height="160"></canvas>
        <script type="application/json" id="categoryChartData"><?= json_encode($categoryCounts, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?></script>
    </article>
</section>

<section class="dashboard-grid">
    <article class="glass-panel">
        <div class="panel-head">
            <div><p class="eyebrow">Operations</p><h2>Recent activity</h2></div>
        </div>
        <div class="activity-list">
            <?php foreach ($recentActivities as $activity): ?>
                <div class="activity-item">
                    <span><i class="bi bi-pulse"></i></span>
                    <div>
                        <strong><?= esc($activity['fullname'] ?? 'System') ?></strong>
                        <p><?= esc($activity['activity']) ?></p>
                        <small><?= esc(date('M d, Y h:i A', strtotime($activity['created_at']))) ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </article>

    <article class="glass-panel">
        <div class="panel-head">
            <div><p class="eyebrow">Inbox</p><h2>Notifications</h2></div>
        </div>
        <div class="activity-list">
            <?php if ($notifications === []): ?>
                <p class="text-soft">No notifications yet.</p>
            <?php endif; ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="activity-item">
                    <span><i class="bi bi-bell"></i></span>
                    <div>
                        <p><?= esc($notification['message']) ?></p>
                        <small><?= esc(date('M d, Y h:i A', strtotime($notification['created_at']))) ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </article>
</section>
<?= $this->endSection() ?>
