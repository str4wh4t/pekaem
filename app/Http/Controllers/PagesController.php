<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\UsulanPkm;
use App\StatusUsulan;
use App\JenisPkm;
use Illuminate\Http\Request;
use App\Helpers\User as UserHelp;
use App\KategoriKegiatan;
use App\Pegawai;
use App\TargetPkmTahunan;
use App\Setting;

class PagesController extends Controller
{
    //

    public function __construct() {}

    public function index()
    {
        if (UserHelp::is_login()) {
            return redirect('dashboard');
        }
        return view('pages.login');
    }

    public function login()
    {
        if (UserHelp::is_login()) {
            return redirect('dashboard');
        }
        return view('pages.login');
    }

    public function dashboard(Request $request)
    {
        if (UserHelp::is_mhs()) {
            return redirect(route('sso.logout'));
        }

        if (UserHelp::is_admin()) {
            if (null === UserHelp::get_selected_role()) {
                return redirect('admin/choose_role');
            }
        }

        // $this->_data['usulan_pkm_total'] = count(UsulanPkm::where('status_usulan_id',StatusUsulan::where('keterangan','DISETUJUI')->first()->id)->get());

        $kode_fakultas = UserHelp::get_selected_kode_fakultas();

        $tahun = Setting::getTahunDipilih();

        if (empty($kode_fakultas)) {
            $this->_data['usulan_pkm_total'] = UsulanPkm::where('tahun', $tahun)->count();
            $this->_data['usulan_pkm_proses'] = UsulanPkm::where('tahun', $tahun)->whereIn('status_usulan_id', StatusUsulan::whereIn('keterangan', ['BARU', 'DISETUJUI'])->pluck('id'))->count();
            $this->_data['usulan_pkm_belum_dinilai'] = UsulanPkm::where('tahun', $tahun)->where('status_usulan_id', StatusUsulan::where('keterangan', 'LANJUT')->first()->id)->count();
            $this->_data['usulan_pkm_sudah_dinilai'] = UsulanPkm::where('tahun', $tahun)->where('status_usulan_id', StatusUsulan::where('keterangan', 'SUDAH_DINILAI')->first()->id)->count();
            $this->_data['usulan_pkm'] = UsulanPkm::where('tahun', $tahun)->get();
            
            // Get target data for all faculties
            $this->_data['target_pkm_list'] = TargetPkmTahunan::where('tahun', $tahun)->with('fakultas')->get();
            $this->_data['target_pkm_total'] = TargetPkmTahunan::where('tahun', $tahun)->sum('target_usulan_pkm');
            
            // Pre-calculate capaian untuk setiap fakultas untuk menghindari N+1 query
            $capaian_per_fakultas = UsulanPkm::where('tahun', $tahun)
                ->selectRaw('kode_fakultas, COUNT(*) as jumlah')
                ->groupBy('kode_fakultas')
                ->pluck('jumlah', 'kode_fakultas')
                ->toArray();
            
            foreach ($this->_data['target_pkm_list'] as $target) {
                $target->capaian = isset($capaian_per_fakultas[$target->kode_fakultas]) ? $capaian_per_fakultas[$target->kode_fakultas] : 0;
            }
        } else {
            $this->_data['usulan_pkm_total'] = UsulanPkm::where('tahun', $tahun)->where('kode_fakultas', $kode_fakultas)->count();
            $this->_data['usulan_pkm_proses'] = UsulanPkm::where('tahun', $tahun)->where('kode_fakultas', $kode_fakultas)->whereIn('status_usulan_id', StatusUsulan::whereIn('keterangan', ['BARU', 'DISETUJUI'])->pluck('id'))->count();
            $this->_data['usulan_pkm_sudah_dinilai'] = UsulanPkm::where('tahun', $tahun)->where('kode_fakultas', $kode_fakultas)->where('status_usulan_id', StatusUsulan::where('keterangan', 'SUDAH_DINILAI')->first()->id)->count();
            $this->_data['usulan_pkm_belum_dinilai'] = UsulanPkm::where('tahun', $tahun)->where('kode_fakultas', $kode_fakultas)->where('status_usulan_id', StatusUsulan::where('keterangan', 'LANJUT')->first()->id)->count();
            $this->_data['usulan_pkm'] = UsulanPkm::where('tahun', $tahun)->where('kode_fakultas', $kode_fakultas)->get();
            
            // Get target data for selected faculty
            $target_pkm = TargetPkmTahunan::where('tahun', $tahun)->where('kode_fakultas', $kode_fakultas)->first();
            $this->_data['target_pkm'] = $target_pkm;
            $this->_data['target_pkm_total'] = $target_pkm ? $target_pkm->target_usulan_pkm : 0;
        }

        // dd($this->_data);

        $this->_data['kategori_kegiatan_list'] = KategoriKegiatan::all();
        $this->_data['tahun'] = $tahun;
        $this->_data['kode_fakultas'] = $kode_fakultas;

        return view('pages.index', $this->_data);
    }

    public function choose_role(Request $request)
    {
        $pegawai_roles = UserHelp::admin_get_pegawai_roles_by_nip(UserHelp::admin_get_logged_nip());
        $choose = $request->input('choose');
        $roles_avail = [];
        if (null !== $choose) {
            foreach ($pegawai_roles as $pegawai_role) {
                if ($choose == $pegawai_role->roles_id) {
                    $request->session()->put('session_data.role_as', $pegawai_role->roles->role);
                    $request->session()->put('session_data.kode_fakultas_as', $pegawai_role->fakultas_id);
                    return redirect('dashboard');
                }
            }
        }
        foreach ($pegawai_roles as $pegawai_role) {
            if ($pegawai_role->roles->role == 'PEMBIMBING')
                continue;
            $roles_avail[] = $pegawai_role->roles;
        }
        $this->_data['roles_avail'] = $roles_avail;
        return view('pages.choose_role', $this->_data);
    }

    public function ajax(Request $request, $method)
    {
        if (method_exists($this, '_' . $method)) {
            return $this->{'_' . $method}($request);
        }
    }

    private function _login_direct(Request $request)
    {
        $user = $request->input('user');
        $password = $request->input('password');
        $status = 'password salah';
        if ($password != '123456') {
            return ['status' => $status];
        }
        $session_data = [];
        if ($user == 'super') {
            $session_data = [
                'username'          => 'super',
                'nama_lengkap'      => 'KOPASUS',
                'kodeF' =>  null,
                'login_at'          => date('Y-m-d H:i:s'),
                'login_as'          => 'ADMIN',
                'role_as'           => 'SUPER',
                'kode_fakultas_as'           => null,
            ];
        }
        $mhs = UserHelp::mhs_get_record_by_nim($user);
        if (!empty($mhs)) {
            $session_data = [
                'username'          => $mhs->nim,
                'nama_lengkap'      => strtoupper($mhs->nama),
                'kodeF' =>          $mhs->kode_fakultas,
                'login_at'          => date('Y-m-d H:i:s'),
                'login_as'          => 'MHS',
                'role_as'           => 'MHS',
                'kode_fakultas_as'           => null,
            ];
        }
        $roles = UserHelp::admin_get_roles_by_nip($user);
        if (!empty($roles)) {
            $pegawai = UserHelp::admin_get_record_by_nip($user);

            $session_data = [
                'username'          => $pegawai->nip,
                'nama_lengkap'      => $pegawai->glr_dpn . ' ' . $pegawai->nama . ' ' . $pegawai->glr_blkg,
                'kodeF' =>          null, // $pegawai->mapping_fakultas->fakultas->kodeF,
                'login_at'          => date('Y-m-d H:i:s'),
                'login_as'          => 'ADMIN',
                'role_as'           => null,
                'kode_fakultas_as'           => null,
            ];
        }
        if (!empty($session_data)) {
            $status = 'ok';
            $request->session()->put('session_data', $session_data);
        }
        return ['status' => $status];
    }

    public function login_as(Request $request, Pegawai $pegawai)
    {
        $roles = UserHelp::admin_get_roles_by_nip($pegawai->nip);
        if (!empty($roles)) {
            $session_data = [
                'username'          => $pegawai->nip,
                'nama_lengkap'      => $pegawai->glr_dpn . ' ' . $pegawai->nama . ' ' . $pegawai->glr_blkg,
                'kodeF' =>          null, // $pegawai->mapping_fakultas->fakultas->kodeF,
                'login_at'          => date('Y-m-d H:i:s'),
                'login_as'          => 'ADMIN',
                'role_as'           => null,
                'kode_fakultas_as'           => null,
            ];
        }
        if (!empty($session_data)) {
            $request->session()->flush();
            $request->session()->put('session_data', $session_data);
        }
        return redirect('dashboard');
    }

    public function apiUser(Request $request)
    {
        return $request->user();
    }
}
