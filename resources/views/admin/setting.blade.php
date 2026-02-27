@extends('template.main')

@push('page_level_css')
<!-- BEGIN PAGE VENDOR CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets') }}/vendors/css/forms/toggle/bootstrap-switch.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets') }}/vendors/css/forms/toggle/switchery.min.css">
<!-- END PAGE VENDOR CSS-->
@endpush

@push('page_custom_css')
<style type="text/css">
    .status-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 8px;
    }
    .status-indicator.active {
        background-color: #28a745;
        box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
    }
    .status-indicator.inactive {
        background-color: #dc3545;
        box-shadow: 0 0 10px rgba(220, 53, 69, 0.5);
    }
    .status-card {
        border-left: 4px solid;
        transition: all 0.3s ease;
    }
    .status-card.active {
        border-left-color: #28a745;
        background-color: #f8fff9;
    }
    .status-card.inactive {
        border-left-color: #dc3545;
        background-color: #fff8f8;
    }
    .switch-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px;
    }
    .switch-info {
        flex: 1;
        margin-right: 20px;
    }
    .switch-control {
        flex-shrink: 0;
    }
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
    const statusCard = document.querySelector('#statusCard');
    const statusText = document.querySelector('#statusText');
    const statusIndicator = document.querySelector('#statusIndicator');
    const statusDescription = document.querySelector('#statusDescription');
    
    let init = new Switchery(switchElement, { 
        secondaryColor: '#FF7575',
        color: '#28a745',
        size: 'large'
    });
    
    // Update status display
    function updateStatusDisplay(isActive) {
        const switchLabel = document.querySelector('#switchLabel');
        if (isActive) {
            statusCard.className = 'card status-card active';
            statusIndicator.className = 'status-indicator active';
            statusText.textContent = 'Aplikasi DIBUKA';
            statusText.className = 'h4 text-success mb-2';
            statusDescription.innerHTML = '<i class="fa fa-check-circle text-success"></i> Pengguna dapat mengajukan proposal PKM';
            if (switchLabel) switchLabel.textContent = 'ON';
        } else {
            statusCard.className = 'card status-card inactive';
            statusIndicator.className = 'status-indicator inactive';
            statusText.textContent = 'Aplikasi DITUTUP';
            statusText.className = 'h4 text-danger mb-2';
            statusDescription.innerHTML = '<i class="fa fa-times-circle text-danger"></i> Pengguna <strong>tidak dapat</strong> mengajukan proposal PKM';
            if (switchLabel) switchLabel.textContent = 'OFF';
        }
    }
    
    // Initialize display
    updateStatusDisplay(switchElement.checked);
    
    // buat listener untuk switch ketika diubah
    switchElement.onchange = function() {
        let status_aplikasi = switchElement.checked ? 1 : 0;
        updateStatusDisplay(switchElement.checked);
        
        $.post('{{ route("admin.setting.update_status_aplikasi") }}', {
            '_token': csrf, 
            'status_aplikasi': status_aplikasi
        }, function(res){
            if(res && res.status == 'ok'){
                if (typeof toastr !== 'undefined') {
                    toastr.success('Status aplikasi berhasil diperbarui');
                }
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Gagal memperbarui status aplikasi');
                }
            }
        }).fail(function() {
            // Revert switch if request fails
            switchElement.checked = !switchElement.checked;
            init.setPosition(true);
            updateStatusDisplay(switchElement.checked);
            if (typeof toastr !== 'undefined') {
                toastr.error('Terjadi kesalahan saat memperbarui status');
            }
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
                @if(session('message'))
                    <div class="alert alert-success alert-dismissible mb-2" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ session('message') }}
                    </div>
                @endif
                <div class="card mt-2">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fa fa-calendar"></i> Tahun Dipilih
                        </h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <p class="text-muted mb-3">
                                Tahun ini digunakan secara global untuk memfilter data (usulan PKM, target PKM tahunan, laporan, dll). Pilih tahun yang aktif untuk aplikasi.
                            </p>
                            <form action="{{ route('admin.setting.update_tahun') }}" method="post" class="form-inline">
                                @csrf
                                <label class="mr-2" for="tahun_dipilih">Tahun:</label>
                                <select name="tahun_dipilih" id="tahun_dipilih" class="form-control mr-2" required>
                                    @foreach($tahun_list as $t)
                                        <option value="{{ $t }}" {{ ((int)($setting->tahun_dipilih ?? date('Y')) === (int)$t) ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary">Simpan Tahun</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="statusCard" class="card {{ $setting->status_aplikasi ? 'status-card active' : 'status-card inactive' }}">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fa fa-cog"></i> Pengaturan Status Aplikasi
                        </h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h5><i class="fa fa-info-circle"></i> Informasi</h5>
                                <p class="mb-0">
                                    Gunakan toggle di bawah ini untuk membuka atau menutup aplikasi. 
                                    Ketika aplikasi <strong>DITUTUP</strong>, pengguna tidak dapat mengajukan proposal PKM baru.
                                </p>
                            </div>
                            
                            <div class="switch-container">
                                <div class="switch-info">
                                    <div class="mb-3">
                                        <span id="statusIndicator" class="status-indicator {{ $setting->status_aplikasi ? 'active' : 'inactive' }}"></span>
                                        <span id="statusText" class="h4 {{ $setting->status_aplikasi ? 'text-success' : 'text-danger' }} mb-2">
                                            {{ $setting->status_aplikasi ? 'Aplikasi DIBUKA' : 'Aplikasi DITUTUP' }}
                                        </span>
                                    </div>
                                    <div id="statusDescription" class="text-muted">
                                        @if($setting->status_aplikasi)
                                            <i class="fa fa-check-circle text-success"></i> Pengguna dapat mengajukan proposal PKM
                                        @else
                                            <i class="fa fa-times-circle text-danger"></i> Pengguna <strong>tidak dapat</strong> mengajukan proposal PKM
                                        @endif
                                    </div>
                                </div>
                                <div class="switch-control">
                                    <label class="d-block text-center mb-2"><strong>Toggle Status</strong></label>
                                    <input type="checkbox" id="switchStatusAplikasi" class="switchery" data-color="success" {{ $setting->status_aplikasi ? 'checked' : '' }}/>
                                    <div class="text-center mt-2">
                                        <small class="text-muted">
                                            <span id="switchLabel">{{ $setting->status_aplikasi ? 'ON' : 'OFF' }}</span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="card border-left-success">
                                        <div class="card-body">
                                            <h6 class="card-title text-success">
                                                <i class="fa fa-unlock"></i> Status: DIBUKA
                                            </h6>
                                            <p class="card-text text-muted small mb-0">
                                                • Pengguna dapat mengajukan proposal PKM<br>
                                                • Form pendaftaran dapat diakses<br>
                                                • Sistem menerima usulan baru
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-left-danger">
                                        <div class="card-body">
                                            <h6 class="card-title text-danger">
                                                <i class="fa fa-lock"></i> Status: DITUTUP
                                            </h6>
                                            <p class="card-text text-muted small mb-0">
                                                • Pengguna <strong>tidak dapat</strong> mengajukan proposal<br>
                                                • Form pendaftaran tidak dapat diakses<br>
                                                • Sistem menolak usulan baru
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
