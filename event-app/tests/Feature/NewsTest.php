<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_unauthorized()
    {
        $response = $this->post('/api/news/', headers: ["Accept" => "application/json"]);

        $response->assertStatus(401);
    }

    public function test_missing_field()
    {
        $user = User::factory()->create();

        $response = $this->post("/api/news/?api_token=$user->api_token", headers: ["Accept" => "application/json"]);

        $response->assertStatus(400);
    }

    public function test_create_ok()
    {
        $user1 = User::factory()->create();

        $response = $this->post("/api/news/?api_token=$user1->api_token",data:[
            "title"=> "some title123",
            "content"=> "some content123",
        ], headers: ["Accept" => "application/json"]);

        $response->assertStatus(200);
        $data = json_decode($response->getContent());
        $id = $data->id;
        $news = News::find($id);
        self::assertNotNull($news);
    }

    public function test_delete_invalid_permission()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $news = new News();
        $news->fill([
            "title"=> "some title123",
            "content"=> "some content123",
        ]);
        $news->user_id = $user2->id;
        $news->save();

        $response = $this->delete("/api/news/$news->id?api_token=$user1->api_token", headers: ["Accept" => "application/json"]);
        $response->assertStatus(403);
        $news = News::find($news->id);
        self::assertNotNull($news);
    }

    public function test_delete_ok() {
        $user1 = User::factory()->create();
        $news = new News();
        $news->fill([
            "title"=> "some title123",
            "content"=> "some content123",
        ]);
        $news->user_id = $user1->id;
        $news->save();

        $response = $this->delete("/api/news/$news->id?api_token=$user1->api_token", headers: ["Accept" => "application/json"]);
        $response->assertStatus(200);
        $news = News::find($news->id);
        self::assertNull($news);
    }

    public function test_delete_with_comment() {
        $user1 = User::factory()->create();
        $news = new News();
        $news->fill([
            "title"=> "some title123",
            "content"=> "some content123",
        ]);
        $news->user_id = $user1->id;
        $news->save();

        $comment = new Comment();
        $comment->content = "lorem ipsum";
        $comment->news_id = $news->id;
        $comment->nick_name = $user1->nick_name ?? $user1->name;
        $comment->user_id = $user1->id;
        $comment->save();

        $response = $this->delete("/api/news/$news->id?api_token=$user1->api_token", headers: ["Accept" => "application/json"]);
        $response->assertStatus(400);
        $news = News::find($news->id);
        self::assertNotNull($news);
    }
}
