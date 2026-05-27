<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<section class="glass-panel">
    <div class="panel-head">
        <div><p class="eyebrow">Administration</p><h2>Registration queue</h2></div>
    </div>
    <div class="table-responsive">
        <table class="table table-glass align-middle">
            <thead>
                <tr>
                    <th>Attendee</th>
                    <th>Event</th>
                    <th>Submitted</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registrations as $registration): ?>
                    <tr>
                        <td><strong><?= esc($registration['fullname']) ?></strong><small><?= esc($registration['email']) ?></small></td>
                        <td><a href="<?= site_url('events/' . $registration['event_id']) ?>"><?= esc($registration['event_title']) ?></a><small><?= esc(date('M d, Y', strtotime($registration['event_date']))) ?></small></td>
                        <td><?= esc(date('M d, Y h:i A', strtotime($registration['created_at']))) ?></td>
                        <td>
                            <?php if ($registration['payment_proof']): ?>
                                <a class="btn btn-ghost btn-sm" href="<?= site_url('uploads/payments/' . $registration['payment_proof']) ?>" target="_blank" rel="noopener">View</a>
                            <?php else: ?>
                                <span class="text-soft">None</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="status-pill <?= esc($registration['status']) ?>"><?= esc($registration['status']) ?></span></td>
                        <td>
                            <form action="<?= site_url('registrations/' . $registration['id'] . '/status') ?>" method="post" class="status-form">
                                <?= csrf_field() ?>
                                <select name="status" class="form-select form-select-sm">
                                    <?php foreach (['pending', 'approved', 'rejected', 'cancelled'] as $status): ?>
                                        <option value="<?= esc($status) ?>" <?= $registration['status'] === $status ? 'selected' : '' ?>><?= esc(ucfirst($status)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn btn-neon btn-sm" type="submit">Save</button>
                            </form>
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
<?= $this->endSection() ?>
