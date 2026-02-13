@php
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"usulan_pkm_laporan_lr_1.xls\"");
@endphp
<table border="1">
    <thead>
        <tr>
            <th colspan="18">Laporan Pengajuan PKM</th>
        </tr>
        <tr>
            <th colspan="18">Universitas Diponegoro</th>
        </tr>
        <tr>
            <th colspan="18">Tahun {{ $tahun }}</th>
        </tr>
        <tr>
            <th colspan="18">&nbsp;</th>
        </tr>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Judul</th>
            <th colspan="7">Mahasiswa</th>
            <th rowspan="2">Kegiatan</th>
            <th rowspan="2">SubKegiatan</th>
            <th rowspan="2">Tema</th>
            <th colspan="4">Dosen Pendamping</th>
            <th rowspan="2">File Proposal</th>
            <th rowspan="2">SubmittedAt</th>
            <th rowspan="2" colspan="2">Catatan</th>
        </tr>
        <tr>
            <th>Nama</th>
            <th>NIM</th>
            <th>Jabatan</th>
            <th>Fakultas</th>
            <th>Prodi</th>
            <th>Email</th>
            <th>No Telp</th>
            <th>Nama</th>
            <th>NUPTK</th>
            <th>Email</th>
            <th>Hp</th>
            <th>Reviewer 1</th>
            <th>Reviewer 2</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($usulan_pkm_list as $usulan_pkm)
        <tr>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}">{{ $loop->iteration }}</td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span>{{ $usulan_pkm->judul }}</span></td>
            @php
            $mhs = $usulan_pkm->anggota_pkm()->where('sebagai', 0)->first()->mhs;
            @endphp
            <td><span style="white-space: nowrap;">{{ $mhs->nama }}</span></td>
            <td><span>{{ "'". $mhs->nim }}</span></td>
            <td><span>{{ "Ketua" }}</span></td>
            <td><span>{{ $mhs->nama_fak_ijazah }}</span></td>
            <td><span>{{ $mhs->nama_forlap }}</span></td>
            <td><span>{{ $usulan_pkm->mhs_email }}</span></td>
            <td><span>{{ "'" . $usulan_pkm->mhs_no_telp }}</span></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span>{{ $usulan_pkm->jenis_pkm->kategori_kegiatan->nama_kategori_kegiatan }}</span></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span>{{ $usulan_pkm->jenis_pkm->nama_pkm }}</span></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span>{{ !empty($usulan_pkm->tema_usulan_pkm_id) ? $usulan_pkm->tema_usulan_pkm->nama_tema : "-" }}</span></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span style="white-space: nowrap;">{{ $usulan_pkm->pegawai->glr_dpn . ' ' . $usulan_pkm->pegawai->nama . ' ' . $usulan_pkm->pegawai->glr_blkg }}</span></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span>{{ "'". $usulan_pkm->pegawai->nuptk }}</span></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span>{{ $usulan_pkm->pegawai_email_sso }}</span></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span>{{ "'" . $usulan_pkm->pegawai_hp }}</span></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}">
                @foreach ($usulan_pkm->usulan_pkm_dokumen()->take(1)->get() as $i => $usulan_pkm_dokumen)
                <b style="display:block;"><a href="{{ asset('storage/' . $usulan_pkm_dokumen->document_path ) }}" target="_blank">{{ 'dokumen('. ($i + 1) . ')' }}</a></span>, 
                @endforeach
            </td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span>{{ $usulan_pkm->created_at }}</span></td>
            @php
            $reviewer1 = $usulan_pkm->reviewer_usulan_pkm()->where('urutan', 1)->first();
            $catatan_reviewer1 = "";
            if (!empty($reviewer1)) {
                $review1 = $usulan_pkm->review()->where('pegawai_id', $reviewer1->reviewer_id)->first();
                if (!empty($review1)) {
                    $catatan_reviewer1 = $review1->catatan_reviewer;
                }
            }
            @endphp
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span>{{ $catatan_reviewer1 }}</span></td>
            @php
            $reviewer2 = $usulan_pkm->reviewer_usulan_pkm()->where('urutan', 2)->first();
            $catatan_reviewer2 = "";
            if (!empty($reviewer2)) {
                $review2 = $usulan_pkm->review()->where('pegawai_id', $reviewer2->reviewer_id)->first();
                if (!empty($review2)) {
                    $catatan_reviewer2 = $review2->catatan_reviewer;
                }
            }
            @endphp
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span>{{ $catatan_reviewer2 }}</span></td>
        </tr>
        @foreach ($usulan_pkm->anggota_pkm()->where('sebagai', 1)->get() as $anggota_pkm)
        <tr>
            <td><span style="white-space: nowrap;">{{ $anggota_pkm->mhs->nama }}</span></td>
            <td><span>{{ "'". $anggota_pkm->mhs->nim }}</span></td>
            <td><span>{{ "Anggota" }}</span></td>
            <td><span>{{ $anggota_pkm->mhs->nama_fak_ijazah }}</span></td>
            <td><span>{{ $anggota_pkm->mhs->nama_forlap }}</span></td>
            <td><span style="background-color: #aaaaaa;">&nbsp;</span></td>
            <td><span style="background-color: #aaaaaa;">&nbsp;</span></td>
        </tr>
        @endforeach
        @endforeach
    </tbody>
</table>
