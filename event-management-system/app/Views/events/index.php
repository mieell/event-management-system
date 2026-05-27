<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<section class="hero-panel glass-panel">
    <div>
        <p class="eyebrow">Mobile Legends events</p>
        <h2>Register for tournaments, scrims, and watch parties</h2>
        <p class="text-soft">Discover MLBB community events, track available slots, and manage your registration in one dashboard.</p>
    </div>
</section>

<section class="glass-panel filter-panel">
    <form action="<?= site_url('events') ?>" method="get" class="filter-grid" data-live-search>
        <div class="input-icon">
            <i class="bi bi-search"></i>
            <input type="search" name="search" class="form-control" placeholder="Search MLBB events" value="<?= esc($filters['search'] ?? '') ?>">
        </div>
        <select name="category" class="form-select">
            <option value="">All categories</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= esc($category['category']) ?>" <?= (($filters['category'] ?? '') === $category['category']) ? 'selected' : '' ?>>
                    <?= esc($category['category']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="date" name="date" class="form-control" value="<?= esc($filters['date'] ?? '') ?>">
        <?php if (in_array(session()->get('role'), ['admin', 'organizer'], true)): ?>
            <select name="status" class="form-select">
                <option value="">Visible events</option>
                <?php foreach (['draft', 'published', 'featured', 'cancelled', 'completed'] as $status): ?>
                    <option value="<?= esc($status) ?>" <?= (($filters['status'] ?? '') === $status) ? 'selected' : '' ?>><?= esc(ucfirst($status)) ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        <button class="btn btn-neon" type="submit"><i class="bi bi-funnel"></i>Filter</button>
    </form>
</section>

<section class="event-grid">
    <?php foreach ($events as $event): ?>
        <article class="event-card glass-panel">
            <a href="<?= site_url('events/' . $event['id']) ?>" class="event-art">
                <?php if (event_image_url($event['image'] ?? null)): ?>
                    <img src="<?= event_image_url($event['image']) ?>" alt="<?= esc($event['title']) ?>">
                <?php else: ?>
                    <span class="event-placeholder"><i class="bi bi-controller"></i></span>
                <?php endif; ?>
                <span class="status-pill floating <?= esc($event['status']) ?>"><?= esc($event['status']) ?></span>
            </a>
            <div class="event-body">
                <div class="event-meta">
                    <span><i class="bi bi-calendar2"></i><?= esc(date('M d, Y', strtotime($event['event_date']))) ?></span>
                    <span><i class="bi bi-clock"></i><?= esc(date('h:i A', strtotime($event['event_time']))) ?></span>
                </div>
                <h2><a href="<?= site_url('events/' . $event['id']) ?>"><?= esc($event['title']) ?></a></h2>
                <p><?= esc(event_excerpt($event['description'], 150)) ?></p>
                <div class="event-footer">
                    <span><i class="bi bi-geo-alt"></i><?= esc($event['venue']) ?></span>
                    <strong><?= esc((int) ($event['registrations_count'] ?? 0)) ?>/<?= esc($event['capacity']) ?></strong>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
</section>

<?php if ($events === []): ?>
    <section class="empty-state glass-panel">
        <i class="bi bi-calendar-x"></i>
        <h2>No Mobile Legends events found</h2>
        <p class="text-soft">Try a different squad event, category, or date filter.</p>
    </section>
<?php endif; ?>

<div class="pager-wrap mt-4">
    <div class="pager-info text-muted small mb-2 text-center">
        Page <?= $pager->getCurrentPage('events') ?> &bull; Total events: <?= $pager->getTotal('events') ?>
    </div>
    <?= $pager->links('events') ?>
</div>
<?= $this->endSection() ?>
