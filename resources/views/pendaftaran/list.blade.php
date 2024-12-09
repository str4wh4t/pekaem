@extends('template.main')

@push('page_level_css')
<!-- BEGIN PAGE LEVEL CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
<!-- END PAGE LEVEL CSS-->
@endpush

@push('page_vendor_level_js')
<!-- BEGIN PAGE VENDOR JS-->
<script src="{{ asset('assets/template/robust/app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
<!-- END PAGE VENDOR JS-->
@endpush

@push('page_level_js')
<!-- BEGIN PAGE LEVEL JS-->
<script type="text/javascript">

// Default

@if(count($usulan_pkm) > 0)
$('.zero-configuration').DataTable();
@endif

$(document).on('click','.btn_hapus',function(){
    if(confirm('Yakin akan menghapus ?')){
        return true;
    }else{
        return false;
    }
});

</script>
<!-- END PAGE LEVEL JS-->
@endpush

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Usulan PKM</h3>
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
    {{-- <div class="content-header-right col-md-6 col-12">
        <fieldset class="form-group relative has-icon-left col-md-5 col-12 float-right p-0">
            <input class="form-control" id="iconLeft" type="text" placeholder="Search...">
            <div class="form-control-position"><i class="fa fa-search"></i></div>
        </fieldset>
    </div> --}}
</div>
<div class="content-body"><!-- Basic form layout section start -->
    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Daftar Usulan PKM</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                {{-- <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                <li><a data-action="close"><i class="ft-x"></i></a></li> --}}
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <p class="card-text">Berikut adalah data usulan PKM yang telah masuk.</p>
                            @if(session()->has('message'))
                                <div class="alert alert-success">
                                    <b>{{ session()->get('message') }}</b>
                                </div>
                            @endif
                            <a class="btn btn-primary" href="{{ route('mhs.pendaftaran.add')  }}"><i class="fa fa-wpforms"></i> Form Pendaftaran</a>
                            <hr>
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mhs</th>
                                        <th>Judul</th>
                                        <th>Jenis</th>
                                        <th>Pembimbing</th>
                                        <th>Tgl Ajuan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($usulan_pkm as $i => $u)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $u->mhs->nama . ' [' . $u->mhs->nim . ']' }}</td>
                                        <td>{{ $u->judul }}</td>
                                        <td>{{ $u->jenis_pkm->nama_pkm }}</td>
                                        {{-- <td>{{ $u->anggota_pkm[0]->mhs->nama }}</td> --}}
                                        <td>{{ $u->pegawai->glr_dpn . " " . $u->pegawai->nama . " " . $u->pegawai->glr_blkg }}</td>
                                        <td>{{ $u->created_at->locale('id')->isoFormat('LL') }}</td>
                                        <td>
                                            {{-- @if(Userhelp::get_selected_role() == 'ADMIN') --}}
                                            {{-- {{ empty($u->has('reviewer')->first())?'A':'B' }} --}}
                                            {{-- @else --}}
                                            {{ $u->status_usulan->keterangan }}
                                            {{-- @endif --}}
                                        </td>
                                        <td>

                                        @if(Userhelp::get_selected_role() == 'MHS')
                                            <a class="btn btn-info btn-sm" href="{{ route('mhs.pendaftaran.edit', ['uuid' => $u->uuid]) }}"  ><i class="fa fa-pencil-square-o" ></i> Lihat</a>
                                            @if($u->status_usulan->keteragan == 'BARU')
                                            <a class="btn btn-danger btn-sm btn_hapus" href="{{ route('mhs.pendaftaran.hapus', ['uuid' => $u->uuid]) }}"  ><i class="fa fa-times" ></i> Hapus</a>
                                            @endif
                                        @endif
                                        @if(Userhelp::get_selected_role() == 'PEMBIMBING')
                                            <a class="btn btn-info btn-sm" href="{{ route('share.pendaftaran.read', ['uuid' => $u->uuid]) }}"  >
                                                <i class="fa fa-folder-open" ></i> Lihat
                                            </a>
                                        @endif
                                        @if(Userhelp::get_selected_role() == 'ADMIN')
                                            {{-- @if(empty($u->has('reviewer')->first())) --}}
{{--                                            @if($u->status_usulan->keterangan == 'DISETUJUI')--}}

                                            @if($u->status_usulan->urutan > 1)

                                                <a class="btn btn-info btn-sm" href="{{ route('share.pendaftaran.read', ['uuid' => $u->uuid]) }}"  ><i class="fa fa-pencil-square-o" ></i> {{ $u->status_usulan->keterangan == 'DISETUJUI' ? 'Proses' : 'Lihat' }}</a>

                                            @else

                                            @endif
                                        @endif
                                        @if(Userhelp::get_selected_role() == 'REVIEWER')
                                            {{-- @if(empty($u->has('reviewer')->first())) --}}
                                            {{-- @if($u->status_usulan->urutan > '1') --}}

                                                <a class="btn btn-info btn-sm" href="{{ route('share.pendaftaran.read', ['uuid' => $u->uuid]) }}"  ><i class="fa fa-pencil-square-o" ></i> Proses</a>

                                            {{-- @else --}}

                                            {{-- @endif --}}
                                        @endif
                                        </td>
                                    </tr>
                                    @empty
	                                <tr>
	                                    <td colspan="6">Belum terdapat event</td>
	                                </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Mhs</th>
                                        <th>Judul</th>
                                        <th>Jenis</th>
                                        <th>Pembimbing</th>
                                        <th>Tgl Ajuan</th>
                                        <th>Status</th>
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
