<?php

namespace App\Observers;

use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskUpdated;
use App\Jobs\ProcessTaskLog;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        ProcessTaskLog::dispatch(new TaskCreated($task, Auth::user()->id));
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        $oldValues = $task->getOriginal();
        event(new TaskUpdated($task, $oldValues));
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        ProcessTaskLog::dispatch(new TaskDeleted($task, Auth::user()->id));
    }
}
