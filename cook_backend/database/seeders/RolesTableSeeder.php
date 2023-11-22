<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $user=Role::firstOrcreate(["name"=>"site_user"]);
       $admin=Role::firstOrcreate(["name"=>"admin"]);
       //permissions
       $admin->givePermissionTo('access to admin panel','edit notes','delete notes');
        $adminAccount = User::create([
            "name"=>"admin",
            'email' => "vetdetmett@gmail.com",
            'password' => Hash::make(7777),
        ]);
        $adminAccount->assignRole($admin);
    }
}
