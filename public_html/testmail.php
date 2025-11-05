<?php
$to = "alamatemailkamu@gmail.com";
$subject = "Tes Kirim Email dari PHP";
$message = "Halo! Ini email tes dari server kamu.";
$headers = "From: noreply@david.pakevan.web.id";

if (mail($to, $subject, $message, $headers)) {
    echo "✅ Email berhasil dikirim!";
} else {
    echo "❌ Gagal mengirim email.";
}
?>
