@php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"usulan_pkm.xls\"");
@endphp
<table border="1">
    <thead>
        <tr>
            <th colspan="11">Laporan Pengajuan PKM</th>
        </tr>
        <tr>
            <th colspan="11">Universitas Diponegoro</th>
        </tr>
        <tr>
            <th colspan="11">Tahun {{ $tahun }}</th>
        </tr>
        <tr>
            <th colspan="11">&nbsp;</th>
        </tr>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Judul</th>
            <th colspan="5">Mahasiswa</th>
            <th rowspan="2">Kegiatan</th>
            <th rowspan="2">SubKegiatan</th>
            <th rowspan="2">File Proposal</th>
            <th rowspan="2">SubmittedAt</th>
        </tr>
        <tr>
            <th>Nama</th>
            <th>NIM</th>
            <th>Jabatan</th>
            <th>Fakultas</th>
            <th>Prodi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($usulan_pkm_list as $usulan_pkm)
        <tr>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}">{{ $loop->iteration }}</td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><b>{{ $usulan_pkm->judul }}</b></td>
            @php
            $mhs = $usulan_pkm->anggota_pkm()->where('sebagai', 0)->first()->mhs;
            @endphp
            <td><b style="white-space: nowrap;">{{ $mhs->nama }}</b></td>
            <td><b>{{ "'". $mhs->nim }}</b></td>
            <td><b>{{ "Ketua" }}</b></td>
            <td><b>{{ $mhs->nama_fak_ijazah }}</b></td>
            <td><b>{{ $mhs->nama_forlap }}</b></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><b>{{ $usulan_pkm->jenis_pkm->kategori_kegiatan->nama_kategori_kegiatan }}</b></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><b>{{ $usulan_pkm->jenis_pkm->nama_pkm }}</b></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}">
                @foreach ($usulan_pkm->usulan_pkm_dokumen()->take(1)->get() as $i => $usulan_pkm_dokumen)
                <b style="display:block;"><a href="{{ asset('storage/' . $usulan_pkm_dokumen->document_path ) }}" target="_blank">{{ 'dokumen('. ($i + 1) . ')' }}</a></b>, 
                @endforeach
            </td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><b>{{ $usulan_pkm->created_at }}</b></td>
        </tr>
        @foreach ($usulan_pkm->anggota_pkm()->where('sebagai', 1)->get() as $anggota_pkm)
        <tr>
            <td><b style="white-space: nowrap;">{{ $anggota_pkm->mhs->nama }}</b></td>
            <td><b>{{ "'". $anggota_pkm->mhs->nim }}</b></td>
            <td><b>{{ "Anggota" }}</b></td>
            <td><b>{{ $anggota_pkm->mhs->nama_fak_ijazah }}</b></td>
            <td><b>{{ $anggota_pkm->mhs->nama_forlap }}</b></td>
        </tr>
        @endforeach
        @endforeach
    </tbody>
</table>
