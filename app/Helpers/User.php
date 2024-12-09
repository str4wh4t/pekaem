<?php

namespace App\Helpers;

// use Illuminate\Support\Facades\DB;
use App\PegawaiRoles;
use App\Roles;
use App\Pegawai;
use App\Mhs;
use Illuminate\Http\Request;

class User
{
	// public static function get_username($user_id) {
	//     $user = DB::table('users')->where('userid', $user_id)->first();
	//     return (isset($user->username) ? $user->username : '');
	// }

	public static function admin_status_role_text($status_role)
	{
		// SEMENTARA FUNGSI INI TIDAK DIPAKAI
		switch ($status_role) {
			case '0':
				return 'TIDAK AKTIF';
				break;
			default:
				return 'AKTIF';
				break;
		}
	}

	public static function admin_get_record_by_nip($nip)
	{

		$pegawai = Pegawai::where('nip', $nip)->first();

		dd($pegawai);

		return $pegawai;
	}

	public static function admin_get_roles_by_nip($nip)
	{
		// $PegawaiRoles = PegawaiRoles::with(['pegawai' => function ($query) use($nip){
		//     $query->where('nip',$nip);
		// }])->get();

		$pegawai = Pegawai::where('nip', $nip)->first();

		$roles = [];
		if (isset($pegawai->pegawai_roles)) {
			foreach ($pegawai->pegawai_roles as $r) {
				$roles[] = $r->roles;
			}
		}

		return $roles;
	}

	public static function admin_get_logged_nip()
	{
		$nip = null;
		if (null !== session('session_data')) {
			$session_data = session('session_data');
			if ($session_data['login_as'] == 'ADMIN') {
				$nip = $session_data['username'];
			}
		}

		return $nip;
	}

	public static function mhs_get_record_by_nim($nim)
	{

		$mhs = Mhs::where('nim', $nim)->first();

		// dd($pegawai);

		return $mhs;
	}


	public static function is_login()
	{
		if (null !== session('session_data')) {
			return true;
		}
		return false;
	}

	public static function is_mhs()
	{
		if (null !== session('session_data')) {
			$session_data = session('session_data');
			if ($session_data['login_as'] == 'MHS') {
				return true;
			}
		}
		return false;
	}

	public static function is_admin()
	{
		if (null !== session('session_data')) {
			$session_data = session('session_data');
			if ($session_data['login_as'] == 'ADMIN') {
				return true;
			}
		}
		return false;
	}

	public static function mhs_get_logged_nim()
	{
		$nim = null;
		if (null !== session('session_data')) {
			$session_data = session('session_data');
			if ($session_data['login_as'] == 'MHS') {
				$nim = $session_data['username'];
			}
		}

		return $nim;
	}


	//// SHARE
	public static function get_selected_role()
	{
		$role = null;
		if (null !== session('session_data')) {
			$session_data = session('session_data');
			// if($session_data['login_as'] == 'ADMIN') {
			$role = $session_data['role_as'];
			// }
		}

		return $role;
	}

	public static function get_selected_nama_lengkap()
	{
		$nama_lengkap = null;
		if (null !== session('session_data')) {
			$session_data = session('session_data');
			// if($session_data['login_as'] == 'ADMIN') {
			$nama_lengkap = $session_data['nama_lengkap'];
			// }
		}

		return $nama_lengkap;
	}
}
