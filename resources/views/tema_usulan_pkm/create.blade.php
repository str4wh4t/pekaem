@extends('template.main')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Tambah Tema Usulan PKM</h3>
    </div>
</div>

<div class="content-body">
    <section id="basic-form-layouts">
        <div class="row match-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="basic-layout-form">Form Tema</h4>
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

                            <form class="form" action="{{ route('tema-usulan-pkm.store') }}" method="POST">
                                @csrf
                                <div class="form-body">
                                    <div class="form-group">
                                        <label for="nama_tema">Nama Tema</label>
                                        <input type="text" id="nama_tema" class="form-control" name="nama_tema" value="{{ old('nama_tema') }}" placeholder="Nama Tema">
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-check-square-o"></i> Save
                                    </button>
                                    <a href="{{ route('tema-usulan-pkm.index') }}" class="btn btn-warning">
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

