<?php

namespace App\Events;
use Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SystemNotificationEvent implements ShouldBroadcastNow
{
    
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     *
     * @param string $message
     */
    public function __construct($message)
    {
        $this->message = $message;
        
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
       
        return new Channel('notifications');
    }

    // /**
    //  * The event's broadcast name.
    //  *
    //  * @return string
    //  */
    public function broadcastAs()
    {
       
        return 'new-notification';
    }

    // /**
    //  * Get the data to broadcast.
    //  *
    //  * @return array
    //  */
    // public function broadcastWith()
    // {
    //     Log::info('this from broadcastwith '.$this->message);
    //     return [
    //         'message' => $this->message,
    //     ];
    // }
}