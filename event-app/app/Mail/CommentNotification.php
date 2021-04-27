<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public string $authorName;
    public string $commentAuthor;
    public string $comment;
    public string $title;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $authorName, string $commentAuthor, string $comment, string $title)
    {

        $this->commentAuthor = $commentAuthor;
        $this->comment = $comment;
        $this->authorName = $authorName;
        $this->title = $title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.comment_notification');
    }
}
