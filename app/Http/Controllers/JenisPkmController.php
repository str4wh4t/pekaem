<?php

namespace App\Http\Controllers;

use App\Http\Requests\JenisPkmRequest;
use App\JenisPkm;
use App\KategoriKegiatan;
use App\KategoriKriteria;
use Illuminate\Http\Request;

class JenisPkmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(KategoriKegiatan $kategoriKegiatan)
    {
        $this->_data['jenis_pkm_list'] = JenisPkm::where('kategori_kegiatan_id', $kategoriKegiatan->id)->with('kategori_kriteria')->get();
        $this->_data['kategori_kegiatan'] = $kategoriKegiatan;
        return view('jenis_pkm.index', $this->_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(KategoriKegiatan $kategoriKegiatan)
    {
        $this->_data['kategori_kegiatan'] = $kategoriKegiatan;
        $this->_data['kategori_kriteria_list'] = KategoriKriteria::all();
        return view('jenis_pkm.create', $this->_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JenisPkmRequest $request, KategoriKegiatan $kategoriKegiatan)
    {
        //
        $jenis_pkm = new JenisPkm();
        $jenis_pkm->kategori_kegiatan_id = $kategoriKegiatan->id;
        $jenis_pkm->kategori_kriteria_id = $request->kategori_kriteria_id;
        $jenis_pkm->nama_pkm = $request->nama_pkm;
        $jenis_pkm->keterangan = $request->keterangan;
        $jenis_pkm->score_min = $request->score_min;
        $jenis_pkm->save();
        return redirect()->route('jenis-pkm.index', ['kategori_kegiatan' => $kategoriKegiatan])->with('message', 'Data berhasil di-simpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\JenisPkm  $jenisPkm
     * @return \Illuminate\Http\Response
     */
    public function show(JenisPkm $jenisPkm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JenisPkm  $jenisPkm
     * @return \Illuminate\Http\Response
     */
    public function edit(KategoriKegiatan $kategoriKegiatan, JenisPkm $jenisPkm)
    {
        $jenis_pkm = JenisPkm::findOrFail($jenisPkm->id);
        $kategori_kegiatan = KategoriKegiatan::findOrFail($kategoriKegiatan->id);
        $kategori_kriteria_list = KategoriKriteria::all();
        // Return view form edit dengan data jenis_pkm
        return view('jenis_pkm.edit', compact('jenis_pkm', 'kategori_kegiatan', 'kategori_kriteria_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JenisPkm  $jenisPkm
     * @return \Illuminate\Http\Response
     */
    public function update(JenisPkmRequest $request, KategoriKegiatan $kategoriKegiatan, JenisPkm $jenisPkm)
    {
        $jenis_pkm = JenisPkm::findOrFail($jenisPkm->id);
        $jenis_pkm->kategori_kriteria_id = $request->kategori_kriteria_id;
        $jenis_pkm->nama_pkm = $request->nama_pkm;
        $jenis_pkm->keterangan = $request->keterangan;
        $jenis_pkm->score_min = $request->score_min;
        $jenis_pkm->save();
        return redirect()->route('jenis-pkm.index', ['kategori_kegiatan' => $kategoriKegiatan])->with('message', 'Data berhasil di-update.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JenisPkm  $jenisPkm
     * @return \Illuminate\Http\Response
     */
    public function destroy(KategoriKegiatan $kategoriKegiatan, JenisPkm $jenisPkm)
    {
        try {
            $jenisPkm->delete();
            return redirect()->route('jenis-pkm.index', ['kategori_kegiatan' => $kategoriKegiatan])->with('message', 'Data berhasil di-hapus.');
        } catch (\Exception $e) {
            return redirect()->route('jenis-pkm.index', ['kategori_kegiatan' => $kategoriKegiatan])->with('message', 'Data tidak bisa dihapus karena sudah digunakan.');
        }
    }
}