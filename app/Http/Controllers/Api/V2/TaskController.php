<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskInputParser;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(protected TaskInputParser $parser)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
//        Gate::authorize('viewAny', Task::class); essa seria uma autorização com gate
        if ($request->user()->cannot('viewAny', Task::class)) {
            abort(403);
        }

        return request()->user()
            ->tasks()
            ->handleSort(request()->query('sort_by') ?? 'time')
            ->handleFilter(request()->query('due_date'))
            ->with('priority')
            ->get()
            ->toResourceCollection();

//        return TaskResource::collection(Task::all());
//        return Task::all()->toResourceCollection();

//        return request()->user()->tasks()->get()->toResourceCollection();
    }

    /**
     * Store a newly created resource in storage.
     */
//    public function store(StoreTaskRequest $request)
//    {
//        if ($request->user()->cannot('create', Task::class)) {
//            abort(403);
//        }
////        $task = Task::create($request->validated() + ['user_id' => $request->user()->id]);
//        $task = $request->user()->tasks()->create($request->validated());
//
//        $task->load('priority');
//
//        return TaskResource::make($task);
//    }

    public function store(StoreTaskRequest $request)
    {
        if ($request->user()->cannot('create', Task::class)) {
            abort(403);
        }

        $data = $request->validated();
        $task = $request->user()->tasks()->create(
            $this->prepareData($data)
        );

        $task->load('priority');

        return $task->toResource();
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task, Request $request)
    {

//        Gate::authorize('view', $task); essa seria uma autorização com gate
        if ($request->user()->cannot('view', $task)) {
            abort(403);
        }
        $task->load('priority');

//        return new TaskResource($task); posso fazer assim
//        return TaskResource::make($task); //ou assim de forma estática, ambas são validas trazem o mesmo resultado
        return $task->toResource(); //assim é de forma mais simples a partir do laravel 12
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task, TaskInputParser $parser)
    {
        if ($request->user()->cannot('update', $task)) {
            abort(403);
        }

        $task->update(
            $this->prepareData($request->validated())
        );
        $task->load('priority');

        return $task->toResource();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, Request $request)
    {
        if ($request->user()->cannot('delete', $task)) {
            abort(403);
        }

        $task->delete();

        return response()->noContent();
    }

    private function prepareData(array $data): array
    {
        $parsed = $this->parser->parse($data['name']);
        if ($parsed === null) {
            abort(422, 'Task name is required');
        }
        if ($parsed) {
            $data['name'] = $parsed['name'];
            $data['priority_id'] = $data['priority_id'] ?? ($parsed['priority_id'] ?? null);
            $data['due_date'] = $data['due_date'] ?? ($parsed['due_date'] ?? null);
        }
        return $data;
    }

}
