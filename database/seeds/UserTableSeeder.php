<?php

use Illuminate\Database\Seeder;
use App\User;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    try{
        if(!User::where('name','='.'admin')->first())
            User::create([
            'email'=>'admin@admin.com',
            'name'=>'admin',
            'password'=>bcrypt('123456')
            ]);
        }catch(\Exception $e){
            Log::error($e->getMessage());
        }
    }
}
