<?php

namespace Tests\Feature;

use App\Models\Dish;
use App\Models\User;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DishTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_dish()
    {
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);
        $user=User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        $dishData=[
            'name'=>"testname",
            'user_id'=>$user->id,
            "description"=>"some test description for dish"
        ];
        $response=$this->post("/api/dishes/",$dishData);
        $response->assertStatus(200);
        $newProduct=Dish::where('name',$dishData['name'])->first();
        $this->assertNotNull($newProduct);
    }
}
