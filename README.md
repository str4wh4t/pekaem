Spesifikasi

1. Laravel 6
2. php >7.2

Install

1. composer update
2. composer install
3. php artisan storage:link
4. php artisan serve

Todo List :

1. - membuat master kategori , cont : PKM, P2MU, dll
2. - membuat master subkategori , cont : PKM-C, PKM-RE
3. - membuat master kriteria_subkategori , field : nama_kriteria, bobot, score, nilai = bobot \* score
4. - pada master subkategori ditambah score_min untuk penentuan kelulusan
5. - dosen pembimbing filter dari dosen aktif ber-nidn
6. - tambah role wd1 / task force fakultas dari persetujuan pembimbing
7. - di reviewer hanya memberikan score berdasarkan penilainan kriteria
8. - manajemen menu yang tidak untuk mahasiswa dihapus
9. - caption pilihan pembimbing pkm (dosen nidn)
10. - pada pencarian msh ditampilkan nama - nim - prodi - fakultas

Todo list (24/12/2024)

1. - menghapus role mhs, pembimbing, taskforce menjadi satu role yaitu operator_fakultas
2. - dari operator_fakultas masuk ke approval wd1 fakultas (dibuat bulk approval)
3. - dari wd1 fakultas masuk ke reviewer universitas
4. - mhs yang sudah dipilih tidak bisa diikutkan lagi menjadi ketua atau anggota
5. - dosen pembimbing dibatasi hanya bisa 10 bimbingan
6. - tambah caption reviewer 1 dan 2
7. - skor penilian di isi dengan model dropdown dengan nilai (1-3) dan (5-7)
8. - penilaian pasangan reviewer ditampilkan di entri nilai reviewer
