<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<section class="profile-grid">
    <article class="glass-panel profile-card">
        <div class="profile-avatar">
            <?php if ($user['profile_image']): ?>
                <img src="<?= site_url('uploads/profiles/' . $user['profile_image']) ?>" alt="<?= esc($user['fullname']) ?>">
            <?php else: ?>
                <span><?= esc(strtoupper(substr($user['fullname'], 0, 1))) ?></span>
            <?php endif; ?>
        </div>
        <h2><?= esc($user['fullname']) ?></h2>
        <p><?= esc($user['email']) ?></p>
        <span class="status-pill <?= esc($user['role']) ?>"><?= esc($user['role']) ?></span>
    </article>

    <article class="glass-panel form-panel">
        <div class="panel-head">
            <div><p class="eyebrow">Account</p><h2>Profile settings</h2></div>
        </div>
        <form action="<?= site_url('profile') ?>" method="post" enctype="multipart/form-data" class="row g-4">
            <?= csrf_field() ?>
            <div class="col-md-6">
                <label class="form-label">Full name</label>
                <input type="text" name="fullname" class="form-control" value="<?= esc(old('fullname', $user['fullname'])) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= esc(old('email', $user['email'])) ?>" required>
            </div>
            <div class="col-12">
                <label class="form-label">Profile image</label>
                <label class="drop-zone" data-drop-zone>
                    <input type="file" name="profile_image" accept="image/png,image/jpeg,image/webp" data-preview="#profilePreview">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <span>Drop profile photo or click to browse</span>
                    <small>JPG, PNG, WEBP up to 2MB</small>
                </label>
                <div class="image-preview mt-3" id="profilePreview"></div>
            </div>
            <div class="col-12">
                <button class="btn btn-neon" type="submit"><i class="bi bi-save"></i>Save profile</button>
            </div>
        </form>
    </article>
</section>

<section class="glass-panel">
    <div class="panel-head">
        <div><p class="eyebrow">History</p><h2>Recent registrations</h2></div>
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
