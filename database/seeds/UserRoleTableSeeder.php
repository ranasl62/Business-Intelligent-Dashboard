<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            $user=User::where('name','=','admin')->first();
            $role=Role::where('name','=','admin')->first();
            if($user && $role)$user->attachRole($role);
        }catch(\Exception $e){
            Log::error($e->getMessage());
        }
    }
}
