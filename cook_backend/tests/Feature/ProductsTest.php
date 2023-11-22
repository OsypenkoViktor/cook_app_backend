<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_be_created(): void
    {
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);
        $user=User::factory()->create();
        $user->assignRole('site_user');
        $this->actingAs($user);
        $productData=[
        'name'=>"testname",
        'parent_id'=>null,
        "calories"=>500,
        "proteins"=>50,
        "fats"=>50,
        "carbohydrates"=>50,
        "description"=>"some test description for product"
        ];
        $response=$this->post("/api/products",$productData);
        $response->assertStatus(200);
        $newProduct=Product::where('name',$productData['name'])->first();
        $this->assertNotNull($newProduct);

    }
}
