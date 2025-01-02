<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenilaianReviewerRequest;
use App\PenilaianReviewer;
use App\KriteriaPenilaian;
use App\UsulanPkm;
use App\Review;
use App\Reviewer;
use Illuminate\Http\Request;
use \App\Helpers\User as UserHelp;
use Illuminate\Support\Facades\DB;

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
        $reviewer = UserHelp::admin_get_record_by_nip(UserHelp::admin_get_logged_nip());
        $penilaian_reviewer = $usulan_pkm->penilaian_reviewer()->where('reviewer_id', $reviewer->id)->first();

        if ($usulan_pkm->status_usulan->keterangan == 'SUDAH_DINILAI') {
            return route('penilaian-reviewer.lihat', ['usulan_pkm' => $usulan_pkm, 'reviewer' => $reviewer]);
        }

        if (!empty($penilaian_reviewer)) {
            return redirect()->route('penilaian-reviewer.edit', ['usulan_pkm' => $usulan_pkm, 'penilaian_reviewer' => $penilaian_reviewer]);
        }
        if (Userhelp::get_selected_role() == 'ADMIN') {
            return redirect()->route('share.pendaftaran.read', ['uuid' => $usulan_pkm->uuid]);
        }
        $this->_data['usulan_pkm'] = $usulan_pkm;
        $this->_data['kategori_kriteria'] = $usulan_pkm->jenis_pkm->kategori_kriteria;
        $this->_data['kriteria_penilaian_list'] = $usulan_pkm->jenis_pkm->kategori_kriteria->kriteria_penilaian->sortBy('urutan');
        $this->_data['reviewer'] = $reviewer;
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

        // Ambil semua data input
        $data = $request->input('data', []);
        $totalNilai = $request->input('total_nilai', 0);

        // Validasi data
        $request->validate([
            'data.*.score' => 'required|numeric|min:0',
            'data.*.komentar' => 'nullable|string|max:255',
            'catatan_reviewer' => 'nullable|string|max:255',
        ]);

        // buat dibawah dalam sebuah try catch dan rollback jika terjadi error

        // Proses data
        $reviewer = UserHelp::admin_get_record_by_nip(UserHelp::admin_get_logged_nip());

        // Gunakan DB::transaction untuk rollback jika terjadi error
        DB::beginTransaction();

        try {
            foreach ($data as $id => $item) {
                // Simpan data ke database atau lakukan perhitungan
                // Misalnya, simpan ke database:
                $kriteria_penilaian = KriteriaPenilaian::findOrFail($id);
                $penilaian_reviewer = new PenilaianReviewer();
                $penilaian_reviewer->usulan_pkm_id = $usulan_pkm->id;
                $penilaian_reviewer->reviewer_id = $reviewer->id;
                $penilaian_reviewer->kriteria_penilaian_id = $id;
                $penilaian_reviewer->score = $item['score'];
                $penilaian_reviewer->bobot = $kriteria_penilaian->bobot;
                $penilaian_reviewer->nilai = $item['score'] * $kriteria_penilaian->bobot;
                $penilaian_reviewer->komentar = $item['komentar'];
                $penilaian_reviewer->save();
            }

            // Simpan catatan reviewer
            $catatan_reviewer = $request->input('catatan_reviewer');
            if (trim($catatan_reviewer) != '') {
                $review = new Review();
                $review->usulan_pkm_id = $usulan_pkm->id;
                $review->pegawai_id = $reviewer->id;
                $review->catatan_reviewer = $catatan_reviewer;
                $review->status_usulan_id = $usulan_pkm->status_usulan_id;
                $review->save();
            }


            // Commit transaksi jika semua berhasil
            DB::commit();

            // Redirect dengan pesan sukses
            return redirect()->route('penilaian-reviewer.create', ['usulan_pkm' => $usulan_pkm])->with('message', 'Data berhasil di-simpan.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollback();

            // Log error untuk debugging
            // \Log::error('Error menyimpan penilaian reviewer: ' . $e->getMessage());

            // Redirect dengan pesan error
            return redirect()->back()->with('message', 'Terjadi kesalahan saat menyimpan data penilaian: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PenilaianReviewer  $penilaianReviewer
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, UsulanPkm $usulan_pkm, PenilaianReviewer $penilaianReviewer)
    {
        //
    }

    public function lihat(UsulanPkm $usulan_pkm, Reviewer $reviewer)
    {
        $reviewer = UserHelp::admin_get_record_by_nip($reviewer->nip);
        $this->_data['usulan_pkm'] = $usulan_pkm;
        $this->_data['kategori_kriteria'] = $usulan_pkm->jenis_pkm->kategori_kriteria;
        $this->_data['kriteria_penilaian_list'] = $usulan_pkm->jenis_pkm->kategori_kriteria->kriteria_penilaian->sortBy('urutan');
        $penilaian_reviewer_list = $usulan_pkm->penilaian_reviewer()->where('reviewer_id', $reviewer->id)->get();
        $penilaian_reviewer = [];
        foreach ($penilaian_reviewer_list as $item) {
            $penilaian_reviewer[$item->kriteria_penilaian_id] = [
                'score' => $item->score,
                'komentar' => $item->komentar,
            ];
        }
        $this->_data['penilaian_reviewer'] = $penilaian_reviewer;
        $this->_data['catatan_reviewer'] = "";
        $review = $usulan_pkm->review()->where('pegawai_id', $reviewer->id)->first();
        if (!empty($review)) {
            $this->_data['catatan_reviewer'] = $review->catatan_reviewer;
        }
        $this->_data['reviewer'] = $reviewer;
        return view('penilaian_reviewer.lihat', $this->_data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PenilaianReviewer  $penilaianReviewer
     * @return \Illuminate\Http\Response
     */
    public function edit(UsulanPkm $usulan_pkm, PenilaianReviewer $penilaianReviewer)
    {
        $reviewer = UserHelp::admin_get_record_by_nip(UserHelp::admin_get_logged_nip());
        $this->_data['usulan_pkm'] = $usulan_pkm;
        $this->_data['kategori_kriteria'] = $usulan_pkm->jenis_pkm->kategori_kriteria;
        $this->_data['kriteria_penilaian_list'] = $usulan_pkm->jenis_pkm->kategori_kriteria->kriteria_penilaian->sortBy('urutan');
        $penilaian_reviewer_list = $usulan_pkm->penilaian_reviewer()->where('reviewer_id', $penilaianReviewer->reviewer_id)->get();
        $penilaian_reviewer = [];
        foreach ($penilaian_reviewer_list as $item) {
            $penilaian_reviewer[$item->kriteria_penilaian_id] = [
                'score' => $item->score,
                'komentar' => $item->komentar,
            ];
        }
        $this->_data['penilaian_reviewer'] = $penilaian_reviewer;
        $this->_data['catatan_reviewer'] = "";
        $review = $usulan_pkm->review()->where('pegawai_id', $penilaianReviewer->reviewer_id)->first();
        if (!empty($review)) {
            $this->_data['catatan_reviewer'] = $review->catatan_reviewer;
        }
        $this->_data['penilaian_reviewer_edit'] = $penilaianReviewer;
        $this->_data['reviewer'] = $reviewer;
        return view('penilaian_reviewer.edit', $this->_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PenilaianReviewer  $penilaianReviewer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UsulanPkm $usulan_pkm, PenilaianReviewer $penilaianReviewer)
    {

        if ($usulan_pkm->status_usulan->keterangan != 'LANJUT') {
            return redirect()->back()->with('message', 'Penilaian tidak bisa diubah karena sudah ditetapkan.');
        }

        // Ambil semua data input
        $data = $request->input('data', []);
        $totalNilai = $request->input('total_nilai', 0);

        // Validasi data
        $request->validate([
            'data.*.score' => 'required|numeric|min:0',
            'data.*.komentar' => 'nullable|string|max:255',
            'catatan_reviewer' => 'nullable|string|max:255',
        ]);

        // buat dibawah dalam sebuah try catch dan rollback jika terjadi error

        // Proses data
        $reviewer = UserHelp::admin_get_record_by_nip(UserHelp::admin_get_logged_nip());

        // Gunakan DB::transaction untuk rollback jika terjadi error
        DB::beginTransaction();

        try {
            foreach ($data as $id => $item) {
                // Simpan data ke database atau lakukan perhitungan
                // Misalnya, simpan ke database:
                $kriteria_penilaian = KriteriaPenilaian::findOrFail($id);
                $penilaian_reviewer = PenilaianReviewer::where('usulan_pkm_id', $usulan_pkm->id)->where('reviewer_id', $reviewer->id)->where('kriteria_penilaian_id', $id)->first();
                $penilaian_reviewer->score = $item['score'];
                $penilaian_reviewer->bobot = $kriteria_penilaian->bobot;
                $penilaian_reviewer->nilai = $item['score'] * $kriteria_penilaian->bobot;
                $penilaian_reviewer->komentar = $item['komentar'];
                $penilaian_reviewer->save();
            }

            // Simpan catatan reviewer
            $catatan_reviewer = $request->input('catatan_reviewer');
            if (trim($catatan_reviewer) != '') {
                $review = Review::where('usulan_pkm_id', $usulan_pkm->id)->where('pegawai_id', $reviewer->id)->first();
                $review->catatan_reviewer = $catatan_reviewer;
                $review->save();
            }


            // Commit transaksi jika semua berhasil
            DB::commit();

            // Redirect dengan pesan sukses
            return redirect()->route('penilaian-reviewer.edit', ['usulan_pkm' => $usulan_pkm, 'penilaian_reviewer' => $penilaianReviewer])->with('message', 'Data berhasil di-simpan.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollback();

            // Log error untuk debugging
            // \Log::error('Error menyimpan penilaian reviewer: ' . $e->getMessage());

            // Redirect dengan pesan error
            return redirect()->back()->with('message', 'Terjadi kesalahan saat menyimpan data penilaian: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PenilaianReviewer  $penilaianReviewer
     * @return \Illuminate\Http\Response
     */
    public function destroy(UsulanPkm $usulan_pkm, PenilaianReviewer $penilaianReviewer)
    {
        //
    }

    public function batal(UsulanPkm $usulan_pkm, PenilaianReviewer $penilaianReviewer)
    {
        //
        if ($usulan_pkm->status_usulan->keterangan != 'LANJUT') {
            return redirect()->back()->with('message', 'Penilaian tidak bisa diubah karena sudah ditetapkan.');
        }

        DB::beginTransaction();

        try {
            $reviewer = UserHelp::admin_get_record_by_nip(UserHelp::admin_get_logged_nip());
            $penilaian_reviewer = $usulan_pkm->penilaian_reviewer()->where('reviewer_id', $reviewer->id)->delete();
            $review = $usulan_pkm->review()->where('pegawai_id', $reviewer->id)->delete();
            DB::commit();
            return redirect()->route('penilaian-reviewer.create', ['usulan_pkm' => $usulan_pkm])->with('message', 'Data berhasil di-batalkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('message', 'Terjadi kesalahan saat memproses: ' . $e->getMessage());
        }
    }
}
