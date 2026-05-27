<div style="font-family:Inter,Arial,sans-serif;background:#0f172a;color:#e0f2fe;padding:32px">
    <div style="max-width:640px;margin:auto;background:rgba(255,255,255,.08);border:1px solid rgba(125,211,252,.25);border-radius:20px;padding:28px">
        <h1 style="margin-top:0;color:#7dd3fc">Event reminder</h1>
        <p>Hello <?= esc($user['fullname']) ?>,</p>
        <p><strong><?= esc($event['title']) ?></strong> is coming up soon.</p>
        <p><strong>Date:</strong> <?= esc(date('M d, Y', strtotime($event['event_date']))) ?><br>
        <strong>Time:</strong> <?= esc(date('h:i A', strtotime($event['event_time']))) ?></p>
    </div>
</div>
