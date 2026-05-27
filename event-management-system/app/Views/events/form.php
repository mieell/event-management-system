<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<section class="glass-panel form-panel">
    <form action="<?= esc($action) ?>" method="post" enctype="multipart/form-data" class="row g-4" novalidate>
        <?= csrf_field() ?>

        <div class="col-lg-8">
            <label class="form-label">Event title</label>
            <input type="text" name="title" class="form-control" value="<?= esc(old('title', $event['title'] ?? '')) ?>" required>
        </div>
        <div class="col-lg-4">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" value="<?= esc(old('category', $event['category'] ?? '')) ?>" required>
        </div>

        <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="5" required><?= esc(old('description', $event['description'] ?? '')) ?></textarea>
        </div>

        <div class="col-lg-5">
            <label class="form-label">Venue</label>
            <input type="text" name="venue" class="form-control" value="<?= esc(old('venue', $event['venue'] ?? '')) ?>" required>
        </div>
        <div class="col-lg-3">
            <label class="form-label">Date</label>
            <input type="date" name="event_date" class="form-control" value="<?= esc(old('event_date', $event['event_date'] ?? '')) ?>" required>
        </div>
        <div class="col-lg-2">
            <label class="form-label">Time</label>
            <input type="time" name="event_time" class="form-control" value="<?= esc(old('event_time', isset($event['event_time']) ? substr($event['event_time'], 0, 5) : '')) ?>" required>
        </div>
        <div class="col-lg-2">
            <label class="form-label">Capacity</label>
            <input type="number" name="capacity" class="form-control" min="1" value="<?= esc(old('capacity', $event['capacity'] ?? 50)) ?>" required>
        </div>

        <div class="col-lg-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <?php foreach (['draft', 'published', 'featured', 'cancelled', 'completed'] as $status): ?>
                    <option value="<?= esc($status) ?>" <?= old('status', $event['status'] ?? 'draft') === $status ? 'selected' : '' ?>><?= esc(ucfirst($status)) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-lg-8">
            <label class="form-label">Event banner</label>
            <label class="drop-zone" data-drop-zone>
                <input type="file" name="image" accept="image/png,image/jpeg,image/webp" data-preview="#eventPreview">
                <i class="bi bi-cloud-arrow-up"></i>
                <span>Drop a banner here or click to upload</span>
                <small>JPG, PNG, WEBP up to 4MB</small>
            </label>
        </div>

        <div class="col-12">
            <div class="image-preview" id="eventPreview">
                <?php if (! empty($event['image'])): ?>
                    <img src="<?= event_image_url($event['image']) ?>" alt="<?= esc($event['title']) ?>">
                <?php endif; ?>
            </div>
        </div>

        <div class="col-12 d-flex gap-3 flex-wrap">
            <button class="btn btn-neon" type="submit"><i class="bi bi-save"></i><?= esc($methodLabel) ?></button>
            <a class="btn btn-ghost" href="<?= site_url('events') ?>">Cancel</a>
        </div>
    </form>
</section>
<?= $this->endSection() ?>
