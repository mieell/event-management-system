<?php
// EMERGENCY DATABASE AND ADMIN SETUP TOOL
// Upload this file to your 'public' folder on InfinityFree, then visit: yourdomain.com/emergency.php

session_start();

$envPath = __DIR__ . '/../.env';
$dbHost = '';
$dbUser = '';
$dbPass = '';
$dbName = '';

// Try to parse .env file
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2) + [NULL, NULL];
        if ($name !== null && $value !== null) {
            $name = trim($name);
            $value = trim($value);
            if ($name === 'database.default.hostname') $dbHost = $value;
            if ($name === 'database.default.username') $dbUser = $value;
            if ($name === 'database.default.password') $dbPass = $value;
            if ($name === 'database.default.database') $dbName = $value;
        }
    }
}

// Handle form submission for custom credentials or creating user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_creds'])) {
        $dbHost = $_POST['host'];
        $dbUser = $_POST['user'];
        $dbPass = $_POST['pass'];
        $dbName = $_POST['name'];
        $_SESSION['db_creds'] = compact('dbHost', 'dbUser', 'dbPass', 'dbName');
    }
}

if (isset($_SESSION['db_creds'])) {
    extract($_SESSION['db_creds']);
}

$conn = null;
$error = null;
$success = null;

if ($dbHost && $dbUser && $dbName) {
    try {
        $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
        if ($conn->connect_error) {
            $error = "Connection failed: " . $conn->connect_error;
            $conn = null;
        }
    } catch (Exception $e) {
        $error = "Connection exception: " . $e->getMessage();
        $conn = null;
    }
}

// Handle Admin Creation
if ($conn && isset($_POST['create_admin'])) {
    $email = trim($_POST['admin_email']);
    $pass = $_POST['admin_password'];
    $fullname = trim($_POST['admin_fullname']);
    $role = $_POST['admin_role'];

    $hashed = password_hash($pass, PASSWORD_DEFAULT);
    $date = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssssss", $fullname, $email, $hashed, $role, $date, $date);
        if ($stmt->execute()) {
            $success = "Successfully created user: $email with role: $role";
        } else {
            $error = "Failed to create user: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Failed to prepare statement. Check if users table exists. " . $conn->error;
    }
}

// Delete user
if ($conn && isset($_POST['delete_user'])) {
    $id = (int)$_POST['delete_id'];
    $conn->query("DELETE FROM users WHERE id = $id");
    $success = "Deleted user ID: $id";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Emergency Setup</title>
    <style>
        body { font-family: sans-serif; padding: 20px; max-width: 800px; margin: auto; background: #f4f4f4; }
        .card { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; }
        input, select { display: block; width: 100%; margin-bottom: 10px; padding: 8px; box-sizing: border-box; }
        button { background: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .success { color: green; background: #e8f5e9; padding: 10px; border-radius: 4px; margin-bottom: 10px;}
        .error { color: red; background: #ffebee; padding: 10px; border-radius: 4px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f8f9fa; }
    </style>
</head>
<body>

<h1>Emergency Admin & DB Setup</h1>

<?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

<div class="card">
    <h2>1. Database Connection</h2>
    <p>Status: <?= $conn ? '<strong style="color:green">Connected!</strong>' : '<strong style="color:red">Not Connected</strong>' ?></p>
    <form method="POST">
        <label>Hostname</label>
        <input type="text" name="host" value="<?= htmlspecialchars($dbHost) ?>" required>
        <label>Username</label>
        <input type="text" name="user" value="<?= htmlspecialchars($dbUser) ?>" required>
        <label>Password</label>
        <input type="text" name="pass" value="<?= htmlspecialchars($dbPass) ?>">
        <label>Database Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($dbName) ?>" required>
        <button type="submit" name="update_creds">Test & Save Connection</button>
    </form>
</div>

<?php if ($conn): ?>
<div class="card">
    <h2>2. Create Working Account</h2>
    <form method="POST">
        <label>Full Name</label>
        <input type="text" name="admin_fullname" value="Emergency Admin" required>
        <label>Email</label>
        <input type="email" name="admin_email" value="admin@example.com" required>
        <label>Password</label>
        <input type="text" name="admin_password" value="password123" required>
        <label>Role</label>
        <select name="admin_role">
            <option value="admin">Admin</option>
            <option value="organizer">Organizer</option>
            <option value="attendee">Attendee</option>
        </select>
        <button type="submit" name="create_admin">Create Account Directly in DB</button>
    </form>
</div>

<div class="card">
    <h2>3. Existing Users</h2>
    <table>
        <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Password Hash</th><th>Action</th></tr>
        <?php
        $res = $conn->query("SELECT * FROM users ORDER BY id DESC LIMIT 20");
        if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                echo "<td style='font-size:10px; word-break: break-all;'>" . htmlspecialchars($row['password']) . "</td>";
                echo "<td><form method='POST' style='margin:0;' onsubmit=\"return confirm('Delete this user?');\"><input type='hidden' name='delete_id' value='".$row['id']."'><button type='submit' name='delete_user' style='background:red;padding:2px 5px;'>Del</button></form></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No users found. " . ($conn->error ? $conn->error : "") . "</td></tr>";
        }
        ?>
    </table>
</div>

<div class="card" style="background:#fff3cd; border: 1px solid #ffeeba;">
    <h2 style="color:#856404;">4. Important Next Steps</h2>
    <ul style="color:#856404;">
        <li>After you successfully log in to your site, <strong>DELETE this emergency.php file immediately</strong> from your server! It is a massive security risk if left online.</li>
    </ul>
</div>
<?php endif; ?>

</body>
</html>
