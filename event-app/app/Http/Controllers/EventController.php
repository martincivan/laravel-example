<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Event::with('comments')->whereDate('valid_from', '<=', $request->get("valid_to"))
            ->whereDate("valid_to", ">=", $request->get("valid_from"))->get();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $event = new Event();
        $event->fill($request->all());
        $event->user_id = Auth::id();
        $event->save();
        return response()->json($event, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return $event;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            return response()->json([
                'error' => 'Insufficient permission'
            ], 403);
        }
        $event->update($request->all());

        return response()->json($event, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $event = Event::with("comments")->findOrFail($id);
        if (!$event) {
            return response()->json([
                'error' => 'Resource not found'
            ], 404);
        }
        if ($event->comments()->count()) {
            return response()->json([
                'error' => 'News have comment(s)'
            ], 400);
        }
        if (Auth::id() === $event->user_id) {
            Event::destroy([$id]);
        } else {
            return response()->json([
                'error' => 'Insufficient permission'
            ], 403);
        }
        return response()->json([
            'message' => 'OK'
        ], 200);
    }

    public function comment(Event $event, Request $request) {
        $comment = new Comment();
        $comment->content = $request->get("content");
        $comment->user_id = Auth::id();
        $comment->nick_name = Auth::user()->nick_name ?? Auth::user()->name;
        $comment->event_id = $event->id;
        $comment->save();
        return $comment;
    }
}
