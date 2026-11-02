<?php
$judul_halaman = 'Kontak';
$errors = [];
$sukses = false;
$nama = $email = $kontak = $pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama   = trim($_POST['nama'] ?? '');
  $email  = trim($_POST['email'] ?? '');
  $kontak = trim($_POST['kontak'] ?? '');
  $pesan  = trim($_POST['pesan'] ?? '');

  if (empty($nama)) { $errors[] = 'Nama wajib diisi.'; }

  if (empty($email)) {
    $errors[] = 'Email wajib diisi.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Format email tidak valid.';
  }

  // Kontak boleh kosong, tapi kalau diisi harus berupa nomor
  if (!empty($kontak) && !preg_match('/^[0-9+\-\s]{8,20}$/', $kontak)) {
    $errors[] = 'Kontak harus berupa nomor (8-20 karakter, hanya angka, +, atau -).';
  }

  if (empty($pesan)) { $errors[] = 'Pesan wajib diisi.'; }

  if (count($errors) === 0) {
    $sukses = true;
    // Simpan pesan ke berkas txt (kalau gagal menyimpan, halaman tetap jalan)
    $baris = date('Y-m-d H:i') . ' | ' . str_replace(["\r", "\n"], ' ', "$nama | $email | $kontak | $pesan") . "\n";
    @file_put_contents(__DIR__ . '/pesan.txt', $baris, FILE_APPEND);
  }
}

include 'includes/header.php';
include 'includes/nav.php';
?>
<main>
  <div id="content">
    <article class="<?= $sukses ? 'sukses' : '' ?>">
      <h1>Hubungi Saya</h1>

      <?php if ($sukses): ?>
        <h2>Terima kasih, <?= htmlspecialchars($nama) ?>! 🎉</h2>
        <p>Pesanmu sudah berhasil terkirim. Terima kasih sudah menghubungi saya, saya akan membalas secepatnya.</p>
        <h3>Ringkasan pesanmu</h3>
        <p><strong>Nama:</strong> <?= htmlspecialchars($nama) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
        <p><strong>Kontak:</strong> <?= htmlspecialchars($kontak !== '' ? $kontak : '-') ?></p>
        <p><strong>Pesan:</strong> <?= htmlspecialchars($pesan) ?></p>
        <a class="btn" href="kontak.php">Kirim pesan lagi</a>
        <a class="btn" href="index.html">Kembali ke Beranda</a>
      <?php else: ?>
        <p>Silakan isi formulir jika ingin mengirim pesan.</p>
        <?php foreach ($errors as $e): ?>
          <p class="error"><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>

        <form method="POST" action="kontak.php">
          <label for="nama">Nama</label>
          <input type="text" id="nama" name="nama" placeholder="Masukkan nama kamu" value="<?= htmlspecialchars($nama) ?>">
          <label for="email">Email</label>
          <input type="text" id="email" name="email" placeholder="Masukkan email kamu" value="<?= htmlspecialchars($email) ?>">
          <label for="kontak-hp">Kontak</label>
          <input type="text" id="kontak-hp" name="kontak" placeholder="Masukkan nomor HP kamu" value="<?= htmlspecialchars($kontak) ?>">
          <label for="pesan">Pesan</label>
          <textarea id="pesan" name="pesan" rows="5" placeholder="Tulis pesan kamu"><?= htmlspecialchars($pesan) ?></textarea>
          <button type="submit">Kirim Pesan</button>
        </form>
      <?php endif; ?>
    </article>
  </div>

  <aside>
    <h2>Navigasi</h2>
    <p><a href="index.html">&larr; Kembali ke Beranda</a></p>
  </aside>
</main>
<?php include 'includes/footer.php'; ?>