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

</script>
@endpush

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Edit Subkegiatan</h3>
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
                        <h4 class="card-title">Edit Subkegiatan</h4>
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
                                <b>Kategori Kegiatan : </b> {{ $kategori_kegiatan->nama_kategori_kegiatan }}
                            </div>
                            <form class="form" action="{{ route('jenis-pkm.update', ['kategori_kegiatan' => $kategori_kegiatan,'jenis_pkm' => $jenis_pkm]) }}" method="POST">
								{{ csrf_field() }}
                                @method('PUT')
								<div class="form-body">
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="nama_pkm">Nama Subkegiatan</label>
												<input type="text" id="nama_pkm" class="form-control" placeholder="Nama Subkegiatan" name="nama_pkm" value="{{ old('nama_pkm', $jenis_pkm->nama_pkm) }}" >
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="keterangan">Keterangan</label>
												<input type="text" id="keterangan" class="form-control" placeholder="Keterangan" name="keterangan" value="{{ old('keterangan', $jenis_pkm->keterangan) }}" >
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="score_min">Score Min</label>
												<input type="text" id="score_min" class="form-control" placeholder="Score Min" name="score_min" value="{{ old('score_min', $jenis_pkm->score_min) }}" >
											</div>
										</div>
                                        <div class="col-md-2">
											<div class="form-group">
												<label for="kamar">Kamar</label>
												<input type="number" id="kamar" class="form-control" placeholder="Kamar" name="kamar" value="{{ old('kamar', $jenis_pkm->kamar) }}" >
											</div>
										</div>
									</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="kategori_kriteria_id">Kriteria Penilaian</label>
                                                <select class="form-control" id="kategori_kriteria_id" name="kategori_kriteria_id">
                                                    <option value="" disabled {{ old('kategori_kriteria_id', $jenis_pkm->kategori_kriteria_id ?? '') == '' ? 'selected' : '' }}>
                                                        Pilih Kategori Kriteria
                                                    </option>
                                                    @foreach($kategori_kriteria_list as $kategori_kriteria)
                                                        <option value="{{ $kategori_kriteria->id }}" 
                                                            {{ old('kategori_kriteria_id', $jenis_pkm->kategori_kriteria_id ?? '') == $kategori_kriteria->id ? 'selected' : '' }}>
                                                            {{ $kategori_kriteria->nama_kategori_kriteria }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="form-actions">
									
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-check-square-o"></i> Save
                                        </button>
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalDelete">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                        <a class="btn btn-warning" href="{{ route('jenis-pkm.index',['kategori_kegiatan' => $kategori_kegiatan]) }}">
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
<!-- Modal -->
<div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form action="{{ route('jenis-pkm.destroy', ['kategori_kegiatan' => $kategori_kegiatan, 'jenis_pkm' => $jenis_pkm]) }}" method="POST">
        @csrf
        @method('DELETE')
            <div class="modal-header">
            <h5 class="modal-title" id="modalDeleteLabel">Perhatian</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            Yakin akan menghapus ?
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    Yakin
                </button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
