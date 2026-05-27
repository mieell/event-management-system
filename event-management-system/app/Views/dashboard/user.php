<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<section class="hero-panel glass-panel">
    <div>
        <p class="eyebrow">Attendee portal</p>
        <h2>Find your next Mobile Legends event</h2>
        <p class="text-soft">Browse tournaments, scrim nights, watch parties, and keep your registration status in view.</p>
    </div>
    <a href="<?= site_url('events') ?>" class="btn btn-neon"><i class="bi bi-search"></i>Browse MLBB events</a>
</section>

<section class="dashboard-grid">
    <article class="glass-panel panel-xl">
        <div class="panel-head">
            <div><p class="eyebrow">Discover</p><h2>Upcoming events</h2></div>
        </div>
        <div class="event-strip">
            <?php foreach ($upcomingEvents as $event): ?>
                <a href="<?= site_url('events/' . $event['id']) ?>" class="event-mini">
                    <span><?= esc(date('M d', strtotime($event['event_date']))) ?></span>
                    <strong><?= esc($event['title']) ?></strong>
                    <small><?= esc($event['venue']) ?></small>
                </a>
            <?php endforeach; ?>
        </div>
    </article>

    <article class="glass-panel">
        <div class="panel-head">
            <div><p class="eyebrow">Inbox</p><h2>Notifications</h2></div>
        </div>
        <div class="activity-list">
            <?php if ($notifications === []): ?><p class="text-soft">No notifications yet.</p><?php endif; ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="activity-item"><span><i class="bi bi-bell"></i></span><div><p><?= esc($notification['message']) ?></p><small><?= esc(date('M d, Y h:i A', strtotime($notification['created_at']))) ?></small></div></div>
            <?php endforeach; ?>
        </div>
    </article>
</section>

<section class="glass-panel">
    <div class="panel-head">
        <div><p class="eyebrow">History</p><h2>Recent registrations</h2></div>
        <a href="<?= site_url('my-registrations') ?>" class="btn btn-ghost btn-sm">View all</a>
    </div>
    <div class="table-responsive">
        <table class="table table-glass align-middle">
            <thead><tr><th>Event</th><th>Date</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($registrations as $registration): ?>
                    <tr>
                        <td><?= esc($registration['event_title']) ?></td>
                        <td><?= esc(date('M d, Y', strtotime($registration['event_date']))) ?></td>
                        <td><span class="status-pill <?= esc($registration['status']) ?>"><?= esc($registration['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
