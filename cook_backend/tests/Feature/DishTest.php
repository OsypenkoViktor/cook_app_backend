<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Dish;
use App\Models\User;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
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
    public function test_create_dish_comment()
    {
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);
        $user=User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        $dish = Dish::factory()->create();
        $comment = [
            'text'=>'test text',
            'user_id'=>$user->id,
            'dish_id'=>$dish->id,
        ];
        $response=$this->post("/api/dishes/$dish->id/comments",$comment);
        $newComment=Comment::where('dish_id',$dish->id)->first();
        $this->assertNotNull($newComment);
    }
    public function test_comment_can_be_updated()
    {
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);
        $user=User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        $comment = Comment::factory()->create();
        $updateComment=[
            'text'=>'updated text'
        ];

        $response=$this->patch("/api/dishes/" . $comment->dish->id . "/comments/$comment->id",$updateComment);
        $response->assertStatus(200);
        $newComment=Comment::find($comment->id)->first();
        $this->assertEquals('updated text',$newComment->text);
    }
    public function test_comment_can_be_deleted()
    {
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);
        $user=User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        $comment = Comment::factory()->create();
        $response=$this->delete("/api/dishes/" . $comment->dish->id . "/comments/$comment->id");
        $response->assertStatus(200);
        $newComment=Comment::find($comment->id);
        $this->assertNull($newComment);
    }

    //TODO: handle testing errors
      /*  public function test_dish_can_be_deleted()
    {
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(RolesTableSeeder::class);
        $user=User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        $comment = Dish::factory()->create();
        $response=$this->delete("/api/dishes/156541654534")->json();
        // Проверка содержимого JSON ответа
        $response->assertJson([
            'message' => 'Блюдо не найдено'
        ]);

        // Проверка отсутствия записи в базе данных
        $this->assertDatabaseMissing('dishes', ['id' => 156541654534]);
    }*/
}
