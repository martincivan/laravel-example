<?php

namespace App\Http\Controllers;

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
        if (Auth::check()) {
            $user = Auth::id();
            echo "OK";
        }

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
        //
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
}
