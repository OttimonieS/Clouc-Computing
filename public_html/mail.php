<?php
session_start();

$email_tujuan = $_SESSION['email_tujuan'] ?? 'Unknown';
$status = $_SESSION['status'] ?? 'failed';

session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Status</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600&display=swap');
    body {
      font-family: 'Montserrat', sans-serif;
      background: linear-gradient(135deg, #0c0c0c, #1a1a1a);
      color: #f5f5f5;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      flex-direction: column;
    }
    .box {
      background: #111;
      border: 1px solid rgba(197,164,77,0.3);
      border-radius: 18px;
      box-shadow: 0 0 25px rgba(197,164,77,0.15);
      text-align: center;
      padding: 40px 60px;
      max-width: 420px;
      animation: fadeIn 0.6s ease;
    }
    h1 {
      color: #c5a44d;
      margin-bottom: 10px;
      font-size: 24px;
    }
    p {
      color: #ddd;
      margin-bottom: 25px;
      line-height: 1.5;
    }
    .timer {
      color: #f1c75b;
      font-weight: 600;
      font-size: 18px;
      margin-bottom: 15px;
    }
    footer {
      margin-top: 40px;
      color: #777;
      font-size: 13px;
      text-align: center;
    }
    a {
      display: inline-block;
      background: linear-gradient(90deg, #c5a44d, #e6c67a);
      color: #111;
      padding: 12px 20px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    a:hover {
      background: linear-gradient(90deg, #e6c67a, #c5a44d);
      transform: scale(1.05);
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>

<body>
  <div class="box">
    <?php if ($status === "success"): ?>
      <h1>Registration Success!</h1>
      <p>
        A confirmation email has been sent to<br>
        <b><?= htmlspecialchars($email_tujuan) ?></b>.<br><br>
        Please confirm your registration within <b>5 minutes</b>.
      </p>
      <p>Otherwise, your registration link will expire automatically.</p>
    <?php else: ?>
  <h1>😕 Oops! Something went wrong</h1>
  <h2 style="color:#ccc; font-weight:400; line-height:1.5; margin-top:10px; font-size:15px; opacity:0.85;">
    Please check your email and confirm your registration within <b>5 minutes</b> if you have already registered.<br><br>
    Otherwise, please register again to receive a new confirmation email.
  </h2>
<?php endif; ?>
    <a href="index.php">Back</a>
  </div>

  <footer>© 2025 David Cloud Computing Registration System 😎</footer>

  
</body>
</html>

