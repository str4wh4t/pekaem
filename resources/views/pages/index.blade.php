@extends('template.main')

@push('page_level_css')
<!-- BEGIN PAGE LEVEL JS-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/css/pages/project.css') }}">

<!-- END PAGE LEVEL JS-->
@endpush

@push('page_level_js')
<!-- BEGIN PAGE LEVEL JS-->


<!-- END PAGE LEVEL JS-->
@endpush

@section('content')

<section class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-head">
            <div class="card-header">
               <h4 class="card-title">Aplikasi PKM</h4>
               <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
               {{-- <div class="heading-elements">
                  <span class="badge badge-default badge-warning">Mobile</span>
                  <span class="badge badge-default badge-success">New</span>
                  <span class="badge badge-default badge-info">iOS</span>
               </div> --}}
            </div>
            {{-- <div class="px-1">
               <ul class="list-inline list-inline-pipe text-center p-1 border-bottom-grey border-bottom-lighten-3">
                  <li>Project Owner: <span class="text-muted">Margaret Govan</span></li>
                  <li>Start: <span class="text-muted">01/Feb/2017</span></li>
                  <li>Due on: <span class="text-muted">01/Oct/2017</span></li>
                  <li><a href="#" class="text-muted" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Export as PDF"><i class="fa fa-file-pdf-o"></i></a></li>
               </ul>
            </div> --}}
         </div>
         <!-- project-info -->
         <div id="project-info" class="card-body row">
            <div class="project-info-count col-lg-4 col-md-12">
               <div class="project-info-icon">
                  <h2><?= $usulan_pkm_total ?></h2>
                  <div class="project-info-sub-icon">
                     <span class="fa fa-files-o"></span>
                  </div>
               </div>
               <div class="project-info-text pt-1">
                  <h5>Total Proposal Masuk</h5>
               </div>
            </div>
            <div class="project-info-count col-lg-4 col-md-12">
               <div class="project-info-icon bg-success">
                  <h2 style="color: #fff"><?= $usulan_pkm_sudah_dinilai ?></h2>
                  <div class="project-info-sub-icon">
                     <span class="fa fa-files-o"></span>
                  </div>
               </div>
               <div class="project-info-text pt-1">
                  <h5>Total Proposal Sudah Dinilai</h5>
               </div>
            </div>
            <div class="project-info-count col-lg-4 col-md-12">
               <div class="project-info-icon bg-danger">
                  <h2 style="color: #fff"><?= $usulan_pkm_belum_dinilai ?></h2>
                  <div class="project-info-sub-icon">
                     <span class="fa fa-files-o"></span>
                  </div>
               </div>
               <div class="project-info-text pt-1">
                  <h5>Total Proposal Belum Dinilai</h5>
               </div>
            </div>
         </div>
         <!-- project-info -->
         <div class="card-body">
            @foreach($kategori_kegiatan_list as $kategori_kegiatan)
            <div class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-1">
               <span>Rasio Usulan {{ $kategori_kegiatan->nama_kategori_kegiatan }}</span>
            </div>
            <div class="row py-2">
               @foreach($kategori_kegiatan->jenis_pkm as $jenis_pkm_row)
               <div class="col-lg-2 col-md-6">
                  <div class="insights px-2">
                     <div><span class="text-info h3 ">{{ count($usulan_pkm->where('jenis_pkm_id',$jenis_pkm_row->id)) }}</span> <span class="float-right">{{ $jenis_pkm_row->nama_pkm }}</span></div>
                     <div class="progress progress-md mt-1 mb-0">
                         @if(!empty($usulan_pkm_total))
                         <div class="progress-bar bg-info" role="progressbar" style="width: {{ round((count($usulan_pkm->where('jenis_pkm_id',$jenis_pkm_row->id))/$usulan_pkm_total)*100,2) }}%" aria-valuenow="{{ round((count($usulan_pkm->where('jenis_pkm_id',$jenis_pkm_row->id))/$usulan_pkm_total)*100,2) }}" aria-valuemin="0" aria-valuemax="100">
                           {{ round((count($usulan_pkm->where('jenis_pkm_id',$jenis_pkm_row->id))/$usulan_pkm_total)*100,2) }}%
                        </div>
                         @else
                         <div class="progress-bar bg-info" role="progressbar" style="width: {{ 0 }}%" aria-valuenow="{{ 0 }}" aria-valuemin="0" aria-valuemax="100">{{ 0 }}%</div>
                         @endif
                     </div>
                  </div>
               </div>
               @endforeach
            </div>
            @endforeach
         </div>
      </div>
   </div>
</section>

@endsection
