@extends('template.main')

@push('page_level_css')
<!-- BEGIN PAGE VENDOR CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/css/plugins/ui/jqueryui.css') }}">
@endpush

@push('page_vendor_level_js')
<!-- BEGIN PAGE VENDOR JS-->
<script src="{{ asset('assets/template/robust/app-assets/js/core/libraries/jquery_ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/template/robust/app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
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

$(document).on('click','#btn_add_role',function(){
    let role = $('#role').val();
    let keterangan = $('#keterangan').val();
    if(role == '' || keterangan == ''){
        alert('Data masih kosong !');
        return false;
    }
    $.ajax({
        url:  "{{ route('admin.users.ajax', ['method' => 'add_role']) }}",
        dataType: "json",
        method:'POST',
        data:{
            'role' : role,
            'keterangan' : keterangan,
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
});

</script>
@endpush

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">List Role</h3>
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
                        <h4 class="card-title">Daftar Role Admin</h4>
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
                                                <input type="text" id="role" class="form-control" placeholder="Role" name="role">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="text" id="keterangan" class="form-control" placeholder="Keterangan" name="keterangan">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    {{-- <button class="btn btn-info"><i class="ft-plus-circle"></i> Asign User</button> --}}
                                    <button type="button" id="btn_add_role" class="btn btn-float btn-float-lg btn-outline-pink"><i class="ft-plus-circle"></i><span>Add Role</span></button>
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
                                        <th>Role</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $i => $role)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $role->role }}</td>
                                        <td>{{ $role->keterangan }}</td>
                                        <td>
                                            <a class="btn btn-danger btn-sm btn_hapus" href="{{ route('admin.users.roles.hapus', ['id' => $role->id]) }}"  ><i class="fa fa-times" ></i> Hapus</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Role</th>
                                        <th>Informasi</th>
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
