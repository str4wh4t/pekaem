<?php

namespace App\Http\Controllers;

use App\Http\Requests\KategoriKriteriaRequest;
use App\Http\Requests\KriteriaPenilaianRequest;
use App\KategoriKriteria;
use Illuminate\Http\Request;

class KategoriKriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategori_kriteria_list = KategoriKriteria::all();
        $this->_data['kategori_kriteria_list'] = $kategori_kriteria_list;
        return view('kategori_kriteria.index', $this->_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kategori_kriteria.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KategoriKriteriaRequest $request)
    {
        $kategori_kriteria = new KategoriKriteria();
        $kategori_kriteria->nama_kategori_kriteria = $request->nama_kategori_kriteria;
        $kategori_kriteria->deskripsi = $request->deskripsi;
        $kategori_kriteria->save();
        return redirect()->route('kategori-kriteria.index')->with('message', 'Data berhasil di-simpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KategoriKriteria  $kategoriKriteria
     * @return \Illuminate\Http\Response
     */
    public function show(KategoriKriteria $kategoriKriteria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KategoriKriteria  $kategoriKriterium
     * @return \Illuminate\Http\Response
     */
    public function edit(KategoriKriteria $kategoriKriterium)
    {
        $kategori_kriteria = KategoriKriteria::findOrFail($kategoriKriterium->id);

        // Return view form edit dengan data kategori_kriteria
        return view('kategori_kriteria.edit', compact('kategori_kriteria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KategoriKriteria  $kategoriKriteria
     * @return \Illuminate\Http\Response
     */
    public function update(KategoriKriteriaRequest $request, KategoriKriteria $kategoriKriterium)
    {
        $kategori_kriteria = KategoriKriteria::findOrFail($kategoriKriterium->id);
        $kategori_kriteria->nama_kategori_kriteria = $request->nama_kategori_kriteria;
        $kategori_kriteria->deskripsi = $request->deskripsi;
        $kategori_kriteria->save();
        return redirect()->route('kategori-kriteria.index')->with('message', 'Data berhasil di-update.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KategoriKriteria  $kategoriKriteria
     * @return \Illuminate\Http\Response
     */
    public function destroy(KategoriKriteria $kategoriKriterium)
    {
        try {
            $kategoriKriterium->delete();
            return redirect()->route('kategori-kriteria.index')->with('message', 'Data berhasil di-hapus.');
        } catch (\Exception $e) {
            return redirect()->route('kategori-kriteria.index')->with('message', 'Data tidak bisa dihapus karena sudah digunakan.');
        }
    }
}
