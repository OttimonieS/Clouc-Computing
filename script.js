document.getElementById("contactForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  const form = e.target;
  const formData = new URLSearchParams(new FormData(form));

  try {
    const response = await fetch("mail.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: formData,
    });

    const text = await response.text();
    console.log("Server response:", text);

    let result;
    try {
      result = JSON.parse(text);
    } catch {
      result = { error: text };
    }

    if (result.message) {
      alert("✅ " + result.message);
    } else if (result.error) {
      alert("⚠️ " + result.error);
    } else {
      alert("❌ Respons server tidak dikenali.");
    }
  } catch (err) {
    alert("❌ Gagal koneksi ke server.");
    console.error(err);
  }
});

