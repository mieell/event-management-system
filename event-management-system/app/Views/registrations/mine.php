<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<section class="glass-panel">
    <div class="panel-head">
        <div><p class="eyebrow">Attendee</p><h2>My registrations</h2></div>
        <a href="<?= site_url('events') ?>" class="btn btn-neon btn-sm"><i class="bi bi-search"></i>Find events</a>
    </div>
    <div class="table-responsive">
        <table class="table table-glass align-middle">
            <thead><tr><th>Event</th><th>Date</th><th>Status</th><th>Payment proof</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($registrations as $registration): ?>
                    <tr>
                        <td><a href="<?= site_url('events/' . $registration['event_id']) ?>"><?= esc($registration['event_title']) ?></a><small><?= esc($registration['capacity']) ?> slots</small></td>
                        <td><?= esc(date('M d, Y', strtotime($registration['event_date']))) ?> <small><?= esc(date('h:i A', strtotime($registration['event_time']))) ?></small></td>
                        <td><span class="status-pill <?= esc($registration['status']) ?>"><?= esc($registration['status']) ?></span></td>
                        <td>
                            <?php if ($registration['payment_proof']): ?>
                                <a href="<?= site_url('uploads/payments/' . $registration['payment_proof']) ?>" target="_blank" rel="noopener" class="btn btn-ghost btn-sm">View proof</a>
                            <?php else: ?>
                                <span class="text-soft">Not uploaded</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-neon btn-sm" data-bs-toggle="modal" data-bs-target="#paymentModal<?= esc($registration['id']) ?>">
                                <i class="bi bi-cloud-arrow-up"></i>Upload
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="pager-wrap">
        <div class="pager-info text-muted small mb-2">
            Page <?= $pager->getCurrentPage('registrations') ?> &bull; Total records: <?= $pager->getTotal('registrations') ?>
        </div>
        <?= $pager->links('registrations') ?>
    </div>
</section>

<?php foreach ($registrations as $registration): ?>
    <div class="modal fade" id="paymentModal<?= esc($registration['id']) ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-modal">
                <div class="modal-header">
                    <h5 class="modal-title">Upload payment proof</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= site_url('registrations/' . $registration['id'] . '/payment') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <label class="drop-zone" data-drop-zone>
                            <input type="file" name="payment_proof" accept="image/png,image/jpeg,image/webp" data-preview="#paymentPreview<?= esc($registration['id']) ?>">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <span>Drop receipt image or click to browse</span>
                            <small>JPG, PNG, WEBP up to 4MB</small>
                        </label>
                        <div class="image-preview mt-3" id="paymentPreview<?= esc($registration['id']) ?>"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-neon">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?= $this->endSection() ?>
