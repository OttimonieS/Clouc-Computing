<?php
// confirm.php

// Ambil data dari URL (simulasi data kiriman dari email)
$nama = $_GET['nama'] ?? null;
$email = $_GET['email'] ?? null;
$tanggal_lahir = $_GET['tanggal_lahir'] ?? null;
$course = "Cloud Computing 2025";

// File tempat menyimpan data pendaftaran
$file = 'registrations.json';

// Status default
$status = "";

// Cek apakah data valid
if (!$email || !$nama) {
  $status = "⚠️ Invalid or incomplete confirmation link!";
} else {
  // Coba baca file registrasi
  if (file_exists($file)) {
    $data = json_decode(file_get_contents($file), true) ?? [];

    // Cari user berdasarkan email
    $found = false;
    foreach ($data as &$entry) {
      if ($entry['email'] === $email) {
        $entry['confirmed'] = true; // ubah jadi confirmed
        $found = true;
        break;
      }
    }

    // Simpan kembali data yang sudah diupdate
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));

    if ($found) {
      $status = "✅ Your registration has been successfully confirmed!";
    } else {
      $status = "⚠️ Email not found in registration records!";
    }
  } else {
    $status = "⚠️ No registration record found!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registration Confirmation</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      background-color: #0b0b0b;
      color: #fff;
      font-family: "Poppins", sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .container {
      background: #111;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 0 25px rgba(255, 215, 0, 0.2);
      max-width: 550px;
      width: 90%;
      text-align: center;
      border: 1px solid rgba(255, 215, 0, 0.2);
    }

    h1 {
      color: #d4af37;
      font-weight: 600;
      margin-bottom: 15px;
    }

    p {
      font-size: 15px;
      color: #ddd;
      margin-bottom: 25px;
    }

    .data-box {
      background: #1a1a1a;
      border: 1px solid rgba(255, 215, 0, 0.15);
      padding: 20px;
      border-radius: 10px;
      color: #f1f1f1;
      text-align: left;
      font-size: 15px;
      line-height: 1.6;
      margin-bottom: 25px;
    }

    .data-box b {
      color: #d4af37;
    }

    a {
      display: inline-block;
      text-decoration: none;
      background: linear-gradient(to right, #c9a227, #f1d06b);
      color: #000;
      padding: 12px 30px;
      border-radius: 8px;
      font-weight: 600;
      transition: 0.3s;
      margin-bottom: 25px;
    }

    a:hover {
      background: linear-gradient(to right, #f1d06b, #c9a227);
      box-shadow: 0 0 12px rgba(255, 215, 0, 0.3);
      transform: translateY(-1px);
    }

    footer {
      margin-top: 20px;
      color: #777;
      font-size: 13px;
    }
  </style>
</head>
<body>

  <div class="container">
    <h1>Cloud Computing 2025</h1>
    <p><?= $status ?></p>

    <?php if ($email && $nama): ?>
      <div class="data-box">
        <b>Your Registered Data:</b><br><br>
        <b>Name:</b> <?= htmlspecialchars($nama) ?><br>
        <b>Email:</b> <?= htmlspecialchars($email) ?><br>
        <b>Birthdate:</b> <?= htmlspecialchars($tanggal_lahir) ?><br>
        <b>Course:</b> <?= htmlspecialchars($course) ?><br>
      </div>

      <p>Best of luck in this course! 🚀</p>
      <a href="index.php">Return to Home</a>
    <?php else: ?>
      <a href="index.php">Return to Main Page</a>
    <?php endif; ?>
  </div>

  <footer>© 2025 David Cloud Computing Registration System 😎</footer>

</body>
</html>

