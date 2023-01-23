<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\LevelOneChanged;
use Illuminate\Contracts\Queue\ShouldQueue;

class LevelOneChangedListener implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\LevelOneChanged  $event
     * @return void
     */
    public function handle(LevelOneChanged $event)
    {
        // broadcast the event to the front-end
        event($event);
    }
}
