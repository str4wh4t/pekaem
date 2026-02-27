<?php

namespace App\Http\Controllers;

use App\Http\Requests\JenisPkmRequest;
use App\JenisPkm;
use App\KategoriKegiatan;
use App\KategoriKriteria;
use App\UsulanPkm;
use App\Setting;
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
        $jenis_pkm->kamar = $request->kamar;
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
        $jenis_pkm->kamar = $request->kamar;
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

    public function daftar_penilaian(KategoriKegiatan $kategoriKegiatan, JenisPkm $jenisPkm, Request $request)
    {
        $tahun = $request->input('tahun', Setting::getTahunDipilih());
        $usulan_pkm_list = UsulanPkm::where('jenis_pkm_id', $jenisPkm->id)
            ->where('tahun', $tahun)
            ->with([
                'anggota_pkm.mhs',
                'usulan_pkm_dokumen',
                'pegawai:id,nama,glr_dpn,glr_blkg,nuptk',
                'tema_usulan_pkm:id,nama_tema',
                'reviewer_usulan_pkm.reviewer:id,nama,glr_dpn,glr_blkg',
                'penilaian_reviewer:id,usulan_pkm_id,reviewer_id,nilai'
            ])
            ->orderBy('nilai_total', 'desc')
            ->get();

        // Pre-calculate data to avoid N+1 queries
        $this->_preCalculateUsulanPkmData($usulan_pkm_list);

        $this->_data['usulan_pkm_list'] = $usulan_pkm_list;
        $this->_data['kategori_kegiatan'] = $kategoriKegiatan;
        $this->_data['jenis_pkm'] = $jenisPkm;
        $this->_data['tahun'] = $tahun;
        return view('jenis_pkm.daftar_penilaian', $this->_data);
    }

    public function daftar_penilaian_excel(KategoriKegiatan $kategoriKegiatan, JenisPkm $jenisPkm, Request $request)
    {
        $tahun = $request->input('tahun', Setting::getTahunDipilih());
        $usulan_pkm_list = UsulanPkm::where('jenis_pkm_id', $jenisPkm->id)
            ->where('tahun', $tahun)
            ->with([
                'anggota_pkm.mhs',
                'usulan_pkm_dokumen',
                'pegawai:id,nama,glr_dpn,glr_blkg,nuptk',
                'tema_usulan_pkm:id,nama_tema',
                'reviewer_usulan_pkm.reviewer:id,nama,glr_dpn,glr_blkg',
                'penilaian_reviewer:id,usulan_pkm_id,reviewer_id,nilai'
            ])
            ->orderBy('nilai_total', 'desc')
            ->get();

        // Pre-calculate data to avoid N+1 queries
        $this->_preCalculateUsulanPkmData($usulan_pkm_list);

        $this->_data['usulan_pkm_list'] = $usulan_pkm_list;
        $this->_data['kategori_kegiatan'] = $kategoriKegiatan;
        $this->_data['jenis_pkm'] = $jenisPkm;
        $this->_data['tahun'] = $tahun;
        return view('jenis_pkm.daftar_penilaian_excel', $this->_data);
    }

    public function daftar_penilaian_kategori_kegiatan_excel(KategoriKegiatan $kategoriKegiatan, Request $request)
    {
        $tahun = $request->input('tahun', Setting::getTahunDipilih());
        $jenisPkmIds = JenisPkm::where('kategori_kegiatan_id', $kategoriKegiatan->id)->pluck('id');

        if ($jenisPkmIds->isEmpty()) {
            $jenisPkmIds = [0];
        }

        $usulan_pkm_list = UsulanPkm::whereIn('jenis_pkm_id', $jenisPkmIds)
            ->where('tahun', $tahun)
            ->with([
                'anggota_pkm.mhs',
                'usulan_pkm_dokumen',
                'jenis_pkm:id,nama_pkm',
                'pegawai:id,nama,glr_dpn,glr_blkg,nuptk',
                'tema_usulan_pkm:id,nama_tema',
                'reviewer_usulan_pkm.reviewer:id,nama,glr_dpn,glr_blkg',
                'penilaian_reviewer:id,usulan_pkm_id,reviewer_id,nilai'
            ])
            ->orderBy('nilai_total', 'desc')
            ->get();

        // Pre-calculate data to avoid N+1 queries
        $this->_preCalculateUsulanPkmData($usulan_pkm_list);

        $this->_data['usulan_pkm_list'] = $usulan_pkm_list;
        $this->_data['kategori_kegiatan'] = $kategoriKegiatan;
        $this->_data['tahun'] = $tahun;
        return view('jenis_pkm.daftar_penilaian_kategori_kegiatan_excel', $this->_data);
    }

    /**
     * Helper method to pre-calculate usulan PKM data to avoid N+1 queries
     */
    private function _preCalculateUsulanPkmData($usulan_pkm_list)
    {
        foreach ($usulan_pkm_list as $usulan_pkm) {
            // Pre-calculate anggota count
            $usulan_pkm->anggota_count = $usulan_pkm->anggota_pkm->count();

            // Pre-get ketua (anggota dengan sebagai = 0)
            $usulan_pkm->ketua = $usulan_pkm->anggota_pkm->where('sebagai', 0)->first();

            // Pre-get anggota (anggota dengan sebagai = 1)
            $usulan_pkm->anggota_list = $usulan_pkm->anggota_pkm->where('sebagai', 1);

            // Pre-get reviewers
            $usulan_pkm->reviewer1 = $usulan_pkm->reviewer_usulan_pkm->where('urutan', 1)->first();
            $usulan_pkm->reviewer2 = $usulan_pkm->reviewer_usulan_pkm->where('urutan', 2)->first();

            // Pre-calculate penilaian sums
            if ($usulan_pkm->reviewer1) {
                $usulan_pkm->nilai_reviewer1 = $usulan_pkm->penilaian_reviewer
                    ->where('reviewer_id', $usulan_pkm->reviewer1->reviewer_id)
                    ->sum('nilai');
            } else {
                $usulan_pkm->nilai_reviewer1 = 0;
            }

            if ($usulan_pkm->reviewer2) {
                $usulan_pkm->nilai_reviewer2 = $usulan_pkm->penilaian_reviewer
                    ->where('reviewer_id', $usulan_pkm->reviewer2->reviewer_id)
                    ->sum('nilai');
            } else {
                $usulan_pkm->nilai_reviewer2 = 0;
            }
        }
    }
}
