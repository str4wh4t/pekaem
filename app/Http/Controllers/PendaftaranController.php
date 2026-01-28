<?php

namespace App\Http\Controllers;

use App\AnggotaPkm;
use App\UsulanPkm;
use App\JenisPkm;
use App\StatusUsulan;
use App\Mhs;
use App\Revisi;
use App\Review;
use App\Setting;
use App\TemaUsulanPkm;
use App\Perbaikan;
use App\ReviewerUsulanPkm;
use App\Http\Controllers\Controller;
use App\KategoriKegiatan;
use App\PegawaiRoles;
use App\UsulanPkmDokumen;
use App\PenilaianReviewer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use \App\Helpers\User as UserHelp;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Validation\ValidationException;
use Webpatser\Uuid\Uuid;

class PendaftaranController extends Controller
{
	//

	public function __construct() {}

	public function simpan(Request $request)
	{
		// dd($request->input());
		// dd($request->input('list_nim'));
		// die;
		//$list_nim = array_column($request->input('list_nim'), 'nim');
		// dd($list_nim[0]);
		// die;
		//validasi multiple file
		// $request->validate([
		// 	'judul'	=> 'required|min:5|max:500',
		// 	'kategori_kegiatan_id'	=> 'required|exists:kategori_kegiatan,id',
		// 	'jenis_pkm_id'	=> 'required|exists:jenis_pkm,id',
		// 	'berkas' => 'required|array', // Pastikan 'berkas' adalah array
		// 	'berkas.*' => 'file|mimes:pdf|max:5120', // Validasi setiap file dalam array
		// 	'pegawai_id'	=> 'required',
		// ]);

		// $request->input('nim');
		// if(!empty($files)){

		// $usulan_pkm->save();
		$id = '';
		$usulan_pkm = null;
		if (!empty($request->id)) {
			// IF EDIT
			$usulan_pkm = UsulanPkm::where('uuid', $request->id)->first();
			$id = $usulan_pkm->id;

			// Cek apakah sudah ada berkas tersimpan
			$hasExistingDocs = $usulan_pkm->usulan_pkm_dokumen()->exists();
			$berkasRule = $hasExistingDocs ? 'nullable|array' : 'required|array';
			$berkasEachRule = 'file|mimes:pdf|max:5120';

			$anggota_pkm_existing = $usulan_pkm->anggota_pkm->count() - 1;

			$max_anggota = 4;
			$max_anggota_valid = $max_anggota - $anggota_pkm_existing;

			$min_anggota = 2;
			$min_anggota_valid = $anggota_pkm_existing > $min_anggota ? 0 : $min_anggota - $anggota_pkm_existing;

			$required = '';
			if ($usulan_pkm->anggota_pkm->count() == 1) {
				$required = 'required|array';
			}

			$request->validate([
				'judul'	=> 'required|min:5|max:500',
				'kategori_kegiatan_id'	=> 'required|exists:kategori_kegiatan,id',
				'jenis_pkm_id'	=> 'required|exists:jenis_pkm,id',
				'tema_usulan_pkm_id'	=> 'required|exists:tema_usulan_pkm,id',
				'tema_usulan_pkm_id'	=> 'required|exists:tema_usulan_pkm,id',
				// Jika belum ada berkas tersimpan, wajib upload; jika sudah ada, boleh kosong
				'berkas' => $berkasRule, // Pastikan 'berkas' adalah array jika ada
				'berkas.*' => $berkasEachRule, // Validasi setiap file dalam array
				'pegawai_id'	=> 'required',
				'email' => 'required|email',
				'telp' => 'required|numeric',
				'list_nim' => $required . '|min:' . $min_anggota_valid . '|max:' . $max_anggota_valid,
				'list_nim.*.nim' => 'required|distinct',
			], [
				'list_nim.required' => 'Anggota harus ditambahkan.',
				'list_nim.min' => 'Min. 2 anggota yang ditambahkan.',
				'list_nim.max' => 'Max. 4 anggota yang ditambahkan.',
				'list_nim.*.nim.distinct' => 'Setiap anggota harus memiliki NIM yang unik.', // Pesan error kustom
				'list_nim.*.nim.required' => 'NIM wajib diisi untuk setiap anggota.',
			]);

			if ($usulan_pkm->created_by != UserHelp::admin_get_logged_nip()) {
				return redirect()->back()->with('message', 'Dilarang mengubah usulan.');
			}
		} else {
			// IF NEW
			$request->validate([
				'mhs_nim'	=> 'required',
				'judul'	=> 'required|min:5|max:500',
				'kategori_kegiatan_id'	=> 'required|exists:kategori_kegiatan,id',
				'jenis_pkm_id'	=> 'required|exists:jenis_pkm,id',
				'tema_usulan_pkm_id'	=> 'required|exists:tema_usulan_pkm,id',
				'berkas' => 'required|array', // Pastikan 'berkas' adalah array
				'berkas.*' => 'file|mimes:pdf|max:5120', // Validasi setiap file dalam array
				'pegawai_id'	=> 'required',
				'email' => 'required|email',
				'telp' => 'required|numeric',
				'list_nim' => 'required|array|min:2|max:4',
				'list_nim.*.nim' => 'required|distinct',
			], [
				'list_nim.required' => 'Anggota harus ditambahkan.',
				'list_nim.min' => 'Min. 2 anggota yang ditambahkan.',
				'list_nim.max' => 'Max. 4 anggota yang ditambahkan.',
				'list_nim.*.nim.distinct' => 'Setiap anggota harus memiliki NIM yang unik.', // Pesan error kustom
				'list_nim.*.nim.required' => 'NIM wajib diisi untuk setiap anggota.',
			]);

			// IF NEW
			$usulan_pkm = new UsulanPkm;
			// $usulan_pkm->mhs_nim = UserHelp::mhs_get_logged_nim();
			$usulan_pkm->mhs_nim = $request->mhs_nim;
			$usulan_pkm->uuid = Uuid::generate();
			$usulan_pkm->kode_fakultas = UserHelp::get_selected_kode_fakultas();
			$usulan_pkm->status_usulan_id = StatusUsulan::where('keterangan', 'BARU')->first()->id;
		}

		$usulan_pkm->judul = mb_convert_encoding($request->judul, 'UTF-8', 'UTF-8');
		$usulan_pkm->kategori_kegiatan_id = $request->kategori_kegiatan_id;
		$usulan_pkm->jenis_pkm_id = $request->jenis_pkm_id;
		$usulan_pkm->tema_usulan_pkm_id = $request->tema_usulan_pkm_id;
		$usulan_pkm->pegawai_id = $request->pegawai_id; // UNTUK INSERT PEMBIMBING
		$usulan_pkm->mhs_email = $request->email;
		$usulan_pkm->mhs_no_telp = $request->telp;
		$usulan_pkm->tahun = date('Y');
		$usulan_pkm->created_by = UserHelp::admin_get_logged_nip();

		DB::beginTransaction();

		try {

			if (!empty(@$request->catatan_perbaikan)) {
				$usulan_pkm->status_usulan_id = StatusUsulan::where('keterangan', 'MENUNGGU')->first()->id;
				$perbaikan = new Perbaikan;
				$perbaikan->usulan_pkm_id = $usulan_pkm->id;
				$perbaikan->catatan_perbaikan = $request->catatan_perbaikan;
				// $perbaikan->mhs_nim = UserHelp::mhs_get_logged_nim();
				$perbaikan->mhs_nim = $request->mhs_nim;
				$perbaikan->save();
			}

			$usulan_pkm->save();

			if (empty($id)) {
				$anggota_pkm = new AnggotaPkm;
				// $anggota_pkm->mhs_nim = UserHelp::mhs_get_logged_nim();
				$anggota_pkm->mhs_nim = $request->mhs_nim;
				$anggota_pkm->sebagai = 0; // 0 : KETUA
				UsulanPkm::find($usulan_pkm->id)->anggota_pkm()->save($anggota_pkm);
			}

			// SET PEGAWAI YANG DIPILIH SEBAGAI PEMBIMBING
			$pegawai_roles = PegawaiRoles::where('pegawai_id', $request->pegawai_id)->where('roles_id', 3)->first();
			if (empty($pegawai_roles)) {
				$pegawai_roles = new PegawaiRoles();
				$pegawai_roles->pegawai_id = $request->pegawai_id;
				$pegawai_roles->roles_id = 3; // 'PEMBIMBING';
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
			// $files = $request->file('berkas');
			//define variable folder sebagai array
			// $folder = [];

			// if ((empty($id)) && (empty($files))) { // IF ADD MUST HAVE FILES
			// 	DB::rollback();
			// 	return redirect()->back()->withErrors(['Berkas upload is required']);
			// }

			if ($request->hasFile('berkas')) {
				foreach ($request->file('berkas') as $file) {

					if (!$file->isValid()) {
						dd([
							// 'index' => $index,
							'error' => $file->getError(), // kode error dari PHP
							'message' => $file->getErrorMessage(), // pesan error
						]);
					}
					//custom name masing2 file
					// $nama_file = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
					// $nama_file = str_replace(' ', '_', $nama_file);
					// $nama_file = str_replace('.', '_', $nama_file);
					// $filename = $usulan_pkm->uuid . '_file_' . $nama_file . '.' . $file->getClientOriginalExtension();
					// upload file
					// $folder[] = $file->storeAs('public/documents/', $filename);
					// $file->storeAs('public/documents/', $filename);

					$path = $file->store('documents', 'public');

					UsulanPkmDokumen::create([
						'usulan_pkm_id' => $usulan_pkm->id,
						'document_path' => $path
					]);
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
		$usulan_pkm = UsulanPkm::where('uuid', $uuid)->firstOrFail();
		if ($usulan_pkm->created_by != UserHelp::admin_get_logged_nip()) {
			return redirect()->back()->with('message', 'Dilarang mengubah usulan.');
		}
		$jenis_pkm = JenisPkm::all()->sortBy("id");
		$kategori_kegiatan_list = KategoriKegiatan::all()->sortBy("id");
		$mhs = $usulan_pkm->mhs;

		// foreach($usulan_pkm->anggota_pkm as $anggota){
		// 	dd($anggota->mhs->nim);
		// }

		// $files = Storage::files('public/documents');
		// $files_to_show = [];
		// foreach ($files as $file) {
		// 	$nama_file = str_replace('public/documents/', '', $file);
		// 	$nama_file_arr = explode('_file_', $nama_file);
		// 	if ($nama_file_arr[0] == $uuid) {
		// 		$files_to_show[] = $nama_file_arr[1];
		// 	}
		// }

		$files_to_show = $usulan_pkm->usulan_pkm_dokumen;

		// try {
		// 	$client = new Client();
		// 	$post_data = [
		// 		'auth' =>  json_encode(['user' => 'sempak', 'pass' => 'teles']),
		// 		'kode_fakultas' => $mhs->kode_fakultas,
		// 		'get' => json_encode(["kodeF", "nama_fak_ijazah"])
		// 	];
		// 	$req = new GuzzleRequest('POST', env('API_SIAP') . '/get_data_fakultas');
		// 	$res = $client->send($req, ['form_params' => $post_data]);
		// 	$return = json_decode($res->getBody()->getContents());
		// 	if (empty($return)) {
		// 		throw new BadResponseException('Data kosong.', $req);
		// 	}
		// } catch (BadResponseException $exception) {
		// 	abort(403, substr($exception->getMessage(), 0, 25));
		// }

		// $mhs->kode_fakultas = $return->nama_fak_ijazah;

		// try {
		// 	$client = new Client();
		// 	$post_data = [
		// 		'auth' =>  json_encode(['user' => 'sempak', 'pass' => 'teles']),
		// 		'kode_prodi' => $mhs->kode_prodi,
		// 		'get' => json_encode(["kode_prodi_pdpt", "nama_ps"])
		// 	];
		// 	$req = new GuzzleRequest('POST', env('API_SIAP') . '/get_data_prodi');
		// 	$res = $client->send($req, ['form_params' => $post_data]);
		// 	$return = json_decode($res->getBody()->getContents());
		// 	if (empty($return)) {
		// 		throw new BadResponseException('Data kosong.', $req);
		// 	}
		// } catch (BadResponseException $exception) {
		// 	abort(403, substr($exception->getMessage(), 0, 25));
		// }

		// $mhs->kode_prodi = $return->nama_ps;

		$this->_data['files_to_show'] = $files_to_show;
		$this->_data['usulan_pkm'] = $usulan_pkm;
		$this->_data['jenis_pkm'] = $jenis_pkm;
		$this->_data['kategori_kegiatan_list'] = $kategori_kegiatan_list;

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
		$id = $request->id;
		$file = $request->file;
		// $file = 'public/documents/' . $uuid . '_file_' . $file;
		$usulan_pkm_dokumen = UsulanPkmDokumen::where('usulan_pkm_id', $id)->where('document_path', $file)->firstOrFail();
		if ($usulan_pkm_dokumen->usulan_pkm->created_by != UserHelp::admin_get_logged_nip()) {
			return redirect()->back()->with('message', 'Dilarang mengubah usulan.');
		}

		if ($usulan_pkm_dokumen->usulan_pkm->status_usulan->keterangan != "BARU") {
			return redirect()->route('share.pendaftaran.list')->with('message', 'Dilarang menghapus usulan.');
		}

		$file = 'public/' . $file;
		Storage::delete($file);
		$usulan_pkm_dokumen->delete();
		return ['status' => 'ok'];
	}

	private function _hapus_anggota(Request $request)
	{
		$anggota_pkm = AnggotaPkm::findOrFail($request->id);
		if ($anggota_pkm->usulan_pkm->created_by != UserHelp::admin_get_logged_nip()) {
			return redirect()->back()->with('message', 'Dilarang mengubah usulan.');
		}

		if ($anggota_pkm->usulan_pkm->status_usulan->keterangan != "BARU") {
			return redirect()->route('share.pendaftaran.list')->with('message', 'Dilarang menghapus usulan.');
		}

		$anggota_pkm->delete();
		return ['status' => 'ok'];
	}

	private function _hapus_reviewer(Request $request)
	{
		if (UserHelp::get_selected_role() != "ADMIN") {
			return ['status' => 'error'];
		}

		$usulan_pkm = UsulanPkm::findOrFail($request->usulan_pkm_id);

		if ($usulan_pkm->status_usulan->keterangan != "BARU") {
			// if(!in_array($usulan_pkm->status_usulan->keterangan, ["BARU", "LANJUT"])){
			// return redirect()->route('share.pendaftaran.list')->with('message', 'Dilarang menghapus usulan.');
			if ($usulan_pkm->status_usulan->keterangan == "LANJUT") {
				if ($usulan_pkm->penilaian_reviewer->count() > 0) {
					return ['status' => 'error', 'message' => 'Dilarang menghapus usulan.'];
				}
			}
		}

		DB::beginTransaction();

		try {
			$reviewer_usulan_pkm = ReviewerUsulanPkm::where('reviewer_id', $request->reviewer_id)->where('usulan_pkm_id', $request->usulan_pkm_id);
			$reviewer_usulan_pkm->delete();
			// urutkan ulang reviewer
			$reviewer_usulan_pkm = ReviewerUsulanPkm::where('usulan_pkm_id', $request->usulan_pkm_id)->get();
			$urutan = 1;
			foreach ($reviewer_usulan_pkm as $reviewer) {
				$reviewer->urutan = $urutan;
				$reviewer->save();
				$urutan++;
			}
			DB::commit();
			return ['status' => 'ok'];
		} catch (\Exception $e) {
			DB::rollback();
			// return redirect()->back()->with('message', 'Terjadi kesalahan saat memproses: ' . $e->getMessage());
			return ['status' => 'error', 'message' => 'Terjadi kesalahan saat memproses: ' . $e->getMessage()];
		}
	}

	private function _ajukan(Request $request)
	{
		$usulan_pkm = UsulanPkm::findOrFail($request->id);
		if ($usulan_pkm->created_by != UserHelp::admin_get_logged_nip()) {
			return ['status' => 'error', 'message' => 'Dilarang mengajukan usulan.'];
		}
		if ($usulan_pkm->usulan_pkm_dokumen->count() == 0) {
			return ['status' => 'error', 'message' => 'Berkas usulan tidak boleh kosong.'];
		}
		// $usulan_pkm->status_usulan_id = StatusUsulan::where('keterangan', 'MENUNGGU')->first()->id;
		$usulan_pkm->status_usulan_id = StatusUsulan::where('keterangan', 'DISETUJUI')->first()->id;
		$usulan_pkm->save();
		$request->session()->flash('message', 'Usulan telah diajukan.');
		return ['status' => 'ok'];
	}

	private function _bulk_ajukan(Request $request)
	{
		$ids = $request->ids;
		$daftar_usulan_pkm = UsulanPkm::whereIn('id', $ids)->get();
		try {
			foreach ($daftar_usulan_pkm as $usulan_pkm) {
				if ($usulan_pkm->created_by != UserHelp::admin_get_logged_nip()) {
					throw new \Exception('Dilarang mengajukan usulan.');
				}
				if ($usulan_pkm->usulan_pkm_dokumen->count() == 0) {
					throw new \Exception('Berkas usulan tidak boleh kosong.');
				}
			}
			foreach ($daftar_usulan_pkm as $usulan_pkm) {
				// $usulan_pkm->status_usulan_id = StatusUsulan::where('keterangan', 'MENUNGGU')->first()->id;
				$usulan_pkm->status_usulan_id = StatusUsulan::where('keterangan', 'DISETUJUI')->first()->id;
				$usulan_pkm->save();
			}
			$request->session()->flash('message', 'Usulan telah diajukan.');
			return ['status' => 'ok'];
		} catch (\Exception $e) {
			return ['status' => 'error', 'message' => $e->getMessage()];
		}
	}

	private function _get_jenis_pkm(Request $request)
	{
		$tahun = date('Y');
		$usulan_pkm_id = $request->usulan_pkm_id;
		$usulan_pkm_selected = null;
		if ($usulan_pkm_id) {
			$kamar_taken = JenisPkm::whereHas('usulan_pkm', function (Builder $query) use ($request, $tahun) {
				$query->where('mhs_nim', $request->mhs_nim)
					->where('tahun', $tahun);
			})->where('kategori_kegiatan_id', $request->kategori_kegiatan_id)
				->distinct('kamar') // Membuat hasil distinct berdasarkan kolom 'kamar'
				->pluck('kamar');
			$usulan_pkm_selected = UsulanPkm::where('id', $usulan_pkm_id)->firstOrFail();
		} else {
			$kamar_taken = JenisPkm::whereHas('usulan_pkm', function (Builder $query) use ($request, $tahun) {
				$query->where('mhs_nim', $request->mhs_nim)
					->where('tahun', $tahun);
			})->where('kategori_kegiatan_id', $request->kategori_kegiatan_id)
				->distinct('kamar') // Membuat hasil distinct berdasarkan kolom 'kamar'
				->pluck('kamar');
		}
		$jenis_pkm_list = JenisPkm::where('kategori_kegiatan_id', $request->kategori_kegiatan_id)
			->whereNotIn('kamar', $kamar_taken)
			->get();

		if (!empty($usulan_pkm_selected)) {
			if ($usulan_pkm_selected->jenis_pkm->kategori_kegiatan_id == $request->kategori_kegiatan_id) {
				$jenis_pkm_list = $jenis_pkm_list instanceof \Illuminate\Support\Collection
					? $jenis_pkm_list
					: collect($jenis_pkm_list);

				// Tambahkan $jenis_pkm ke dalam $jenis_pkm_list
				$jenis_pkm_list = $jenis_pkm_list->merge([$usulan_pkm_selected->jenis_pkm]);

				// Hapus item duplikat jika diperlukan
				$jenis_pkm_list = $jenis_pkm_list->unique('id');
			}
		}
		$this->_data['jenis_pkm_list'] = $jenis_pkm_list;

		return $this->_data;
	}

	public function list(Request $request)
	{
		// $usulan_pkm = UsulanPkm::all()->sortBy("judul");
		$tahun = $request->input('tahun', date('Y'));
		$perPage = $request->input('per_page', 15); // Default 15 items per page
		$search = $request->input('search');

		$query = null;

		if (UserHelp::get_selected_role() == "SUPER") {
			$query = UsulanPkm::where('tahun', $tahun);
		}

		if (UserHelp::get_selected_role() == "MHS") {
			$query = UsulanPkm::where('tahun', $tahun)->where('mhs_nim', UserHelp::mhs_get_logged_nim());
		}
		if (UserHelp::get_selected_role() == "PEMBIMBING") {
			$pembimbing = UserHelp::admin_get_record_by_nip(UserHelp::admin_get_logged_nip());
			$query = UsulanPkm::where('tahun', $tahun)
				->where('pegawai_id', $pembimbing->id)
				->whereHas('status_usulan', function (Builder $query) {
					$query->where('urutan', '>', 0);
				});
		}
		if (UserHelp::get_selected_role() == "ADMINFAKULTAS") {
			$kode_fakultas = UserHelp::get_selected_kode_fakultas();
			$query = UsulanPkm::where('kode_fakultas', $kode_fakultas)
				->where('tahun', $tahun);
		}
		if (UserHelp::get_selected_role() == "WD1") {
			$kode_fakultas = UserHelp::get_selected_kode_fakultas();
			$query = UsulanPkm::whereHas('status_usulan', function (Builder $query) {
				$query->where('urutan', '>', 1);
			})
				->where('kode_fakultas', $kode_fakultas)
				->where('tahun', $tahun);
		}
		if (UserHelp::get_selected_role() == "ADMIN") {
			// $usulan_pkm = UsulanPkm::where('status_usulan_id',StatusUsulan::where('keterangan','DISETUJUI')->first()->id)
			$query = UsulanPkm::where('tahun', $tahun)
				->whereHas('status_usulan', function (Builder $query) {
					$query->where('urutan', '>', 2);
				});
			if (!empty($request->jenis)) {
				if ($request->jenis == 'pembimbing') {
					$query = UsulanPkm::where('tahun', $tahun)->where('pegawai_id', $request->pegawai_id);
				}
				if ($request->jenis == 'reviewer') {
					$query = UsulanPkm::where('tahun', $tahun)
						->wherehas('reviewer', function (Builder $query) use ($request) {
							$query->where('reviewer.id', $request->pegawai_id);
						});
				}
			}
		}
		if (UserHelp::get_selected_role() == "REVIEWER") {
			$reviewer = UserHelp::admin_get_record_by_nip(UserHelp::admin_get_logged_nip());
			$query = UsulanPkm::where('tahun', $tahun)
				->wherehas('reviewer', function (Builder $query) use ($reviewer) {
					$query->where('reviewer.id', $reviewer->id);
				});
		}

		// Apply search filter if search term exists (only search in judul, NIM, and nama mahasiswa)
		if (!empty($search)) {
			if ($query) {
				$query->where(function ($q) use ($search) {
					$q->where('judul', 'like', '%' . $search . '%')
						->orWhereHas('mhs', function ($q) use ($search) {
							$q->where('nim', 'like', '%' . $search . '%')
								->orWhere('nama', 'like', '%' . $search . '%');
						});
				});
			} else {
				// If no role query but has search, create base query
				$query = UsulanPkm::where('tahun', $tahun)
					->where(function ($q) use ($search) {
						$q->where('judul', 'like', '%' . $search . '%')
							->orWhereHas('mhs', function ($q) use ($search) {
								$q->where('nim', 'like', '%' . $search . '%')
									->orWhere('nama', 'like', '%' . $search . '%');
							});
					});
			}
		}

		// Apply eager loading and pagination if query exists
		if ($query) {
			$usulan_pkm = $query->with([
				'mhs:nim,nama',
				'jenis_pkm:id,nama_pkm,kategori_kegiatan_id',
				'jenis_pkm.kategori_kegiatan:id,nama_kategori_kegiatan',
				'tema_usulan_pkm:id,nama_tema',
				'pegawai:id,nama,glr_dpn,glr_blkg',
				'status_usulan:id,keterangan,urutan',
				'penilaian_reviewer:id,usulan_pkm_id,reviewer_id',
				'reviewer_usulan_pkm:id,usulan_pkm_id,reviewer_id'
			])
				->orderBy('created_at', 'desc')
				->paginate($perPage)
				->appends($request->except('page'));
		} else {
			// Fallback if no role matches
			$usulan_pkm = UsulanPkm::where('tahun', $tahun)
				->with([
					'mhs:nim,nama',
					'jenis_pkm:id,nama_pkm,kategori_kegiatan_id',
					'jenis_pkm.kategori_kegiatan:id,nama_kategori_kegiatan',
					'tema_usulan_pkm:id,nama_tema',
					'pegawai:id,nama,glr_dpn,glr_blkg',
					'status_usulan:id,keterangan,urutan',
					'penilaian_reviewer:id,usulan_pkm_id,reviewer_id',
					'reviewer_usulan_pkm:id,usulan_pkm_id,reviewer_id'
				])
				->orderBy('created_at', 'desc')
				->paginate($perPage)
				->appends($request->except('page'));
		}

		if (UserHelp::is_admin()) {
			$pegawai = UserHelp::admin_get_record_by_nip(UserHelp::admin_get_logged_nip());
			$this->_data['pegawai'] = $pegawai;
		}

		$this->_data['usulan_pkm'] = $usulan_pkm;
		$this->_data['tahun'] = $tahun;

		// Get list of available years from database
		$this->_data['tahun_list'] = UsulanPkm::select('tahun')
			->distinct()
			->orderBy('tahun', 'desc')
			->pluck('tahun')
			->toArray();

		// If current year is not in the list, add it
		if (!in_array($tahun, $this->_data['tahun_list'])) {
			$this->_data['tahun_list'][] = $tahun;
			rsort($this->_data['tahun_list']);
		}

		return view('pendaftaran.list', $this->_data);
	}

	public function add()
	{
		return redirect()->route('share.pendaftaran.list')->with('message', 'Fitur ini ditutup.');
		$jenis_pkm = JenisPkm::all()->sortBy("id");
		$kategori_kegiatan_list = KategoriKegiatan::all()->sortBy("id");
		$status_usulan = StatusUsulan::all()->sortBy("id");
		$mhs = Mhs::findOrFail(UserHelp::mhs_get_logged_nim());

		// try {
		// 	$client = new Client(['verify' => false]);
		// 	$post_data = [
		// 		'auth' =>  json_encode(['user' => 'sempak', 'pass' => 'teles']),
		// 		'kode_fakultas' => $mhs->kode_fakultas,
		// 		'get' => json_encode(["kodeF", "nama_fak_ijazah"])
		// 	];
		// 	$req = new GuzzleRequest('POST', env('API_SIAP') . '/get_data_fakultas');
		// 	$res = $client->send($req, ['form_params' => $post_data]);
		// 	$return = json_decode($res->getBody()->getContents());
		// 	if (empty($return)) {
		// 		throw new BadResponseException('Data kosong.', $req);
		// 	}
		// } catch (BadResponseException $exception) {
		// 	//	        dd($exception->getMessage());
		// 	abort(403, substr($exception->getMessage(), 0, 25));
		// }

		// $mhs->kode_fakultas = $return->nama_fak_ijazah;

		// try {
		// 	$client = new Client();
		// 	$post_data = [
		// 		'auth' =>  json_encode(['user' => 'sempak', 'pass' => 'teles']),
		// 		'kode_prodi' => $mhs->kode_prodi,
		// 		'get' => json_encode(["kode_prodi_pdpt", "nama_ps"])
		// 	];
		// 	$req = new GuzzleRequest('POST', env('API_SIAP') . '/get_data_prodi');
		// 	$res = $client->send($req, ['form_params' => $post_data]);
		// 	$return = json_decode($res->getBody()->getContents());
		// 	if (empty($return)) {
		// 		throw new BadResponseException('Data kosong.', $req);
		// 	}
		// } catch (BadResponseException $exception) {
		// 	//	        dd($exception->getMessage());
		// 	abort(403, substr($exception->getMessage(), 0, 25));
		// }

		// $mhs->kode_prodi = $return->nama_ps;

		$this->_data['jenis_pkm'] = $jenis_pkm;
		$this->_data['kategori_kegiatan_list'] = $kategori_kegiatan_list;
		$this->_data['status_usulan'] = $status_usulan;
		$this->_data['mhs'] = $mhs;
		return view('pendaftaran.form', $this->_data);
	}

	public function create(Request $request, Mhs $mhs)
	{

		$setting = Setting::where('status_aplikasi', '1')->first();

		if (empty($setting)) {
			return view('pages.closed');
		}

		$jenis_pkm = JenisPkm::all()->sortBy("id");
		$tema_usulan_pkm_list = TemaUsulanPkm::all()->sortBy("id");
		$kategori_kegiatan_list = KategoriKegiatan::all()->sortBy("id");
		$status_usulan = StatusUsulan::all()->sortBy("id");
		$nim = $request->input('nim');
		if (!empty($nim)) {
			$mhs = Mhs::findOrFail($nim);
		}

		// try {
		// 	$client = new Client(['verify' => false]);
		// 	$post_data = [
		// 		'auth' =>  json_encode(['user' => 'sempak', 'pass' => 'teles']),
		// 		'kode_fakultas' => $mhs->kode_fakultas,
		// 		'get' => json_encode(["kodeF", "nama_fak_ijazah"])
		// 	];
		// 	$req = new GuzzleRequest('POST', env('API_SIAP') . '/get_data_fakultas');
		// 	$res = $client->send($req, ['form_params' => $post_data]);
		// 	$return = json_decode($res->getBody()->getContents());
		// 	if (empty($return)) {
		// 		throw new BadResponseException('Data kosong.', $req);
		// 	}
		// } catch (BadResponseException $exception) {
		// 	//	        dd($exception->getMessage());
		// 	abort(403, substr($exception->getMessage(), 0, 25));
		// }

		// $mhs->kode_fakultas = $return->nama_fak_ijazah;

		// try {
		// 	$client = new Client();
		// 	$post_data = [
		// 		'auth' =>  json_encode(['user' => 'sempak', 'pass' => 'teles']),
		// 		'kode_prodi' => $mhs->kode_prodi,
		// 		'get' => json_encode(["kode_prodi_pdpt", "nama_ps"])
		// 	];
		// 	$req = new GuzzleRequest('POST', env('API_SIAP') . '/get_data_prodi');
		// 	$res = $client->send($req, ['form_params' => $post_data]);
		// 	$return = json_decode($res->getBody()->getContents());
		// 	if (empty($return)) {
		// 		throw new BadResponseException('Data kosong.', $req);
		// 	}
		// } catch (BadResponseException $exception) {
		// 	//	        dd($exception->getMessage());
		// 	abort(403, substr($exception->getMessage(), 0, 25));
		// }

		// $mhs->kode_prodi = $return->nama_ps;

		$this->_data['jenis_pkm'] = $jenis_pkm;
		$this->_data['tema_usulan_pkm_list'] = $tema_usulan_pkm_list;
		$this->_data['kategori_kegiatan_list'] = $kategori_kegiatan_list;
		$this->_data['status_usulan'] = $status_usulan;
		$this->_data['mhs'] = $mhs;
		return view('pendaftaran.create', $this->_data);
	}

	public function hapus($uuid)
	{
		$usulan_pkm = UsulanPkm::where('uuid', $uuid)->firstOrFail();
		if ($usulan_pkm->created_by != UserHelp::admin_get_logged_nip()) {
			return redirect()->route('share.pendaftaran.list')->with('message', 'Dilarang menghapus usulan.');
		}

		if ($usulan_pkm->status_usulan->keterangan != "BARU") {
			return redirect()->route('share.pendaftaran.list')->with('message', 'Dilarang menghapus usulan.');
		}

		if ($usulan_pkm->usulan_pkm_dokumen->count() > 0) {
			foreach ($usulan_pkm->usulan_pkm_dokumen as $usulan_pkm_dokumen) {
				$file = 'public/' . $usulan_pkm_dokumen->document_path;
				Storage::delete($file);
				$usulan_pkm_dokumen->delete();
			}
		}

		// foreach ($usulan_pkm->usulan_pkm_dokumen as $usulan_pkm_dokumen) {
		// 	$nama_file = str_replace('public/documents/', '', $file);
		// 	$nama_file_arr = explode('_file_', $nama_file);
		// 	if ($nama_file_arr[0] == $uuid) {
		// 		$file_to_delete = 'public/documents/' . $uuid . '_file_' . $nama_file_arr[1];
		// 		Storage::delete($file_to_delete);
		// 	}
		// }

		$usulan_pkm->delete();

		return redirect()->route('share.pendaftaran.list')->with('message', 'Data berhasil di-hapus.');
	}

	public function forcedelete($uuid)
	{
		DB::transaction(function () use ($uuid) {
			$usulan_pkm = UsulanPkm::where('uuid', $uuid)->with('revisi', 'penilaian_reviewer', 'review', 'perbaikan', 'reviewer_usulan_pkm', 'usulan_pkm_dokumen')->firstOrFail();

			$usulan_pkm->revisi()->delete();
			$usulan_pkm->penilaian_reviewer()->delete();
			$usulan_pkm->review()->delete();
			$usulan_pkm->perbaikan()->delete();
			$usulan_pkm->reviewer_usulan_pkm()->delete();

			if ($usulan_pkm->usulan_pkm_dokumen->count() > 0) {
				foreach ($usulan_pkm->usulan_pkm_dokumen as $usulan_pkm_dokumen) {
					$file = 'public/' . $usulan_pkm_dokumen->document_path;
					Storage::delete($file);
					$usulan_pkm_dokumen->delete();
				}
			}
			$usulan_pkm->delete();
		});



		return redirect()->route('share.pendaftaran.list')->with('message', 'Data berhasil di-hapus.');
	}

	public function read(Request $request, $uuid)
	{
		$id = UsulanPkm::where('uuid', $uuid)->first()->id;
		$usulan_pkm = UsulanPkm::findOrFail($id);
		$kategori_kegiatan_list = KategoriKegiatan::all()->sortBy("id");
		$jenis_pkm = JenisPkm::all()->sortBy("id");
		$tema_usulan_pkm_list = TemaUsulanPkm::all()->sortBy("id");
		$status_usulan = StatusUsulan::all()->sortBy("id");
		$pegawai = UserHelp::admin_get_record_by_nip(UserHelp::admin_get_logged_nip());
		// $mhs = Mhs::findOrFail(UserHelp::mhs_get_logged_nim());

		$mhs = $usulan_pkm->anggota_pkm()->where('sebagai', 0)->first()->mhs;

		// foreach($usulan_pkm->anggota_pkm as $anggota){
		// dd($usulan_pkm->reviewer_usulan_pkm->where('reviewer_id',27)[0]->id);
		// }

		// $files = Storage::files('public/documents');
		// $files_to_show = [];
		// foreach ($files as $file) {
		// 	$nama_file = str_replace('public/documents/', '', $file);
		// 	$nama_file_arr = explode('_file_', $nama_file);
		// 	if ($nama_file_arr[0] == $uuid) {
		// 		$files_to_show[] = $nama_file_arr[1];
		// 	}
		// }

		$files_to_show = $usulan_pkm->usulan_pkm_dokumen;

		// try {
		// 	$client = new Client();
		// 	$post_data = [
		// 		'auth' =>  json_encode(['user' => 'sempak', 'pass' => 'teles']),
		// 		'kode_fakultas' => $mhs->kode_fakultas,
		// 		'get' => json_encode(["kodeF", "nama_fak_ijazah"])
		// 	];
		// 	$req = new GuzzleRequest('POST', env('API_SIAP') . '/get_data_fakultas');
		// 	$res = $client->send($req, ['form_params' => $post_data]);
		// 	$return = json_decode($res->getBody()->getContents());
		// 	if (empty($return)) {
		// 		throw new BadResponseException('Data kosong.', $req);
		// 	}
		// } catch (BadResponseException $exception) {
		// 	//	        dd($exception->getMessage());
		// 	abort(403, substr($exception->getMessage(), 0, 25));
		// }

		// $mhs->kode_fakultas = $return->nama_fak_ijazah;

		// try {
		// 	$client = new Client();
		// 	$post_data = [
		// 		'auth' =>  json_encode(['user' => 'sempak', 'pass' => 'teles']),
		// 		'kode_prodi' => $mhs->kode_prodi,
		// 		'get' => json_encode(["kode_prodi_pdpt", "nama_ps"])
		// 	];
		// 	$req = new GuzzleRequest('POST', env('API_SIAP') . '/get_data_prodi');
		// 	$res = $client->send($req, ['form_params' => $post_data]);
		// 	$return = json_decode($res->getBody()->getContents());
		// 	if (empty($return)) {
		// 		throw new BadResponseException('Data kosong.', $req);
		// 	}
		// } catch (BadResponseException $exception) {
		// 	//	        dd($exception->getMessage());
		// 	abort(403, substr($exception->getMessage(), 0, 25));
		// }

		// $mhs->kode_prodi = $return->nama_ps;

		$this->_data['files_to_show'] = $files_to_show;
		$this->_data['usulan_pkm'] = $usulan_pkm;
		$this->_data['kategori_kegiatan_list'] = $kategori_kegiatan_list;
		$this->_data['jenis_pkm'] = $jenis_pkm;
		$this->_data['tema_usulan_pkm_list'] = $tema_usulan_pkm_list;
		$this->_data['status_usulan'] = $status_usulan;
		$this->_data['mhs'] = $mhs;
		$this->_data['pegawai'] = $pegawai;

		return view('pendaftaran.read', $this->_data);
	}

	public function approval(Request $request, $uuid)
	{
		$usulan_pkm = UsulanPkm::where('uuid', $uuid)->firstOrFail();

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

		if (UserHelp::get_selected_role() == "ADMIN") {
			if ($request->approval == "SUDAH_DINILAI") {
				$nilai_total = 0;
				$penilaian_reviewer = PenilaianReviewer::where('usulan_pkm_id', $usulan_pkm->id)->get();
				$jml_reviewer = ReviewerUsulanPkm::where('usulan_pkm_id', $usulan_pkm->id)->count();
				foreach ($penilaian_reviewer as $penilaian) {
					$nilai_total += $penilaian->nilai;
				}
				$nilai_total = $nilai_total / $jml_reviewer;
				$usulan_pkm->nilai_total = $nilai_total;
			}
		}

		$usulan_pkm->status_usulan_id = $status_usulan->id;
		$usulan_pkm->save();

		return redirect()->back()->with('message', 'Data berhasil di-simpan.');
	}

	private function _bulk_approval(Request $request)
	{

		$ids = $request->ids;
		$daftar_usulan_pkm = UsulanPkm::whereIn('id', $ids)->get();
		$status_usulan = StatusUsulan::where('keterangan', $request->approval)->firstOrFail();

		try {
			foreach ($daftar_usulan_pkm as $usulan_pkm) {
				$usulan_pkm->status_usulan_id = $status_usulan->id;
				$usulan_pkm->save();
			}
			$request->session()->flash('message', 'Data berhasil di-simpan.');
			return ['status' => 'ok'];
		} catch (\Exception $e) {
			return ['status' => 'error', 'message' => $e->getMessage()];
		}
	}

	private function _bulk_tetapkan_nilai(Request $request)
	{

		$ids = $request->ids;
		$daftar_usulan_pkm = UsulanPkm::whereIn('id', $ids)->get();
		$status_usulan = StatusUsulan::where('keterangan', $request->approval)->firstOrFail();

		try {
			foreach ($daftar_usulan_pkm as $usulan_pkm) {
				$usulan_pkm->status_usulan_id = $status_usulan->id;

				if ($request->approval == "SUDAH_DINILAI") {
					$nilai_total = 0;
					$penilaian_reviewer = PenilaianReviewer::where('usulan_pkm_id', $usulan_pkm->id)->get();
					$jml_reviewer = ReviewerUsulanPkm::where('usulan_pkm_id', $usulan_pkm->id)->count();
					foreach ($penilaian_reviewer as $penilaian) {
						$nilai_total += $penilaian->nilai;
					}
					$nilai_total = $nilai_total / $jml_reviewer;
					$usulan_pkm->nilai_total = $nilai_total;
				}

				$usulan_pkm->save();
			}
			$request->session()->flash('message', 'Data berhasil di-simpan.');
			return ['status' => 'ok'];
		} catch (\Exception $e) {
			return ['status' => 'error', 'message' => $e->getMessage()];
		}
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
				$max_urutan = ReviewerUsulanPkm::where('usulan_pkm_id', $usulan_pkm->id)->max('urutan');
				foreach ($list_id as $urutan => $id) {
					$reviewer_usulan_pkm = new ReviewerUsulanPkm;
					$reviewer_usulan_pkm->usulan_pkm_id = $usulan_pkm->id;
					$reviewer_usulan_pkm->reviewer_id = $id;
					$reviewer_usulan_pkm->urutan = $max_urutan + 1 + $urutan;
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

	public function report(Request $request)
	{
		// Gunakan tahun yang dipilih di dashboard/list, default ke tahun berjalan
		$tahun = $request->input('tahun', date('Y'));

		if (UserHelp::get_selected_role() == 'ADMINFAKULTAS' || UserHelp::get_selected_role() == 'WD1') {
			$kode_fakultas = UserHelp::get_selected_kode_fakultas();
			$jenis_pkm = JenisPkm::whereHas('usulan_pkm', function ($query) use ($tahun, $kode_fakultas) {
				$query->where('tahun', $tahun)->where('kode_fakultas', $kode_fakultas);
			})->orderBy('kategori_kegiatan_id')->get();
		} else {
			$jenis_pkm = JenisPkm::whereHas('usulan_pkm', function ($query) use ($tahun) {
				$query->where('tahun', $tahun);
			})->orderBy('kategori_kegiatan_id')->get();
		}

		$usulan_pkm_list = collect(); // Inisialisasi koleksi kosong

		foreach ($jenis_pkm as $jenis) { // Gunakan variabel berbeda untuk elemen loop
			if (UserHelp::get_selected_role() == 'ADMINFAKULTAS' || UserHelp::get_selected_role() == 'WD1') {
				$usulan_data = UsulanPkm::where('jenis_pkm_id', $jenis->id)
					->where('tahun', $tahun)
					->with(['anggota_pkm.mhs', 'usulan_pkm_dokumen'])
					->where('kode_fakultas', $kode_fakultas)
					->orderBy('created_at')
					->get();
			} else {
				$usulan_data = UsulanPkm::where('jenis_pkm_id', $jenis->id)
					->where('tahun', $tahun)
					->with(['anggota_pkm.mhs', 'usulan_pkm_dokumen'])
					->orderBy('created_at')
					->get();
			}

			$usulan_pkm_list = $usulan_pkm_list->merge($usulan_data); // Gabungkan data ke dalam koleksi utama
		}

		$this->_data['usulan_pkm_list'] = $usulan_pkm_list;
		$this->_data['tahun'] = $tahun;

		return view('pendaftaran.report', $this->_data);
	}
}
