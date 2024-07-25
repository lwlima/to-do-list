<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Models\TaskLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogTaskCreation
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskCreated $event): void
    {
        TaskLog::Create([
            'task_id' => $event->task->id,
            'user_id' => $event->userId ?? null,
            'event' => 'created',
            'ip' => request()->ip(),
            'details' => []
        ]);
    }
}
