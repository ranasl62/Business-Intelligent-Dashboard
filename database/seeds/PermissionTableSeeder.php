<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\User;
class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    try{
    $permission=[

        [
            'name' => 'File-Upload',
            'display_name' => 'File Upload Permmission',
            'description' => 'File Upload Permmission'
        ],
        [
            'name' => 'DashBoard-Permission',
            'display_name' => 'Admin-Permission',
            'description' => 'Admin is the super User'
        ],
        [
            'name' => 'Admin-Permission',
            'display_name' => 'Admin-Permission',
            'description' => 'Admin is the super User'
        ],
        [
            'name' => 'VR-Chart',
            'display_name' => 'Can Read VR Chart',
            'description' => 'Users should see the report of vertual recharge'
        ],
        [
            'name' => 'SMS-Chart',
            'display_name' => 'Can Read SMS Chart',
            'description' => 'Users should see the report of SMS'
        ],
        [
            'name' => 'PGW-Chart',
            'display_name' => 'Can Read PGW Chart',
            'description' => 'Users should see the report of payment gateway'
        ],
        [
            'name' => 'VR-Report',
            'display_name' => 'Can Read VR Report',
            'description' => 'Users should see the report of vertual Recharge'
        ],
        [
            'name' => 'SMS-Report',
            'display_name' => 'Can Read SMS Report',
            'description' => 'Users should see the report of SMS'
        ],
        [
            'name' => 'PGW-Report',
            'display_name' => 'Can Read PGW Report',
            'description' => 'Users should see the report of payment gateway'
        ], 
        [
            'name' => 'VR-Invoice',
            'display_name' => 'Can Read VR Invoice',
            'description' => 'Users should see the invoice of vertual recharge'
        ], 
        [
            'name' => 'VR-Invoice-Payment',
            'display_name' => 'Can Pay VR Payment',
            'description' => 'Users should see the invoice of vertual recharge'
        ],
        [
            'name' => 'SMS-Invoice',
            'display_name' => 'Can Read SMS Invoice',
            'description' => 'Users should see the invoice of SMS'
        ], 
        [
            'name' => 'PGW-Invoice',
            'display_name' => 'Can Read PGW Invoice',
            'description' => 'Users should see the report of Vertual Recharge'
        ]
    ];
    foreach($permission as $value)Permission::create($value);

    }catch(\Exception $e){
        \Log::error($e->getMessage());
    }
}
}
