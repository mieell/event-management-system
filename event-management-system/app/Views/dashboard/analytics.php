<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<section class="dashboard-grid">
    <article class="glass-panel panel-xl">
        <div class="panel-head">
            <div><p class="eyebrow">Performance</p><h2>12-month registrations</h2></div>
        </div>
        <canvas id="monthlyChart" height="120"></canvas>
        <script type="application/json" id="monthlyChartData"><?= json_encode($monthlyRegistrations, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?></script>
    </article>

    <article class="glass-panel">
        <div class="panel-head">
            <div><p class="eyebrow">Audience</p><h2>User roles</h2></div>
        </div>
        <div class="role-list">
            <?php foreach ($roleCounts as $role => $total): ?>
                <div><span><?= esc(ucfirst($role)) ?></span><strong><?= esc($total) ?></strong></div>
            <?php endforeach; ?>
        </div>
    </article>
</section>

<section class="glass-panel">
    <div class="panel-head">
        <div><p class="eyebrow">Inventory</p><h2>Event categories</h2></div>
    </div>
    <canvas id="categoryChart" height="120"></canvas>
    <script type="application/json" id="categoryChartData"><?= json_encode($categoryCounts, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?></script>
</section>
<?= $this->endSection() ?>
