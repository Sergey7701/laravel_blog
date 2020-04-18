<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class NewsletterAboutNewArticles extends Notification
{

    use Queueable;

    private $interval;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($interval)
    {
        $this->interval = $interval;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $articles = \App\Models\Article::where('created_at', '>', (new Carbon)->subDays($this->interval))->get();
        $mail     = '';

        $mailText = (new MailMessage)
            ->subject('Новые статьи на сайте ' . env('APP_NAME'))
            ->line('За последнее время на нашем сайте появились следующие статьи:');
        foreach ($articles as $article) {
            $mailText = $mailText
                ->line(($article->header));
        }
        return $mailText;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
