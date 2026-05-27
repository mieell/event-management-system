<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<section class="stats-grid">
    <article class="stat-card glass-panel"><span class="stat-icon cyan"><i class="bi bi-calendar2-event"></i></span><div><p>Events</p><h2 data-counter="<?= esc($stats['events']) ?>">0</h2></div></article>
    <article class="stat-card glass-panel"><span class="stat-icon emerald"><i class="bi bi-ticket"></i></span><div><p>Registrations</p><h2 data-counter="<?= esc($stats['registrations']) ?>">0</h2></div></article>
    <article class="stat-card glass-panel"><span class="stat-icon amber"><i class="bi bi-hourglass-split"></i></span><div><p>Pending</p><h2 data-counter="<?= esc($stats['pending']) ?>">0</h2></div></article>
    <article class="stat-card glass-panel"><span class="stat-icon violet"><i class="bi bi-stars"></i></span><div><p>Featured</p><h2 data-counter="<?= esc($stats['featured']) ?>">0</h2></div></article>
</section>

<section class="dashboard-grid">
    <article class="glass-panel panel-xl">
        <div class="panel-head">
            <div><p class="eyebrow">Schedule</p><h2>Upcoming events</h2></div>
            <a href="<?= site_url('events/create') ?>" class="btn btn-neon btn-sm"><i class="bi bi-plus-circle"></i>Create</a>
        </div>
        <div class="table-responsive">
            <table class="table table-glass align-middle">
                <thead><tr><th>Event</th><th>Category</th><th>Date</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($upcomingEvents as $event): ?>
                        <tr>
                            <td><strong><?= esc($event['title']) ?></strong><small><?= esc($event['venue']) ?></small></td>
                            <td><?= esc($event['category']) ?></td>
                            <td><?= esc(date('M d, Y', strtotime($event['event_date']))) ?></td>
                            <td><span class="status-pill <?= esc($event['status']) ?>"><?= esc($event['status']) ?></span></td>
                            <td><a class="icon-btn" href="<?= site_url('events/' . $event['id']) ?>" aria-label="View event"><i class="bi bi-arrow-up-right"></i></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </article>

    <article class="glass-panel">
        <div class="panel-head">
            <div><p class="eyebrow">Timeline</p><h2>Recent activity</h2></div>
        </div>
        <div class="activity-list">
            <?php foreach ($recentActivities as $activity): ?>
                <div class="activity-item">
                    <span><i class="bi bi-pulse"></i></span>
                    <div>
                        <p><?= esc($activity['activity']) ?></p>
                        <small><?= esc(date('M d, Y h:i A', strtotime($activity['created_at']))) ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </article>
</section>
<?= $this->endSection() ?>
