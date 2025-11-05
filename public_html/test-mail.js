import nodemailer from "nodemailer";

const transporter = nodemailer.createTransport({
  host: "mail.david.pakevan.web.id",
  port: 587,
  secure: false, // karena 587 pakai TLS
  auth: {
    user: "tes@david.pakevan.web.id",
    pass: "@1234567890qwertyuiopP",
  },
  tls: { rejectUnauthorized: false },
});

transporter
  .sendMail({
    from: "tes@david.pakevan.web.id",
    to: "ddwijanuar@student.ciputra.ac.id", // ← ganti ke email asli kamu
    subject: "Tes Kirim Email Langsung",
    text: "Halo! Ini pesan uji coba dari Node.js via mail.david.pakevan.web.id",
  })
  .then((info) => {
    console.log("✅ Email berhasil dikirim!");
    console.log(info);
  })
  .catch((err) => console.error("❌ Gagal kirim email:", err));

