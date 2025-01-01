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
								<div class="form-body">
                                    
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-center align-middle">No</th>
                                                <th class="text-center align-middle">Judul</th>
                                                <th class="text-center align-middle">Ketua</th>
                                                <th class="text-center align-middle">NIM</th>
                                                <th class="text-center align-middle">Fakultas</th>
                                                <th class="text-center align-middle">Nilai1</th>
                                                <th class="text-center align-middle">Nilai2</th>
                                                <th class="text-center align-middle">NilaiAkhir</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($usulan_pkm_list as $usulan_pkm)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td><b>{{ $usulan_pkm->judul }}</b></td>
                                                <td><b>{{ $usulan_pkm->mhs->nama }}</b></td>
                                                <td><b>{{ $usulan_pkm->mhs->nim }}</b></td>
                                                <td><b>{{ $usulan_pkm->mhs->fakultas->nama_fak_ijazah }}</b></td>
                                                @if($usulan_pkm->status_usulan_id == 8)
                                                    <td class="text-center align-middle">
                                                        @php
                                                        $reviewer1 = $usulan_pkm->reviewer_usulan_pkm()->where('urutan', 1)->first();
                                                        @endphp
                                                        <span class="text-danger">
                                                            <b><a target="_blank" href="{{ route('penilaian-reviewer.lihat', ['usulan_pkm' => $usulan_pkm, 'reviewer' => $reviewer1->reviewer_id]) }}">{{ $usulan_pkm->penilaian_reviewer()->where('reviewer_id', $reviewer1->reviewer_id)->distinct()->sum('nilai') }}</a></b>
                                                        </span>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        @php
                                                        $reviewer2 = $usulan_pkm->reviewer_usulan_pkm()->where('urutan', 2)->first();   
                                                        @endphp
                                                        @if(!empty($reviewer2))
                                                        <span class="text-danger">
                                                            <b><a target="_blank" href="{{ route('penilaian-reviewer.lihat', ['usulan_pkm' => $usulan_pkm, 'reviewer' => $reviewer2->reviewer_id]) }}">{{ $usulan_pkm->penilaian_reviewer()->where('reviewer_id', $reviewer2->reviewer_id)->distinct()->sum('nilai') }}</a></b>
                                                        </span>
                                                        @else
                                                        <span class="text-danger"><b>0</b></span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <span class="text-danger"><b>{{ $usulan_pkm->nilai_total }}</b></span>
                                                    </td>
                                                @else
                                                    <td class="text-center align-middle">
                                                        <span class="text-danger"><b>0</b></span>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <span class="text-danger"><b>0</b></span>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <span class="text-danger"><b>0</b></span>
                                                    </td>
                                                @endif
                                            </tr>
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
