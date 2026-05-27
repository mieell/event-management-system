<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<section class="auth-card glass-panel">
    <a class="brand auth-brand" href="<?= site_url('/') ?>">
        <span class="brand-mark"><i class="bi bi-gem"></i></span>
        <span>
            <strong>Evenira</strong>
            <small>Player Portal</small>
        </span>
    </a>

    <div class="auth-copy">
        <p class="eyebrow">New attendee</p>
        <h1>Create your account</h1>
        <p>Browse Mobile Legends events, reserve squad slots, upload payment proof, and receive confirmation emails.</p>
    </div>

    <form action="<?= site_url('register') ?>" method="post" class="form-stack" novalidate>
        <?= csrf_field() ?>

        <label class="form-label">Full name</label>
        <div class="input-icon">
            <i class="bi bi-person"></i>
            <input type="text" name="fullname" class="form-control" value="<?= esc(old('fullname')) ?>" required autocomplete="name">
        </div>

        <label class="form-label">Email address</label>
        <div class="input-icon">
            <i class="bi bi-envelope"></i>
            <input type="email" name="email" class="form-control" value="<?= esc(old('email')) ?>" required autocomplete="email">
        </div>

        <label class="form-label">Password</label>
        <div class="input-icon">
            <i class="bi bi-lock"></i>
            <input type="password" name="password" class="form-control" required autocomplete="new-password">
        </div>

        <label class="form-label">Confirm password</label>
        <div class="input-icon">
            <i class="bi bi-shield-lock"></i>
            <input type="password" name="password_confirm" class="form-control" required autocomplete="new-password">
        </div>

        <label class="form-label">Account type</label>
        <div class="input-icon">
            <i class="bi bi-person-badge"></i>
            <select name="role" class="form-control" required style="padding-left: 2.5rem; appearance: auto; -webkit-appearance: auto;">
                <option value="attendee" <?= old('role') === 'attendee' ? 'selected' : '' ?>>Attendee</option>
                <option value="organizer" <?= old('role') === 'organizer' ? 'selected' : '' ?>>Organizer</option>
            </select>
        </div>

        <button class="btn btn-neon w-100 btn-lg" type="submit">
            <i class="bi bi-person-plus"></i>
            Create account
        </button>

        <p class="text-center mb-0 text-soft">Already registered? <a href="<?= site_url('login') ?>" class="link-soft">Sign in</a></p>
    </form>
</section>
<?= $this->endSection() ?>
