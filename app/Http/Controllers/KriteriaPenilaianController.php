<?php

namespace App\Http\Controllers;

use App\Http\Requests\KriteriaPenilaianRequest;
use App\KategoriKriteria;
use App\KriteriaPenilaian;
use Illuminate\Http\Request;

class KriteriaPenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(KategoriKriteria $kategoriKriteria)
    {
        $kriteria_penilaian_list = KriteriaPenilaian::where('kategori_kriteria_id', $kategoriKriteria->id)->orderBy('urutan')->get();
        $this->_data['kriteria_penilaian_list'] = $kriteria_penilaian_list;
        $this->_data['kategori_kriteria'] = $kategoriKriteria;
        return view('kriteria_penilaian.index', $this->_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(KategoriKriteria $kategoriKriteria)
    {
        $this->_data['kategori_kriteria'] = $kategoriKriteria;
        return view('kriteria_penilaian.create', $this->_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KriteriaPenilaianRequest $request, KategoriKriteria $kategoriKriteria)
    {
        //
        $kriteria_penilaian = new KriteriaPenilaian();
        $lastUrutan = KriteriaPenilaian::where('kategori_kriteria_id', $kategoriKriteria->id)
            ->max('urutan');

        // Tambahkan nilai urutan dengan 1
        $kriteria_penilaian->urutan = $lastUrutan ? $lastUrutan + 1 : 1;
        $kriteria_penilaian->kategori_kriteria_id = $kategoriKriteria->id;
        $kriteria_penilaian->nama_kriteria = $request->nama_kriteria;
        $kriteria_penilaian->bobot = $request->bobot;
        $kriteria_penilaian->save();
        return redirect()->route('kriteria-penilaian.index', ['kategori_kriteria' => $kategoriKriteria])->with('message', 'Data berhasil di-simpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KriteriaPenilaian  $kriteriaPenilaian
     * @return \Illuminate\Http\Response
     */
    public function show(KriteriaPenilaian $kriteriaPenilaian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KriteriaPenilaian  $kriteriaPenilaian
     * @return \Illuminate\Http\Response
     */
    public function edit(KategoriKriteria $kategoriKriteria, KriteriaPenilaian $kriteriaPenilaian)
    {
        $kriteria_penilaian = KriteriaPenilaian::findOrFail($kriteriaPenilaian->id);
        $kategori_kriteria = KategoriKriteria::findOrFail($kategoriKriteria->id);
        // Return view form edit dengan data kriteria_penilaian
        return view('kriteria_penilaian.edit', compact('kriteria_penilaian', 'kategori_kriteria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KriteriaPenilaian  $kriteriaPenilaian
     * @return \Illuminate\Http\Response
     */
    public function update(KriteriaPenilaianRequest $request, KategoriKriteria $kategoriKriteria, KriteriaPenilaian $kriteriaPenilaian)
    {
        $kriteria_penilaian = KriteriaPenilaian::findOrFail($kriteriaPenilaian->id);
        $kriteria_penilaian->nama_kriteria = $request->nama_kriteria;
        $kriteria_penilaian->urutan = $request->urutan;
        $kriteria_penilaian->bobot = $request->bobot;
        $kriteria_penilaian->save();
        return redirect()->route('kriteria-penilaian.index', ['kategori_kriteria' => $kategoriKriteria])->with('message', 'Data berhasil di-update.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KriteriaPenilaian  $kriteriaPenilaian
     * @return \Illuminate\Http\Response
     */
    public function destroy(KategoriKriteria $kategoriKriteria, KriteriaPenilaian $kriteriaPenilaian)
    {
        try {
            $kriteriaPenilaian->delete();
            return redirect()->route('kriteria-penilaian.index', ['kategori_kriteria' => $kategoriKriteria])->with('message', 'Data berhasil di-hapus.');
        } catch (\Exception $e) {
            return redirect()->route('kriteria-penilaian.index', ['kategori_kriteria' => $kategoriKriteria])->with('message', 'Data tidak bisa dihapus karena sudah digunakan.');
        }
    }
}
