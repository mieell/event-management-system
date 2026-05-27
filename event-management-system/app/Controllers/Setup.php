<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * TEMPORARY SETUP CONTROLLER - DELETE AFTER USE!
 * Access via: yourdomain.com/setup
 */
class Setup extends BaseController
{
    // Secret key to protect this page - change this if you want!
    private string $secret = 'evenira2025';

    public function index(): string
    {
        $key = $this->request->getGet('key');
        $authorized = ($key === $this->secret);

        $users = [];
        if ($authorized) {
            $users = (new UserModel())->orderBy('created_at', 'DESC')->findAll();
        }

        $success = session()->getFlashdata('success');
        $error   = session()->getFlashdata('error');

        ob_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Emergency Setup</title>
    <style>
        body { font-family: sans-serif; padding: 20px; max-width: 800px; margin: auto; background: #f4f4f4; }
        .card { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,.1); }
        h1, h2 { margin-top: 0; }
        input, select { display: block; width: 100%; margin-bottom: 10px; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 14px; }
        button:hover { background: #0056b3; }
        .success { color: green; background: #e8f5e9; padding: 10px; border-radius: 4px; margin-bottom: 10px; }
        .error   { color: red;   background: #ffebee; padding: 10px; border-radius: 4px; margin-bottom: 10px; }
        .warn    { color: #856404; background: #fff3cd; border: 1px solid #ffeeba; padding: 15px; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 13px; }
        th { background: #f8f9fa; }
        .del-btn { background: red; padding: 4px 8px; font-size: 12px; }
    </style>
</head>
<body>
<h1>🚨 Emergency Setup Tool</h1>

<?php if (! $authorized): ?>
<div class="card">
    <h2>Access Denied</h2>
    <p>Add <strong>?key=evenira2025</strong> to the URL to access this tool.</p>
    <p>Example: <code><?= current_url() ?>?key=evenira2025</code></p>
</div>
<?php else: ?>

<?php if ($success): ?><div class="success"><?= esc($success) ?></div><?php endif; ?>
<?php if ($error):   ?><div class="error"><?= esc($error) ?></div><?php endif; ?>

<div class="card">
    <h2>✅ Create Admin / Organizer Account</h2>
    <form action="<?= site_url('setup/create') ?>?key=<?= esc($key) ?>" method="POST">
        <?= csrf_field() ?>
        <label>Full Name</label>
        <input type="text" name="fullname" value="Admin User" required>
        <label>Email</label>
        <input type="email" name="email" value="admin@evenira.com" required>
        <label>Password (min 8 chars, 1 uppercase, 1 number)</label>
        <input type="text" name="password" value="Admin@1234" required>
        <label>Role</label>
        <select name="role">
            <option value="admin">Admin</option>
            <option value="organizer">Organizer</option>
            <option value="attendee">Attendee</option>
        </select>
        <button type="submit">Create Account</button>
    </form>
</div>

<div class="card">
    <h2>👥 All Users in Database</h2>
    <?php if (empty($users)): ?>
        <p>No users found in the database.</p>
    <?php else: ?>
    <table>
        <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Created</th><th>Action</th></tr>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= esc($u['fullname']) ?></td>
            <td><?= esc($u['email']) ?></td>
            <td><?= esc($u['role']) ?></td>
            <td><?= $u['created_at'] ?></td>
            <td>
                <form method="POST" action="<?= site_url('setup/delete') ?>?key=<?= esc($key) ?>" onsubmit="return confirm('Delete this user?');" style="margin:0">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                    <button type="submit" class="del-btn">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>

<div class="card warn">
    <strong>⚠️ IMPORTANT:</strong> After you finish creating your accounts and can log in normally,
    <strong>delete the file <code>app/Controllers/Setup.php</code> from your server immediately!</strong>
    Leaving it online is a security risk.
</div>

<?php endif; ?>
</body>
</html>
<?php
        return ob_get_clean();
    }

    public function create()
    {
        $key = $this->request->getGet('key');
        if ($key !== $this->secret) {
            return redirect()->to('/');
        }

        $fullname = trim((string) $this->request->getPost('fullname'));
        $email    = mb_strtolower(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('password');
        $role     = (string) $this->request->getPost('role');

        if (empty($fullname) || empty($email) || empty($password)) {
            return redirect()->to(site_url('setup') . '?key=' . $key)->with('error', 'All fields are required.');
        }

        $model = new UserModel();

        if ($model->where('email', $email)->first()) {
            return redirect()->to(site_url('setup') . '?key=' . $key)->with('error', "Email '$email' already exists. Choose a different email.");
        }

        $model->skipValidation(true)->insert([
            'fullname' => $fullname,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role'     => $role,
        ]);

        return redirect()->to(site_url('setup') . '?key=' . $key)->with('success', "Created '$role' account for $email. You can now log in!");
    }

    public function delete()
    {
        $key = $this->request->getGet('key');
        if ($key !== $this->secret) {
            return redirect()->to('/');
        }

        $id = (int) $this->request->getPost('id');
        (new UserModel())->delete($id);

        return redirect()->to(site_url('setup') . '?key=' . $key)->with('success', "Deleted user ID: $id");
    }
}
