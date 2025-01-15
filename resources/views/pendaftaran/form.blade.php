@extends('template.main')

@push('page_level_css')
<!-- BEGIN PAGE LEVEL CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/vendors/css/forms/selects/select2.min.css') }}">
<!-- END PAGE LEVEL CSS-->
@endpush

@push('page_vendor_level_js')
<!-- BEGIN PAGE VENDOR JS-->
<script src="{{ asset('assets/template/robust/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<!-- END PAGE VENDOR JS-->
@endpush

@push('page_level_js')
<!-- BEGIN PAGE LEVEL JS-->
<script src="{{ asset('assets/template/robust/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js') }}"></script>
<script type="text/javascript">

let csrf = $('meta[name=csrf-token]').attr("content");
// Default
const countAnggotaPkm = {{ $usulan_pkm->anggota_pkm->count() }}; // Jumlah elemen awal
const maxAnggotaPkm = 4 - countAnggotaPkm; // Maksimal jumlah elemen
let $repeater = $('.repeater-default').repeater({
	// isFirstItemUndeletable: true,
	show:function(){
		init_select();
		$(this).show();
		updateLabels();
		toggleAddButton();
	},
	hide: function (deleteElement) {
		$(this).remove(); 
		updateLabels();
		toggleAddButton();
	}
});

// Fungsi untuk memperbarui label Reviewer
function updateLabels() {
	$repeater.find('[data-repeater-item]').each(function (index) {
		$(this).find('.label-control').text('Anggota ' + (index + countAnggotaPkm));
	});
}

// Fungsi untuk menampilkan/menyembunyikan tombol "Tambah Reviewer"
function toggleAddButton() {
	const itemCount = $repeater.find('[data-repeater-item]').length;
	if (itemCount > maxAnggotaPkm) {
		$('#add-anggotapkm').hide();
	} else {
		$('#add-anggotapkm').show();
	}
}

@isset($usulan_pkm->jenis_pkm_id)
$('#jenis').val('{{ $usulan_pkm->jenis_pkm_id }}');
@endisset

{{--@isset($usulan_pkm->anggota_pkm[1]->nim)--}}
{{--$repeater.setList([--}}
{{--	@foreach($usulan_pkm->anggota_pkm as $i => $anggota_pkm)--}}
{{--		--}}{{-- @if ($i > 0) --}}
{{--    	{ 'nim': {{ $anggota_pkm->nim }} },--}}
{{--    	--}}{{-- @endif --}}
{{--    @endforeach--}}
{{--]);--}}
{{--@endisset--}}

$(document).on('click','.btn_hapus_document',function(){
	let id = $(this).data('id');
	let file = $(this).data('file');
	let csrf = $('meta[name=csrf-token]').attr("content");
	if(confirm('Yakin akan menghapus ?')){
		$.post('{{ route('share.pendaftaran.ajax', ['method' => 'hapus_document']) }}',{'_token':csrf,'id':id,'file':file},function(result){
			if(result.status === 'ok'){
				location.reload();
			}else{
				alert('Maaf,terjadi kesalahan!');
			}
		});
	}
});

$(document).on('click','.btn_hapus_anggota',function(){
	let id = $(this).data('id');
	let csrf = $('meta[name=csrf-token]').attr("content");
	if(confirm('Yakin akan menghapus ?')){
		$.post('{{ route('share.pendaftaran.ajax', ['method' => 'hapus_anggota']) }}',{'_token':csrf,'id':id},function(result){
			if(result.status === 'ok'){
				location.reload();
			}else{
				alert('Maaf,terjadi kesalahan!');
			}
		});
	}
});

$("#pembimbing").select2({
	placeholder: 'Cari pembimbing',
	minimumInputLength: 3,
 	ajax: {
	    url: "{{ route('admin.users.ajax', ['method' => 'cari_pembimbing']) }}",
	    dataType: 'json',
	    data: function (params){
      			var query = {
        			'text' : params.term,
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

@isset($usulan_pkm->pegawai_id)
var data = {
    id: '{{ $usulan_pkm->pegawai_id }}',
    text: '{{ $usulan_pkm->pegawai->glr_dpn . " " . $usulan_pkm->pegawai->nama . " " . $usulan_pkm->pegawai->glr_blkg }}'
};
var newOption = new Option(data.text, data.id, false, false);
$('#pembimbing').append(newOption).trigger('change');
@endisset

init_select();

function init_select(){
	$(".cari_mhs").select2({
		placeholder: 'Cari mhs',
		minimumInputLength: 3,
		width: '100%',
	 	ajax: {
		    url: "{{ route('admin.users.ajax', ['method' => 'cari_mhs']) }}",
		    dataType: 'json',
		    data: function (params){
	      			var query = {
	        			'text' : params.term,
						'jenis_pkm_id' : $('#jenis_pkm').val(),
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

	$(".cari_mhs_anggota").select2({
		placeholder: 'Cari mhs',
		minimumInputLength: 3,
		width: '100%',
	 	ajax: {
		    url: "{{ route('admin.users.ajax', ['method' => 'cari_mhs_anggota']) }}",
		    dataType: 'json',
		    data: function (params){
	      			var query = {
	        			'text' : params.term,
						'jenis_pkm_id' : $('#jenis_pkm').val(),
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
}

$(document).on('click','#btn_ajukan',function(){
	let id = $(this).data('id');
	let csrf = $('meta[name=csrf-token]').attr("content");
	if(confirm('Yakin akan mengajukan ?')){
		$.post('{{ route('share.pendaftaran.ajax', ['method' => 'ajukan']) }}',{'_token':csrf,'id':id},function(result){
			if(result.status === 'ok'){
				location.href = "{{ url()->current() }}";
			}else{
				alert('Maaf,terjadi kesalahan!');
			}
		});
	}
});

function hapus_pendaftaran(){
    if(confirm('Yakin akan dihapus ?')){
        return true;
    }
    return false;
}

$(document).on('change', '#jenis_pkm', function () {
	$('.cari_mhs_anggota').each(function(){
		$(this).val(null).trigger('change');
	});
});

$(document).on('change', '#kategori_kegiatan', {'_token':csrf}, function () {
    const kategori_kegiatan_id = $(this).val();
	$('#jenis_pkm').empty().append('<option value="" disabled selected>Pilih Subkategori</option>');

    // Kirim permintaan AJAX ke server
    $.ajax({
        url: ("{{ route('share.pendaftaran.ajax', ['method' => 'get_jenis_pkm']) }}"), // Endpoint untuk mendapatkan data
		method: 'POST', // Gunakan POST jika Anda mengirimkan data
		data: {
			'kategori_kegiatan_id': kategori_kegiatan_id,
			'mhs_nim': '{{ $mhs->nim }}', // Data yang dikirim
			'usulan_pkm_id': '{{ @$usulan_pkm->id }}',
			'_token': csrf // Token CSRF untuk validasi Laravel
		},
		method:'POST',
        success: function (data) {
            $('#jenis_pkm').empty().append('<option value="" disabled selected>Pilih Subkategori</option>');

            $.each(data.jenis_pkm_list, function (key, value) {
                $('#jenis_pkm').append(
                    `<option value="${value.id}">${value.nama_pkm}</option>`
                );
            });

			@if( old('jenis_pkm_id', @$usulan_pkm->jenis_pkm_id) != null )

			const valueToCheck = "{{ old('jenis_pkm_id', @$usulan_pkm->jenis_pkm_id) }}";
			const isValueExists = $('#jenis_pkm option[value="'+ valueToCheck +'"]').length > 0;
			
			if (isValueExists) {
				$('#jenis_pkm').val(valueToCheck);
				$('#jenis_pkm').trigger('change');
			} else {
				$('#kategori_kriteria').val(null);
			}
			
			@endif
        }
    });

	$('.cari_mhs_anggota').each(function(){
		$(this).val(null).trigger('change');
	});
});

@if( old('kategori_kegiatan_id', @$usulan_pkm->kategori_kegiatan_id) != null )
$('#kategori_kegiatan').val("{{ old('kategori_kegiatan_id', @$usulan_pkm->kategori_kegiatan_id) }}");
$('#kategori_kegiatan').trigger('change');
@endif

</script>
<!-- END PAGE LEVEL JS-->
@endpush

@section('content')
<div class="content-header row">
	<div class="content-header-left col-md-6 col-12 mb-2">
		<h3 class="content-header-title">Pendaftaran</h3>
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
	<section id="basic-form-layouts">
		<div class="row match-height">
			<div class="col-md-12">
				<div class="card" style="">
					<div class="card-header">
						<h4 class="card-title" id="basic-layout-form">Form Pendaftaran</h4>
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
							<div class="card-text">
								<p>Silahkan isikan data diri anda dan informasi kegiatan yang akan anda ajukan.</p>
							</div>

							@if(session()->has('message'))
							    <div class="alert alert-success">
							        <b>{{ session()->get('message') }}</b>
							    </div>
							@endif

							@if($errors->any())
					            <div class="alert alert-danger">
					            	<div><b>Pendaftaran gagal, silahkan cek isian berikut :</b></div>
					            	<hr>
				                    @foreach ($errors->all() as $error)
				                        <div>&#42; {{ $error }}</div>
				                    @endforeach
					            </div>
					        @endif

							<form class="form" action="{{ route('admin.pendaftaran.simpan') }}" method="POST" enctype="multipart/form-data">
								{{ csrf_field() }}
								<input type="hidden" name="id" value="{{ @$usulan_pkm->uuid }}">
								<div class="form-body">
									<h4 class="form-section"><i class="ft-user"></i> Personal Info (KETUA)</h4>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="nama_lengkap">Nama Lengkap</label>
												<input type="text" id="nama_lengkap" class="form-control" placeholder="Nama Lengkap" name="nama_lengkap" value="{{ $mhs->nama }}" readonly="readonly" >
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="nim">NIM</label>
												<input type="text" id="nim" class="form-control" placeholder="NIM" name="nim" value="{{ $mhs->nim }}" readonly="readonly" >
											</div>
										</div>
									</div>
                                    <div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="tahun_masuk">Tahun Masuk</label>
												<input type="text" id="tahun_masuk" class="form-control" placeholder="tahun_masuk" name="tahun_masuk" value="{{ $mhs->tahun_masuk }}" readonly="readonly" >
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="semester">Semester</label>
												<input type="text" id="semester" class="form-control" placeholder="semester" name="semester" value="{{ $mhs->nim }}" readonly="readonly" >
											</div>
										</div>
									</div>
									 <div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="fakultas">Fakultas</label>
												<input type="text" id="fakultas" class="form-control" placeholder="Fakultas" name="fakultas" value="{{ $mhs->nama_fak_ijazah }}" readonly="readonly" >
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="prodi">Prodi</label>
												<input type="text" id="prodi" class="form-control" placeholder="Prodi" name="prodi" value="{{ $mhs->nama_forlap }}" readonly="readonly" >
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="email">Email</label>
												<input type="text" id="email" class="form-control" placeholder="Email" name="email" value="{{ $mhs->sso_email }}" readonly="readonly" >
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="telp">No Telp/WA</label>
												<input type="text" id="telp" class="form-control" placeholder="Telp" name="telp" value="{{ $mhs->hp }}" readonly="readonly" >
											</div>
										</div>
									</div>

									<h4 class="form-section"><i class="fa fa-paperclip"></i> Info Kegiatan</h4>

									<div class="form-group">
										<label for="companyName">Judul</label>
										<input type="text" id="judul" class="form-control" placeholder="Judul" name="judul" value="{{ old('judul', @$usulan_pkm->judul) }}">
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="kategori_kegiatan">Kategori</label>
												<input type="text" id="kategori_kegiatan_text" name="kategori_kegiatan_text_id" class="form-control" value="{{ $usulan_pkm->jenis_pkm->kategori_kegiatan->nama_kategori_kegiatan }}" readonly="readonly" />
												<input type="hidden" id="kategori_kegiatan" name="kategori_kegiatan_id" />
												{{-- 
												<select id="kategori_kegiatan" name="kategori_kegiatan_id" class="form-control">
													<option value="" disabled {{ old('kategori_kegiatan_id', @$usulan_pkm->kategori_kegiatan_id ?? '') == null ? 'selected' : '' }}>Pilih kategori</option>
													@foreach($kategori_kegiatan_list as $kategori_kegiatan)
													<option value="{{ $kategori_kegiatan->id }}">
														{{ $kategori_kegiatan->nama_kategori_kegiatan }}
													</option>
													@endforeach
												</select>
												--}}
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="jenis_pkm">Jenis</label>
												<input type="text" id="jenis_pkm_text" name="jenis_pkm_text_id" class="form-control" value="{{ $usulan_pkm->jenis_pkm->nama_pkm }}" readonly="readonly" >
												<input type="hidden" id="jenis_pkm" name="jenis_pkm_id" />
												{{--
												<select id="jenis_pkm" name="jenis_pkm_id" class="form-control">
													<option value="" disabled selected>Pilih subkategori</option>
												</select>
												--}}
											</div>
										</div>
									</div>

									<h4 class="form-section"><i class="ft-user"></i> Anggota</h4>

									<div class="row">
										<div class="col-md-3">
											<label class="label-control" for="nim">Ketua</label>
										</div>
										<div class="col-md-7">
											<div class="form-group">
												<input type="text" id="" class="form-control" placeholder="" name="nim" value="{{ $mhs->nama }}" disabled="disabled" >
											</div>
										</div>
									</div>

									@isset($usulan_pkm->anggota_pkm)
										@php($j = 1)
										@forelse($usulan_pkm->anggota_pkm as $anggota)
										@continue($anggota->sebagai == 0)
										<div class="row">
											<div class="col-md-3">
												<label class="label-control" for="nim">Anggota {{ $j }}</label>
											</div>
											<div class="col-md-7">
												<div class="form-group">
													<input type="text" id="" class="form-control" placeholder="" name="nim" value="{{ $anggota->mhs->nama }}" disabled="disabled" >
													<small>
														Fakultas : {{ $anggota->mhs->nama_fak_ijazah }} , Prodi : {{ $anggota->mhs->nama_forlap }}
													</small>
												</div>
											</div>
											@if(($usulan_pkm->status_usulan->keterangan == "BARU")||($usulan_pkm->status_usulan->keterangan == "DITOLAK"))
											<div class="col-md-2">
												<button class="btn btn-danger btn_hapus_anggota" type="button" data-id="{{ $anggota->id }}"><i class="ft-x"></i></button>
											</div>
											@endif
										</div>
										@php($j++)
										@empty

										@endforelse
									@endisset

									@if($usulan_pkm->anggota_pkm->count() < 5)
									<div class="form-group repeater-default">
										<input type="hidden" name="list_nim" value="" />
		                                <div data-repeater-list="list_nim">
		                                    <div class="row" data-repeater-item>
												<div class="col-md-3">
													<label class="label-control" for="nim">Anggota {{ $usulan_pkm->anggota_pkm->count() }}</label>
												</div>
												<div class="col-md-7">
													<div class="form-group">
														<select id="" class="form-control cari_mhs_anggota" placeholder="Cari mhs" name="nim"></select>
													</div>
												</div>
												<div class="col-md-2">
													<button class="btn btn-danger" type="button" data-repeater-delete><i class="ft-x"></i></button>
												</div>
											</div>
		                                </div>
		                                <button type="button" data-repeater-create id="add-anggotapkm" style="{{ $usulan_pkm->anggota_pkm->count() > 3 ? 'display: none' : ''}}" class="btn btn-primary">
		                                    <i class="ft-plus"></i> Tambah Anggota
		                                </button>
		                            </div>
									@endif

									<h4 class="form-section"><i class="ft-file"></i> Berkas Proposal</h4>

									<div class="form-group">
										<label>Upload Files (MAKS. 5 MB)</label>
									{{--										<label id="file" class="file center-block">--}}
									{{--											<input type="file" id="file" name="berkas[]" multiple>--}}
									{{--											<span class="file-custom"></span>--}}
									{{--										</label>--}}
										<fieldset class="form-group">
												<div class="row">
													<div class="col-md-6">
														<label id="file" class="file center-block">
															<input type="file" class="form-control" id="file" name="berkas[]" multiple>
															<span class="file-custom"></span>
														</label>
													</div>
												</div>
											</fieldset>
									</div>

									@isset($files_to_show)
										<div>File yang telah disimpan :</div>
										<hr>
										@forelse ($files_to_show as $file)
											<div class="alert alert-warning">
												@if(($usulan_pkm->status_usulan->keterangan == "BARU")||($usulan_pkm->status_usulan->keterangan == "DITOLAK"))
												<button class="btn btn-danger btn-sm btn_hapus_document" type="button" data-repeater-delete="" data-id="{{ $usulan_pkm->id }}" data-file="{{ $file->document_path }}"><i class="ft-x"></i></button>
												@endif
												<a href="{{ asset('storage/' . $file->document_path ) }}" target="_blank">{{ $file->document_path }}</a></div>
										@empty
											<div class="alert alert-danger">Anda belum memiliki berkas untuk diverifikasi.</div>
										@endforelse
									@endisset

		                            <h4 class="form-section"><i class="ft-user"></i> Pendamping</h4>
		                            @isset($usulan_pkm->status_usulan->keterangan)
			                            @if($usulan_pkm->status_usulan->keterangan == 'BARU')
										<div class="input-group">
	                                        <div class="input-group-prepend">
	                                            <span class="input-group-text"><i class="ft-search"></i></span>
	                                        </div>
	                                        <select id="pembimbing" class="form-control" placeholder="Cari dosen" name="pegawai_id" style="width: 80%"></select>
	                                    </div>
	                                    @else
	                                    <div class="input-group">
	                                        <div class="input-group-prepend">
	                                            <span class="input-group-text"><i class="ft-search"></i></span>
	                                        </div>
	                                        <input type="hidden" name="pegawai_id" value="{{ $usulan_pkm->pegawai_id }}">
	                                        {{-- <select id="pembimbing" class="form-control" placeholder="Cari dosen" name="pegawai_id" style="width: 80%" disabled="disabled"></select> --}}
											<input type="text" id="" class="form-control" placeholder="" value="{{ $usulan_pkm->pegawai->glr_dpn . " " . $usulan_pkm->pegawai->nama . " " . $usulan_pkm->pegawai->glr_blkg . " [" . $usulan_pkm->pegawai->nip . "]" . " [" . $usulan_pkm->pegawai->nidn . "]" }}" disabled="disabled" >
	                                    </div>
	                                    @endif
                                    @else
										<div class="input-group">
	                                        <div class="input-group-prepend">
	                                            <span class="input-group-text"><i class="ft-search"></i></span>
	                                        </div>
	                                        <select id="pembimbing" class="form-control" placeholder="Cari dosen" name="pegawai_id" style="width: 80%"></select>
	                                    </div>
                                    @endisset

                                    <br>

									@isset($usulan_pkm->status_usulan->keterangan)
										{{--
										@if(@$usulan_pkm->status_usulan->keterangan != "BARU")
		                                    <h4 class="form-section"><i class="fa fa-pencil"></i> Catatan Pendamping</h4>
		                                    @forelse ($usulan_pkm->revisi as $revisi)
						                        <div class="alert {{ $revisi->status_usulan->keterangan == "DITOLAK" ? "alert-warning" : "alert-success" }}">
						                        	{!! '<b>STATUS :</b> '. $revisi->status_usulan->keterangan .' , <b>Catatan :</b> '.  $revisi->catatan_pembimbing .' <b>[ Pada : '. $revisi->created_at .' ]</b>' !!}
						                        </div>
						                    @empty
												<div class="alert alert-info">
						                        	Belum ada catatan dari pembimbing
						                        </div>
						                    @endforelse
					                    @endif
										--}}

					                    @if(@$usulan_pkm->status_usulan->urutan >= 2)
		                                    <h4 class="form-section"><i class="fa fa-pencil"></i> Catatan Reviewer</h4>
		                                    @forelse ($usulan_pkm->review as $review)
						                        <div class="alert {{ $review->status_usulan->keterangan == "GAGAL" ? "alert-warning" : "alert-success" }}">
						                        	{!! '<b>STATUS :</b> '. $review->status_usulan->keterangan .' , <b>Catatan :</b> '.  $review->catatan_reviewer .' <b>[ Pada : '. $review->created_at .' ]</b>' !!}
						                        </div>
						                    @empty
												<div class="alert alert-info">
						                        	Belum ada catatan dari reviewer
						                        </div>
						                    @endforelse
					                    @endif

					                    @if($usulan_pkm->status_usulan->keterangan != "BARU")

										<h4 class="form-section"><i class="fa fa-pencil"></i> Catatan Perbaikan</h4>
										@forelse ($usulan_pkm->perbaikan as $perbaikan)
					                    <div class="alert alert-info">
					                       	{!! '<b>Catatan :</b> '.  $perbaikan->catatan_perbaikan .' <b>[ Pada : '. $perbaikan->created_at .' ]</b>' !!}
				                        </div>
				                        @empty
				                        	<div class="alert alert-info">
					                        	Belum ada catatan perubahan
					                        </div>
				                        @endforelse

										@if($usulan_pkm->status_usulan->keterangan != "DISETUJUI")
										<div class="form-group">
											<textarea class="form-control" placeholder="Isian catatan perbaikan" name="catatan_perbaikan"></textarea>
										</div>
										@endif

										@endif

									@endisset

								</div>

								<div class="form-actions">
									@isset($usulan_pkm->status_usulan->keterangan)
										@if(@$usulan_pkm->status_usulan->keterangan == "BARU")
                                            <button type="submit" class="btn btn-primary">
												<i class="fa fa-check-square-o"></i> Save
											</button>
											<button type="button" class="btn btn-success" id="btn_ajukan" data-id="{{ $usulan_pkm->id }}">
												<i class="fa fa-share-square-o"></i> Ajukan
											</button>
                                            <a class="btn btn-danger" onclick="return hapus_pendaftaran()" href="{{ route('admin.pendaftaran.hapus',['uuid' => $usulan_pkm->uuid])  }}">
												<i class="fa fa-trash-o"></i> Hapus
											</a>
										@elseif(@$usulan_pkm->status_usulan->keterangan == "DITOLAK")
											<button type="submit" class="btn btn-primary">
												<i class="fa fa-check-square-o"></i> Save
											</button>
										@else
					                        	<div class="alert alert-info">
						                        	Usulan anda telah diproses [ <b>STATUS :</b> {{ $usulan_pkm->status_usulan->keterangan == 'DISETUJUI' ? 'DIUSULKAN' : $usulan_pkm->status_usulan->keterangan }} ]
						                        </div>
												{{--
												<a class="btn btn-danger" onclick="return hapus_pendaftaran()" href="{{ route('admin.pendaftaran.hapus',['uuid' => $usulan_pkm->uuid])  }}">
												<i class="fa fa-trash-o"></i> Hapus
												--}}
											</a>
										@endif
									@else
										<button type="submit" class="btn btn-primary">
											<i class="fa fa-check-square-o"></i> Save
										</button>
									@endisset
                                            <a class="btn btn-warning" href="{{ route('share.pendaftaran.list') }}">
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
	<!-- // Basic form layout section end -->
</div>
@endsection
