<?php
session_start();
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nama = $_POST["nama"] ?? "";
  $email_tujuan = $_POST["email_tujuan"] ?? "";
  $password = $_POST["password"] ?? "";
  $tanggal_lahir = $_POST["tanggal_lahir"] ?? "";

  if ($nama && $email_tujuan && $password && $tanggal_lahir) {
    $to = $email_tujuan;
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: Cloud Computing 2025 <no-reply@" . $_SERVER['SERVER_NAME'] . ">\r\n";

  $data = [
    'nama' => $nama,
    'email' => $email_tujuan,
    'tanggal_lahir' => $tanggal_lahir,
    'course' => 'Cloud Computing 2025',
    'confirmed' => false,
    'registered_at' => date('Y-m-d H:i:s')
  ];

  $file = 'registrations.json';
  $existing = [];

  if (file_exists($file)) {
    $existing = json_decode(file_get_contents($file), true) ?? [];
  }

  $existing[] = $data;
  file_put_contents($file, json_encode($existing, JSON_PRETTY_PRINT));


    $template = file_get_contents('email_template.html');

    $htmlMessage = str_replace(
      ['{{nama}}', '{{email_tujuan}}', '{{tanggal_lahir}}'],
      [$nama, $email_tujuan, $tanggal_lahir],
      $template
    );

    if (mail($to, "Registration Confirmation - Cloud Computing 2025", $htmlMessage, $headers)) {
      $_SESSION['email_tujuan'] = $email_tujuan;
      $_SESSION['status'] = "success";
      header("Location: mail.php");
      exit();
    } else {
      $_SESSION['email_tujuan'] = $email_tujuan;
      $_SESSION['status'] = "failed";
      header("Location: mail.php");
      exit();
    }
  } else {
    $message = "⚠️ Please fill all required fields!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cloud Computing 2025 Registration</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: "Poppins", sans-serif;
    }

    body {
      background-color: #0b0b0b;
      color: #fff;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .form-container {
      background: #111;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 0 25px rgba(255, 215, 0, 0.2);
      width: 100%;
      max-width: 500px;
      border: 1px solid rgba(255, 215, 0, 0.2);
      text-align: left;
    }

    h1 {
      text-align: center;
      color: #d4af37;
      font-weight: 600;
    }
    
    h2 {
      text-align: center;
      color: #d4af37;
      margin-bottom: 30px;
      font-size: 16px;
      opacity: 0.7;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
    }

    input {
      width: 100%;
      background: #1a1a1a;
      color: #fff;
      border: 1px solid #333;
      padding: 10px 14px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 14px;
      transition: border 0.3s, box-shadow 0.3s;
    }

    input:focus {
      border: 1px solid #d4af37;
      outline: none;
      box-shadow: 0 0 10px rgba(212, 175, 55, 0.2);
    }

    button {
      width: 100%;
      background: linear-gradient(to right, #c9a227, #f1d06b);
      color: #000;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 15px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: linear-gradient(to right, #f1d06b, #c9a227);
      box-shadow: 0 0 12px rgba(255, 215, 0, 0.3);
      transform: translateY(-1px);
    }

    #password_error {
      color: #ff4d4d;
      display: none;
      font-size: 13px;
      margin-top: -15px;
      margin-bottom: 15px;
    }

    footer {
      margin-top: 40px;
      color: #777;
      font-size: 13px;
      text-align: center;
    }

  </style>
</head>
<body>

  <div class="form-container">
    <h1>Cloud Computing 2025</h1>
    <h2>Registration Form</h2>
    <?php if ($message): ?>
      <p class="msg"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST" action="index.php" onsubmit="return validatePassword()">
      <label>Full Name:</label>
      <input type="text" name="nama" placeholder="Example: John Doe" required>
      
      <label>Student Email:</label>
      <input type="email" name="email_tujuan" placeholder="Example: JohnDoe@email.com" required>

      <label>Password:</label>
      <input type="password" id="password" name="password" placeholder="Create a secure password" required>
    
      <label>Confirm Password:</label>
      <input type="password" id="confirm_password" placeholder="Re-enter your password" required>
      
      <small id="password_error">Passwords not match!</small>
      
      <label>Birth Date:</label>
      <input type="date" name="tanggal_lahir" required>

      <button type="submit">Register</button>
    </form>
  </div>

  <footer>© 2025 David Cloud Computing Registration System 😎</footer>

  <script>
  function validatePassword() {
    const pass = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    const errorText = document.getElementById('password_error');
    const birthInput = document.querySelector('input[name="tanggal_lahir"]');
    const birthValue = new Date(birthInput.value);
    const today = new Date();

    today.setHours(0,0,0,0);
    birthValue.setHours(0,0,0,0);

    if (pass !== confirm) {
      errorText.style.display = 'block';
      return false;
    } else {
      errorText.style.display = 'none';
    }

    if (birthValue >= today) {
      alert("⚠️ Birth date must be earlier than today!");
      return false;
    }

    return true;
  }
</script>


</body>
</html>

