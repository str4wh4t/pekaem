@extends('template.main')

@push('page_level_js')
<!-- BEGIN PAGE LEVEL JS-->
<script type="text/javascript">

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
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Target PKM Tahunan</h3>
    </div>
</div>

<div class="content-body">
    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Daftar Target PKM Tahunan</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <div class="form-group mb-0 mr-2" style="display: inline-block;">
                                <label for="tahun_filter" class="mr-2" style="margin-bottom: 0;">Tahun:</label>
                                <select id="tahun_filter" name="tahun" class="form-control form-control-sm" style="display: inline-block; width: auto; min-width: 100px;">
                                    @foreach($tahun_list as $tahun_option)
                                    <option value="{{ $tahun_option }}" {{ $tahun == $tahun_option ? 'selected' : '' }}>{{ $tahun_option }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <a href="{{ route('target-pkm-tahunan.create', ['tahun' => $tahun]) }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Target</a>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            @if(session()->has('message'))
                                <div class="alert alert-success">
                                    <b>{{ session()->get('message') }}</b>
                                </div>
                            @endif

                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Fakultas</th>
                                        <th>Jumlah Mahasiswa Aktif</th>
                                        <th>Target Usulan PKM</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($target_list as $target)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $target->tahun }}</td>
                                        <td>{{ $target->fakultas ? $target->fakultas->nama_fak_ijazah : $target->kode_fakultas }}</td>
                                        <td>{{ number_format($target->jumlah_mahasiswa_aktif, 0, ',', '.') }}</td>
                                        <td>{{ number_format($target->target_usulan_pkm, 0, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ route('target-pkm-tahunan.edit', $target) }}" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i> Edit</a>
                                            <form action="{{ route('target-pkm-tahunan.destroy', $target) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin akan menghapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6">Belum terdapat data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
