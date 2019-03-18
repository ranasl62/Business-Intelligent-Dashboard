<?php

use Illuminate\Database\Seeder;
use App\Role;
class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            if(!Role::where('name','=','admin')->first()){
                Role::create([
                    'name'          => 'admin',
                    'display_name'  => 'admin',
                    'description'   => 'admin'
                ]);
            }
        }catch(\Exception $e){
            \Log::error($e->getMessage());
        }
    }
}
