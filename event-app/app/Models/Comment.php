<?php

namespace App\Models;

use App\Events\CommentCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'news_id', 'event_id'];

    public static function boot()
    {
        parent::boot();
        self::created(function(Comment $comment) {
            CommentCreated::dispatch($comment);
        });
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
