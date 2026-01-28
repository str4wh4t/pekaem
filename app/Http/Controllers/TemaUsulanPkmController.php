<?php

namespace App\Http\Controllers;

use App\TemaUsulanPkm;
use Illuminate\Http\Request;

class TemaUsulanPkmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->_data['tema_list'] = TemaUsulanPkm::orderBy('nama_tema')->get();
        return view('tema_usulan_pkm.index', $this->_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tema_usulan_pkm.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_tema' => 'required|string|max:255',
        ]);

        $tema = new TemaUsulanPkm();
        $tema->nama_tema = $request->nama_tema;
        $tema->save();

        return redirect()->route('tema-usulan-pkm.index')->with('message', 'Data berhasil di-simpan.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TemaUsulanPkm  $temaUsulanPkm
     * @return \Illuminate\Http\Response
     */
    public function edit(TemaUsulanPkm $temaUsulanPkm)
    {
        $tema = TemaUsulanPkm::findOrFail($temaUsulanPkm->id);
        return view('tema_usulan_pkm.edit', compact('tema'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TemaUsulanPkm  $temaUsulanPkm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TemaUsulanPkm $temaUsulanPkm)
    {
        $request->validate([
            'nama_tema' => 'required|string|max:255',
        ]);

        $tema = TemaUsulanPkm::findOrFail($temaUsulanPkm->id);
        $tema->nama_tema = $request->nama_tema;
        $tema->save();

        return redirect()->route('tema-usulan-pkm.index')->with('message', 'Data berhasil di-update.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TemaUsulanPkm  $temaUsulanPkm
     * @return \Illuminate\Http\Response
     */
    public function destroy(TemaUsulanPkm $temaUsulanPkm)
    {
        try {
            $temaUsulanPkm->delete();
            return redirect()->route('tema-usulan-pkm.index')->with('message', 'Data berhasil di-hapus.');
        } catch (\Exception $e) {
            return redirect()->route('tema-usulan-pkm.index')->with('message', 'Data tidak bisa dihapus karena sudah digunakan.');
        }
    }
}

