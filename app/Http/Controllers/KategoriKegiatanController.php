<?php

namespace App\Http\Controllers;

use App\Http\Requests\KategoriKegiatanRequest;
use App\KategoriKegiatan;
use App\UsulanPkm;
use Illuminate\Http\Request;

class KategoriKegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategori_kegiatan_list = KategoriKegiatan::all();
        $this->_data['kategori_kegiatan_list'] = $kategori_kegiatan_list;
        
        // Get list of available years from database
        $this->_data['tahun_list'] = UsulanPkm::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();
        
        // If current year is not in the list, add it
        $current_year = date('Y');
        if (!in_array($current_year, $this->_data['tahun_list'])) {
            $this->_data['tahun_list'][] = $current_year;
            rsort($this->_data['tahun_list']);
        }
        
        return view('kategori_kegiatan.index', $this->_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kategori_kegiatan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KategoriKegiatanRequest $request)
    {
        $kategori_kegiatan = new KategoriKegiatan();
        $kategori_kegiatan->nama_kategori_kegiatan = $request->nama_kategori_kegiatan;
        $kategori_kegiatan->deskripsi = $request->deskripsi;
        $kategori_kegiatan->save();
        return redirect()->route('kategori-kegiatan.index')->with('message', 'Data berhasil di-simpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KategoriKegiatan  $kategoriKegiatan
     * @return \Illuminate\Http\Response
     */
    public function show(KategoriKegiatan $kategoriKegiatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KategoriKegiatan  $kategoriKegiatan
     * @return \Illuminate\Http\Response
     */
    public function edit(KategoriKegiatan $kategoriKegiatan)
    {
        $kategori_kegiatan = KategoriKegiatan::findOrFail($kategoriKegiatan->id);

        // Return view form edit dengan data kategori_kegiatan
        return view('kategori_kegiatan.edit', compact('kategori_kegiatan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KategoriKegiatan  $kategoriKegiatan
     * @return \Illuminate\Http\Response
     */
    public function update(KategoriKegiatanRequest $request, KategoriKegiatan $kategoriKegiatan)
    {
        $kategori_kegiatan = KategoriKegiatan::findOrFail($kategoriKegiatan->id);
        $kategori_kegiatan->nama_kategori_kegiatan = $request->nama_kategori_kegiatan;
        $kategori_kegiatan->deskripsi = $request->deskripsi;
        $kategori_kegiatan->save();
        return redirect()->route('kategori-kegiatan.index')->with('message', 'Data berhasil di-update.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KategoriKegiatan  $kategoriKegiatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(KategoriKegiatan $kategoriKegiatan)
    {
        try {
            $kategoriKegiatan->delete();
            return redirect()->route('kategori-kegiatan.index')->with('message', 'Data berhasil di-hapus.');
        } catch (\Exception $e) {
            return redirect()->route('kategori-kegiatan.index')->with('message', 'Data tidak bisa dihapus karena sudah digunakan.');
        }
    }
}
