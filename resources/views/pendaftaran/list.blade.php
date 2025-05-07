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
$('.zero-configuration').DataTable(
    {
        scrollX: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]], 
    }
);
@endif

$(document).on('click','.btn_hapus',function(){
    if(confirm('Yakin akan menghapus ?')){
        return true;
    }else{
        return false;
    }
});

function toggleButtonState() {
    const isAnyChecked = $('.select-item:checked').length > 0;
    $('#usulkanButton').prop('disabled', !isAnyChecked);
    $('#lanjutkanButton').prop('disabled', !isAnyChecked);
    $('#tetapkanNilaiButton').prop('disabled', !isAnyChecked);
}

$(document).ready(function () {
    // const $usulkanButton = $('#usulkanButton');
    // const $lanjutkanButton = $('#lanjutkanButton');
    // const $checkboxes = $('.select-item');

    // // Fungsi untuk memeriksa apakah ada checkbox yang dipilih
    // function toggleButtonState() {
    //     const anyChecked = $checkboxes.is(':checked');
    //     $usulkanButton.attr('disabled', !anyChecked);
    //     $lanjutkanButton.attr('disabled', !anyChecked);
    // }

    // // Tambahkan event listener ke semua checkbox
    // $checkboxes.on('change', toggleButtonState);

    // // Panggil fungsi untuk memeriksa status awal
    // toggleButtonState();

    // Event listener untuk checkbox "Select All"
    $('.select-all').on('change', function () {
        // Set semua .select-item berdasarkan status .select-all
        const isChecked = $(this).prop('checked');
        $('.select-item').prop('checked', isChecked);
    });

    // Event listener untuk checkbox individual
    $('.select-item').on('change', function () {
        const totalItems = $('.select-item').length;
        const checkedItems = $('.select-item:checked').length;

        if (checkedItems === totalItems) {
            // Jika semua checkbox individual tercentang, centang Select All
            $('.select-all').prop('checked', true);
        } else {
            // Jika ada checkbox yang tidak tercentang, uncheck Select All
            $('.select-all').prop('checked', false);
        }
    });

    // Event listener untuk perubahan status checkbox individual
    $('.select-item').on('change', function () {
        toggleButtonState();
    });

    // Event listener untuk Select All
    $('.select-all').on('change', function () {
        // Check/uncheck semua checkbox individual
        const isChecked = $(this).prop('checked');
        $('.select-item').prop('checked', isChecked);

        // Perbarui status tombol
        toggleButtonState();
    });

    // Panggil fungsi saat halaman dimuat
    toggleButtonState();
});

$(document).on('click','#usulkanButton',function(){

    if(confirm('Yakin akan mengajukan ?')){
        var ids = [];
        $('.select-item').each(function(){
            if($(this).is(':checked')){
                ids.push($(this).val());
            }
        });
        var csrf = '{{ csrf_token() }}';
		$.post('{{ route('share.pendaftaran.ajax', ['method' => 'bulk_ajukan']) }}',{'_token':csrf,'ids':ids},function(result){
			if(result.status === 'ok'){
				location.href = "{{ url()->current() }}";
			}else{
				alert('Maaf,terjadi kesalahan!');
			}
		});
	}
});

$(document).on('click','#lanjutkanButton',function(){

    if(confirm('Yakin akan melanjutkan ?')){
        var ids = [];
        $('.select-item').each(function(){
            if($(this).is(':checked')){
                ids.push($(this).val());
            }
        });
        var csrf = '{{ csrf_token() }}';
        let approval = 'LANJUT';
        $.post('{{ route('share.pendaftaran.ajax', ['method' => 'bulk_approval']) }}',{'_token':csrf, ids, approval},function(result){
            if(result.status === 'ok'){
                location.href = "{{ url()->current() }}";
            }else{
                alert('Maaf,terjadi kesalahan!');
            }
        });
    }
});

$(document).on('click','#tetapkanNilaiButton',function(){

if(confirm('Yakin akan menetapkan ?')){
    var ids = [];
    $('.select-item').each(function(){
        if($(this).is(':checked')){
            ids.push($(this).val());
        }
    });
    var csrf = '{{ csrf_token() }}';
    let approval = 'SUDAH_DINILAI';
    $.post('{{ route('share.pendaftaran.ajax', ['method' => 'bulk_tetapkan_nilai']) }}',{'_token':csrf, ids, approval},function(result){
        if(result.status === 'ok'){
            location.href = "{{ url()->current() }}";
        }else{
            alert('Maaf,terjadi kesalahan!');
        }
    });
}
});

</script>
<!-- END PAGE LEVEL JS-->
@endpush

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Usulan Kegiatan</h3>
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
                        <h4 class="card-title">Daftar Usulan</h4>
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
                            <p class="card-text">Berikut adalah data usulan yang telah masuk.</p>
                            @if(session()->has('message'))
                                <div class="alert alert-success">
                                    <b>{{ session()->get('message') }}</b>
                                </div>
                            @endif
                            @if(UserHelp::get_selected_role() == 'ADMINFAKULTAS')
                            <a class="btn btn-primary" href="{{ route('admin.pendaftaran.create')  }}"><i class="fa fa-wpforms"></i> Form Pendaftaran</a>
                            <button class="btn btn-success" id="usulkanButton" type="button">
                                <i class="fa fa-paper-plane"></i> Usulkan
                            </button>
                            <a class="btn btn-secondary" href="{{ route('admin.pendaftaran.report')  }}"><i class="fa fa-file"></i> Laporan LR-1</a>
                            <hr>
                            @endif
                            @if(UserHelp::get_selected_role() == 'WD1')
                            <button class="btn btn-success" id="lanjutkanButton" type="button">
                                <i class="fa fa-paper-plane"></i> Lanjutkan
                            </button>
                            <a class="btn btn-secondary" href="{{ route('admin.pendaftaran.report')  }}"><i class="fa fa-file"></i> Laporan LR-1</a>
                            <hr>
                            @endif
                            @if(UserHelp::get_selected_role() == 'ADMIN')
                            <button class="btn btn-success" id="tetapkanNilaiButton" type="button">
                                <i class="fa fa-paper-plane"></i> Tetapkan Nilai
                            </button>
                            <a class="btn btn-secondary" href="{{ route('admin.pendaftaran.report')  }}"><i class="fa fa-file"></i> Laporan LR-1</a>
                            <hr>
                            @endif
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>
                                            @if(Userhelp::get_selected_role() == 'ADMINFAKULTAS' || Userhelp::get_selected_role() == 'WD1' || Userhelp::get_selected_role() == 'ADMIN')
                                            <input type="checkbox" class="checkbox-item select-all" name="selectAll" value="" />
                                            @else
                                            &nbsp;
                                            @endif
                                        </th>
                                        <th>No</th>
                                        <th>Mhs</th>
                                        <th>Judul</th>
                                        <th>Keg</th>
                                        <th>Subkeg</th>
                                        <th>Tema</th>
                                        <th>Pendamping</th>
                                        <th>Tgl Ajuan</th>
                                        <th>Created By</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($usulan_pkm as $i => $u)
                                    <tr>
                                        <td>
                                            @if(Userhelp::get_selected_role() == 'ADMINFAKULTAS')
                                                @if($u->status_usulan->keterangan == 'BARU')
                                                <input type="checkbox" class="checkbox-item select-item" name="ids[]" value="{{ $u->id }}" />
                                                @else
                                                <input type="checkbox" disabled />
                                                @endif
                                            @endif

                                            @if(Userhelp::get_selected_role() == 'WD1')
                                                @if($u->status_usulan->keterangan == 'DISETUJUI')
                                                <input type="checkbox" class="checkbox-item select-item" name="ids[]" value="{{ $u->id }}" />
                                                @else
                                                <input type="checkbox" disabled />
                                                @endif
                                            @endif

                                            @if(Userhelp::get_selected_role() == 'ADMIN')
                                                @if($u->status_usulan->keterangan == 'LANJUT')
                                                    @if($u->penilaian_reviewer()->distinct()->count('reviewer_id') > 0)
                                                        @if($u->penilaian_reviewer()->distinct()->count('reviewer_id') == $u->reviewer_usulan_pkm->count())
                                                        <input type="checkbox" class="checkbox-item select-item" name="ids[]" value="{{ $u->id }}" />
                                                        @else
                                                        <input type="checkbox" disabled />
                                                        @endif
                                                    @else
                                                    <input type="checkbox" disabled />
                                                    @endif
                                                @else
                                                <input type="checkbox" disabled />
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $u->mhs->nama . ' [' . $u->mhs->nim . ']' }}</td>
                                        <td>{{ $u->judul }}</td>
                                        <td>{{ $u->jenis_pkm->kategori_kegiatan->nama_kategori_kegiatan }}</td>
                                        <td>{{ $u->jenis_pkm->nama_pkm }}</td>
                                        <td>{{ !empty($u->tema_usulan_pkm_id) ? $u->tema_usulan_pkm->nama_tema : "-"}}</td>
                                        {{-- <td>{{ $u->anggota_pkm[0]->mhs->nama }}</td> --}}
                                        <td>{{ $u->pegawai->glr_dpn . " " . $u->pegawai->nama . " " . $u->pegawai->glr_blkg }}</td>
                                        <td>{{ $u->created_at->locale('id')->isoFormat('LL') }}</td>
                                        <td>{{ $u->created_by }}</td>
                                        <td>
                                            {{-- @if(Userhelp::get_selected_role() == 'ADMIN') --}}
                                            {{-- {{ empty($u->has('reviewer')->first())?'A':'B' }} --}}
                                            {{-- @else --}}

                                            {{ $u->status_usulan->keterangan == 'DISETUJUI' ? 'DIUSULKAN' : $u->status_usulan->keterangan }}
                                            @if(Userhelp::get_selected_role() == 'REVIEWER' && $u->status_usulan->keterangan == 'LANJUT')
                                            {!! $u->penilaian_reviewer()->where('reviewer_id', $pegawai->id)->count() != 0 ? '<small><span class="text-success"><b>(SUDAH_DINILAI)<b></span></small>' : '' !!}
                                            @endif

                                            @if(Userhelp::get_selected_role() == 'ADMIN' && $u->status_usulan->keterangan == 'LANJUT')
                                                @if($u->penilaian_reviewer()->distinct()->count('reviewer_id') > 0)
                                                {!! $u->penilaian_reviewer()->distinct()->count('reviewer_id') == $u->reviewer_usulan_pkm->count() ? '<small><span class="text-success"><b>(SIAP_DITETAPKAN)<b></span></small>' : '' !!}
                                                @endif
                                            @endif

                                            {{-- @endif --}}
                                        </td>
                                        <td>

                                        @if(Userhelp::get_selected_role() == 'MHS')
                                            <a class="btn btn-info btn-sm" href="{{ route('mhs.pendaftaran.edit', ['uuid' => $u->uuid]) }}"  ><i class="fa fa-pencil-square-o" ></i> Lihat</a>
                                            @if($u->status_usulan->keterangan == 'BARU')
                                            <a class="btn btn-danger btn-sm btn_hapus" href="{{ route('mhs.pendaftaran.hapus', ['uuid' => $u->uuid]) }}"  ><i class="fa fa-times" ></i> Hapus</a>
                                            @endif
                                        @endif
                                        @if(Userhelp::get_selected_role() == 'ADMINFAKULTAS')
                                            <a class="btn btn-info btn-sm" href="{{ route('admin.pendaftaran.edit', ['uuid' => $u->uuid]) }}"  ><i class="fa fa-pencil-square-o" ></i> Lihat</a>
                                        @endif
                                        @if(Userhelp::get_selected_role() == 'PEMBIMBING' || Userhelp::get_selected_role() == 'WD1')
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
	                                    <td colspan="6">Belum terdapat data</td>
	                                </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>No</th>
                                        <th>Mhs</th>
                                        <th>Judul</th>
                                        <th>Keg</th>
                                        <th>Subkeg</th>
                                        <th>Pendamping</th>
                                        <th>Tgl Ajuan</th>
                                        <th>Created By</th>
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
