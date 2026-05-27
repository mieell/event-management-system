<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<section class="stats-grid">
    <?php foreach ($roleCounts as $role => $total): ?>
        <article class="stat-card glass-panel">
            <span class="stat-icon cyan"><i class="bi bi-person-badge"></i></span>
            <div><p><?= esc(ucfirst($role)) ?>s</p><h2 data-counter="<?= esc($total) ?>">0</h2></div>
        </article>
    <?php endforeach; ?>
</section>

<section class="glass-panel">
    <div class="panel-head">
        <div><p class="eyebrow">Access control</p><h2>User directory</h2></div>
        <form action="<?= site_url('admin/users') ?>" method="get" data-live-search class="table-search">
            <div class="input-icon">
                <i class="bi bi-search"></i>
                <input type="search" name="search" class="form-control" placeholder="Search users" value="<?= esc($search) ?>">
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-glass align-middle">
            <thead><tr><th>User</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <div class="user-line">
                                <span class="avatar-sm">
                                    <?php if ($user['profile_image']): ?>
                                        <img src="<?= site_url('uploads/profiles/' . $user['profile_image']) ?>" alt="<?= esc($user['fullname']) ?>">
                                    <?php else: ?>
                                        <?= esc(strtoupper(substr($user['fullname'], 0, 1))) ?>
                                    <?php endif; ?>
                                </span>
                                <strong><?= esc($user['fullname']) ?></strong>
                            </div>
                        </td>
                        <td><?= esc($user['email']) ?></td>
                        <td><span class="status-pill <?= esc($user['role']) ?>"><?= esc($user['role']) ?></span></td>
                        <td><?= esc(date('M d, Y', strtotime($user['created_at']))) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="pager-wrap">
        <div class="pager-info text-muted small mb-2">
            Page <?= $pager->getCurrentPage('users') ?> &bull; Total records: <?= $pager->getTotal('users') ?>
        </div>
        <?= $pager->links('users') ?>
    </div>
</section>
<?= $this->endSection() ?>
