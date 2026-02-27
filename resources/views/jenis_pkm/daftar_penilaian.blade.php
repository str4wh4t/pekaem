@extends('template.main')

@push('page_level_css')
<!-- BEGIN PAGE VENDOR CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/vendors/css/forms/selects/select2.min.css') }}">
<!-- END PAGE VENDOR CSS-->
@endpush

@push('page_custom_css')
<style type="text/css">

</style>
@endpush

@push('page_vendor_level_js')
<!-- BEGIN PAGE VENDOR JS-->
<script src="{{ asset('assets/template/robust/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/template/robust/app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
<!-- END PAGE VENDOR JS-->
@endpush

@push('page_level_js')
<script type="text/javascript">

let csrf = $('meta[name=csrf-token]').attr("content");

// Default
$('.zero-configuration').DataTable();


$(document).ready(function () {
    // Fungsi untuk memvalidasi input angka dan float
    function validateNumberInput(value) {
        return /^-?\d*(\.\d*)?$/.test(value); // Hanya angka atau float
    }

    // Fungsi untuk menghitung total nilai
    function calculateTotal() {
        let total = 0;
        $('.nilai b').each(function () {
            total += parseFloat($(this).text()) || 0; // Ambil nilai dari setiap kolom Nilai
        });
        $('#total-nilai').text(total.toFixed(2)); // Tampilkan total dengan 2 desimal
    }

    // Event listener untuk input skor
    $('.score-input').on('input', function () {
        const $input = $(this);
        const value = $input.val();
        const $row = $input.closest('tr');
        const bobot = parseFloat($row.find('.bobot b').text()); // Ambil nilai bobot
        const $nilai = $row.find('.nilai b'); // Elemen nilai

        // Validasi input hanya menerima angka dan float
        if (!validateNumberInput(value)) {
            $input.val(value.slice(0, -1)); // Hapus karakter terakhir jika tidak valid
            return;
        }

        // Hitung nilai jika input valid
        const score = parseFloat(value) || 0; // Konversi ke float atau default 0
        const hasil = score * bobot; // Hitung skor * bobot
        $nilai.text(hasil.toFixed(2)); // Tampilkan hasil dengan 2 desimal

        // Hitung ulang total nilai
        calculateTotal();
    });

    // Hitung total saat halaman dimuat
    calculateTotal();

    $('.score-input').trigger('input'); // Trigger input untuk menghitung nilai saat halaman dimuat
});

function batalkan_penilaian(){
    if(confirm('Yakin akan dibatalkan ?')){
        return true;
    }
    return false;
}

</script>
@endpush

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Daftar Penilaian</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    {{-- <li class="breadcrumb-item"><a href="index.html">Home</a>
                    </li> --}}
                    {{-- <li class="breadcrumb-item"><a href="#">Form Layouts</a>
                    </li>
                    <li class="breadcrumb-item active">Basic Forms
                    </li> --}}
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content-body"><!-- Basic form layout section start -->
    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tabel Penilaian</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <div class="alert alert-info">
                                <b>Subkegiatan : </b> {{ $jenis_pkm->nama_pkm }}
                            </div>
                            <div class="alert alert-info">
                                <b>Kategori Kriteria : </b> {{ $jenis_pkm->kategori_kriteria->nama_kategori_kriteria }}
                            </div>
                            <div class="alert alert-info">
                                <b>Tahun : </b> {{ $tahun }}
                            </div>
                            <div class="block mb-1">
                                <a class="btn btn-primary" href="{{ route('jenis-pkm.daftar-penilaian-excel', ['kategori_kegiatan' => $jenis_pkm->kategori_kegiatan,'jenis_pkm' => $jenis_pkm, 'tahun' => $tahun]) }}"><i class="fa fa-file"></i> Laporan LR-2</a>
                                <a class="btn btn-warning" href="{{ route('kategori-kegiatan.show', ['kategori_kegiatan' => $jenis_pkm->kategori_kegiatan]) }}"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </div>
								<div class="form-body">
                                    
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-center align-middle" rowspan="2">No</th>
                                                <th class="text-center align-middle" rowspan="2">Judul</th>
                                                <th class="text-center align-middle" colspan="5">Mahasiswa</th>
                                                <th class="text-center align-middle" colspan="2">Dosen Pendamping</th>
                                                <th class="text-center align-middle" rowspan="2">Tema</th>
                                                <th class="text-center align-middle" rowspan="2">File Proposal</th>
                                                <th class="text-center align-middle" rowspan="2">Submitted At</th>
                                                <th class="text-center align-middle" rowspan="2">Reviewer1</th>
                                                <th class="text-center align-middle" rowspan="2">Reviewer2</th>
                                                <th class="text-center align-middle" >Nilai Rev1</th>
                                                <th class="text-center align-middle" >Nilai Rev2</th>
                                                <th class="text-center align-middle" >Nilai Akhir</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center align-middle">Nama</th>
                                                <th class="text-center align-middle">NIM</th>
                                                <th class="text-center align-middle">Jabatan</th>
                                                <th class="text-center align-middle" >Fakultas</th>
                                                <th class="text-center align-middle" >Prodi</th>
                                                <th class="text-center align-middle">Nama</th>
                                                <th class="text-center align-middle">NUPTK</th>
                                                <th class="text-center align-middle">a</th>
                                                <th class="text-center align-middle">b</th>
                                                <th class="text-center align-middle">(a+b)/2</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($usulan_pkm_list as $usulan_pkm)
                                            <tr>
                                                <td class="text-center" rowspan="{{ $usulan_pkm->anggota_count }}">{{ $loop->iteration }}</td>
                                                <td rowspan="{{ $usulan_pkm->anggota_count }}"><b>{{ $usulan_pkm->judul }}</b></td>
                                                @if($usulan_pkm->ketua && $usulan_pkm->ketua->mhs)
                                                <td><b style="white-space: nowrap;">{{ $usulan_pkm->ketua->mhs->nama }}</b></td>
                                                <td><b>{{ $usulan_pkm->ketua->mhs->nim }}</b></td>
                                                <td><b>{{ "Ketua" }}</b></td>
                                                <td><b>{{ $usulan_pkm->ketua->mhs->nama_fak_ijazah }}</b></td>
                                                <td><b>{{ $usulan_pkm->ketua->mhs->nama_forlap }}</b></td>
                                                @else
                                                <td><b>-</b></td>
                                                <td><b>-</b></td>
                                                <td><b>-</b></td>
                                                <td><b>-</b></td>
                                                <td><b>-</b></td>
                                                @endif
                                                <td rowspan="{{ $usulan_pkm->anggota_count }}"><b style="white-space: nowrap;">{{ $usulan_pkm->pegawai ? ($usulan_pkm->pegawai->glr_dpn . ' ' . $usulan_pkm->pegawai->nama . ' ' . $usulan_pkm->pegawai->glr_blkg) : '-' }}</b></td>
                                                <td rowspan="{{ $usulan_pkm->anggota_count }}"><b>{{ $usulan_pkm->pegawai ? $usulan_pkm->pegawai->nuptk : '-' }}</b></td>
                                                <td rowspan="{{ $usulan_pkm->anggota_count }}"><b>{{ $usulan_pkm->tema_usulan_pkm ? $usulan_pkm->tema_usulan_pkm->nama_tema : "-" }}</b></td>
                                                <td rowspan="{{ $usulan_pkm->anggota_count }}">
                                                    @foreach ($usulan_pkm->usulan_pkm_dokumen as $i => $usulan_pkm_dokumen)
                                                    <b style="display:block;"><a href="{{ asset('storage/' . $usulan_pkm_dokumen->document_path ) }}" target="_blank">{{ 'dokumen('. ($i + 1) . ')' }}</a></b>
                                                    @endforeach
                                                </td>
                                                <td rowspan="{{ $usulan_pkm->anggota_count }}"><b>{{ $usulan_pkm->created_at }}</b></td>
                                                @if($usulan_pkm->reviewer1 && $usulan_pkm->reviewer1->reviewer)
                                                <td rowspan="{{ $usulan_pkm->anggota_count }}"><b style="white-space: nowrap;">{{ $usulan_pkm->reviewer1->reviewer->glr_dpn . ' ' . $usulan_pkm->reviewer1->reviewer->nama . ' ' . $usulan_pkm->reviewer1->reviewer->glr_blkg }}</b></td>
                                                @else
                                                <td rowspan="{{ $usulan_pkm->anggota_count }}"><b>{{ "" }}</b></td>
                                                @endif
                                                @if($usulan_pkm->reviewer2 && $usulan_pkm->reviewer2->reviewer)
                                                <td rowspan="{{ $usulan_pkm->anggota_count }}"><b style="white-space: nowrap;">{{ $usulan_pkm->reviewer2->reviewer->glr_dpn . ' ' . $usulan_pkm->reviewer2->reviewer->nama . ' ' . $usulan_pkm->reviewer2->reviewer->glr_blkg }}</b></td>
                                                @else
                                                <td rowspan="{{ $usulan_pkm->anggota_count }}"><b>{{ "" }}</b></td>
                                                @endif
                                                @if($usulan_pkm->status_usulan_id > 4)
                                                    <td class="text-center" rowspan="{{ $usulan_pkm->anggota_count }}">
                                                        @if($usulan_pkm->reviewer1)
                                                        <span class="text-danger">
                                                            <b><a href="{{ route('penilaian-reviewer.lihat', ['usulan_pkm' => $usulan_pkm, 'reviewer' => $usulan_pkm->reviewer1->reviewer_id]) }}">{{ $usulan_pkm->nilai_reviewer1 }}</a></b>
                                                        </span>
                                                        @else
                                                        <span class="text-danger"><b>0</b></span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center" rowspan="{{ $usulan_pkm->anggota_count }}">
                                                        @if($usulan_pkm->reviewer2)
                                                        <span class="text-danger">
                                                            <b><a href="{{ route('penilaian-reviewer.lihat', ['usulan_pkm' => $usulan_pkm, 'reviewer' => $usulan_pkm->reviewer2->reviewer_id]) }}">{{ $usulan_pkm->nilai_reviewer2 }}</a></b>
                                                        </span>
                                                        @else
                                                        <span class="text-danger"><b>0</b></span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center" rowspan="{{ $usulan_pkm->anggota_count }}">
                                                        <span class="text-danger"><b>{{ $usulan_pkm->nilai_total }}</b></span>
                                                    </td>
                                                @else
                                                    <td class="text-center" rowspan="{{ $usulan_pkm->anggota_count }}">
                                                        <span class="text-danger"><b>0</b></span>
                                                    </td>
                                                    <td class="text-center" rowspan="{{ $usulan_pkm->anggota_count }}">
                                                        <span class="text-danger"><b>0</b></span>
                                                    </td>
                                                    <td class="text-center" rowspan="{{ $usulan_pkm->anggota_count }}">
                                                        <span class="text-danger"><b>0</b></span>
                                                    </td>
                                                @endif
                                            </tr>
                                            @foreach ($usulan_pkm->anggota_list as $anggota_pkm)
                                            <tr>
                                                <td><b style="white-space: nowrap;">{{ $anggota_pkm->mhs ? $anggota_pkm->mhs->nama : '-' }}</b></td>
                                                <td><b>{{ $anggota_pkm->mhs ? $anggota_pkm->mhs->nim : '-' }}</b></td>
                                                <td><b>{{ "Anggota" }}</b></td>
                                                <td><b>{{ $anggota_pkm->mhs ? $anggota_pkm->mhs->nama_fak_ijazah : '-' }}</b></td>
                                                <td><b>{{ $anggota_pkm->mhs ? $anggota_pkm->mhs->nama_forlap : '-' }}</b></td>
                                            </tr>
                                            @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
