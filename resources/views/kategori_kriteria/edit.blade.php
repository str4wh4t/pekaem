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
        <h3 class="content-header-title">Edit Kategori Kriteria</h3>
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
                        <h4 class="card-title">Edit Kategori Kriteria</h4>
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
                            <form class="form" action="{{ route('kategori-kriteria.update', ['kategori_kriterium' => $kategori_kriteria]) }}" method="POST">
								{{ csrf_field() }}
                                @method('PUT')
								<div class="form-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="nama_kategori_kriteria">Nama Kategori Kriteria</label>
												<input type="text" id="nama_kategori_kriteria" class="form-control" placeholder="Nama Kategori Kriteria" name="nama_kategori_kriteria" value="{{ old('nama_kategori_kriteria', $kategori_kriteria->nama_kategori_kriteria) }}" >
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="deskripsi">Deskripsi</label>
												<input type="text" id="deskripsi" class="form-control" placeholder="Deskripsi" name="deskripsi" value="{{ old('deskripsi', $kategori_kriteria->deskripsi) }}" >
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
                                        <a class="btn btn-warning" href="{{ route('kategori-kriteria.index') }}">
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
        <form action="{{ route('kategori-kriteria.destroy', ['kategori_kriterium' => $kategori_kriteria]) }}" method="POST">
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
