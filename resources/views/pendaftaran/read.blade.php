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
const maxReviewers = 2; // Maksimal jumlah elemen
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
		$(this).find('.label-control').text('Reviewer ' + (index + 1));
	});
}

// Fungsi untuk menampilkan/menyembunyikan tombol "Tambah Reviewer"
function toggleAddButton() {
	const itemCount = $repeater.find('[data-repeater-item]').length;
	if (itemCount >= maxReviewers) {
		$('#add-reviewer').hide();
	} else {
		$('#add-reviewer').show();
	}
}


@isset($usulan_pkm->jenis_pkm_id)
$('#jenis').val('{{ $usulan_pkm->jenis_pkm_id }}');
$('#kategori_kegiatan').val("{{ $usulan_pkm->kategori_kegiatan_id }}");
@endif

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

$(document).on('click','.btn_hapus_reviewer',function(){
	let reviewer_id = $(this).data('reviewer-id');
	let usulan_pkm_id = $(this).data('usulan-pkm-id');
	let csrf = $('meta[name=csrf-token]').attr("content");
	if(confirm('Yakin akan menghapus ?')){
		$.post('{{ route('share.pendaftaran.ajax', ['method' => 'hapus_reviewer']) }}',{'_token':csrf,'reviewer_id':reviewer_id,'usulan_pkm_id':usulan_pkm_id},function(result){
			if(result.status === 'ok'){
				location.reload();
			}else{
				alert(result.message);
			}
		});
	}
});

$("#pembimbing").select2({
	placeholder: 'Cari pembimbing',
	minimumInputLength: 3,
 	ajax: {
	    url: "{{ route('mhs.users.ajax', ['method' => 'cari_pembimbing']) }}",
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
		    url: "{{ route('mhs.users.ajax', ['method' => 'cari_mhs']) }}",
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

	$(".cari_reviewer").select2({
		placeholder: 'Cari reviewer',
		minimumInputLength: 3,
		width: '100%',
	 	ajax: {
		    url: "{{ route('admin.users.ajax', ['method' => 'cari_reviewer']) }}",
		    dataType: 'json',
		    data: function (params){
	      			var query = {
	        			'text' : params.term,
                        'usulan_pkm_id' : '{{ $usulan_pkm->id }}',
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

$(document).on('click','#btn_setujui',function(){
	let pesan = $('textarea[name="catatan_pembimbing"]').val();
	if(pesan.trim() != ''){
		if(confirm("Yakin akan menyetujui ?")){
			$('input[name="approval"]').val("DISETUJUI");
			$('form[name="form_read"]').submit();
		}
	}else{
		alert('Catatan pembimbing masih kosong.');
	}
	return false;
});

$(document).on('click','#btn_tetapkan_penilaian',function(){
	if(confirm("Yakin akan menetapkan ?")){
		$('form[name="form_read"]').attr('action', '{{ route('admin.pendaftaran.approval', ['uuid' => $usulan_pkm->uuid]) }}');
		$('input[name="approval"]').val("SUDAH_DINILAI");
		$('form[name="form_read"]').submit();
	}
	return false;
});

$(document).on('click','#btn_tolak',function(){
	let pesan = $('textarea[name="catatan_pembimbing"]').val();
	if(pesan.trim() != ''){
		if(confirm("Yakin akan menolak ?")){
			$('input[name="approval"]').val("DITOLAK");
			$('form[name="form_read"]').submit();
		}
	}else{
		alert('Catatan pembimbing masih kosong.');
	}
	return false;
});

$(document).on('click','#btn_lanjut',function(){
	// let pesan = $('textarea[name="catatan_pembimbing"]').val();
	// if(pesan.trim() != ''){
	// 	if(confirm("Yakin akan menyetujui ?")){
	// 		$('input[name="approval"]').val("DISETUJUI");
	// 		$('form[name="form_read"]').submit();
	// 	}
	// }else{
	// 	alert('Catatan pembimbing masih kosong.');
	// }

	if(confirm("Yakin akan menyetujui ?")){
		$('input[name="approval"]').val("LANJUT");
		$('form[name="form_read"]').submit();
	}
	return false;
});

$(document).on('click','#btn_gagal_lanjut',function(){
	// let pesan = $('textarea[name="catatan_pembimbing"]').val();
	// if(pesan.trim() != ''){
	// 	if(confirm("Yakin akan menyetujui ?")){
	// 		$('input[name="approval"]').val("DISETUJUI");
	// 		$('form[name="form_read"]').submit();
	// 	}
	// }else{
	// 	alert('Catatan pembimbing masih kosong.');
	// }

	if(confirm("Yakin akan dikembalikan ?")){
		$('input[name="approval"]').val("BARU");
		$('form[name="form_read"]').submit();
	}
	return false;
});

$(document).on('click','#btn_lolos',function(){
	let pesan = $('textarea[name="catatan_reviewer"]').val();
	if(pesan.trim() != ''){
		if(confirm("Yakin akan memproses ?")){
			$('input[name="approval"]').val("LOLOS");
			$('form[name="form_read"]').submit();
		}
	}else{
		alert('Catatan reviewer masih kosong.');
	}
	return false;
});

$(document).on('click','#btn_gagal',function(){
	let pesan = $('textarea[name="catatan_reviewer"]').val();
	if(pesan.trim() != ''){
		if(confirm("Yakin akan memproses ?")){
			$('input[name="approval"]').val("GAGAL");
			$('form[name="form_read"]').submit();
		}
	}else{
		alert('Catatan reviewer masih kosong.');
	}
	return false;
});

</script>
<!-- ENDPAGE LEVEL JS-->
@endpush

@section('content')
<div class="content-header row">
	<div class="content-header-left col-md-6 col-12 mb-2">
		<h3 class="content-header-title">Pendaftaran PKM</h3>
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
								<p>Silahkan isikan data diri anda dan informasi PKM yang akan anda ajukan.</p>
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

							@if((Userhelp::get_selected_role() == 'PEMBIMBING') || (Userhelp::get_selected_role() == 'WD1') || (Userhelp::get_selected_role() == 'REVIEWER'))
							<form class="form" action="{{ route('admin.pendaftaran.approval', ['uuid' => $usulan_pkm->uuid]) }}" method="POST" enctype="multipart/form-data" name="form_read">
							@endif
							@if(Userhelp::get_selected_role() == 'ADMIN')
							<form class="form" action="{{ route('admin.pendaftaran.set-reviewer', ['uuid' => $usulan_pkm->uuid]) }}" method="POST" enctype="multipart/form-data" name="form_read">
							@endif
								{{ csrf_field() }}
								<input type="hidden" name="id" value="{{ @$usulan_pkm->uuid }}">
								<input type="hidden" name="approval" value="">
								<div class="form-body">
									<h4 class="form-section"><i class="ft-user"></i> Personal Info (KETUA PKM)</h4>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="projectinput1">Nama Lengkap</label>
												<input type="text" id="nama_lengkap" class="form-control" placeholder="Nama Lengkap" name="nama_lengkap" value="{{ $mhs->nama }}" disabled="disabled" >
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="projectinput2">NIM</label>
												<input type="text" id="nim" class="form-control" placeholder="NIM" name="nim" value="{{ $mhs->nim }}" disabled="disabled" >
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
												<label for="projectinput3">Email</label>
												<input type="text" id="email" class="form-control" placeholder="Email" name="email" value="{{ $mhs->sso_email }}" disabled="disabled" >
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="projectinput4">No Telp/WA</label>
												<input type="text" id="telp" class="form-control" placeholder="Telp" name="telp" value="{{ $mhs->hp }}" disabled="disabled" >
											</div>
										</div>
									</div>

									<h4 class="form-section"><i class="fa fa-paperclip"></i> Info PKM</h4>

									<div class="form-group">
										<label for="companyName">Judul</label>
										<input type="text" id="judul" class="form-control" placeholder="Judul" name="judul" value="{{ @$usulan_pkm->judul }}" disabled="disabled">
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="kategori_kegiatan">Kategori</label>
												<select id="kategori_kegiatan" name="kategori_kegiatan_id" class="form-control" disabled="disabled">
													@foreach($kategori_kegiatan_list as $kategori_kegiatan)
													<option value="{{ $kategori_kegiatan->id }}">
														{{ $kategori_kegiatan->nama_kategori_kegiatan }}
													</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>

									<div class="row">

										<div class="col-md-6">
											<div class="form-group">
												<label for="jenis">Jenis</label>
												<select id="jenis" name="jenis" class="form-control" disabled="disabled">
													@foreach($jenis_pkm as $j)
													<option value="{{ $j->id }}">{{ $j->nama_pkm }}</option>
													@endforeach
												</select>
											</div>
										</div>

										{{-- <div class="col-md-6">
											<div class="form-group">
												<label for="projectinput6">Budget</label>
												<select id="projectinput6" name="budget" class="form-control">
													<option value="0" selected="" disabled="">Budget</option>
													<option value="less than 5000$">less than 5000$</option>
													<option value="5000$ - 10000$">5000$ - 10000$</option>
													<option value="10000$ - 20000$">10000$ - 20000$</option>
													<option value="more than 20000$">more than 20000$</option>
												</select>
											</div>
										</div> --}}

									</div>

									<div class="row">

										<div class="col-md-6">
											<div class="form-group">
												<label for="tema_usulan_pkm">Tema</label>
												<select id="tema_usulan_pkm" name="tema_usulan_pkm" class="form-control" disabled="disabled">
													@foreach($tema_usulan_pkm_list as $tema_usulan_pkm)
													@if(!empty($usulan_pkm->tema_usulan_pkm_id))
													<option value="{{ $tema_usulan_pkm->id }}" {{ old('tema_usulan_pkm_id', @$usulan_pkm->tema_usulan_pkm_id) == $tema_usulan_pkm->id ? 'selected' : '' }}>
														{{ $tema_usulan_pkm->nama_tema }}
													</option>
													@else
													<option value="" disabled selected>Pilih tema</option>
													@endif
													@endforeach
												</select>
											</div>
										</div>

										{{-- <div class="col-md-6">
											<div class="form-group">
												<label for="projectinput6">Budget</label>
												<select id="projectinput6" name="budget" class="form-control">
													<option value="0" selected="" disabled="">Budget</option>
													<option value="less than 5000$">less than 5000$</option>
													<option value="5000$ - 10000$">5000$ - 10000$</option>
													<option value="10000$ - 20000$">10000$ - 20000$</option>
													<option value="more than 20000$">more than 20000$</option>
												</select>
											</div>
										</div> --}}

									</div>

									<h4 class="form-section"><i class="ft-user"></i> Anggota PKM</h4>

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

									@isset($usulan_pkm->id)
									@foreach($usulan_pkm->anggota_pkm as $anggota)
									@continue($anggota->sebagai == 0)
									<div class="row">
										<div class="col-md-3">
											<label class="label-control" for="nim">Anggota</label>
										</div>
										<div class="col-md-7">
											<div class="form-group">
												<input type="text" id="" class="form-control" placeholder="" name="nim" value="{{ $anggota->mhs->nama }}" disabled="disabled" >
												<small>
													Fakultas : {{ $anggota->mhs->nama_fak_ijazah }} , Prodi : {{ $anggota->mhs->nama_forlap }}
												</small>
											</div>
										</div>
										{{-- <div class="col-md-2">
											<button class="btn btn-danger btn_hapus_anggota" type="button" data-id="{{ $anggota->id }}"><i class="ft-x"></i></button>
										</div> --}}
									</div>
									@endforeach
									@endisset

									{{-- <div class="form-group repeater-default">

		                                <div data-repeater-list="list_nim">
		                                    <div class="row" data-repeater-item>
												<div class="col-md-3">
													<label class="label-control" for="nim">Anggota</label>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<select id="" class="form-control cari_mhs" placeholder="Cari mhs" name="nim"></select>
													</div>
												</div>
												<div class="col-md-2">
													<button class="btn btn-danger" type="button" data-repeater-delete><i class="ft-x"></i></button>
												</div>
											</div>

		                                </div>
		                                <button type="button" data-repeater-create class="btn btn-primary">
		                                    <i class="ft-plus"></i> Tambah Anggota
		                                </button>
		                            </div> --}}

									<h4 class="form-section"><i class="ft-file"></i> Berkas Proposal</h4>

									{{-- <div class="form-group">
										<label>Upload Files</label>
										<label id="file" class="file center-block">
											<input type="file" id="file" name="berkas[]" multiple>
											<span class="file-custom"></span>
										</label>
									</div> --}}

									@isset($files_to_show)
									<div>File yang telah disimpan :</div>
									<hr>
									@forelse ($files_to_show as $d => $file)
										<div class="alert alert-warning">
											<a href="{{ asset('storage/' . $file->document_path ) }}" target="_blank">{{ 'dokumen('. ($d + 1) . ')' }}</a>
										</div>
									@empty
										<div class="alert alert-danger">Anda belum memiliki berkas untuk diverifikasi.</div>
									@endforelse
									@endisset

		                            <h4 class="form-section"><i class="ft-user"></i> Pendamping</h4>
									<div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ft-search"></i></span>
                                        </div>
                                        {{-- <select id="pembimbing" class="form-control" placeholder="Cari dosen" name="pegawai_id" disabled="disabled" style="width: 80%"></select> --}}
										<input type="text" id="" class="form-control" placeholder="" value="{{ $usulan_pkm->pegawai->glr_dpn . " " . $usulan_pkm->pegawai->nama . " " . $usulan_pkm->pegawai->glr_blkg . " [" . $usulan_pkm->pegawai->nip . "]" . " [" . $usulan_pkm->pegawai->nuptk . "]" }}" disabled="disabled" >
	                                </div>

									<br>
									{{--
                                    <h4 class="form-section"><i class="fa fa-pencil"></i> Catatan Pendamping</h4>
                                    @forelse ($usulan_pkm->revisi as $revisi)
				                        <div class="alert {{ $revisi->status_usulan->keterangan == "DITOLAK" ? "alert-warning" : "alert-success" }}">
				                        	{!! '<b>STATUS :</b> '. $revisi->status_usulan->keterangan .' , <b>Catatan :</b> '.  $revisi->catatan_pembimbing .' <b>[ Pada : '. $revisi->created_at .' ]</b>' !!}
				                        </div>
				                    @empty

				                    @endforelse

									@if(Userhelp::get_selected_role() == 'PEMBIMBING')
									<div class="form-group">
										<textarea class="form-control" placeholder="Isian catatan pembimbing" name="catatan_pembimbing"></textarea>
									</div>
									@endif

									<br>
									--}}
									
									{{--
									<h4 class="form-section"><i class="fa fa-pencil"></i> Catatan Reviewer</h4>
                                    @forelse ($usulan_pkm->review as $review)
				                        <div class="alert {{ $review->status_usulan->keterangan == "TOLAK" ? "alert-warning" : "alert-success" }}">
				                        	{!! '<b>STATUS :</b> '. $review->status_usulan->keterangan .' , <b>Catatan :</b> '.  $review->catatan_reviewer .' <b>[ Pada : '. $review->created_at .' ]</b>' !!}
				                        </div>
				                    @empty
				                    	<div class="alert alert-info">
				                        	Belum ada catatan reviewer
				                        </div>
				                    @endforelse

									@if(Userhelp::get_selected_role() == 'REVIEWER')
									<div class="form-group">
										<textarea class="form-control" placeholder="Isian catatan reviewer" name="catatan_reviewer"></textarea>
									</div>
									@endif

									<br>
									--}}

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

									<br>

								@if(Userhelp::get_selected_role() == 'ADMIN')
								<h4 class="form-section"><i class="fa fa-pencil"></i> Ploting Reviewer</h4>

								@if($usulan_pkm->reviewer->count() == 1)
									@foreach($usulan_pkm->reviewer as $i => $reviewer)
									<div class="row">
										<div class="col-md-3">
											<label class="label-control" for="id">Reviewer {{ $i + 1 }}</label>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="text" id="" class="form-control" placeholder="" name="id" value="{{ $reviewer->glr_dpn . " " . $reviewer->nama . " " . $reviewer->glr_blkg }}" disabled="disabled" >
											</div>
										</div>
                                        @if($reviewer->penilaian_reviewer()->where('usulan_pkm_id', $usulan_pkm->id)->count() == 0)
										<div class="col-md-2">
											<button class="btn btn-danger btn_hapus_reviewer" type="button" data-reviewer-id="{{ $reviewer->id }}" data-usulan-pkm-id="{{ $usulan_pkm->id }}"><i class="ft-x"></i></button>
										</div>
										@else
										<div class="col-md-2">
											<a href="{{ route('penilaian-reviewer.lihat', ['usulan_pkm' => $usulan_pkm, 'reviewer' => $reviewer]) }}" >Lihat Penilaian</a>
										</div>
                                        @endif
									</div>
									@endforeach
	                                <div class="form-group repeater-default">
	                                <div data-repeater-list="list_id">
	                                    <div class="row" data-repeater-item>
											<div class="col-md-3">
												<label class="label-control" for="id">Reviewer 2</label>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<select id="" class="form-control cari_reviewer" placeholder="Cari reviewer" name="id"></select>
												</div>
											</div>
											<div class="col-md-2">
												<button class="btn btn-danger" type="button" data-repeater-delete><i class="ft-x"></i></button>
											</div>
										</div>
	                                </div>
	                            </div>
								@elseif($usulan_pkm->reviewer->count() == 2)
									@foreach($usulan_pkm->reviewer()->orderBy('urutan')->get() as $i => $reviewer)
									<div class="row">
										<div class="col-md-3">
											<label class="label-control" for="id">Reviewer {{ $i + 1 }}</label>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="text" id="" class="form-control" placeholder="" name="id" value="{{ $reviewer->glr_dpn . " " . $reviewer->nama . " " . $reviewer->glr_blkg }}" disabled="disabled" >
											</div>
										</div>
                                        @if($reviewer->penilaian_reviewer()->where('usulan_pkm_id', $usulan_pkm->id)->count() == 0)
										<div class="col-md-2">
											<button class="btn btn-danger btn_hapus_reviewer" type="button" data-reviewer-id="{{ $reviewer->id }}" data-usulan-pkm-id="{{ $usulan_pkm->id }}"><i class="ft-x"></i></button>
										</div>
										@else
										<div class="col-md-2">
											<a href="{{ route('penilaian-reviewer.lihat', ['usulan_pkm' => $usulan_pkm, 'reviewer' => $reviewer]) }}" >Lihat Penilaian</a>
										</div>
                                        @endif
									</div>
									@endforeach
								@else
                                <div class="form-group repeater-default">
	                                <div data-repeater-list="list_id">
	                                    <div class="row" data-repeater-item>
											<div class="col-md-3">
												<label class="label-control" for="id">Reviewer 1</label>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<select id="" class="form-control cari_reviewer" placeholder="Cari reviewer" name="id"></select>
												</div>
											</div>
											<div class="col-md-2">
												<button class="btn btn-danger" type="button" data-repeater-delete><i class="ft-x"></i></button>
											</div>
										</div>
	                                </div>
	                                <button type="button" data-repeater-create id="add-reviewer" class="btn btn-primary">
	                                    <i class="ft-plus"></i> Tambah Reviewer
	                                </button>
	                            </div>
								@endif
	                            @endif

                                </div>

                                <div class="form-actions">
	                            @if(Userhelp::get_selected_role() == 'PEMBIMBING')
										{{-- <a class="btn btn-danger btn_hapus" href="{{ route('admin.pendaftaran.approval', ['id' => $usulan_pkm->id]) }}"  >
											<i class="fa fa-thumbs-down" ></i> Tolak
										</a> --}}
										@if($usulan_pkm->status_usulan->keterangan == "MENUNGGU")
										<button class="btn btn-info" id="btn_tolak" type="button">
											<i class="fa fa-pencil" ></i> Tolak
										</button>
										<button class="btn btn-success" id="btn_setujui" type="button">
											<i class="fa fa-thumbs-up" ></i> Setujui
										</button>
										@else
										<div class="alert alert-warning">
											Anda telah memproses usulan ini.
										</div>
										@endif
                                @endif

								@if(Userhelp::get_selected_role() == 'WD1')
										@if($usulan_pkm->status_usulan->keterangan == "DISETUJUI")
										{{-- <button class="btn btn-info" id="btn_tolak" type="button">
											<i class="fa fa-pencil" ></i> Tolak
										</button> --}}
										<button class="btn btn-success" id="btn_lanjut" type="button">
											<i class="fa fa-thumbs-up" ></i> Lanjutkan
										</button>
										<button class="btn btn-danger" id="btn_gagal_lanjut" type="button">
											<i class="fa fa-thumbs-down" ></i> Kembalikan
										</button>
										@else
										<div class="alert alert-warning">
											Anda telah memproses usulan ini.
										</div>
										@endif
                                @endif

                                @if(Userhelp::get_selected_role() == 'ADMIN')
										{{-- <a class="btn btn-danger btn_hapus" href="{{ route('admin.pendaftaran.approval', ['id' => $usulan_pkm->id]) }}"  >
											<i class="fa fa-thumbs-down" ></i> Tolak
										</a> --}}
										@if($usulan_pkm->status_usulan->keterangan == "LANJUT")
											<button class="btn btn-success" type="submit">
												<i class="fa fa-thumbs-up" ></i> Save
											</button>
											@if($usulan_pkm->penilaian_reviewer()->distinct()->count('reviewer_id') > 0)
												@if($usulan_pkm->penilaian_reviewer()->distinct()->count('reviewer_id') == $usulan_pkm->reviewer_usulan_pkm->count())
												<button class="btn btn-danger" type="button" id="btn_tetapkan_penilaian">
													<i class="fa fa-paper-plane" ></i> Tetapkan Penilaian
												</button>
												@endif
											@endif
										@else
										<div class="alert alert-warning">
											Anda telah memproses usulan ini.
										</div>
										@endif
                                @endif

                                @if(Userhelp::get_selected_role() == 'REVIEWER')
										{{-- <a class="btn btn-danger btn_hapus" href="{{ route('admin.pendaftaran.approval', ['id' => $usulan_pkm->id]) }}"  >
											<i class="fa fa-thumbs-down" ></i> Tolak
										</a> --}}
										@if($usulan_pkm->status_usulan->keterangan == "LANJUT")
										{{-- <button class="btn btn-danger" id="btn_gagal" type="submit">
											<i class="fa fa-thumbs-down" ></i> Gagal
										</button>
										<button class="btn btn-success" id="btn_lolos" type="submit">
											<i class="fa fa-thumbs-up" ></i> Lolos
										</button> --}}
											@if($usulan_pkm->penilaian_reviewer()->where('reviewer_id', $pegawai->id)->count() > 0)
											<div class="alert alert-warning">
												Anda telah menilai usulan ini.
											</div>
											@endif
										<a class="btn btn-success" href="{{ route('penilaian-reviewer.create', ['usulan_pkm' => $usulan_pkm]) }}">
											<i class="fa fa-edit" ></i> Nilai
										</a>
										@elseif($usulan_pkm->status_usulan->keterangan == "SUDAH_DINILAI")
										<div class="alert alert-warning">
											Anda telah menilai usulan ini.
										</div>
										<a class="btn btn-info" href="{{ route('penilaian-reviewer.lihat', ['usulan_pkm' => $usulan_pkm, 'reviewer' => $pegawai->id]) }}" >
											<i class="fa fa-edit" ></i> Lihat Nilai
										</a>
										@else
										{{-- <a class="btn btn-success" href="#">
											<i class="fa fa-edit" ></i> Nilai
										</a> --}}
										@endif
                                @endif

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
