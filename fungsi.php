<?php
// Mencari kata kunci pada judul atau deskripsi
function cari_konten($data, $kata) {
  $hasil = [];
  foreach ($data as $item) {
    if (stripos($item['judul'], $kata) !== false || stripos($item['deskripsi'], $kata) !== false) {
      $hasil[] = $item;
    }
  }
  return $hasil;
}

// Mencari item yang judulnya persis sama dengan kata kunci (tanpa peduli huruf besar/kecil)
function cari_judul_persis($data, $kata) {
  foreach ($data as $item) {
    if (strcasecmp($item['judul'], $kata) === 0) {
      return $item;
    }
  }
  return null;
}