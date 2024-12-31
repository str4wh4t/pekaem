<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PagesController@index')->name('home');
Route::get('login', 'PagesController@login')->name('login');
Route::get('dashboard', 'PagesController@dashboard')->name('dashboard');
Route::post('login/ajax/{method}', 'PagesController@ajax')->name('login.ajax');

// Route::get('/pendaftaran', function () {
//     return view('pendaftaran.list');
// });

// Route::get('/pendaftaran/form', function () {

//     return view('pendaftaran.form');
// });

Route::get('mhs/pendaftaran/add', 'PendaftaranController@add')->name('mhs.pendaftaran.add');
Route::get('mhs/pendaftaran/edit/{uuid}', 'PendaftaranController@edit')->name('mhs.pendaftaran.edit');
Route::get('mhs/pendaftaran/hapus/{uuid}', 'PendaftaranController@hapus')->name('mhs.pendaftaran.hapus');
Route::post('mhs/pendaftaran/simpan', 'PendaftaranController@simpan')->name('mhs.pendaftaran.simpan');
Route::post('mhs/users/ajax/{method}', 'AdminController@ajax')->name('mhs.users.ajax');

Route::get('admin/users/list', 'AdminController@list')->name('admin.users.list');
Route::get('super/users/roles', 'AdminController@roles')->name('admin.users.roles');
Route::get('super/users/roles/hapus/{id}', 'AdminController@hapus_role')->name('admin.users.roles.hapus');
Route::post('admin/users/asign', 'AdminController@asign')->name('admin.users.asign');
Route::post('admin/users/ajax/{method}', 'AdminController@ajax')->name('admin.users.ajax');
Route::get('admin/choose_role', 'PagesController@choose_role')->name('admin.choose_role');
Route::post('admin/pendaftaran/approval/{uuid}', 'PendaftaranController@approval')->name('admin.pendaftaran.approval');
Route::post('admin/pendaftaran/set/reviewer/{uuid}', 'PendaftaranController@set_reviewer')->name('admin.pendaftaran.set.reviewer');
Route::get('admin/pendaftaran/ploting/reviewer', 'PendaftaranController@ploting_reviewer')->name('admin.pendaftaran.ploting.reviewer');
Route::get('admin/pendaftaran/ploting/list', 'PendaftaranController@ploting_reviewer')->name('admin.pendaftaran.ploting.list');
Route::get('admin/pembimbing/list', 'AdminController@list_pembimbing')->name('admin.pembimbing.list');
Route::get('admin/reviewer/list', 'AdminController@list_reviewer')->name('admin.reviewer.list');

Route::get('sso/login', 'SsoController@login')->name('sso.login');
Route::get('sso/logout', 'SsoController@logout')->name('sso.logout');
Route::get('sso/authen', 'SsoController@authen')->name('sso.authen');
Route::get('sso/tes/{nip}', 'SsoController@tes')->name('sso.tes');

Route::get('share/pendaftaran', 'PendaftaranController@list')->name('share.pendaftaran');
Route::get('share/pendaftaran/list/{jenis?}/{pegawai_id?}', 'PendaftaranController@list')->name('share.pendaftaran.list');
Route::get('share/pendaftaran/read/{uuid}', 'PendaftaranController@read')->name('share.pendaftaran.read');
Route::post('share/pendaftaran/ajax/{method}', 'PendaftaranController@ajax')->name('share.pendaftaran.ajax');

Route::prefix('admin')->group(function () {
    Route::resource('/kategori-kriteria', 'KategoriKriteriaController');
});

Route::prefix('admin/kategori-kriteria/{kategori_kriteria}')->group(function () {
    Route::resource('kriteria-penilaian', 'KriteriaPenilaianController');
});

Route::prefix('admin')->group(function () {
    Route::resource('/kategori-kegiatan', 'KategoriKegiatanController');
});

Route::prefix('admin/kategori-kegiatan/{kategori_kegiatan}')->group(function () {
    Route::resource('jenis-pkm', 'JenisPkmController');
});

Route::prefix('admin/usulan-pkm/{usulan_pkm}')->group(function () {
    Route::resource('penilaian-reviewer', 'PenilaianReviewerController');
});

// Route::resource('sso','SsoController');
// Route::resource('crud','CrudsController');
