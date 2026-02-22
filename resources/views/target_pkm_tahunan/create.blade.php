@extends('template.main')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Tambah Target PKM Tahunan</h3>
    </div>
</div>

<div class="content-body">
    <section id="basic-form-layouts">
        <div class="row match-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="basic-layout-form">Form Target PKM Tahunan</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <div>&#42; {{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif

                            <form class="form" action="{{ route('target-pkm-tahunan.store') }}" method="POST">
                                @csrf
                                <div class="form-body">
                                    <div class="form-group">
                                        <label>Tahun</label>
                                        <div class="form-control" style="background-color: #f5f5f5; cursor: not-allowed;">
                                            <strong>{{ isset($tahun) ? $tahun : date('Y') }}</strong>
                                        </div>
                                        <input type="hidden" name="tahun" value="{{ old('tahun', isset($tahun) ? $tahun : date('Y')) }}">
                                        <small class="form-text text-muted">Tahun yang dipilih dari filter</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="kode_fakultas">Fakultas <span class="text-danger">*</span></label>
                                        <select id="kode_fakultas" class="form-control" name="kode_fakultas" required>
                                            <option value="">-- Pilih Fakultas --</option>
                                            @foreach($fakultas_list as $fakultas)
                                                <option value="{{ $fakultas->kodeF }}" {{ old('kode_fakultas') == $fakultas->kodeF ? 'selected' : '' }}>
                                                    {{ $fakultas->nama_fak_ijazah }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Pilih fakultas untuk target PKM tahunan</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="jumlah_mahasiswa_aktif">Jumlah Mahasiswa Aktif <span class="text-danger">*</span></label>
                                        <input type="number" id="jumlah_mahasiswa_aktif" class="form-control" name="jumlah_mahasiswa_aktif" value="{{ old('jumlah_mahasiswa_aktif') }}" placeholder="Jumlah Mahasiswa Aktif" min="0" required>
                                        <small class="form-text text-muted">Masukkan jumlah mahasiswa aktif pada tahun tersebut</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="target_usulan_pkm">Target Usulan PKM <span class="text-danger">*</span></label>
                                        <input type="number" id="target_usulan_pkm" class="form-control" name="target_usulan_pkm" value="{{ old('target_usulan_pkm') }}" placeholder="Target Usulan PKM" min="0" required>
                                        <small class="form-text text-muted">Masukkan target jumlah usulan PKM yang harus diajukan pada tahun tersebut</small>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-check-square-o"></i> Save
                                    </button>
                                    <a href="{{ route('target-pkm-tahunan.index', ['tahun' => isset($tahun) ? $tahun : date('Y')]) }}" class="btn btn-warning">
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
</div>
@endsection
