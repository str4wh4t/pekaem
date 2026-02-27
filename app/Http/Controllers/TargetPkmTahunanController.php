<?php

namespace App\Http\Controllers;

use App\TargetPkmTahunan;
use App\Fakultas;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TargetPkmTahunanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tahun = Setting::getTahunDipilih();

        $this->_data['target_list'] = TargetPkmTahunan::with('fakultas')
            ->where('tahun', $tahun)
            ->orderBy('kode_fakultas', 'asc')
            ->get();

        $this->_data['tahun'] = $tahun;

        return view('target_pkm_tahunan.index', $this->_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $tahun = Setting::getTahunDipilih();
        $this->_data['fakultas_list'] = Fakultas::orderBy('nama_fak_ijazah')->get();
        $this->_data['tahun'] = $tahun;
        return view('target_pkm_tahunan.create', $this->_data);
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
            'tahun' => 'required|numeric|min:2000|max:2100',
            'kode_fakultas' => 'required|string|max:2|exists:fakultas,kodeF',
            'jumlah_mahasiswa_aktif' => 'required|numeric|min:0',
            'target_usulan_pkm' => 'required|numeric|min:0',
        ], [], [
            'kode_fakultas' => 'fakultas',
        ]);

        // Custom validation: unique tahun + kode_fakultas
        $exists = TargetPkmTahunan::where('tahun', $request->tahun)
            ->where('kode_fakultas', $request->kode_fakultas)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['tahun' => 'Target untuk tahun dan fakultas ini sudah ada.'])
                ->withInput();
        }

        $target = new TargetPkmTahunan();
        $target->tahun = $request->tahun;
        $target->kode_fakultas = $request->kode_fakultas;
        $target->jumlah_mahasiswa_aktif = $request->jumlah_mahasiswa_aktif;
        $target->target_usulan_pkm = $request->target_usulan_pkm;
        $target->save();

        return redirect()->route('target-pkm-tahunan.index', ['tahun' => $request->tahun])->with('message', 'Data berhasil di-simpan.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TargetPkmTahunan  $targetPkmTahunan
     * @return \Illuminate\Http\Response
     */
    public function edit(TargetPkmTahunan $targetPkmTahunan)
    {
        $target = TargetPkmTahunan::findOrFail($targetPkmTahunan->id);
        $this->_data['target'] = $target;
        $this->_data['fakultas_list'] = Fakultas::orderBy('nama_fak_ijazah')->get();
        return view('target_pkm_tahunan.edit', $this->_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TargetPkmTahunan  $targetPkmTahunan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TargetPkmTahunan $targetPkmTahunan)
    {
        $request->validate([
            'tahun' => 'required|numeric|min:2000|max:2100',
            'kode_fakultas' => 'required|string|max:2|exists:fakultas,kodeF',
            'jumlah_mahasiswa_aktif' => 'required|numeric|min:0',
            'target_usulan_pkm' => 'required|numeric|min:0',
        ], [], [
            'kode_fakultas' => 'fakultas',
        ]);

        // Custom validation: unique tahun + kode_fakultas (exclude current record)
        $exists = TargetPkmTahunan::where('tahun', $request->tahun)
            ->where('kode_fakultas', $request->kode_fakultas)
            ->where('id', '!=', $targetPkmTahunan->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['tahun' => 'Target untuk tahun dan fakultas ini sudah ada.'])
                ->withInput();
        }

        $target = TargetPkmTahunan::findOrFail($targetPkmTahunan->id);
        $target->tahun = $request->tahun;
        $target->kode_fakultas = $request->kode_fakultas;
        $target->jumlah_mahasiswa_aktif = $request->jumlah_mahasiswa_aktif;
        $target->target_usulan_pkm = $request->target_usulan_pkm;
        $target->save();

        return redirect()->route('target-pkm-tahunan.index', ['tahun' => $request->tahun])->with('message', 'Data berhasil di-update.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TargetPkmTahunan  $targetPkmTahunan
     * @return \Illuminate\Http\Response
     */
    public function destroy(TargetPkmTahunan $targetPkmTahunan)
    {
        $tahun = $targetPkmTahunan->tahun;
        try {
            $targetPkmTahunan->delete();
            return redirect()->route('target-pkm-tahunan.index', ['tahun' => $tahun])->with('message', 'Data berhasil di-hapus.');
        } catch (\Exception $e) {
            return redirect()->route('target-pkm-tahunan.index', ['tahun' => $tahun])->with('message', 'Data tidak bisa dihapus karena sudah digunakan.');
        }
    }
}
