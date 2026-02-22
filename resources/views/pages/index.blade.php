@extends('template.main')

@push('page_level_css')
<!-- BEGIN PAGE LEVEL JS-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/template/robust/app-assets/css/pages/project.css') }}">
<style type="text/css">
   .project-info-sub-icon:hover {
       background-color: #007bff !important;
       color: white;
       cursor: pointer;
       transform: scale(1.1);
       transition: all 0.3s ease;
   }
   
   .project-info-count {
       margin-bottom: 20px;
   }
   
   .project-info-icon {
       transition: all 0.3s ease;
       box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
   }
   
   .project-info-icon:hover {
       transform: translateY(-5px);
       box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
   }
   
   .stat-card {
       border-radius: 10px;
       transition: all 0.3s ease;
       border: none;
       box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
   }
   
   .stat-card:hover {
       transform: translateY(-3px);
       box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
   }
   
   .stat-card-primary {
       background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
       color: white;
   }
   
   .stat-card-warning {
       background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
       color: white;
   }
   
   .stat-card-danger {
       background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
       color: white;
   }
   
   .stat-card-success {
       background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
       color: white;
   }
   
   .insights {
       background: #f8f9fa;
       border-radius: 8px;
       padding: 15px;
       margin-bottom: 15px;
       border-left: 4px solid #17a2b8;
       transition: all 0.3s ease;
       box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
   }
   
   .insights:hover {
       transform: translateX(5px);
       box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
       background: #fff;
   }
   
   .monitoring-card {
       border-radius: 10px;
       box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
       margin-bottom: 20px;
   }
   
   .table th {
       background-color: #f8f9fa;
       font-weight: 600;
       text-transform: uppercase;
       font-size: 0.85rem;
       letter-spacing: 0.5px;
   }
   
   .progress {
       height: 25px;
       border-radius: 10px;
       overflow: hidden;
   }
   
   .progress-bar {
       font-weight: 600;
       display: flex;
       align-items: center;
       justify-content: center;
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
         <div class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-3">
            <span><b><i class="fa fa-bar-chart"></i> Rekap Usulan Proposal</b></span>
         </div>
         <div id="project-info" class="card-body row p-3">
            <div class="project-info-count col-lg-3 col-md-6 col-sm-12 mb-3">
               <div class="stat-card stat-card-primary text-center p-4" onclick="return openlink('{{ route('dashboard') }}')" style="cursor: pointer;">
                  <div class="d-flex justify-content-between align-items-center">
                     <div>
                        <h6 class="text-white-50 mb-2" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">Proposal Masuk</h6>
                        <h2 class="mb-0 text-white" style="font-weight: 700; font-size: 2.5rem;">{{ number_format($usulan_pkm_total, 0, ',', '.') }}</h2>
                     </div>
                     <div>
                        <i class="fa fa-file-text-o fa-3x text-white-50"></i>
                     </div>
                  </div>
               </div>
            </div>
            <div class="project-info-count col-lg-3 col-md-6 col-sm-12 mb-3">
               <div class="stat-card stat-card-warning text-center p-4">
                  <div class="d-flex justify-content-between align-items-center">
                     <div>
                        <h6 class="text-white-50 mb-2" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">Proposal Proses</h6>
                        <h2 class="mb-0 text-white" style="font-weight: 700; font-size: 2.5rem;">{{ number_format($usulan_pkm_proses, 0, ',', '.') }}</h2>
                     </div>
                     <div>
                        <i class="fa fa-clock-o fa-3x text-white-50"></i>
                     </div>
                  </div>
               </div>
            </div>
            <div class="project-info-count col-lg-3 col-md-6 col-sm-12 mb-3">
               <div class="stat-card stat-card-danger text-center p-4">
                  <div class="d-flex justify-content-between align-items-center">
                     <div>
                        <h6 class="text-white-50 mb-2" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">Belum Dinilai</h6>
                        <h2 class="mb-0 text-white" style="font-weight: 700; font-size: 2.5rem;">{{ number_format($usulan_pkm_belum_dinilai, 0, ',', '.') }}</h2>
                     </div>
                     <div>
                        <i class="fa fa-exclamation-triangle fa-3x text-white-50"></i>
                     </div>
                  </div>
               </div>
            </div>
            <div class="project-info-count col-lg-3 col-md-6 col-sm-12 mb-3">
               <div class="stat-card stat-card-success text-center p-4">
                  <div class="d-flex justify-content-between align-items-center">
                     <div>
                        <h6 class="text-white-50 mb-2" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">Sudah Dinilai</h6>
                        <h2 class="mb-0 text-white" style="font-weight: 700; font-size: 2.5rem;">{{ number_format($usulan_pkm_sudah_dinilai, 0, ',', '.') }}</h2>
                     </div>
                     <div>
                        <i class="fa fa-check-circle fa-3x text-white-50"></i>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- project-info -->
         <!-- Monitoring Target dan Capaian -->
         <div class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-3">
            <span><b><i class="fa fa-line-chart"></i> Monitoring Target dan Capaian Usulan PKM</b></span>
         </div>
         <div class="card-body">
            @if(empty($kode_fakultas))
               <!-- View for all faculties (super admin) -->
               @if(isset($target_pkm_list) && count($target_pkm_list) > 0)
                  <div class="table-responsive monitoring-card">
                     <table class="table table-striped table-bordered table-hover">
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
                                 $capaian = isset($target->capaian) ? $target->capaian : 0;
                                 $target_value = $target->target_usulan_pkm;
                                 $persentase = $target_value > 0 ? round(($capaian / $target_value) * 100, 2) : 0;
                                 $status_class = $persentase >= 100 ? 'success' : ($persentase >= 75 ? 'warning' : 'danger');
                                 $status_text = $persentase >= 100 ? 'Tercapai' : ($persentase >= 75 ? 'Mendekati' : 'Belum Tercapai');
                              @endphp
                              <tr>
                                 <td><strong>{{ $target->fakultas ? $target->fakultas->nama_fak_ijazah : $target->kode_fakultas }}</strong></td>
                                 <td class="text-right"><strong>{{ number_format($target_value, 0, ',', '.') }}</strong></td>
                                 <td class="text-right"><strong>{{ number_format($capaian, 0, ',', '.') }}</strong></td>
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
            <div class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-3">
               <span><b><i class="fa fa-pie-chart"></i> Rasio Usulan {{ $kategori_kegiatan->nama_kategori_kegiatan }}</b></span>
            </div>
            <div class="row py-2">
               @foreach($kategori_kegiatan->jenis_pkm as $jenis_pkm_row)
               @php
                  $jumlah_jenis = count($usulan_pkm->where('jenis_pkm_id',$jenis_pkm_row->id));
                  $persentase_jenis = !empty($usulan_pkm_total) ? round(($jumlah_jenis/$usulan_pkm_total)*100,2) : 0;
               @endphp
               <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                  <div class="insights">
                     <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-info h4 mb-0" style="font-weight: 700;">{{ number_format($jumlah_jenis, 0, ',', '.') }}</span>
                        <span class="badge badge-info">{{ $persentase_jenis }}%</span>
                     </div>
                     <div class="mb-2">
                        <small class="text-muted font-weight-bold">{{ $jenis_pkm_row->nama_pkm }}</small>
                     </div>
                     <div class="progress progress-md mt-2 mb-0">
                         @if(!empty($usulan_pkm_total))
                         <div class="progress-bar bg-info" role="progressbar" style="width: {{ $persentase_jenis }}%" aria-valuenow="{{ $persentase_jenis }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                         @else
                         <div class="progress-bar bg-info" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
