<?php
namespace App\Events;

use App\Version;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ArticleUpdated extends ArticleCreated
{

    use Dispatchable,
        InteractsWithSockets,
        SerializesModels;

    public $reason;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Version $version)
    {
        $this->reason['data'] = $version->toArray();
        foreach ($this->reason['data'] as $key => $val) {
            if (!isset($this->reason['data']["old_$key"]) || $this->reason['data']["old_$key"]
                == $this->reason['data'][$key]) {
                unset($this->reason['data'][$key]);
            }
        }
        $this->reason['service'] = [
            'prefix'    => $version->prefix,
            'slug'      => $version->slug,
            'header'    => $version->header,
            'editor_id' => $version->editor_id,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return (new PrivateChannel('editor-notify'));
    }
}
