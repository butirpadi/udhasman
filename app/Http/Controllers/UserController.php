<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use App\Permission;

class UserController extends Controller
{
	public function index(){
		$data = \DB::table('view_users')
					->where('hide','N')
					->orderBy('created_at','desc')
					->get();

		$roles = Role::get();
		$selectRole = [];
		foreach($roles as $rl){
			$selectRole[$rl->id] = $rl->description;
		}

		$permissions = Permission::get();

		return view('user.index',[
				'data' => $data,
				'roles' => $selectRole,
				'permissions' => $permissions,
			]);
	}

	public function create(){
		$roles = \DB::table('roles')
				->get();
		$selectRole = [];
		foreach($roles as $rl){
			$selectRole[$rl->id] = $rl->description;
		}
		return view('user.create',[
			'role' => $selectRole
		]);
	}

	public function insert(Request $req){
		return \DB::transaction(function()use($req){
			$user = new User();
			$user->username = $req->username;
			$user->password = bcrypt($req->password);
			$user->save();
			
			// update user role
			\DB::table('user_role')
				->insert([
					'user_id' => $user->id,
					'role_id' => $req->role
				]);

			return redirect('setting/user');
			
		});
	}

	public function register(){
		$user = new User();
		$user->username = 'eries';
		$user->password = bcrypt('herman');
		$user->save();
		echo 'register user done';
	}

	public function delete(Request $req){
		$dataid = json_decode($req->dataid);
		return \db::transaction(function()use($dataid){
			// delete dari database
			foreach($dataid as $dt){
				\DB::table('users')->delete($dt->id);
			}

			return redirect('setting/user');

		});
	}

	public function getRolePermission($id){
		$role = Role::find($id);
		return json_encode($role->permissions);
	}

	public function updateRolePermissions(Request $req){
		return \DB::transaction(function()use($req){
			\DB::table('role_permission')
				->where('role_id',$req->role_id)
				->delete();

			// insert update permissions
			$permissions = json_decode($req->permissions);
			foreach($permissions as $dt){
				\DB::table('role_permission')
					->insert([
						'role_id' => $req->role_id,
						'permission_id' => $dt->id
					]);
			}

			return redirect()->back();
		});
	}

}
