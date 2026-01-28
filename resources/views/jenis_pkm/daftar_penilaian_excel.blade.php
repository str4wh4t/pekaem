@php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"usulan_pkm_laporan_lr_2.xls\"");
@endphp
<table border="1">
    <thead>
        <tr>
            <th colspan="21">Laporan Pengajuan PKM</th>
        </tr>
        <tr>
            <th colspan="21">Universitas Diponegoro</th>
        </tr>
        <tr>
            <th colspan="21">Tahun {{ $tahun }}</th>
        </tr>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Judul</th>
            <th colspan="7">Mahasiswa</th>
            <th colspan="4">Dosen Pendamping</th>
            <th rowspan="2">Tema</th>
            <th rowspan="2">File Proposal</th>
            <th rowspan="2">Submitted At</th>
            <th rowspan="2">Reviewer1</th>
            <th rowspan="2">Reviewer2</th>
            <th>Nilai Rev1</th>
            <th>Nilai Rev2</th>
            <th>Nilai Akhir</th>
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
            <th>HP</th>
            <th>a</th>
            <th>b</th>
            <th>(a+b)/2</th>
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
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span style="white-space: nowrap;">{{ $usulan_pkm->pegawai->glr_dpn . ' ' . $usulan_pkm->pegawai->nama . ' ' . $usulan_pkm->pegawai->glr_blkg }}</span></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span>{{ "'". $usulan_pkm->pegawai->nuptk }}</span></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span>{{ $usulan_pkm->pegawai_email_sso }}</span></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span>{{ "'" . $usulan_pkm->pegawai_hp }}</span></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span>{{ !empty($usulan_pkm->tema_usulan_pkm_id) ? $usulan_pkm->tema_usulan_pkm->nama_tema : "-" }}</span></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}">
                @foreach ($usulan_pkm->usulan_pkm_dokumen()->take(1)->get() as $i => $usulan_pkm_dokumen)
                <span style="display:block;"><a href="{{ asset('storage/' . $usulan_pkm_dokumen->document_path ) }}" target="_blank">{{ 'dokumen('. ($i + 1) . ')' }}</a></span>, 
                @endforeach
            </td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span>{{ $usulan_pkm->created_at }}</span></td>
            @if($usulan_pkm->reviewer_usulan_pkm->count() > 0)
            @php
            $reviewer1 = $usulan_pkm->reviewer_usulan_pkm()->where('urutan', 1)->first();
            @endphp
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span style="white-space: nowrap;">{{ $reviewer1->reviewer->glr_dpn . ' ' . $reviewer1->reviewer->nama . ' ' . $reviewer1->reviewer->glr_blkg }}</span></td>
                @php
                $reviewer2 = $usulan_pkm->reviewer_usulan_pkm()->where('urutan', 2)->first();   
                @endphp
                @if(!empty($reviewer2))
                <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span style="white-space: nowrap;">{{ $reviewer2->reviewer->glr_dpn . ' ' . $reviewer2->reviewer->nama . ' ' . $reviewer2->reviewer->glr_blkg }}</span></td>
                @else
                <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span>{{ "" }}</span></td>
                @endif
            @else
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span>{{ "" }}</span></td>
            <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}"><span>{{ "" }}</span></td>
            @endif
            @if($usulan_pkm->status_usulan_id > 4)
                <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}">
                    @php
                    $reviewer1 = $usulan_pkm->reviewer_usulan_pkm()->where('urutan', 1)->first();
                    @endphp
                    @if(!empty($reviewer1))
                    <span>
                        <span><a href="{{ route('penilaian-reviewer.lihat', ['usulan_pkm' => $usulan_pkm, 'reviewer' => $reviewer1->reviewer_id]) }}">{{ $usulan_pkm->penilaian_reviewer()->where('reviewer_id', $reviewer1->reviewer_id)->sum('nilai') }}</a></span>
                    </span>
                    @else
                    <span><span>0</span></span>
                    @endif
                </td>
                <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}">
                    @php
                    $reviewer2 = $usulan_pkm->reviewer_usulan_pkm()->where('urutan', 2)->first();   
                    @endphp
                    @if(!empty($reviewer2))
                    <span>
                        <span><a href="{{ route('penilaian-reviewer.lihat', ['usulan_pkm' => $usulan_pkm, 'reviewer' => $reviewer2->reviewer_id]) }}">{{ $usulan_pkm->penilaian_reviewer()->where('reviewer_id', $reviewer2->reviewer_id)->sum('nilai') }}</a></span>
                    </span>
                    @else
                    <span><span>0</span></span>
                    @endif
                </td>
                <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}">
                    <span><span>{{ "'" . $usulan_pkm->nilai_total }}</span></span>
                </td>
            @else
                <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}">
                    <span><span>0</span></span>
                </td>
                <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}">
                    <span><span>0</span></span>
                </td>
                <td rowspan="{{ $usulan_pkm->anggota_pkm()->count() }}">
                    <span><span>0</span></span>
                </td>
            @endif
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
