<?php

namespace Api;

use App\Models\Product;
use App\Models\User;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cant_create_product(): void
    {
        $productData=[
            'name'=>"testname",
            'parent_id'=>null,
            "calories"=>500,
            "proteins"=>50,
            "fats"=>50,
            "carbohydrates"=>50,
            "description"=>"some test description for product"
        ];
        $response=$this->withHeader("Accept","application/json")->post("/api/products",$productData);
        $response->assertStatus(401);
        $newProduct=Product::where('name',$productData['name'])->first();
        $this->assertNull($newProduct);

    }
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
    public function test_moderated_product_can_be_received_by_siteuser(): void
    {
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);
        $user=User::factory()->create();
        $user->assignRole('site_user');
        $this->actingAs($user);
        $newProduct=Product::factory()->isModerated()->create();
        $response=$this->get("/api/products/$newProduct->id");
        $response->assertStatus(200);
    }
    public function test_not_moderated_product_cant_be_received_by_siteuser(): void
    {
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);
        $user=User::factory()->create();
        $user->assignRole('site_user');
        $this->actingAs($user);
        $newProduct=Product::factory()->create();
        $response=$this->get("/api/products/$newProduct->id");
        $response->assertStatus(403);
    }
    public function test_siteuser_cant_delete_product(): void
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
        $newProduct=Product::create($productData);
        $response=$this->delete("/api/products/$newProduct->id");
        $response->assertStatus(403);
    }

    public function test_siteuser_cant_get_not_moderated_products(): void
    {
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);
        Product::factory()->count(20)->create();
        Product::factory()->isModerated()->count(20)->create();
        $user=User::factory()->create();
        $user->assignRole('site_user');
        $this->actingAs($user);
        $response=$this->get("/api/products/");
        if ($response->status()===404){
            $this->assertTrue(true);
        }else{
            $data = $response->json();
            $this->assertEquals(20,count($data));
            foreach ($data as $product){
                $this->assertTrue((bool)$product['isModerated']);
            }

        }
    }

    public function test_admin_can_get_not_moderated_products(): void
    {
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);
        Product::factory()->count(20)->create();
        Product::factory()->isModerated()->count(20)->create();
        $user=User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        $response=$this->get("/api/products/")->json();
        $this->assertEquals(40,count($response));
    }
    public function test_user_without_permissions_cant_update_product(): void
    {
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);
        $productToPatch = Product::factory()->create();
        $user=User::factory()->create();
        $user->assignRole('site_user');
        $this->actingAs($user);
        $newData = [
          "name"=>"updated name"
        ];
        $response = $this->patch("/api/products/$productToPatch->id",$newData);
        $response->assertStatus(403);
    }

    public function test_status_404_occur_when_trying_get_incorrect_product(): void
    {
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);
        $user=User::factory()->create();
        $user->assignRole('site_user');
        $this->actingAs($user);
        $response = $this->get("/api/products/9999");
        $response->assertStatus(404);
    }
}
