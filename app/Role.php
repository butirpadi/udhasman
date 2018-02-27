<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	protected $table = 'roles';

	public function users()
    {
        return $this->belongsToMany('App\Role', 'user_role');
    }

	public function permissions()
    {
        return $this->belongsToMany('App\Permission', 'role_permission');
    }    

    public function has($permission){
        foreach($this->permissions as $per){
            if ($per->name == $permission){
                return true;
                exit;
            }
        }
        return false;
    }

}
