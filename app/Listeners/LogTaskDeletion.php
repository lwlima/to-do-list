<?php

namespace App\Listeners;

use App\Events\TaskDeleted;
use App\Models\TaskLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogTaskDeletion
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
    public function handle(TaskDeleted $event): void
    {
        TaskLog::Create([
            'task_id' => $event->task->id,
            'user_id' => $event->userId ?? null,
            'event' => 'deleted',
            'ip' => request()->ip(),
            'details' => []
        ]);
    }
}
