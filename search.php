<?php
$judul_halaman = 'Pencarian';
require 'data.php';
require 'fungsi.php';

$sudah_cari = isset($_GET['q']);
$kata  = trim($_GET['q'] ?? '');
$hasil = $daftar_konten;   // default: tampilkan semua bagian
$pesan = '';
$judul_hasil = 'Semua bagian website';

if ($sudah_cari) {
  if (empty($kata)) {
    $pesan = 'Kata kunci tidak boleh kosong.';
  } else {
    // 1. Judul persis sama (mis. "Kontak") -> langsung pindah ke bagian itu
    $persis = cari_judul_persis($daftar_konten, $kata);
    if ($persis !== null) {
      header('Location: ' . $persis['link']);
      exit;
    }

    $hasil = cari_konten($daftar_konten, $kata);

    // 2. Hanya ada satu hasil -> langsung pindah juga
    if (count($hasil) === 1) {
      header('Location: ' . $hasil[0]['link']);
      exit;
    }

    $judul_hasil = 'Hasil pencarian untuk "' . $kata . '"';
    if (count($hasil) === 0) {
      $pesan = 'Maaf, tidak ada hasil yang cocok dengan kata kunci tersebut.';
    }
  }
}

include 'includes/header.php';
include 'includes/nav.php';
?>
<main>
  <div id="content">
    <article>
      <h1>Cari Informasi</h1>
      <p>Ketik kata kunci, misalnya Beranda, Kontak, atau Excel.</p>
      <form method="GET" action="search.php">
        <label for="q">Kata kunci</label>
        <input type="text" id="q" name="q" placeholder="Contoh: Excel atau Kontak" value="<?= htmlspecialchars($kata) ?>">
        <button type="submit">Cari</button>
      </form>
    </article>

    <?php if ($pesan !== ''): ?>
      <p class="error"><?= htmlspecialchars($pesan) ?></p>
    <?php endif; ?>

    <?php if (count($hasil) > 0): ?>
      <p><strong><?= htmlspecialchars($judul_hasil) ?></strong> (klik untuk membuka)</p>
      <?php foreach ($hasil as $item): ?>
        <article class="hasil">
          <h2><a href="<?= htmlspecialchars($item['link']) ?>"><?= htmlspecialchars($item['judul']) ?></a></h2>
          <p><?= htmlspecialchars($item['deskripsi']) ?></p>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <aside>
    <h2>Navigasi</h2>
    <p><a href="index.html">&larr; Kembali ke Beranda</a></p>
  </aside>
</main>
<?php include 'includes/footer.php'; ?>