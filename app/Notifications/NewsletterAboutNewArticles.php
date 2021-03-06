<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewsletterAboutNewArticles extends Notification
{

    use Queueable;

    private $entries;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($entries)
    {
        $this->entries = $entries;
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
        $mailText = (new MailMessage)
            ->subject('Новые статьи на сайте ' . env('APP_NAME'))
            ->line('За последнее время на нашем сайте появились следующие статьи:');
        foreach ($this->entries as $entry) {
            $mailText = $mailText
                ->line(($entry->entryable->header));
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
