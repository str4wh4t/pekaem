@extends('template.main')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Tema Usulan PKM</h3>
    </div>
</div>

<div class="content-body">
    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Daftar Tema</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <a href="{{ route('tema-usulan-pkm.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Tema</a>
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
                                        <th>Nama Tema</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tema_list as $i => $tema)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tema->nama_tema }}</td>
                                        <td>
                                            <a href="{{ route('tema-usulan-pkm.edit', $tema) }}" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i> Edit</a>
                                            <form action="{{ route('tema-usulan-pkm.destroy', $tema) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin akan menghapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3">Belum terdapat data</td>
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

