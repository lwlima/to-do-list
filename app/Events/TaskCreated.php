<?php

namespace App\Events;

use App\Models\Task;
use App\Models\TaskLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $total;
    public int $totalComplete;
    public int $totalPending;

    /**
     * Create a new event instance.
     */
    public function __construct(public Task $task, public int $userId)
    {
        $this->total = Task::query()->count();
        $this->totalComplete = Task::query()->where('status', 'concluída')->count();
        $this->totalPending = Task::query()->where('status', 'pendente')->count();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('dashboard'),
        ];
    }

    /**
     * Define the data that should be broadcasted.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'total' => $this->total,
            'totalComplete' => $this->totalComplete,
            'totalPending' => $this->totalPending,
        ];
    }
}
