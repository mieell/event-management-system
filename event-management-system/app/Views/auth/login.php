<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<section class="auth-card glass-panel">
    <a class="brand auth-brand" href="<?= site_url('/') ?>">
        <span class="brand-mark"><i class="bi bi-gem"></i></span>
        <span>
            <strong>Evenira</strong>
            <small>Mobile Legends Event Suite</small>
        </span>
    </a>

    <div class="auth-copy">
        <p class="eyebrow">Secure access</p>
        <h1>Welcome back</h1>
        <p>Run Mobile Legends tournaments, scrims, watch parties, and player registrations from one polished command center.</p>
    </div>

    <form action="<?= site_url('login') ?>" method="post" class="form-stack" novalidate>
        <?= csrf_field() ?>

        <label class="form-label">Email address</label>
        <div class="input-icon">
            <i class="bi bi-envelope"></i>
            <input type="email" name="email" class="form-control" value="<?= esc(old('email', $rememberedEmail ?? '')) ?>" required autocomplete="email">
        </div>

        <label class="form-label">Password</label>
        <div class="input-icon">
            <i class="bi bi-lock"></i>
            <input type="password" name="password" class="form-control" required autocomplete="current-password">
        </div>

        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <label class="check-line">
                <input type="checkbox" name="remember" value="1">
                <span>Remember this device</span>
            </label>
            <a href="<?= site_url('register') ?>" class="link-soft">Create account</a>
        </div>

        <button class="btn btn-neon w-100 btn-lg" type="submit">
            <i class="bi bi-box-arrow-in-right"></i>
            Sign in
        </button>
    </form>

    <div class="demo-accounts">
        <span>Demo password: <strong>Admin123!</strong></span>
        <small>admin@Evenira.test | organizer@Evenira.test | attendee@Evenira.test</small>
    </div>
</section>
<?= $this->endSection() ?>
