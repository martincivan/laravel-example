<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EventTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_unauthorized()
    {
        $response = $this->post('/api/events/', headers: ["Accept" => "application/json"]);

        $response->assertStatus(401);
    }

    public function test_missing_field()
    {
        $user = User::factory()->create();

        $response = $this->post("/api/events/?api_token=$user->api_token", headers: ["Accept" => "application/json"]);

        $response->assertStatus(400);
    }

    public function test_create_ok()
    {
        $user1 = User::factory()->create();

        $response = $this->post("/api/events/?api_token=$user1->api_token",data:[
            "title"=> "some title123",
            "content"=> "some content123",
            "valid_from"=> "2000-01-01 00:00:00",
            "valid_to"=> "2001-01-01 23:59:59",
            "gps_lat"=> 10.25,
            "gps_lng"=> 12.34
        ], headers: ["Accept" => "application/json"]);

        $response->assertStatus(200);
        $data = json_decode($response->getContent());
        $id = $data->id;
        $event = Event::find($id);
        self::assertNotNull($event);
    }

    public function test_delete_invalid_permission()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $event = new Event();
        $event->fill([
            "title"=> "some title123",
            "content"=> "some content123",
            "valid_from"=> "2000-01-01 00:00:00",
            "valid_to"=> "2001-01-01 23:59:59",
            "gps_lat"=> 10.25,
            "gps_lng"=> 12.34
        ]);
        $event->user_id = $user2->id;
        $event->save();

        $response = $this->delete("/api/events/$event->id?api_token=$user1->api_token", headers: ["Accept" => "application/json"]);
        $response->assertStatus(403);
        $event = Event::find($event->id);
        self::assertNotNull($event);
    }

    public function test_delete_ok() {
        $user1 = User::factory()->create();
        $event = new Event();
        $event->fill([
            "title"=> "some title123",
            "content"=> "some content123",
            "valid_from"=> "2000-01-01 00:00:00",
            "valid_to"=> "2001-01-01 23:59:59",
            "gps_lat"=> 10.25,
            "gps_lng"=> 12.34
        ]);
        $event->user_id = $user1->id;
        $event->save();

        $response = $this->delete("/api/events/$event->id?api_token=$user1->api_token", headers: ["Accept" => "application/json"]);
        $response->assertStatus(200);
        $event = Event::find($event->id);
        self::assertNull($event);
    }

    public function test_delete_with_comment() {
        $user1 = User::factory()->create();
        $event = new Event();
        $event->fill([
            "title"=> "some title123",
            "content"=> "some content123",
            "valid_from"=> "2000-01-01 00:00:00",
            "valid_to"=> "2001-01-01 23:59:59",
            "gps_lat"=> 10.25,
            "gps_lng"=> 12.34
        ]);
        $event->user_id = $user1->id;
        $event->save();

        $comment = new Comment();
        $comment->content = "lorem ipsum";
        $comment->event_id = $event->id;
        $comment->nick_name = $user1->nick_name ?? $user1->name;
        $comment->user_id = $user1->id;
        $comment->save();

        $response = $this->delete("/api/events/$event->id?api_token=$user1->api_token", headers: ["Accept" => "application/json"]);
        $response->assertStatus(400);
        $event = Event::find($event->id);
        self::assertNotNull($event);
    }

}
