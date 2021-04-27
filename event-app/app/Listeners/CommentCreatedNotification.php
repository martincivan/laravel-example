<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use App\Mail\CommentNotification;
use App\Models\Event;
use App\Models\News;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Psr\Log\LoggerInterface;

class CommentCreatedNotification implements ShouldQueue
{
    private LoggerInterface $logger;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Handle the event.
     *
     * @param  CommentCreated  $commentCreated
     * @return void
     */
    public function handle(CommentCreated $commentCreated)
    {
        $comment = $commentCreated->getComment();
        if ($comment->event_id !== null && $comment->news_id === null) {
            $event = Event::with("user")->find($comment->event_id);
            if (!$event) {
                $this->logger->error("Skipping email notification - event not found", ["comment" => $comment]);
                return;
            }
            $title = $event->title;
            $to = $event->user->email;
            $authorName = $event->user->nick_name ?? $event->user->name;

        } else if ($comment->news_id !== null && $comment->event_id === null) {
            $news = News::with("user")->find($comment->news_id);
            if (!$news) {
                $this->logger->error("Skipping email notification - news not found", ["comment" => $comment]);
                return;
            }
            $title = $news->title;
            $to = $news->user->email;
            $authorName = $news->user->nick_name ?? $news->user->name;
        } else {
            $this->logger->error("Comment - event or news inconsistency", ["comment" => $comment]);
            return;
        }

        Mail::to($to)->send(new CommentNotification($authorName, $comment->nick_name, $comment->content, $title));
    }
}
