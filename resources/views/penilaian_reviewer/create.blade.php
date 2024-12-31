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
                            <form class="form" action="{{ route('penilaian-reviewer.store', ['usulan_pkm' => $usulan_pkm]) }}" method="POST">
								{{ csrf_field() }}
                                <input type="hidden" name="urutan" value="{{ 1 }}">
								<div class="form-body">
                                    @foreach ( $kriteria_penilaian_list as $kriteria_penilaian )
									<div class="row">
										<div class="col-md-9 d-flex align-items-center">
											{{-- <div class="form-group"> --}}
												<label for="nama_kriteria_{{ $kriteria_penilaian->id }}"><b>{{ $kriteria_penilaian->nama_kriteria }}</b> [ bobot : <span class="text-danger"><b>{{ $kriteria_penilaian->bobot }}</b></span> ]</label>
                                                {{-- <input type="text" id="nama_kriteria_{{ $kriteria_penilaian->id }}" class="form-control" placeholder="" name="nama_kriteria_{{ $kriteria_penilaian->id }}" value="{{ old('nama_kriteria') }}" > --}}
											{{-- </div> --}}
										</div>
                                        <div class="col-md-3">
											<div class="form-group">
												<label for="score_{{ $kriteria_penilaian->id }}">Skor</label>
												<input type="text" id="score_{{ $kriteria_penilaian->id }}" class="form-control" placeholder="Bobot" name="score_{{ $kriteria_penilaian->id }}" value="{{ old('score_' . $kriteria_penilaian->id ) }}" >
											</div>
										</div>
									</div>
                                    @endforeach
                                </div>
                                <div class="form-actions">
									
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-check-square-o"></i> Save
                                        </button>
                                    
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
