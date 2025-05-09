<?php

namespace App\Http\Controllers;

use App\Fakultas;
use App\Helpers\User as UserHelp;
use App\PegawaiRoles;
use App\AnggotaPkm;
use App\Roles;
use App\Setting;
use App\Pegawai;
use App\Reviewer;
use App\JenisPkm;
use App\UsulanPkm;
use App\Mhs;
use App\Http\Controllers\Controller;
use App\KriteriaPenilaian;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    //
    public function list()
    {
        // $pegawai_roles = PegawaiRoles::all()->sortBy("pegawai_id");
        $roles = Roles::all()->where('id', '!=', 1)->where('id', '!=', 3)->sortBy("id"); /// EXCEPT SUPER
        $pegawai_has_role = Pegawai::whereHas('roles', function ($query) {
                                    $query->where('role', '!=', 'PEMBIMBING'); // Kondisi untuk mengecualikan role "PEMBIMBING"
                                })->with('pegawai_roles.roles')->get();
        // dd($pegawai_has_role);
        $this->_data['roles'] = $roles;
        $this->_data['pegawai_has_role'] = $pegawai_has_role;
        // $this->_data['pegawai'] = $pegawai ;
        return view('admin.list', $this->_data);
    }

    private function _asign(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|integer|exists:pegawai,id',
            'roles_id' => 'required|integer|exists:roles,id',
            'fakultas_id' => [
                'string',
                'nullable',
                'exists:fakultas,kodeF',
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->input('roles_id'), [5, 6]); // 5: WD1, 6: ADMINFAKULTAS
                }),
            ],
        ]);
        $pegawai_roles = new PegawaiRoles;
        $pegawai_roles->pegawai_id = $request->pegawai_id;
        $pegawai_roles->roles_id = $request->roles_id;
        $pegawai_roles->fakultas_id = $request->fakultas_id;
        $pegawai_roles->status_role = '1'; // OTOMATIS AKTIF
        $pegawai_roles->save();

        return ['status' => 'ok'];
    }

    private function _unasign(Request $request)
    {

        $pegawai_roles = PegawaiRoles::where([
            'pegawai_id'   => $request->id,
            'roles_id'      => $request->role
        ])->first();
        $pegawai_roles->delete();

        return ['status' => 'ok'];
    }

    public function ajax(Request $request, $method)
    {
        if (method_exists($this, '_' . $method)) {
            return $this->{'_' . $method}($request);
        }
    }

    private function _cari(Request $request)
    {
        // dd($request->role);
        $pegawai = Pegawai::where(function ($query) use ($request) {
            $query->where('nip', 'LIKE', '%' . $request->text . '%')
                ->orWhere('nama', 'LIKE', '%' . $request->text . '%');
        })
            ->whereIn('status', ['1','20']) // AKTIF, TUGAS BELAJAT TDK MENINGGALKAN JAB
            ->whereDoesntHave('roles', function (Builder $query) use ($request) {
                $query->where('roles.id', $request->role);
            })
            ->get(['id', DB::raw('CONCAT(glr_dpn," ",nama," ",glr_blkg, " - ", nip) as text')]);

        $this->_data['items'] = $pegawai;

        return $this->_data;
    }

    private function _cari_fakultas(Request $request)
    {
        // dd($request->role);
        $fakultas = Fakultas::where(function ($query) use ($request) {
            $query->where('nama_fak_ijazah', 'LIKE', '%' . $request->text . '%');
        })->get([DB::raw('kodeF as id'), DB::raw('nama_fak_ijazah as text')]);

        $this->_data['items'] = $fakultas;

        return $this->_data;
    }

    private function _cari_pembimbing(Request $request)
    {
        // dd($request->role);
        $tahun = date('Y');
        $pembimbing = Pegawai::where(function ($query) use ($request) {
                $query->where('nip', 'LIKE', '%' . $request->text . '%')
                    ->orWhere('nama', 'LIKE', '%' . $request->text . '%')
                    ->orWhere('nuptk', 'LIKE', '%' . $request->text . '%');
            })
            ->where('jnspeg', '1') // HANYA DOSEN
            ->whereIn('status', ['1','20']) // AKTIF, TUGAS BELAJAT TDK MENINGGALKAN JAB
            ->whereNotNull('nuptk')->where('nuptk', '!=', '0')
            ->where('nuptk', '!=', '') // HANYA YANG MEMILIKI NIDN
            // ->whereHas('roles', function (Builder $query) use ($request) {
            //     $query->where('roles.role', 'PEMBIMBING');
            // })
            ->whereDoesntHave('usulan_pkm', function (Builder $query) use ($request, $tahun) {
                $query->where('tahun', $tahun)
                    ->havingRaw('COUNT(*) > 1'); 
            })
            // ->get(['id', DB::raw('CONCAT(glr_dpn," ",nama," ",glr_blkg," ","[",nip,"]"," ","[",nidn,"]") as text')]);
            ->get(['id', DB::raw('CONCAT(glr_dpn," ",nama," ",glr_blkg," ","[",nip,"]"," ","[",nuptk,"]") as text')]);

        $this->_data['items'] = $pembimbing;

        return $this->_data;
    }

    private function _cari_reviewer(Request $request)
    {
        // dd($request->role);
        //        $reviewer = Pegawai::where(function($query)use($request){
        //                            $query->where('nip', 'LIKE', '%' . $request->text . '%')
        //                                  ->orWhere('nama', 'LIKE', '%' . $request->text . '%');
        //                            })
        //                           ->where('jnspeg', '1') // HANYA DOSEN
        //                           ->where('status','1') // HANYA AKTIF
        //                           ->whereHas('roles', function (Builder $query) use ($request) {
        //                               $query->where('roles.role', 'REVIEWER');
        //                           })
        //                           ->get([
        //                               'id',
        //                               DB::raw('CONCAT(glr_dpn," ",nama," ",glr_blkg," ","[",nip,"]") as text'),
        //                           ]);
        $reviewer = Reviewer::where(function ($query) use ($request) {
            $query->where('nip', 'LIKE', '%' . $request->text . '%')
                ->orWhere('nama', 'LIKE', '%' . $request->text . '%');
        })
            ->get([
                'id',
                DB::raw('CONCAT(glr_dpn," ",nama," ",glr_blkg," ","[",nip,"]") as text'),
            ]);

        if (!empty($request->usulan_pkm_id)) {
            $reviewer = Reviewer::where(function ($query) use ($request) {
                $query->where('nip', 'LIKE', '%' . $request->text . '%')
                    ->orWhere('nama', 'LIKE', '%' . $request->text . '%');
            })
                ->whereDoesntHave('usulan_pkm', function (Builder $query) use ($request) {
                    $query->where('usulan_pkm.id', $request->usulan_pkm_id);
                })
                ->get([
                    'id',
                    DB::raw('CONCAT(glr_dpn," ",nama," ",glr_blkg," ","[",nip,"]") as text'),
                ]);
        }

        $this->_data['items'] = $reviewer;

        return $this->_data;
    }

    private function _cari_mhs(Request $request)
    {
        // dd($request->role);
        $mhs = [];
        $tahun = date('Y');
        $jenis_pkm = JenisPkm::find($request->jenis_pkm_id);
        if (UserHelp::get_selected_role() == "ADMINFAKULTAS") {
            $kode_fakultas = UserHelp::get_selected_kode_fakultas();
            $mhs = Mhs::where(function ($query) use ($request, $kode_fakultas) {
                $query->where('nim', 'LIKE', '%' . $request->text . '%')
                    ->orWhere('nama', 'LIKE', '%' . $request->text . '%');
                    
                })
                // ->where('kode_fakultas', $kode_fakultas)
                ->whereNotIn('strata', ['pasca', 'Sp1', 'S3', 'PPDS I'])
                ->where('status_terakhir', 'Aktif')
                ->whereDoesntHave('anggota_pkm', function (Builder $query) use ($request, $tahun, $jenis_pkm) {
                    $query->whereHas('usulan_pkm', function (Builder $query) use ($request, $tahun, $jenis_pkm) {
                        $query->where('usulan_pkm.tahun', $tahun);
                            // ->whereHas('jenis_pkm', function (Builder $query) use ($request, $jenis_pkm) {
                            //     if(!empty($jenis_pkm)){
                            //         $query->where('jenis_pkm.kamar', '!=', $jenis_pkm->kamar);
                            //     }
                            // });
                    }); 
                })
                // ->whereDoesntHave('anggota_pkm', function (Builder $query) use ($request, $tahun, $jenis_pkm) {
                //     $query->whereHas('usulan_pkm', function (Builder $query) use ($request, $tahun, $jenis_pkm) {
                //         $query->where('usulan_pkm.tahun', $tahun)
                //             ->whereHas('jenis_pkm', function (Builder $query) use ($request, $jenis_pkm) {
                //                 $query->where('jenis_pkm.kamar', $jenis_pkm->kamar)
                //                     ->where('jenis_pkm.kategori_kegiatan_id', $jenis_pkm->kategori_kegiatan_id);
                //             });
                //     }); 
                // })
                ->get(['nim AS id', DB::raw('CONCAT(nama," ","[",nim,"]"," ","[",nama_forlap,"]"," ","[",nama_fak_ijazah,"]") as text')]);
        }

        $this->_data['items'] = $mhs;

        return $this->_data;
    }

    private function _cari_mhs_anggota(Request $request)
    {
        // dd($request->role);
        $mhs = [];
        $tahun = date('Y');
        $usulan_pkm_id = $request->usulan_pkm_id;
        $jenis_pkm = JenisPkm::find($request->jenis_pkm_id);
        $ketua_nim = $request->ketua_nim;
        $mhs = [];
        if(!empty($jenis_pkm)){
            if (UserHelp::get_selected_role() == "ADMINFAKULTAS") {
                $kode_fakultas = UserHelp::get_selected_kode_fakultas();
                // if($usulan_pkm_id){
    
                // }else{

                    $mhs = Mhs::where(function ($query) use ($request, $kode_fakultas, $ketua_nim) {
                        $query->where('nim', 'LIKE', '%' . $request->text . '%')
                            ->orWhere('nama', 'LIKE', '%' . $request->text . '%');
                        })
                        ->where('nim', '!=', $ketua_nim)
                        // ->where('kode_fakultas', $kode_fakultas)
                        ->where('status_terakhir', 'Aktif')
                        ->whereDoesntHave('anggota_pkm', function (Builder $query) use ($request, $tahun, $jenis_pkm) {
                            $query->whereHas('usulan_pkm', function (Builder $query) use ($request, $tahun, $jenis_pkm) {
                                $query->where('usulan_pkm.tahun', $tahun);
                                //     ->whereHas('jenis_pkm', function (Builder $query) use ($request, $jenis_pkm) {
                                //         $query->where('jenis_pkm.kamar', $jenis_pkm->kamar)
                                //             ->where('jenis_pkm.kategori_kegiatan_id', $jenis_pkm->kategori_kegiatan_id);
                                //     });
                            }); 
                        })
                        ->get(['nim AS id', DB::raw('CONCAT(nama," ","[",nim,"]"," ","[",nama_forlap,"]"," ","[",nama_fak_ijazah,"]") as text')]);
                // }
            }
        }

        $this->_data['items'] = $mhs;

        return $this->_data;
    }

    public function hapus_role($id)
    {
        $roles = Roles::findOrFail($id);
        $roles->delete();

        return redirect()->back()->with('message', 'Data berhasil di-hapus.');
    }


    //
    public function roles()
    {
        $roles = Roles::all()->sortBy("id");
        $this->_data['roles'] = $roles;
        return view('admin.roles', $this->_data);
    }

    private function _add_role(Request $request)
    {

        $roles = new Roles;
        $roles->role = strtoupper($request->role);
        $roles->keterangan = $request->keterangan;
        $roles->save();

        return ['status' => 'ok'];
    }

    //
    public function list_pembimbing()
    {
        $tahun = date('Y');
        // $pegawai_roles = PegawaiRoles::all()->sortBy("pegawai_id");
        if (UserHelp::get_selected_role() == 'PEMBIMBING') {
            $pembimbing = Pegawai::where('nip', UserHelp::admin_get_logged_nip())->get();
        } else {
            $pembimbing = Pegawai::where('jnspeg', '1') // HANYA DOSEN
                ->where('status', '1') // HANYA AKTIF
                ->whereHas('roles', function (Builder $query) {
                    $query->where('roles.role', 'PEMBIMBING');
                })
                ->whereHas('usulan_pkm', function (Builder $query) use ($tahun) {
                    $query->where('usulan_pkm.tahun', $tahun);
                })
                ->get();
        }


        $this->_data['pembimbing'] = $pembimbing;

        return view('admin.list_pembimbing', $this->_data);
    }

    public function list_reviewer()
    {
        $tahun = date('Y');
        // $pegawai_roles = PegawaiRoles::all()->sortBy("pegawai_id");
        $reviewer = Reviewer::whereHas('usulan_pkm', function (Builder $query) use ($tahun) {
            $query->where('usulan_pkm.tahun', $tahun);
        })
            ->get();

        $this->_data['reviewer'] = $reviewer;

        return view('admin.list_reviewer', $this->_data);
    }

    public function setting(Request $request)
    {
        if(UserHelp::get_selected_role() != 'ADMIN' && UserHelp::get_selected_role() != 'SUPER'){
            return redirect()->route('dashboard');
        }
        // buat listener dari ajax untuk update status aplikasi
        $setting = Setting::where('status_aplikasi', '1')->first();
        if ($request->post()) {
            $setting = Setting::whereIn('status_aplikasi', ['1', '0'])->first();
            if (!$setting) {
                $setting = new Setting;
            }
            $setting->status_aplikasi = $request->post('status_aplikasi');
            $setting->save();
            return ['status' => 'ok'];
        }
        return view('admin.setting', ['setting' => $setting]);
    }
}
