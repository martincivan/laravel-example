<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function destroy(int $id) {
        $comment = Comment::findOrFail($id);
        if (Auth::id() === $comment->user_id) {
            Commend::destroy($id);
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
