<?php

namespace App\Listeners;

use App\Events\ArticleCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class SendMailArticleCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
 
    /**
     * Handle the event.
     *
     * @param  ArticleCreated  $event
     * @return void
     */
    public function handle(ArticleCreated $event)
    {
        \Mail::to(env('MAIL_FROM_ADDRESS'))->send(new \App\Mail\ArticleCreated($event->article));
    }
}
