<?php

namespace App\Listeners;

use App\Events\TaskUpdated;
use App\Models\TaskLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogTaskUpdate
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
    public function handle(TaskUpdated $event): void
    {
        $old = [];
        $new = [];
        foreach ($event->task->getDirty() as $dirtyKey => $dirtyValue) {
            if ($dirtyKey !== 'updated_at') {
                $old[$dirtyKey] = $event->task->getOriginal($dirtyKey);
                $new[$dirtyKey] = $dirtyValue;
            }
        }

        if (count($old) == 0 || count($new) == 0)
            return;

        TaskLog::Create([
            'task_id' => $event->task->id,
            'user_id' => auth()->user()->id ?? null,
            'event' => 'updated',
            'ip' => request()->ip(),
            'details' => [
                'old' => $old,
                'new' => $new
            ]
        ]);
    }
}
