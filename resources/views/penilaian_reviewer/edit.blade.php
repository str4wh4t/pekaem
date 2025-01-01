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

    $('.score-input').each(function () {
        const $input = $(this);
        const value = $input.val();
        const $row = $input.closest('tr');
        const bobot = parseFloat($row.find('.bobot b').text()); // Ambil nilai bobot
        const $nilai = $row.find('.nilai b'); // Elemen nilai

        // Hitung nilai jika input valid
        const score = parseFloat(value) || 0; // Konversi ke float atau default 0
        const hasil = score * bobot; // Hitung skor * bobot
        $nilai.text(hasil.toFixed(2)); // Tampilkan hasil dengan 2 desimal
    });

    // Hitung total saat halaman dimuat
    calculateTotal();
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
        <h3 class="content-header-title">Create Penilaian Reviewer</h3>
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
                        <h4 class="card-title">Create Penilaian Reviewer</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            {{-- <p class="card-text">Berikut adalah daftar admin yang terdaftar pada sistem PKM.</p> --}}
                            @if(session()->has('message'))
                                <div class="alert alert-success">
                                    <b>{{ session()->get('message') }}</b>
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li style="list-style: initial">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="alert alert-info">
                                <b>Subkegiatan : </b> {{ $usulan_pkm->jenis_pkm->nama_pkm }}
                            </div>
                            <div class="alert alert-info">
                                <b>Kategori Kriteria : </b> {{ $kategori_kriteria->nama_kategori_kriteria }}
                            </div>
                            <div class="alert alert-warning">
                                @php($urutan = $usulan_pkm->reviewer_usulan_pkm()->where('reviewer_id', $reviewer->id)->first()->urutan)
                                <b>Anda sebagai : Reviewer {{ $urutan }}</b> 
                                @if($usulan_pkm->reviewer_usulan_pkm()->count() > 1)
                                <b class="float-right">Lihat Penilaian : <a href="{{ route('penilaian-reviewer.lihat', ['usulan_pkm' => $usulan_pkm, 'reviewer' => $usulan_pkm->reviewer_usulan_pkm()->where('urutan', $urutan == 1 ? 2 : 1)->first()->reviewer_id ]) }}">Reviewer {{ $urutan == 1 ? "2" : "1" }}</a></b>
                                @endif
                            </div>
                            <form class="form" action="{{ route('penilaian-reviewer.update', ['usulan_pkm' => $usulan_pkm, 'penilaian_reviewer' => $penilaian_reviewer_edit]) }}" method="POST">
                                {{ csrf_field() }}
                                @method('PUT')
								<div class="form-body">
                                    
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-center align-middle">No</th>
                                                <th class="text-center align-middle">Kriteria</th>
                                                <th class="text-center align-middle">Bobot</th>
                                                <th class="text-center align-middle">Skor</th>
                                                <th class="text-center align-middle">Nilai</th>
                                                <th class="text-center align-middle">Komentar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kriteria_penilaian_list as $kriteria_penilaian)
                                            <tr>
                                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                <td class="align-middle"><b>{{ $kriteria_penilaian->nama_kriteria }}</b></td>
                                                <td class="text-center align-middle">
                                                    <span class="text-danger bobot"><b>{{ $kriteria_penilaian->bobot }}</b></span>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="form-group mb-0">
                                                        <select id="score_{{ $kriteria_penilaian->id }}" 
                                                                class="form-control form-control-sm text-center score-input" 
                                                                name="data[{{ $kriteria_penilaian->id }}][score]">
                                                            <option value="" disabled selected>Pilih Skor</option>
                                                            @foreach ([1, 2, 3, 5, 6, 7] as $score)
                                                                <option value="{{ $score }}" 
                                                                        {{ old('data.' . $kriteria_penilaian->id . '.score', $penilaian_reviewer[$kriteria_penilaian->id]['score'] ?? null) == $score ? 'selected' : '' }}>
                                                                    {{ $score }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="form-group mb-0">
                                                        <span class="text-danger nilai" id="nilai_{{ $kriteria_penilaian->id }}"><b>0</b></span>
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="form-group mb-0">
                                                        <input type="text" id="komentar_{{ $kriteria_penilaian->id }}" 
                                                            class="form-control form-control-sm" 
                                                            placeholder="" 
                                                            name="data[{{ $kriteria_penilaian->id }}][komentar]" 
                                                            value="{{ old('data.' . $kriteria_penilaian->id . '.komentar', $penilaian_reviewer[$kriteria_penilaian->id]['komentar']) }}">
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-right align-middle">Total</th>
                                                <th class="text-center align-middle">
                                                    <span id="total-nilai" class="text-danger"><b>0</b></span>
                                                </th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                </div>
                                <h4 class="form-section"><i class="fa fa-pencil"></i> Catatan Reviewer</h4>
									<div class="form-group">
										<textarea class="form-control" placeholder="Isian catatan reviewer" name="catatan_reviewer">{{ old('catatan_reviewer', $catatan_reviewer ) }}</textarea>
									</div>
                                <div class="form-actions">
									
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-check-square-o"></i> Save
                                        </button>

                                        <a class="btn btn-danger" onclick="return batalkan_penilaian()" href="{{ route('penilaian-reviewer.batal',['usulan_pkm' => $usulan_pkm, 'penilaian_reviewer' => $penilaian_reviewer_edit])  }}">
												<i class="fa fa-trash-o"></i> Batalkan
											</a>
                                    
                                        <a class="btn btn-warning" href="{{ route('share.pendaftaran.read', ['uuid' => $usulan_pkm->uuid]) }}">
                                            <i class="fa fa-undo"></i> Kembali
                                        </a>
								</div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
