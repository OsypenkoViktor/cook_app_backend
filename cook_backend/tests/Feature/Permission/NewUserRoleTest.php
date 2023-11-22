<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;

class NewUserRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_user_has_siteuser_role(): void
    {
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->post("/register",$userData);
        $user = User::where('email', $userData['email'])->first();
        $this->assertNotNull($user); // Проверяем, что пользователь был создан

        $this->assertTrue($user->hasRole('site_user')); // Проверяем, что пользователю присвоена роль "site_user"
    }
}
