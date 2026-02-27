@extends('template.main')

@push('page_level_css')
<!-- BEGIN PAGE VENDOR CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
<!-- END PAGE VENDOR CSS-->
@endpush

@push('page_vendor_level_js')
<!-- BEGIN PAGE VENDOR JS-->
<script src="{{ asset('assets/template/robust/app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
<!-- END PAGE VENDOR JS-->
@endpush

@push('page_level_js')
<!-- BEGIN PAGE LEVEL JS-->
<script type="text/javascript">
    $(document).ready(function() {
        $('.zero-configuration').DataTable();
    });
</script>
<!-- END PAGE LEVEL JS-->
@endpush

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Jenis PKM - {{ $kategori_kegiatan->nama_kategori_kegiatan }}</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('kategori-kegiatan.index') }}">Kategori Kegiatan</a></li>
                    <li class="breadcrumb-item active">Jenis PKM</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content-body">
    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Daftar Jenis PKM - {{ $kategori_kegiatan->nama_kategori_kegiatan }}</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            @if(session()->has('message'))
                                <div class="alert alert-success">
                                    <b>{{ session()->get('message') }}</b>
                                </div>
                            @endif
                            
                            {{-- <div class="alert alert-info">
                                <b>Kategori Kegiatan:</b> {{ $kategori_kegiatan->nama_kategori_kegiatan }}<br>
                                <b>Deskripsi:</b> {{ $kategori_kegiatan->deskripsi }}
                            </div> --}}
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Jenis PKM</th>
                                            {{-- <th>Kategori Kriteria</th> --}}
                                            {{-- <th>Keterangan</th> --}}
                                            <th>Kamar</th>
                                            {{-- <th>Score Min</th> --}}
                                            <th class="text-center">Jumlah Usulan</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($jenis_pkm_list as $jenis_pkm)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>{{ $jenis_pkm->nama_pkm }}</strong></td>
                                            {{-- <td>{{ $jenis_pkm->kategori_kriteria ? $jenis_pkm->kategori_kriteria->nama_kategori_kriteria : '-' }}</td> --}}
                                            {{-- <td>{{ $jenis_pkm->keterangan ? $jenis_pkm->keterangan : '-' }}</td> --}}
                                            <td class="text-center">{{ $jenis_pkm->kamar }}</td>
                                            {{-- <td class="text-center">{{ $jenis_pkm->score_min ? $jenis_pkm->score_min : '-' }}</td> --}}
                                            <td class="text-center">{{ number_format($jenis_pkm->jumlah_usulan, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                @if($jenis_pkm->jumlah_usulan > 0)
                                                    <a href="{{ route('jenis-pkm.daftar-penilaian', ['kategori_kegiatan' => $kategori_kegiatan, 'jenis_pkm' => $jenis_pkm, 'tahun' => $tahun]) }}" class="btn btn-sm btn-primary" title="Lihat Penilaian">
                                                        <i class="fa fa-eye"></i> Lihat Penilaian
                                                    </a>
                                                @else
                                                    <span class="badge badge-secondary">Tidak ada usulan</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada jenis PKM untuk kategori kegiatan ini</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    {{-- @if($jenis_pkm_list->count() > 0)
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-right"><strong>Total:</strong></th>
                                            <th class="text-center">{{ number_format($jenis_pkm_list->sum('jumlah_usulan'), 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                    @endif --}}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('page_level_js')
<script type="text/javascript">
</script>
@endpush
@endsection
