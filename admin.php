<?php
session_start();
$loggedIn = isset($_SESSION['admin']);
$file = 'registrations.json';
$error = "";
$message = "";

// === LOGIN HANDLER ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  if ($email === 'admin@com' && $password === 'admin') {
    $_SESSION['admin'] = true;
    header("Location: admin.php");
    exit();
  } else {
    $error = "Wrong email or password!";
  }
}

// === DELETE HANDLER ===
if (isset($_GET['delete']) && $loggedIn) {
  $deleteEmail = $_GET['delete'];

  if (file_exists($file)) {
    $data = json_decode(file_get_contents($file), true);
    $data = array_filter($data, fn($row) => $row['email'] !== $deleteEmail);
    file_put_contents($file, json_encode(array_values($data), JSON_PRETTY_PRINT));
    $message = "✅ Record for $deleteEmail has been deleted.";
  }
}

// === LOAD DATA ===
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

if (!$loggedIn):
?>

<!-- ====== LOGIN PAGE ====== -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <style>
    body {
      background: #0d0d0d;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: Arial, sans-serif;
    }
    form {
      background: #111;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(200,170,90,0.2);
    }
    input {
      display: block;
      margin-bottom: 15px;
      width: 250px;
      padding: 10px;
      border-radius: 6px;
      border: none;
      background: #1a1a1a;
      color: white;
    }
    button {
      background: #bfa16a;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }
    h2 { color: #d4b76f; }
  </style>
</head>
<body>
  <form method="POST">
    <h2>Admin Login</h2>
    <?php if (!empty($error)): ?>
      <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <input type="email" name="email" placeholder="Admin Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
  </form>
</body>
</html>

<?php
exit();
endif;
?>

<!-- ====== ADMIN DASHBOARD ====== -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title></title>
  <style>
    body { background:#0d0d0d; color:#fff; font-family:Arial, sans-serif; padding:40px;}
    table { width:100%; border-collapse:collapse; margin-top:20px;}
    th, td { padding:12px; border-bottom:1px solid #333; text-align:left;}
    th { background:#bfa16a; color:#111;}
    tr:hover { background:#1a1a1a;}
    a.logout { color:#d4b76f; text-decoration:none; float:right;}
    a.delete-btn { color:#ff6b6b; text-decoration:none; font-weight:bold; }
    a.delete-btn:hover { text-decoration:underline; }
    .msg { text-align:center; color:#d4b76f; margin-bottom:10px; }
  </style>
</head>
<body>

<h1></h1>
<a href="logout.php" class="logout">Logout</a>
<?php if ($message): ?><p class="msg"><?= $message ?></p><?php endif; ?>

<table>
  <tr>
    <th>Full Name</th>
    <th>Email</th>
    <th>Birthdate</th>
    <th>Course</th>
    <th>Confirmed?</th>
    <th>Registered At</th>
    <th>Action</th>
  </tr>

<?php if (!empty($data)): ?>
  <?php foreach ($data as $row): ?>
  <tr>
    <td><?= htmlspecialchars($row['nama']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>
    <td><?= htmlspecialchars($row['course']) ?></td>
    <td><?= !empty($row['confirmed']) ? '✅ Yes' : '❌ No' ?></td>
    <td><?= htmlspecialchars($row['registered_at']) ?></td>
    <td><a href="?delete=<?= urlencode($row['email']) ?>" class="delete-btn" onclick="return confirm('Delete this record?')">Delete</a></td>
  </tr>
  <?php endforeach; ?>
<?php else: ?>
  <tr><td colspan="7" style="text-align:center; color:#999;">No registrations yet</td></tr>
<?php endif; ?>

</table>

</body>
</html>

