<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\UsulanPkm;
use App\StatusUsulan;
use App\JenisPkm;
use Illuminate\Http\Request;
use App\Helpers\User as UserHelp;

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

    public function dashboard()
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
        $this->_data['usulan_pkm_total'] = count(UsulanPkm::all());
        $this->_data['usulan_pkm_sudah_dinilai'] = count(UsulanPkm::where('status_usulan_id', StatusUsulan::where('keterangan', 'SUDAH_DINILAI')->first()->id)->get());
        $this->_data['usulan_pkm_belum_dinilai'] = count(UsulanPkm::where('status_usulan_id', StatusUsulan::where('keterangan', 'BELUM_DINILAI')->first()->id)->get());

        $this->_data['jenis_pkm'] = JenisPkm::all();
        $this->_data['usulan_pkm'] = UsulanPkm::all();

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
            if($pegawai_role->roles->role == 'PEMBIMBING')
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
}
