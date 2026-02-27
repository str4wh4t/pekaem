@extends('template.main')

@push('page_level_css')
<!-- BEGIN PAGE VENDOR CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/vendors/css/forms/selects/select2.min.css') }}">
<!-- END PAGE VENDOR CSS-->
@endpush

@push('page_custom_css')
<style type="text/css">
    .zero-configuration th.text-center {
        white-space: nowrap;
        vertical-align: middle;
    }
    .zero-configuration td.text-center {
        white-space: nowrap;
        vertical-align: middle;
    }
    .zero-configuration .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
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
        <h3 class="content-header-title">List Kategori Kegiatan</h3>
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
                        <h4 class="card-title">Daftar Kategori Kegiatan</h4>
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
                            <a class="btn btn-primary" href="{{ route('kategori-kegiatan.create')  }}"><i class="fa fa-plus-circle"></i> Tambah data</a>
                            <p class="text-muted small mt-2 mb-0"><i class="fa fa-info-circle"></i> Laporan LR-3: klik tombol tahun untuk download laporan kategori tersebut.</p>
                            <hr>
                            <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kategori Kegiatan</th>
                                        <th>Deskripsi</th>
                                        <th>Aksi</th>
                                        <th class="text-center">Laporan LR-3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kategori_kegiatan_list as $i => $kategori_kegiatan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $kategori_kegiatan->nama_kategori_kegiatan }}</td>
                                        <td>{{ $kategori_kegiatan->deskripsi }}</td>
                                        <td>
                                           <a class="btn btn-info btn-sm" href="{{ route('kategori-kegiatan.edit', ['kategori_kegiatan' => $kategori_kegiatan]) }}"><i class="fa fa-pencil-square-o"></i> Edit</a>
                                           <a class="btn btn-success btn-sm" href="{{ route('jenis-pkm.index', ['kategori_kegiatan' => $kategori_kegiatan]) }}"><i class="fa fa-pencil-square-o"></i> Subkegiatan</a>
                                        </td>
                                        <td class="text-center">
                                            @if(isset($tahun_list) && count($tahun_list) > 0)
                                                @foreach($tahun_list as $tahun_item)
                                                    <a class="btn btn-primary btn-sm mr-1 mb-1" href="{{ route('jenis-pkm.daftar-penilaian-kategori-kegiatan-excel', ['kategori_kegiatan' => $kategori_kegiatan, 'tahun' => $tahun_item]) }}" title="Download LR-3 tahun {{ $tahun_item }}">
                                                        <i class="fa fa-file"></i> {{ $tahun_item }}
                                                    </a>
                                                @endforeach
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
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
    </section>
</div>
@endsection
