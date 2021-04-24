<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return News::with('comments')->whereDate('created_at', Carbon::today())->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $news = new News();
        $news->fill($request->all());
        $news->user_id = Auth::id();
        $news->save();
        return response()->json($news, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\News $news
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return News::with("comments")->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\News $news
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, News $news)
    {
        if ($news->user_id != Auth::id()) {
            return response()->json([
                'error' => 'Insufficient permission'
            ], 403);
        }
        $news->update($request->all());

        return response()->json($news, 200);
    }


    public function destroy(int $id)
    {
        $news = News::with("comments")->findOrFail($id);
        if (!$news) {
            return response()->json([
                'error' => 'Resource not found'
            ], 404);
        }
        if ($news->comments()->count()) {
            return response()->json([
                'error' => 'News have comment(s)'
            ], 400);
        }
        if (Auth::id() === $news->user_id) {
            News::destroy([$id]);
        } else {
            return response()->json([
                'error' => 'Insufficient permission'
            ], 403);
        }
        return response()->json([
            'message' => 'OK'
        ], 200);
    }

    public function comment(News $news, Request $request) {
        $comment = new Comment();
        $comment->content = $request->get("content");
        $comment->user_id = Auth::id();
        $comment->nick_name = Auth::user()->nick_name;
        $comment->news_id = $news->id;
        $comment->save();
        return $comment;
    }
}
