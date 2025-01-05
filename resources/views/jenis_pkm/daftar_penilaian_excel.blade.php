@php
header("Content-Type: application/vnd.ms-excel");
 header("Content-Disposition: attachment; filename=\"usulan_pkm.xls\"");
@endphp
<table border="1">
    <thead>
        <tr>
            <th colspan="13">Laporan Pengajuan PKM</th>
        </tr>
        <tr>
            <th colspan="13">Universitas Diponegoro</th>
        </tr>
        <tr>
            <th colspan="13">Tahun {{ $tahun }}</th>
        </tr>
        <tr>
            <th colspan="13">&nbsp;</th>
        </tr>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Judul</th>
            <th colspan="3">Mahasiswa</th>
            <th rowspan="2">Fakultas</th>
            <th colspan="2">Dosen Pendamping</th>
            <th rowspan="2">File Proposal</th>
            <th rowspan="2">SubmittedAt</th>
            <th rowspan="2">Nilai1</th>
            <th rowspan="2">Nilai2</th>
            <th rowspan="2">NilaiAkhir</th>
        </tr>
        <tr>
            <th>Nama</th>
            <th>NIM</th>
            <th>Jabatan</th>
            <th>Nama</th>
            <th>NIDN</th>
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
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><b>{{ $usulan_pkm->mhs->fakultas->nama_fak_ijazah }}</b></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><b style="white-space: nowrap;">{{ $usulan_pkm->pegawai->glr_dpn . ' ' . $usulan_pkm->pegawai->nama . ' ' . $usulan_pkm->pegawai->glr_blkg }}</b></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><b>{{ "'". $usulan_pkm->pegawai->nidn }}</b></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}">
                @foreach ($usulan_pkm->usulan_pkm_dokumen()->take(1)->get() as $i => $usulan_pkm_dokumen)
                <b style="display:block;"><a href="{{ asset('storage/' . $usulan_pkm_dokumen->document_path ) }}" target="_blank">{{ 'dokumen('. ($i + 1) . ')' }}</a></b>, 
                @endforeach
            </td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><b>{{ $usulan_pkm->created_at }}</b></td>
            @if($usulan_pkm->status_usulan_id == 8)
                <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}">
                    @php
                    $reviewer1 = $usulan_pkm->reviewer_usulan_pkm()->where('urutan', 1)->first();
                    @endphp
                    <span>
                        <b><a href="{{ route('penilaian-reviewer.lihat', ['usulan_pkm' => $usulan_pkm, 'reviewer' => $reviewer1->reviewer_id]) }}">{{ $usulan_pkm->penilaian_reviewer()->where('reviewer_id', $reviewer1->reviewer_id)->distinct()->sum('nilai') }}</a></b>
                    </span>
                </td>
                <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}">
                    @php
                    $reviewer2 = $usulan_pkm->reviewer_usulan_pkm()->where('urutan', 2)->first();   
                    @endphp
                    @if(!empty($reviewer2))
                    <span>
                        <b><a href="{{ route('penilaian-reviewer.lihat', ['usulan_pkm' => $usulan_pkm, 'reviewer' => $reviewer2->reviewer_id]) }}">{{ $usulan_pkm->penilaian_reviewer()->where('reviewer_id', $reviewer2->reviewer_id)->distinct()->sum('nilai') }}</a></b>
                    </span>
                    @else
                    <span><b>0</b></span>
                    @endif
                </td>
                <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}">
                    <span><b>{{ $usulan_pkm->nilai_total }}</b></span>
                </td>
            @else
                <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}">
                    <span><b>0</b></span>
                </td>
                <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}">
                    <span><b>0</b></span>
                </td>
                <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}">
                    <span><b>0</b></span>
                </td>
            @endif
        </tr>
        @foreach ($usulan_pkm->anggota_pkm()->where('sebagai', 1)->get() as $anggota_pkm)
        <tr>
            <td><b style="white-space: nowrap;">{{ $anggota_pkm->mhs->nama }}</b></td>
            <td><b>{{ "'". $anggota_pkm->mhs->nim }}</b></td>
            <td><b>{{ "Anggota" }}</b></td>
        </tr>
        @endforeach
        @endforeach
    </tbody>
</table>
