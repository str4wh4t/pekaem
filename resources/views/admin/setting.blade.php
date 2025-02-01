@extends('template.main')

@push('page_level_css')
<!-- BEGIN PAGE VENDOR CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets') }}/vendors/css/forms/toggle/bootstrap-switch.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets') }}/vendors/css/forms/toggle/switchery.min.css">
<!-- END PAGE VENDOR CSS-->
@endpush

@push('page_custom_css')
<style type="text/css">

</style>
@endpush

@push('page_vendor_level_js')
<!-- BEGIN PAGE VENDOR JS-->
<script src="{{ asset('assets/template/robust/app-assets') }}/vendors/js/forms/toggle/bootstrap-switch.min.js"></script>
<script src="{{ asset('assets/template/robust/app-assets') }}/vendors/js/forms/toggle/bootstrap-checkbox.min.js"></script>
<script src="{{ asset('assets/template/robust/app-assets') }}/vendors/js/forms/toggle/switchery.min.js"></script>
<!-- END PAGE VENDOR JS-->
@endpush

@push('page_level_js')
<script type="text/javascript">

let csrf = $('meta[name=csrf-token]').attr("content");

$(document).ready(function () {
    const switchElement = document.querySelector('#switchStatusAplikasi');
    let init = new Switchery(switchElement, { secondaryColor: '#FF7575' });
    // buat listener untuk switch ketika diubah
    switchElement.onchange = function() {
        let status_aplikasi = switchElement.checked ? 1 : 0;
        $.post('{{ route("admin.setting.update_status_aplikasi") }}', {'_token': csrf, 'status_aplikasi': status_aplikasi}, function(res){
            // if(res.status == 'ok'){
            //     toastr.success(res.message);
            // }else{
            //     toastr.error(res.message);
            // }
        });
    }
});

</script>
@endpush

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Setting</h3>
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
                        <h4 class="card-title">Status Aplikasi</h4>
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
                        <div class="card-body">
                            <div class="col-sm-4 col-2 mb-1">
                                <input type="checkbox" id="switchStatusAplikasi" class="switchery" data-color="success" {{ $setting ? 'checked' : '' }}/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
