<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenilaianReviewerRequest;
use App\PenilaianReviewer;
use App\UsulanPkm;
use Illuminate\Http\Request;

class PenilaianReviewerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(UsulanPkm $usulan_pkm)
    {
        //
        $this->_data['usulan_pkm'] = $usulan_pkm;
        $this->_data['kategori_kriteria'] = $usulan_pkm->jenis_pkm->kategori_kriteria;
        $this->_data['kriteria_penilaian_list'] = $usulan_pkm->jenis_pkm->kategori_kriteria->kriteria_penilaian->sortBy('urutan');
        return view('penilaian_reviewer.create', $this->_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsulanPkm $usulan_pkm, PenilaianReviewerRequest $request)
    {
        //

        $scores = [];
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'score_') === 0) { // Key dimulai dengan "score_"
                $id = explode('_', $key)[1]; // Ambil angka setelah underscore
                $scores[] = [
                    'id' => (int) $id, // Konversi ke integer
                    'value' => (int) $value, // Konversi ke integer
                ];
            }
        }

        // foreach ($scores as $score) {
        //     $penilaian_reviewer = new PenilaianReviewer();
        //     $penilaian_reviewer->usulan_pkm_id = $usulan_pkm->id;
        //     $penilaian_reviewer->kriteria_penilaian_id = $score['id'];
        //     $penilaian_reviewer->score = $score['value'];
        //     $penilaian_reviewer->save();
        // }

        return redirect()->route('penilaian-reviewer.create', ['usulan_pkm' => $usulan_pkm])->with('message', 'Data berhasil di-simpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PenilaianReviewer  $penilaianReviewer
     * @return \Illuminate\Http\Response
     */
    public function show(PenilaianReviewer $penilaianReviewer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PenilaianReviewer  $penilaianReviewer
     * @return \Illuminate\Http\Response
     */
    public function edit(PenilaianReviewer $penilaianReviewer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PenilaianReviewer  $penilaianReviewer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PenilaianReviewer $penilaianReviewer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PenilaianReviewer  $penilaianReviewer
     * @return \Illuminate\Http\Response
     */
    public function destroy(PenilaianReviewer $penilaianReviewer)
    {
        //
    }
}
