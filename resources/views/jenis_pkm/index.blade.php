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
        <h3 class="content-header-title">List Subkegiatan</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a>
                    </li>
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
                        <h4 class="card-title">Daftar Subkegiatan</h4>
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
                            <div class="alert alert-info">
                                <b>Kategori Kegiatan : </b> {{ $kategori_kegiatan->nama_kategori_kegiatan }}
                            </div>
                            <a class="btn btn-primary" href="{{ route('jenis-pkm.create', ['kategori_kegiatan' => $kategori_kegiatan])  }}"><i class="fa fa-plus-circle"></i> Tambah data</a>
                            <hr>
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Subkegiatan</th>
                                        <th>Keterangan</th>
                                        <th>Kriteria Penilaian</th>
                                        <th>Score Min</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jenis_pkm_list as $i => $jenis_pkm)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $jenis_pkm->nama_pkm }}</td>
                                        <td>{{ $jenis_pkm->keterangan }}</td>
                                        <td>{{ $jenis_pkm->kategori_kriteria->nama_kategori_kriteria ?? null }}</td>
                                        <td>{{ $jenis_pkm->score_min }}</td>
                                        <td>
                                           <a class="btn btn-info btn-sm" href="{{ route('jenis-pkm.edit', ['kategori_kegiatan' => $kategori_kegiatan, 'jenis_pkm' => $jenis_pkm]) }}"  ><i class="fa fa-pencil-square-o" ></i> Edit</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Subkegiatan</th>
                                        <th>Keterangan</th>
                                        <th>Kriteria Penilaian</th>
                                        <th>Score Min</th>
                                         <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection