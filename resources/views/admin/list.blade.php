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

$(document).on('click','.btn_hapus',function(){
    if(confirm('Yakin akan menghapus ?')){
        return true;
    }else{
        return false;
    }
});

$(document).on('change','#roles',function(){
    // $('#pegawai').val('');
    $('#pegawai').val(null).trigger('change');
    $('#fakultas').val(null).trigger('change');
});

$(document).on('click','#btn_asign_user',function(){
    let roles_id = $('#roles').val();
    let pegawai_id = $('#pegawai').val();
    let fakultas_id = $('#fakultas').val();
    // Validasi awal untuk memastikan roles_id dan pegawai_id tidak kosong
    console.log(roles_id, pegawai_id, fakultas_id);
    if (roles_id == null || pegawai_id == null) {
        alert('Data belum dipilih!');
        return false;
    }

    // Validasi tambahan jika roles_id adalah 5 atau 6
    if ((roles_id == '5' || roles_id == '6') && fakultas_id == null) {
        alert('Fakultas harus dipilih jika Roles adalah WD1 atau ADMINFAKULTAS!');
        return false;
    }
    
    if(confirm('Yakin akan asign user ?')){
        $.ajax({
            url:  "{{ route('admin.users.ajax', ['method' => 'asign']) }}",
            dataType: "json",
            method:'POST',
            data:{
                'roles_id' : roles_id,
                'pegawai_id' : pegawai_id,
                'fakultas_id': fakultas_id,
                '_token' :csrf,
            },
            success: function(result){
                if(result.status === 'ok'){
                    location.reload();
                }else{
                    alert('Maaf,terjadi kesalahan!');
                }
            }
        });
    }
});

$(document).on('click','.del_role',function(){
    let role = $(this).data('role');
    let id = $(this).data('pegawai');
    if(role == '' || id == ''){
        alert('Data belum dipilih !');
        return false;
    }
    if(confirm('Yakin akan un-asign user ?')){
        $.ajax({
            url:  "{{ route('admin.users.ajax', ['method' => 'unasign']) }}",
            dataType: "json",
            method:'POST',
            data:{
                'role' : role,
                'id' : id,
                '_token' :csrf,
            },
            success: function(result){
                if(result.status === 'ok'){
                    location.reload();
                }else{
                    alert('Maaf,terjadi kesalahan!');
                }
            }
        });
    }
    return false;
});

// $("#pegawai").autocomplete({
//     source:function(request, response){
//         let role = $('#roles').val();
//         $.ajax({
//             url:  "{{ route('admin.users.ajax', ['method' => 'cari']) }}",
//             dataType: "json",
//             method:'POST',
//             data:{
//                 'text' : request.term,
//                 'role' : role,
//                 '_token' :csrf,
//             },
//             success: function(data) {
//                 response($.map(data, function (item) {
//                     return {
//                         label: item.nip + ' | ' + item.glr_dpn + ' ' + item.nama + ' ' + item.glr_blkg,
//                         value: item.nip,
//                     };
//                 }));
//             }
//         });
//     },
//     minLength: 4,
// });

$("#pegawai").select2({
    ajax: {
        url:  "{{ route('admin.users.ajax', ['method' => 'cari']) }}",
        dataType: 'json',
        data: function (params){
                var query = {
                    'text' : params.term,
                    'role' : $('#roles').val(),
                    '_token' :csrf,
                }
            return query;
        },
        method:'POST',
        processResults: function (data){
            // Transforms the top-level key of the response object from 'items' to 'results'
            return {
                results: data.items
            };
        }
  }
});

$("#fakultas").select2({
    ajax: {
        url:  "{{ route('admin.users.ajax', ['method' => 'cari_fakultas']) }}",
        dataType: 'json',
        data: function (params){
                var query = {
                    'text' : params.term,
                    'role' : $('#roles').val(),
                    '_token' :csrf,
                }
            return query;
        },
        method:'POST',
        processResults: function (data){
            // Transforms the top-level key of the response object from 'items' to 'results'
            return {
                results: data.items
            };
        }
  }
});

</script>
@endpush

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">List User</h3>
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
                        <h4 class="card-title">Daftar User Admin</h4>
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
                            {{-- <p class="card-text">Berikut adalah daftar admin yang terdaftar pada sistem PKM.</p> --}}
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                {{-- <input type="text" id="nama_lengkap" class="form-control" placeholder="Role" name="nama_lengkap"> --}}
                                                <fieldset class="form-group">
                                                    <select class="custom-select" id="roles">
                                                        @foreach($roles as $role)
                                                        <option value="{{ $role->id }}">{{ $role->role }}</option>
                                                        @endforeach
                                                    </select>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                {{-- <input type="text" id="nama_lengkap" class="form-control" placeholder="Pegawai" name="nama_lengkap"> --}}
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="ft-search"></i></span>
                                                    </div>
                                                    <select id="pegawai" class="form-control" placeholder="Cari pegawai" name="pegawai"></select>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                {{-- <input type="text" id="fakultas" class="form-control" placeholder="Pegawai" name="fakultas"> --}}
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="ft-search"></i></span>
                                                    </div>
                                                    <select id="fakultas" class="form-control" placeholder="Cari fakultas" name="fakultas"></select>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    {{-- <button class="btn btn-info"><i class="ft-plus-circle"></i> Asign User</button> --}}
                                    <button type="button" id="btn_asign_user" class="btn btn-float btn-float-lg btn-outline-pink"><i class="ft-plus-circle"></i><span>Asign User</span></button>
                                </div>
                            </div>
                            <hr>
                            @if(session()->has('message'))
                                <div class="alert alert-success">
                                    <b>{{ session()->get('message') }}</b>
                                </div>
                            @endif
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Nip</th>
                                        {{-- <th>Jns Pegawai</th>
                                        <th>Unit</th> --}}
                                        <th>Role</th>
                                        <th>Login</th>
                                        {{-- <th>Status</th> --}}
                                        {{-- <th>Aksi</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pegawai_has_role as $i => $pegawai)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pegawai->glr_dpn . ' ' . $pegawai->nama . ' ' . $pegawai->glr_blkg }}</td>
                                        <td>{{ $pegawai->nip }}</td>
                                        {{-- <td>{{ $pegawai->jenis_pegawai }}</td>
                                        <td>{{ $pegawai->prodi_eduk }}</td> --}}
                                        <td>
                                            @foreach($pegawai->pegawai_roles as $pegawai_role)
                                                @if($pegawai_role->roles->role == 'PEMBIMBING')
                                                {!! '[ ' . $pegawai_role->roles->role . ' ] ' !!}
                                                @elseif($pegawai_role->roles->role == 'ADMINFAKULTAS' || $pegawai_role->roles->role == 'WD1')
                                                {!! '[ ' . $pegawai_role->roles->role . ' <small>('. $pegawai_role->fakultas->nama_fak_ijazah .')</small> <a href="#" class="del_role" data-pegawai="'. $pegawai->id .'" data-role="'. $pegawai_role->roles->id .'"><i class="fa fa-times" ></i></a> ] ' !!}
                                                @else
                                                {!! '[ ' . $pegawai_role->roles->role . ' <a href="#" class="del_role" data-pegawai="'. $pegawai->id .'" data-role="'. $pegawai_role->roles->id .'"><i class="fa fa-times" ></i></a> ] ' !!}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            <a class="btn btn-info" href="{{ route('admin.login-as', ['pegawai' => $pegawai]) }}" onclick="return confirm('Yakin akan buka?')"><i class="fa fa-unlock"></i></a>
                                            </td>
                                        {{-- <td>{{ UserHelp::admin_status_role_text("") }}</td> --}}
                                        {{-- <td>
                                            <a class="btn btn-danger btn-sm btn_hapus" href="{{ route('admin.users.hapus', ['id' => $pegawai->id]) }}"  ><i class="fa fa-times" ></i> Hapus</a>
                                        </td> --}}
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Nip</th>
                                        {{-- <th>Jns Pegawai</th>
                                        <th>Unit</th> --}}
                                        <th>Role</th>
                                        <th>Aksi</th>
                                        {{-- <th>Status</th> --}}
                                        {{-- <th>Aksi</th> --}}
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
