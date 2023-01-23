<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LevelOneChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The level one data.
     *
     * @var array
     */
    public $levelOne;

    /**
     * The symbol.
     *
     * @var string
     */
    public $symbol;

    /**
     * Create a new event instance.
     *
     * @param  array  $levelOne
     * @param  string  $symbol
     * @return void
     */
    public function __construct(array $levelOne, string $symbol)
    {
        $this->levelOne = $levelOne;
        $this->symbol = $symbol;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('level-one-'.$this->symbol);
    }
}
