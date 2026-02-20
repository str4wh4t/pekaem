@extends('template.main')

@push('page_level_css')
<!-- BEGIN PAGE LEVEL JS-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/css/pages/project.css') }}">
<style type="text/css">
   .project-info-sub-icon:hover {
       background-color: #007bff !important; /* Biru Bootstrap */
       color: white; /* Opsional: Ubah warna teks atau ikon */
       cursor: pointer; /* Opsional: Tampilkan kursor pointer */
   }
   </style>
<!-- END PAGE LEVEL JS-->
@endpush

@push('page_level_js')
<!-- BEGIN PAGE LEVEL JS-->
<script type="text/javascript">

   function openlink(url) {
      console.log(url);
       window.location = url;
       return false;
   }

   // Handle tahun filter change
   document.addEventListener('DOMContentLoaded', function() {
      var tahunFilter = document.getElementById('tahun_filter');
      if (tahunFilter) {
         tahunFilter.addEventListener('change', function() {
            var selectedTahun = this.value;
            var currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('tahun', selectedTahun);
            window.location.href = currentUrl.toString();
         });
      }
   });

</script>
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
               <div class="heading-elements">
                  <div class="form-group mb-0">
                     <label for="tahun_filter" class="mr-2" style="margin-bottom: 0;">Tahun:</label>
                     <select id="tahun_filter" name="tahun" class="form-control form-control-sm" style="display: inline-block; width: auto; min-width: 100px;">
                        @foreach($tahun_list as $tahun_option)
                        <option value="{{ $tahun_option }}" {{ $tahun == $tahun_option ? 'selected' : '' }}>{{ $tahun_option }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
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
         <div class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-2">
            <span><b>Rekap Usulan Proposal</b></span>
         </div>
         <div id="project-info" class="card-body row p-0">
            <div class="project-info-count col-lg-3 col-md-12">
               <div class="project-info-icon" onclick="return openlink('{{ route('dashboard') }}')">
                  <h2><?= $usulan_pkm_total ?></h2>
                  <div class="project-info-sub-icon">
                     <span class="fa fa-files-o"></span>
                  </div>
               </div>
               <div class="project-info-text">
                  <h5>Proposal Masuk</h5>
               </div>
            </div>
            <div class="project-info-count col-lg-3 col-md-12">
               <div class="project-info-icon bg-warning">
                  <h2 style="color: #fff"><?= $usulan_pkm_proses ?></h2>
                  <div class="project-info-sub-icon">
                     <span class="fa fa-files-o"></span>
                  </div>
               </div>
               <div class="project-info-text">
                  <h5>Proposal Proses</h5>
               </div>
            </div>
            <div class="project-info-count col-lg-3 col-md-12">
               <div class="project-info-icon bg-danger">
                  <h2 style="color: #fff"><?= $usulan_pkm_belum_dinilai ?></h2>
                  <div class="project-info-sub-icon">
                     <span class="fa fa-files-o"></span>
                  </div>
               </div>
               <div class="project-info-text">
                  <h5>Proposal Belum Dinilai</h5>
               </div>
            </div>
            <div class="project-info-count col-lg-3 col-md-12">
               <div class="project-info-icon bg-success">
                  <h2 style="color: #fff"><?= $usulan_pkm_sudah_dinilai ?></h2>
                  <div class="project-info-sub-icon">
                     <span class="fa fa-files-o"></span>
                  </div>
               </div>
               <div class="project-info-text">
                  <h5>Proposal Sudah Dinilai</h5>
               </div>
            </div>
         </div>
         <!-- project-info -->
         <!-- Monitoring Target dan Capaian -->
         <div class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-2">
            <span><b>Monitoring Target dan Capaian Usulan PKM</b></span>
         </div>
         <div class="card-body">
            @if(empty($kode_fakultas))
               <!-- View for all faculties (super admin) -->
               @if(isset($target_pkm_list) && count($target_pkm_list) > 0)
                  <div class="table-responsive">
                     <table class="table table-striped table-bordered">
                        <thead>
                           <tr>
                              <th>Fakultas</th>
                              <th>Target</th>
                              <th>Capaian</th>
                              <th>Persentase</th>
                              <th>Status</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($target_pkm_list as $target)
                              @php
                                 $capaian = \App\UsulanPkm::where('tahun', $tahun)->where('kode_fakultas', $target->kode_fakultas)->count();
                                 $target_value = $target->target_usulan_pkm;
                                 $persentase = $target_value > 0 ? round(($capaian / $target_value) * 100, 2) : 0;
                                 $status_class = $persentase >= 100 ? 'success' : ($persentase >= 75 ? 'warning' : 'danger');
                                 $status_text = $persentase >= 100 ? 'Tercapai' : ($persentase >= 75 ? 'Mendekati' : 'Belum Tercapai');
                              @endphp
                              <tr>
                                 <td>{{ $target->fakultas ? $target->fakultas->nama_fak_ijazah : $target->kode_fakultas }}</td>
                                 <td class="text-right">{{ number_format($target_value, 0, ',', '.') }}</td>
                                 <td class="text-right">{{ number_format($capaian, 0, ',', '.') }}</td>
                                 <td>
                                    <div class="progress progress-md mt-1 mb-0">
                                       <div class="progress-bar bg-{{ $status_class }}" role="progressbar" 
                                            style="width: {{ min($persentase, 100) }}%" 
                                            aria-valuenow="{{ $persentase }}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100">
                                          {{ $persentase }}%
                                       </div>
                                    </div>
                                 </td>
                                 <td>
                                    <span class="badge badge-{{ $status_class }}">{{ $status_text }}</span>
                                 </td>
                              </tr>
                           @endforeach
                           <tr class="font-weight-bold bg-light">
                              <td>Total</td>
                              <td class="text-right">{{ number_format($target_pkm_total, 0, ',', '.') }}</td>
                              <td class="text-right">{{ number_format($usulan_pkm_total, 0, ',', '.') }}</td>
                              <td>
                                 @php
                                    $total_persentase = $target_pkm_total > 0 ? round(($usulan_pkm_total / $target_pkm_total) * 100, 2) : 0;
                                    $total_status_class = $total_persentase >= 100 ? 'success' : ($total_persentase >= 75 ? 'warning' : 'danger');
                                 @endphp
                                 <div class="progress progress-md mt-1 mb-0">
                                    <div class="progress-bar bg-{{ $total_status_class }}" role="progressbar" 
                                         style="width: {{ min($total_persentase, 100) }}%" 
                                         aria-valuenow="{{ $total_persentase }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                       {{ $total_persentase }}%
                                    </div>
                                 </div>
                              </td>
                              <td>
                                 <span class="badge badge-{{ $total_status_class }}">
                                    {{ $total_persentase >= 100 ? 'Tercapai' : ($total_persentase >= 75 ? 'Mendekati' : 'Belum Tercapai') }}
                                 </span>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               @else
                  <div class="alert alert-info">
                     <i class="fa fa-info-circle"></i> Belum ada data target PKM untuk tahun {{ $tahun }}.
                  </div>
               @endif
            @else
               <!-- View for specific faculty -->
               @if(isset($target_pkm) && $target_pkm)
                  @php
                     $capaian = $usulan_pkm_total;
                     $target_value = $target_pkm->target_usulan_pkm;
                     $persentase = $target_value > 0 ? round(($capaian / $target_value) * 100, 2) : 0;
                     $status_class = $persentase >= 100 ? 'success' : ($persentase >= 75 ? 'warning' : 'danger');
                     $status_text = $persentase >= 100 ? 'Tercapai' : ($persentase >= 75 ? 'Mendekati' : 'Belum Tercapai');
                  @endphp
                  <div class="row">
                     <div class="col-md-6">
                        <div class="card border-left-primary shadow h-100 py-2">
                           <div class="card-body">
                              <div class="row no-gutters align-items-center">
                                 <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Target Usulan PKM</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($target_value, 0, ',', '.') }}</div>
                                 </div>
                                 <div class="col-auto">
                                    <i class="fa fa-bullseye fa-2x text-gray-300"></i>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="card border-left-info shadow h-100 py-2">
                           <div class="card-body">
                              <div class="row no-gutters align-items-center">
                                 <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Capaian Usulan PKM</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($capaian, 0, ',', '.') }}</div>
                                 </div>
                                 <div class="col-auto">
                                    <i class="fa fa-check-circle fa-2x text-gray-300"></i>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row mt-3">
                     <div class="col-md-12">
                        <div class="card">
                           <div class="card-body">
                              <h6 class="card-title">Persentase Pencapaian</h6>
                              <div class="progress progress-lg mt-2">
                                 <div class="progress-bar bg-{{ $status_class }}" role="progressbar" 
                                      style="width: {{ min($persentase, 100) }}%" 
                                      aria-valuenow="{{ $persentase }}" 
                                      aria-valuemin="0" 
                                      aria-valuemax="100">
                                    <strong>{{ $persentase }}%</strong>
                                 </div>
                              </div>
                              <div class="mt-2">
                                 <span class="badge badge-{{ $status_class }} badge-lg">{{ $status_text }}</span>
                                 <span class="text-muted ml-2">
                                    ({{ number_format($capaian, 0, ',', '.') }} dari {{ number_format($target_value, 0, ',', '.') }} target)
                                 </span>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               @else
                  <div class="alert alert-warning">
                     <i class="fa fa-exclamation-triangle"></i> Belum ada data target PKM untuk fakultas ini pada tahun {{ $tahun }}.
                  </div>
               @endif
            @endif
         </div>
         <!-- End Monitoring Target dan Capaian -->
         <div class="card-body">
            @foreach($kategori_kegiatan_list as $kategori_kegiatan)
            <div class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-1">
               <span>Rasio Usulan {{ $kategori_kegiatan->nama_kategori_kegiatan }}</span>
            </div>
            <div class="row py-2">
               @foreach($kategori_kegiatan->jenis_pkm as $jenis_pkm_row)
               <div class="col-lg-2 col-md-6 mb-1">
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
