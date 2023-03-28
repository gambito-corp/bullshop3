<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersController extends Controller
{
    public function index()
    {
//        $role = Role::create(['name' => 'admin']);
//        $permission = Permission::create(['name' => 'editar posts']);
//        $role->givePermissionTo($permission);
//// O usando el nombre del permiso
//        $role->givePermissionTo('editar posts');
//        $user = User::all()->first();
//        $user->assignRole($role);
//// O usando el nombre del rol



        $user = User::all()->first();
//        if ($user->hasRole('admin')) {
//        }else{
//            dump('fracaso');
//        }
            dump($user->roles);


    }
}
