<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;
class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            $permission=Permission::all();
            $role=Role::where('name','=','admin')->first();
            if($permission && $role){
                $role->perms()->sync($permission);
            }
        }catch(\Ecxception $e){

            \Log::error($e->getMessage());
            return "DB";
        }
    }
}
