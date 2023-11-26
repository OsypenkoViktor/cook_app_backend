<?php

namespace Tests\Feature;

use App\Models\CookProcess;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CookProcessTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_cook_process_can_be_created(): void
    {
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);
        $user=User::factory()->create();
        $user->assignRole('site_user');
        $this->actingAs($user);
        $product=Product::factory()->create();
        $processData = [
            "name"=>'testname',
            "duration"=>fake()->numberBetween(1,10),
            "cookPresenceInterval"=>fake()->numberBetween(0,5),
            "description"=>fake()->text(50),
            "product_id"=>$product->id
        ];
        $response=$this->post("/api/products/".$product->id . '/cookProcessCreate',$processData);
        $response->assertStatus(200);
        $createdProcess = CookProcess::where('name','testname')->first();
        $this->assertNotNull($createdProcess);
    }
    public function test_cook_process_can_be_received(): void
    {
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);
        $user=User::factory()->create();
        $user->assignRole('site_user');
        $this->actingAs($user);
        $processWithProduct = CookProcess::factory()->create();
        $response=$this->get("/api/products/".$processWithProduct->product->id."/".$processWithProduct->id);
        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $processWithProduct->id]);
    }
    public function test_cook_process_cant_be_deleted_by_site_user(): void
    {
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);
        $user=User::factory()->create();
        $user->assignRole('site_user');
        $this->actingAs($user);
        $processWithProduct = CookProcess::factory()->create();
        $response=$this->delete("/api/products/".$processWithProduct->product->id."/".$processWithProduct->id);
        $response->assertStatus(403);
    }
    public function test_cook_process_can_be_updated_by_admin(): void
    {
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);
        $user=User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        $processWithProduct = CookProcess::factory()->create();
        $dataToUpdate=[
            'name'=>'updated'
        ];
        $response=$this->patch("/api/products/{$processWithProduct->product->id}/{$processWithProduct->id}",$dataToUpdate);
        $updatedProcess = CookProcess::find($processWithProduct->id);
        $this->assertEquals('updated',$updatedProcess->name);
        $response->assertStatus(200);
    }
}
