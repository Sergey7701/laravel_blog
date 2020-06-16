<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatisticReport extends Notification
{

    use Queueable;

    protected $statistic;
    protected $csvAttach;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($statistic, $csvAttach)
    {
        $this->statistic = $statistic;
        $this->csvAttach = $csvAttach;
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
        $mail = (new MailMessage)
            ->greeting('Запрошена статистика по сайту ' . env('app.name'));           
        foreach ($this->statistic as $key => $value) {
            foreach ($value as $text => $result) {
                $mail->line($text . $result);
            }
            $mail->line('____________________________________________________');
        }
         $mail->attachData($this->csvAttach, 'statistic.csv', [
                'mime' => 'text/csv',
            ]);
        return $mail;
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
