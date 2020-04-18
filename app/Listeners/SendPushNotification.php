<?php
namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ArticleCreated;

class SendPushNotification
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ArticleCreated $event)
    {
        if ($event->article->publish) {
            (app(\App\Service\Pushall::class))->send('Новая статья на сайте', $event->article->header);
        }
    }

    private function sendPush(Pushall $pushall)
    {
        
    }
}
