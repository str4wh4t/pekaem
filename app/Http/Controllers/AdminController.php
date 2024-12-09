<?php

namespace App\Http\Controllers;

use App\PegawaiRoles;
use App\Roles;
use App\Pegawai;
use App\Reviewer;
use App\Mhs;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //
    public function list()
    {
    	// $pegawai_roles = PegawaiRoles::all()->sortBy("pegawai_id");
        $roles = Roles::all()->where('id', '!=' , 1)->sortBy("id"); /// EXCEPT SUPER
        $pegawai_has_role = Pegawai::has('roles')->get();
    	// dd($pegawai_has_role);
        $this->_data['roles'] = $roles ;
	    $this->_data['pegawai_has_role'] = $pegawai_has_role ;
	    // $this->_data['pegawai'] = $pegawai ;
    	return view('admin.list',$this->_data);
    }

    private function _asign(Request $request)
    {
    	$pegawai_roles = new PegawaiRoles;
    	$pegawai_roles->pegawai_id = $request->id;
    	$pegawai_roles->roles_id = $request->role;
    	$pegawai_roles->status_role = '1' ; // OTOMATIS AKTIF
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

	public function ajax(Request $request,$method)
    {
    	if(method_exists($this, '_'.$method)){
    		return $this->{'_'.$method}($request);
    	}
    }

    private function _cari(Request $request){
    	// dd($request->role);
    	$pegawai = Pegawai::where(function($query)use($request){
                            $query->where('nip', 'LIKE', '%' . $request->text . '%')
                                  ->orWhere('nama', 'LIKE', '%' . $request->text . '%');
                            })
                            ->where('status','1') // HANYA AKTIF
                            ->whereDoesntHave('roles', function (Builder $query) use($request){
                                $query->where('roles.id',$request->role );
                            })
    						->get(['id',DB::raw('CONCAT(glr_dpn," ",nama," ",glr_blkg) as text')]);

    	$this->_data['items'] = $pegawai;

        return $this->_data;
    }

    private function _cari_pembimbing(Request $request){
        // dd($request->role);
        $pembimbing = Pegawai::where(function($query)use($request){
                            $query->where('nip', 'LIKE', '%' . $request->text . '%')
                                  ->orWhere('nama', 'LIKE', '%' . $request->text . '%');
                            })
                            ->where('jnspeg', '1') // HANYA DOSEN
                            ->where('status','1') // HANYA AKTIF
                            ->whereHas('roles', function (Builder $query) use($request){
                                $query->where('roles.role', 'PEMBIMBING');
                            })
                            ->get(['id',DB::raw('CONCAT(glr_dpn," ",nama," ",glr_blkg," ","[",nip,"]") as text')]);

        $this->_data['items'] = $pembimbing;

        return $this->_data;
    }

    private function _cari_reviewer(Request $request){
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
        $reviewer = Reviewer::where(function($query)use($request){
                                $query->where('nip', 'LIKE', '%' . $request->text . '%')
                                  ->orWhere('nama', 'LIKE', '%' . $request->text . '%');
                                })
                                ->get([
                                   'id',
                                   DB::raw('CONCAT(glr_dpn," ",nama," ",glr_blkg," ","[",nip,"]") as text'),
                               ]);

        if(!empty($request->usulan_pkm_id)){
            $reviewer = Reviewer::where(function($query)use($request){
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

    private function _cari_mhs(Request $request){
        // dd($request->role);

        $mhs = Mhs::where(function($query)use($request){
                            $query->where('nim','LIKE','%'. $request->text .'%')
                                  ->orWhere('nama','LIKE','%'. $request->text .'%');
                        })
                        ->where('status_terakhir','Aktif')
                        ->get(['nim AS id',DB::raw('CONCAT(nama," ","[",nim,"]") as text')]);

        $this->_data['items'] = $mhs;

        return $this->_data;
    }

    public function hapus_role($id){
    	$roles = Roles::findOrFail($id);
    	$roles->delete();

    	 return redirect()->back()->with('message', 'Data berhasil di-hapus.');

    }


    //
    public function roles()
    {
        $roles = Roles::all()->sortBy("id");
        $this->_data['roles'] = $roles ;
        return view('admin.roles',$this->_data);
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
    	// $pegawai_roles = PegawaiRoles::all()->sortBy("pegawai_id");
        $pembimbing = Pegawai::where('jnspeg', '1') // HANYA DOSEN
                                ->where('status','1') // HANYA AKTIF
                                ->whereHas('roles', function (Builder $query){
                                    $query->where('roles.role', 'PEMBIMBING');
                                })
                                ->get();
//        dd($pembimbing);
	    $this->_data['pembimbing'] = $pembimbing ;

    	return view('admin.list_pembimbing',$this->_data);
    }

    public function list_reviewer()
    {
    	// $pegawai_roles = PegawaiRoles::all()->sortBy("pegawai_id");
        $reviewer = Reviewer::whereHas('usulan_pkm')
                                ->get();

	    $this->_data['reviewer'] = $reviewer ;

    	return view('admin.list_reviewer',$this->_data);
    }


}
