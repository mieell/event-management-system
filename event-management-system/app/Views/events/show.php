<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<section class="event-detail glass-panel">
    <div class="detail-art">
        <?php if (event_image_url($event['image'] ?? null)): ?>
            <img src="<?= event_image_url($event['image']) ?>" alt="<?= esc($event['title']) ?>">
        <?php else: ?>
            <span class="event-placeholder large"><i class="bi bi-controller"></i></span>
        <?php endif; ?>
    </div>
    <div class="detail-copy">
        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
            <div>
                <p class="eyebrow"><?= esc($event['category']) ?></p>
                <h2><?= esc($event['title']) ?></h2>
            </div>
            <span class="status-pill <?= esc($event['status']) ?>"><?= esc($event['status']) ?></span>
        </div>

        <p class="text-soft"><?= esc($event['description']) ?></p>

        <div class="detail-facts">
            <span><i class="bi bi-calendar2"></i><?= esc(date('M d, Y', strtotime($event['event_date']))) ?></span>
            <span><i class="bi bi-clock"></i><?= esc(date('h:i A', strtotime($event['event_time']))) ?></span>
            <span><i class="bi bi-geo-alt"></i><?= esc($event['venue']) ?></span>
            <span><i class="bi bi-people"></i><?= esc((int) ($event['registrations_count'] ?? 0)) ?>/<?= esc($event['capacity']) ?> slots</span>
        </div>

        <div class="action-row">
            <?php if (session()->get('role') === 'attendee'): ?>
                <?php if ($alreadyRegistered): ?>
                    <a href="<?= site_url('my-registrations') ?>" class="btn btn-ghost"><i class="bi bi-bookmark-check"></i>Already registered</a>
                <?php else: ?>
                    <button class="btn btn-neon" data-bs-toggle="modal" data-bs-target="#registerModal"><i class="bi bi-ticket-perforated"></i>Register</button>
                <?php endif; ?>
            <?php elseif (! session()->get('logged_in')): ?>
                <a href="<?= site_url('login') ?>" class="btn btn-neon"><i class="bi bi-box-arrow-in-right"></i>Sign in to register</a>
            <?php endif; ?>

            <?php if (in_array(session()->get('role'), ['admin', 'organizer'], true)): ?>
                <a href="<?= site_url('events/' . $event['id'] . '/edit') ?>" class="btn btn-ghost"><i class="bi bi-pencil"></i>Edit</a>
                <button class="btn btn-danger-soft" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="bi bi-trash"></i>Delete</button>
            <?php endif; ?>
        </div>
    </div>
</section>

<div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-modal">
            <div class="modal-header">
                <h5 class="modal-title">Confirm registration</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('events/' . $event['id'] . '/register') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <p class="text-soft mb-0">Submit your registration for <strong><?= esc($event['title']) ?></strong>. You can upload payment proof from your registration history.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-neon">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-modal">
            <div class="modal-header">
                <h5 class="modal-title">Delete event</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('events/' . $event['id'] . '/delete') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <p class="text-soft mb-0">This action removes the event and related registrations.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger-soft">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
