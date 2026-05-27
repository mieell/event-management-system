<?php if (session()->getFlashdata('success')): ?>
    <div class="alert glass-alert alert-success" role="alert">
        <i class="bi bi-check-circle"></i><?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert glass-alert alert-danger" role="alert">
        <i class="bi bi-exclamation-triangle"></i><?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert glass-alert alert-danger" role="alert">
        <i class="bi bi-exclamation-triangle"></i>
        <div>
            <?php foreach ((array) session()->getFlashdata('errors') as $error): ?>
                <div><?= esc($error) ?></div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
