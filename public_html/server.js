import express from "express";
import { PrismaClient } from "@prisma/client";
import bodyParser from "body-parser";
import nodemailer from "nodemailer";
import dotenv from "dotenv";
import path, { dirname } from "path";
import { fileURLToPath } from "url";

dotenv.config();

const prisma = new PrismaClient();
const app = express();

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

app.use(express.static(__dirname));
app.use(bodyParser.json());

const transporter = nodemailer.createTransport({
  host: process.env.MAIL_HOST,
  port: Number(process.env.MAIL_PORT),
  secure: false,
  auth: {
    user: process.env.MAIL_USERNAME,
    pass: process.env.MAIL_PASSWORD,
  },
  tls: {
    rejectUnauthorized: false,
  },
});

transporter.verify((error, success) => {
  if (error) {
    console.error("❌ SMTP connection error:", error);
  } else {
    console.log("✅ SMTP server is ready to send emails!");
  }
});

app.post("/submit", async (req, res) => {
  try {
    const { subject, nama, email_tujuan, tanggal_lahir, pesan } = req.body;
    const tanggalDate = tanggal_lahir ? new Date(tanggal_lahir) : null;

    const newMsg = await prisma.message.create({
      data: { subject, nama, email_tujuan, tanggal_lahir: tanggalDate, pesan },
    });

    const mailOptions = {
      from: `${process.env.MAIL_FROM_NAME || "Mailer"} <${process.env.MAIL_FROM_ADDRESS}>`,
      to: email_tujuan,
      subject: subject || "Pesan Baru dari Form",
      html: `
        <div style="font-family:'Segoe UI',Arial,sans-serif;background:#f4f4f4;padding:30px;">
          <div style="max-width:600px;margin:auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 15px rgba(0,0,0,0.08);">
            <div style="background:linear-gradient(135deg,#bfa16a,#8e793e);text-align:center;padding:25px;">
              <h2 style="color:#fff;margin:0;">Pesan Baru dari ${nama}</h2>
            </div>
            <div style="padding:25px 30px;color:#333;">
              <p><b>Nama:</b> ${nama}</p>
              <p><b>Tanggal Lahir:</b> ${tanggal_lahir || "-"}</p>
              <p><b>Email Tujuan:</b> 
                <a href="mailto:${email_tujuan}" style="color:#0077cc;text-decoration:none;">${email_tujuan}</a>
              </p>
              <div style="margin-top:20px;">
                <p><b>Isi Pesan:</b></p>
                <div style="background:#f9f9f9;padding:15px;border-left:5px solid #bfa16a;border-radius:6px;">
                  ${pesan}
                </div>
              </div>
            </div>
            <div style="background:#fafafa;color:#666;text-align:center;font-size:13px;padding:15px;border-top:1px solid #eee;">
              <p style="margin:0;">Dikirim otomatis oleh sistem mail test Cloud Computing 💻</p>
            </div>
          </div>
        </div>
      `,
    };

    await transporter.sendMail(mailOptions);
    console.log(`📩 Email terkirim ke: ${email_tujuan}`);

    res.json({ id: newMsg.id, message: "✅ Data tersimpan & email terkirim!" });
  } catch (err) {
    console.error("❌ Error utama:", err);
    res.status(500).json({ error: "Gagal menyimpan atau mengirim email" });
  }
});

app.get("/messages", async (req, res) => {
  try {
    const data = await prisma.message.findMany({
      orderBy: { id: "desc" },
    });
    res.json(data);
  } catch (err) {
    res.status(500).json({ error: "Gagal mengambil data" });
  }
});

const PORT = process.env.PORT || 8080;
const HOST = process.env.HOST || "0.0.0.0";

app.listen(PORT, HOST, () => {
  console.log(`✅ Server running at http://${HOST}:${PORT}`);
});

