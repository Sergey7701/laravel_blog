<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StatisticReport implements ShouldBroadcast
{

    use Dispatchable,
        InteractsWithSockets,
        SerializesModels;

    public $statistic;
    public $user;

    public function __construct($statistic, int $user)
    {
        $this->user      = $user;
        $this->statistic = $statistic;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.User.' . $this->user);
    }
}
