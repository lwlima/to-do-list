<?php

namespace App\Http\Controllers;

use App\Events\TaskUpdated;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\TaskLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filter = Request::input('filter') && Request::input('filter') != 'all'
            ? Request::input('filter')
            : null;
        $tasks = Task::query()
            ->when($filter, function ($query, $filter) {
                $query->where('status', $filter);
            })
            ->when(Request::input('search'), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when(!Auth::user()->is_admin, function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->orderByDesc('status')
            ->orderByRaw(
                "CASE priority
                WHEN 'alta' THEN 1
                WHEN 'média' THEN 2
                WHEN 'baixa' THEN 3
                ELSE 4
                END"
            )
            ->orderByDesc('due_date')
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Tasks/Tasks', [
            'filter' => Request::input('filter') ?? 'all',
            'search' => Request::input('search') ?? '',
            'tasks' => $tasks,
            'users' => User::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        Task::create($request->safe()->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, int $id)
    {
        $task = Task::find($id);
        if (!$task)
            return redirect()->back()->withErrors(['message' => 'Tarefa não encontrada.']);

        $result = $task->update($request->safe()->all());
        if (!$result)
            return redirect()->back()->withErrors(['message' => 'Tarefa não atualizada.']);

        return redirect()->back()->with('message', 'Tarefa atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $affected = Task::destroy($id);

        if ($affected === 0)
            return redirect()->back()->withErrors(['message' => 'Erro ao excluir tarefa.']);

        return redirect()->back()->with('message', 'Tarefa excluída com sucesso.');
    }

    /**
     * updated task status to completed
     */
    public function complete(int $id)
    {
        $task = Task::find($id);
        if (!$task)
            return redirect()->back()->withErrors(['message' => 'Tarefa não encontrada.']);

        $result = $task->update(['status' => 'concluída']);
        if (!$result)
            return redirect()->back()->withErrors(['message' => 'Erro ao concluir tarefa.']);

        return redirect()->back()->with('message', 'Tarefa concluída com sucesso.');
    }

    public function report()
    {
        if (!Auth::user()->is_admin)
            return response()->redirectTo('tasks');

        $report['total'] = Task::query()->count();
        $report['total_complete'] = Task::query()->where('status', 'concluída')->count();
        $report['total_pending'] = Task::query()->where('status', 'pendente')->count();
        $report['recent_activities'] = TaskLog::query()->with('user')->orderByDesc('created_at')->take(10)->get();

        return Inertia::render('Dashboard/Dashboard', [
            'report' => $report
        ]);
    }
}
