<?php
namespace App\Events;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
class CatCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cat;

    public function __construct($cat)
    {
        $this->cat = $cat;
    }

    public function broadcastOn()
    {
        return new Channel('cats-channel');
    }

    public function broadcastAs()
    {
        return 'cat.created';
    }
}
