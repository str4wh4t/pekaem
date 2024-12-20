<?php

namespace App\Http\Controllers;

use App\AnggotaPkm;
use App\UsulanPkm;
use App\JenisPkm;
use App\StatusUsulan;
use App\Mhs;
use App\Revisi;
use App\Review;
use App\Perbaikan;
use App\ReviewerUsulanPkm;
use App\Http\Controllers\Controller;
use App\KategoriKegiatan;
use App\PegawaiRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Uuid;
use UserHelp;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Validation\ValidationException;

class PendaftaranController extends Controller
{
	//

	public function __construct() {}

	public function simpan(Request $request)
	{
		//    	 dd($request->input());
		// dd($request->input('list_nim'));
		// die;
		//$list_nim = array_column($request->input('list_nim'), 'nim');
		// dd($list_nim[0]);
		// die;
		//validasi multiple file
		$validation = $request->validate([
			'judul'	=> 'required|min:5|max:500',
			'jenis'	=> 'required',
			'berkas.*' => 'required|mimes:pdf|max:5120',
			'pegawai_id'	=> 'required',
		]);

		// $request->input('nim');
		// if(!empty($files)){

		// $usulan_pkm->save();
		$id = '';
		if (!empty($request->id)) {
			$id = UsulanPkm::where('uuid', $request->id)->first()->id;
		}

		if (empty($id)) {
			/// IF NEW
			$usulan_pkm = new UsulanPkm;
			$usulan_pkm->mhs_nim = UserHelp::mhs_get_logged_nim();
			$usulan_pkm->uuid = Uuid::generate();
			$usulan_pkm->status_usulan_id = StatusUsulan::where('keterangan', 'BARU')->first()->id;
		} else {
			/// IF EDIT
			$usulan_pkm = UsulanPkm::findOrFail($id);
			// $anggota_pkm_old = AnggotaPkm::where('usulan_pkm_id',$id);
			// $anggota_pkm_old->delete();
		}

		$usulan_pkm->judul = $request->judul;
		$usulan_pkm->jenis_pkm_id = $request->jenis;
		$usulan_pkm->pegawai_id = $request->pegawai_id;


		DB::beginTransaction();

		try {

			if (!empty(@$request->catatan_perbaikan)) {
				$usulan_pkm->status_usulan_id = StatusUsulan::where('keterangan', 'MENUNGGU')->first()->id;
				$perbaikan = new Perbaikan;
				$perbaikan->usulan_pkm_id = $usulan_pkm->id;
				$perbaikan->catatan_perbaikan = $request->catatan_perbaikan;
				$perbaikan->mhs_nim = UserHelp::mhs_get_logged_nim();
				$perbaikan->save();
			}

			$usulan_pkm->save();

			if (empty($id)) {
				$anggota_pkm = new AnggotaPkm;
				$anggota_pkm->mhs_nim = UserHelp::mhs_get_logged_nim();
				$anggota_pkm->sebagai = 0; // 0 : KETUA
				UsulanPkm::find($usulan_pkm->id)->anggota_pkm()->save($anggota_pkm);

				// SET PEGAWAI YANG DIPILIH SEBAGAI PEMBIMBING
				$pegawai_roles = new PegawaiRoles();
				$pegawai_roles->pegawai_id = $request->pegawai_id;
				$pegawai_roles->roles_id = 'PEMBIMBING';
				$pegawai_roles->status_role = '1'; // OTOMATIS AKTIF
				$pegawai_roles->save();
			}

			$list_nim = $request->list_nim;
			if (!empty($list_nim)) {
				$list_nim = array_column($request->list_nim, 'nim');
				if (!empty($list_nim[0])) {
					foreach ($list_nim as $nim) {
						$anggota_pkm = new AnggotaPkm;
						$anggota_pkm->mhs_nim = $nim;
						$anggota_pkm->sebagai = 1; // 1 : ANGGOTA
						UsulanPkm::find($usulan_pkm->id)->anggota_pkm()->save($anggota_pkm);
					}
				}
			}
			// if(empty($list_nim)){
			// 	DB::rollback();
			// 	return redirect()->back()->withErrors(['NIM anggota is required']);
			// }

			//tampung file yang diupload
			$files = $request->file('berkas');
			//define variable folder sebagai array
			// $folder = [];

			if ((empty($id)) && (empty($files))) { // IF ADD MUST HAVE FILES
				DB::rollback();
				return redirect()->back()->withErrors(['Berkas upload is required']);
			}

			if (!empty($files)) {
				foreach ($files as $i => $file) {
					//custom name masing2 file
					$nama_file = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
					$nama_file = str_replace(' ', '_', $nama_file);
					$nama_file = str_replace('.', '_', $nama_file);
					$filename = $usulan_pkm->uuid . '_file_' . $nama_file . '.' . $file->getClientOriginalExtension();
					// upload file
					// $folder[] = $file->storeAs('public/documents/', $filename);
					$file->storeAs('public/documents/', $filename);
				}
			}
		} catch (ValidationException $e) {
			DB::rollback();
			throw $e;
		}

		DB::commit();

		return redirect()->back()->with('message', 'Data berhasil di-simpan.');

		// $anggota_pkm->push();

		// $usulan_pkm->anggota_pkm()->save($anggota_pkm);
		// $anggota_pkm->usulan_pkm()->associate($usulan_pkm);
		// $anggota_pkm->save();

		// return view('user.profile', ['user' => User::findOrFail($id)]);
	}

	public function edit(Request $request, $uuid)
	{
		$id = UsulanPkm::where('uuid', $uuid)->first()->id;
		$usulan_pkm = UsulanPkm::findOrFail($id);
		$jenis_pkm = JenisPkm::all()->sortBy("id");
		$mhs = Mhs::findOrFail(UserHelp::mhs_get_logged_nim());

		// foreach($usulan_pkm->anggota_pkm as $anggota){
		// 	dd($anggota->mhs->nim);
		// }

		$files = Storage::files('public/documents');
		$files_to_show = [];
		foreach ($files as $file) {
			$nama_file = str_replace('public/documents/', '', $file);
			$nama_file_arr = explode('_file_', $nama_file);
			if ($nama_file_arr[0] == $uuid) {
				$files_to_show[] = $nama_file_arr[1];
			}
		}

		try {
			$client = new Client();
			$post_data = [
				'auth' =>  json_encode(['user' => 'sempak', 'pass' => 'teles']),
				'kode_fakultas' => $mhs->kode_fakultas,
				'get' => json_encode(["kodeF", "nama_fak_ijazah"])
			];
			$req = new GuzzleRequest('POST', env('API_SIAP') . '/get_data_fakultas');
			$res = $client->send($req, ['form_params' => $post_data]);
			$return = json_decode($res->getBody()->getContents());
			if (empty($return)) {
				throw new BadResponseException('Data kosong.', $req);
			}
		} catch (BadResponseException $exception) {
			//	        dd($exception->getMessage());
			abort(403, substr($exception->getMessage(), 0, 25));
		}

		$mhs->kode_fakultas = $return->nama_fak_ijazah;

		try {
			$client = new Client();
			$post_data = [
				'auth' =>  json_encode(['user' => 'sempak', 'pass' => 'teles']),
				'kode_prodi' => $mhs->kode_prodi,
				'get' => json_encode(["kode_prodi_pdpt", "nama_ps"])
			];
			$req = new GuzzleRequest('POST', env('API_SIAP') . '/get_data_prodi');
			$res = $client->send($req, ['form_params' => $post_data]);
			$return = json_decode($res->getBody()->getContents());
			if (empty($return)) {
				throw new BadResponseException('Data kosong.', $req);
			}
		} catch (BadResponseException $exception) {
			//	        dd($exception->getMessage());
			abort(403, substr($exception->getMessage(), 0, 25));
		}

		$mhs->kode_prodi = $return->nama_ps;

		$this->_data['files_to_show'] = $files_to_show;
		$this->_data['usulan_pkm'] = $usulan_pkm;
		$this->_data['jenis_pkm'] = $jenis_pkm;
		$this->_data['mhs'] = $mhs;

		return view('pendaftaran.form', $this->_data);
	}

	public function ajax(Request $request, $method)
	{
		if (method_exists($this, '_' . $method)) {
			return $this->{'_' . $method}($request);
		}
	}

	private function _hapus_document(Request $request)
	{
		$uuid = $request->id;
		$file = $request->file;
		$file = 'public/documents/' . $uuid . '_file_' . $file;
		// dd($file);
		Storage::delete($file);
		return ['status' => 'ok'];
	}

	private function _hapus_anggota(Request $request)
	{
		$anggota_pkm = AnggotaPkm::findOrFail($request->id);
		$anggota_pkm->delete();
		return ['status' => 'ok'];
	}

	private function _hapus_reviewer(Request $request)
	{
		if (UserHelp::get_selected_role() != "ADMIN") {
			return ['status' => 'error'];
		}
		$reviewer_usulan_pkm = ReviewerUsulanPkm::where('reviewer_id', $request->reviewer_id)->where('usulan_pkm_id', $request->usulan_pkm_id);
		$reviewer_usulan_pkm->delete();
		return ['status' => 'ok'];
	}

	private function _ajukan(Request $request)
	{
		$usulan_pkm = UsulanPkm::findOrFail($request->id);
		$usulan_pkm->status_usulan_id = StatusUsulan::where('keterangan', 'MENUNGGU')->first()->id;
		$usulan_pkm->save();
		$request->session()->flash('message', 'Usulan telah diajukan.');
		return ['status' => 'ok'];
	}

	public function list(Request $request)
	{
		$usulan_pkm = UsulanPkm::all()->sortBy("judul");

		if (UserHelp::get_selected_role() == "MHS") {
			$usulan_pkm = UsulanPkm::where('mhs_nim', UserHelp::mhs_get_logged_nim())->get();
		}
		if (UserHelp::get_selected_role() == "PEMBIMBING") {
			$pembimbing = UserHelp::admin_get_record_by_nip(UserHelp::admin_get_logged_nip());
			$usulan_pkm = UsulanPkm::where('pegawai_id', $pembimbing->id)
				->where('status_usulan_id', '!=', StatusUsulan::where('keterangan', 'BARU')->first()->id)
				->get();
		}
		if (UserHelp::get_selected_role() == "ADMIN") {
			// $usulan_pkm = UsulanPkm::where('status_usulan_id',StatusUsulan::where('keterangan','DISETUJUI')->first()->id)
			if (!empty($request->jenis)) {
				if ($request->jenis == 'pembimbing') {
					$usulan_pkm = UsulanPkm::where('pegawai_id', $request->pegawai_id)->get();
				}
				if ($request->jenis == 'reviewer') {
					$usulan_pkm = UsulanPkm::wherehas('reviewer', function (Builder $query) use ($request) {
						$query->where('reviewer.id', $request->pegawai_id);
					})->get();
				}
			}
		}
		if (UserHelp::get_selected_role() == "REVIEWER") {
			$reviewer = UserHelp::admin_get_record_by_nip(UserHelp::admin_get_logged_nip());
			$usulan_pkm = UsulanPkm::wherehas('reviewer', function (Builder $query) use ($reviewer) {
				$query->where('reviewer.id', $reviewer->id);
			})->get();
		}
		$this->_data['usulan_pkm'] = $usulan_pkm;

		// echo '<pre>';
		// foreach($usulan_pkm as $u){
		// var_dump($u->has('reviewer')->get());
		// }
		// die;
		return view('pendaftaran.list', $this->_data);
	}

	public function add()
	{
		$jenis_pkm = JenisPkm::all()->sortBy("id");
		$kategori_kegiatan_list = KategoriKegiatan::all()->sortBy("id");
		$status_usulan = StatusUsulan::all()->sortBy("id");
		$mhs = Mhs::findOrFail(UserHelp::mhs_get_logged_nim());

		try {
			$client = new Client(['verify' => false]);
			$post_data = [
				'auth' =>  json_encode(['user' => 'sempak', 'pass' => 'teles']),
				'kode_fakultas' => $mhs->kode_fakultas,
				'get' => json_encode(["kodeF", "nama_fak_ijazah"])
			];
			$req = new GuzzleRequest('POST', env('API_SIAP') . '/get_data_fakultas');
			$res = $client->send($req, ['form_params' => $post_data]);
			$return = json_decode($res->getBody()->getContents());
			if (empty($return)) {
				throw new BadResponseException('Data kosong.', $req);
			}
		} catch (BadResponseException $exception) {
			//	        dd($exception->getMessage());
			abort(403, substr($exception->getMessage(), 0, 25));
		}

		$mhs->kode_fakultas = $return->nama_fak_ijazah;

		try {
			$client = new Client();
			$post_data = [
				'auth' =>  json_encode(['user' => 'sempak', 'pass' => 'teles']),
				'kode_prodi' => $mhs->kode_prodi,
				'get' => json_encode(["kode_prodi_pdpt", "nama_ps"])
			];
			$req = new GuzzleRequest('POST', env('API_SIAP') . '/get_data_prodi');
			$res = $client->send($req, ['form_params' => $post_data]);
			$return = json_decode($res->getBody()->getContents());
			if (empty($return)) {
				throw new BadResponseException('Data kosong.', $req);
			}
		} catch (BadResponseException $exception) {
			//	        dd($exception->getMessage());
			abort(403, substr($exception->getMessage(), 0, 25));
		}

		$mhs->kode_prodi = $return->nama_ps;

		$this->_data['jenis_pkm'] = $jenis_pkm;
		$this->_data['kategori_kegiatan_list'] = $kategori_kegiatan_list;
		$this->_data['status_usulan'] = $status_usulan;
		$this->_data['mhs'] = $mhs;
		return view('pendaftaran.form', $this->_data);
	}

	public function hapus($uuid)
	{
		$id = UsulanPkm::where('uuid', $uuid)->first()->id;
		$usulan_pkm = UsulanPkm::findOrFail($id);
		$usulan_pkm->delete();

		$files = Storage::files('public/documents');
		foreach ($files as $file) {
			$nama_file = str_replace('public/documents/', '', $file);
			$nama_file_arr = explode('_file_', $nama_file);
			if ($nama_file_arr[0] == $uuid) {
				$file_to_delete = 'public/documents/' . $uuid . '_file_' . $nama_file_arr[1];
				Storage::delete($file_to_delete);
			}
		}

		return redirect()->route('share.pendaftaran.list')->with('message', 'Data berhasil di-hapus.');
	}

	public function read(Request $request, $uuid)
	{
		$id = UsulanPkm::where('uuid', $uuid)->first()->id;
		$usulan_pkm = UsulanPkm::findOrFail($id);
		$jenis_pkm = JenisPkm::all()->sortBy("id");
		$status_usulan = StatusUsulan::all()->sortBy("id");
		// $mhs = Mhs::findOrFail(UserHelp::mhs_get_logged_nim());

		$mhs = $usulan_pkm->anggota_pkm()->where('sebagai', 0)->first()->mhs;

		// foreach($usulan_pkm->anggota_pkm as $anggota){
		// dd($usulan_pkm->reviewer_usulan_pkm->where('reviewer_id',27)[0]->id);
		// }

		$files = Storage::files('public/documents');
		$files_to_show = [];
		foreach ($files as $file) {
			$nama_file = str_replace('public/documents/', '', $file);
			$nama_file_arr = explode('_file_', $nama_file);
			if ($nama_file_arr[0] == $uuid) {
				$files_to_show[] = $nama_file_arr[1];
			}
		}

		try {
			$client = new Client();
			$post_data = [
				'auth' =>  json_encode(['user' => 'sempak', 'pass' => 'teles']),
				'kode_fakultas' => $mhs->kode_fakultas,
				'get' => json_encode(["kodeF", "nama_fak_ijazah"])
			];
			$req = new GuzzleRequest('POST', env('API_SIAP') . '/get_data_fakultas');
			$res = $client->send($req, ['form_params' => $post_data]);
			$return = json_decode($res->getBody()->getContents());
			if (empty($return)) {
				throw new BadResponseException('Data kosong.', $req);
			}
		} catch (BadResponseException $exception) {
			//	        dd($exception->getMessage());
			abort(403, substr($exception->getMessage(), 0, 25));
		}

		$mhs->kode_fakultas = $return->nama_fak_ijazah;

		try {
			$client = new Client();
			$post_data = [
				'auth' =>  json_encode(['user' => 'sempak', 'pass' => 'teles']),
				'kode_prodi' => $mhs->kode_prodi,
				'get' => json_encode(["kode_prodi_pdpt", "nama_ps"])
			];
			$req = new GuzzleRequest('POST', env('API_SIAP') . '/get_data_prodi');
			$res = $client->send($req, ['form_params' => $post_data]);
			$return = json_decode($res->getBody()->getContents());
			if (empty($return)) {
				throw new BadResponseException('Data kosong.', $req);
			}
		} catch (BadResponseException $exception) {
			//	        dd($exception->getMessage());
			abort(403, substr($exception->getMessage(), 0, 25));
		}

		$mhs->kode_prodi = $return->nama_ps;

		$this->_data['files_to_show'] = $files_to_show;
		$this->_data['usulan_pkm'] = $usulan_pkm;
		$this->_data['jenis_pkm'] = $jenis_pkm;
		$this->_data['status_usulan'] = $status_usulan;
		$this->_data['mhs'] = $mhs;

		return view('pendaftaran.read', $this->_data);
	}

	public function approval(Request $request, $uuid)
	{
		$id = UsulanPkm::where('uuid', $uuid)->first()->id;

		$usulan_pkm = UsulanPkm::findOrFail($id);
		$status_usulan = StatusUsulan::where('keterangan', $request->approval)->firstOrFail();

		if (UserHelp::get_selected_role() == "PEMBIMBING") {

			$revisi = new Revisi;
			$revisi->usulan_pkm_id = $usulan_pkm->id;
			$revisi->catatan_pembimbing = $request->catatan_pembimbing;
			$pembimbing = UserHelp::admin_get_record_by_nip(UserHelp::admin_get_logged_nip());
			$revisi->pegawai_id = $pembimbing->id;
			$revisi->status_usulan_id = $status_usulan->id;
			$revisi->save();
		}

		if (UserHelp::get_selected_role() == "REVIEWER") {
			$review = new Review;
			$review->usulan_pkm_id = $usulan_pkm->id;
			$review->catatan_reviewer = $request->catatan_reviewer;
			$reviewer = UserHelp::admin_get_record_by_nip(UserHelp::admin_get_logged_nip());
			$review->pegawai_id = $reviewer->id;
			$review->status_usulan_id = $status_usulan->id;
			$review->save();
		}

		$usulan_pkm->status_usulan_id = $status_usulan->id;
		$usulan_pkm->save();

		return redirect()->back()->with('message', 'Data berhasil di-simpan.');
	}

	public function ploting_reviewer()
	{
		$usulan_pkm = UsulanPkm::all()->sortBy("judul");
		if (UserHelp::get_selected_role() == "ADMIN") {
			$usulan_pkm = UsulanPkm::where('status_usulan_id', StatusUsulan::where('keterangan', 'DISETUJUI')->first()->id)
				->get();
		}
		$this->_data['UsulanPkm'] = $usulan_pkm;
		return view('pendaftaran.list', $this->_data);
	}

	public function set_reviewer(Request $request, $uuid)
	{
		$id = UsulanPkm::where('uuid', $uuid)->first()->id;

		$usulan_pkm = UsulanPkm::findOrFail($id);

		$list_id = $request->list_id;
		if (!empty($list_id)) {
			$list_id = array_column($request->list_id, 'id');
			if (!empty($list_id[0])) {
				foreach ($list_id as $id) {
					$reviewer_usulan_pkm = new ReviewerUsulanPkm;
					$reviewer_usulan_pkm->usulan_pkm_id = $usulan_pkm->id;
					$reviewer_usulan_pkm->reviewer_id = $id;
					$reviewer_usulan_pkm->save();
				}
				return redirect()->back()->with('message', 'Data berhasil di-simpan.');
			} else {
				return redirect()->back()->withErrors(['Data reviewer masih kosong']);
			}
		} else {
			return redirect()->back()->withErrors(['Data reviewer masih kosong']);
		}

		// $revisi_status = StatusUsulan::where('keterangan', $request->approval)->firstOrFail();

		// $revisi = new Revisi;
		// $revisi->usulan_pkm_id = $usulan_pkm->id;
		// $revisi->catatan_pembimbing = $request->catatan_pembimbing;
		// $pembimbing = UserHelp::admin_get_record_by_nip(UserHelp::admin_get_logged_nip());
		// $revisi->pegawai_id = $pembimbing->id;
		// $revisi->status_usulan_id = $revisi_status->id;
		// $revisi->save();

		// $usulan_pkm->status_usulan_id = $revisi_status->id;
		// $usulan_pkm->save();

		// return redirect()->back()->with('message', 'Data berhasil di-simpan.');
	}
}
