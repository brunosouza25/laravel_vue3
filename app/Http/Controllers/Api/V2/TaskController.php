<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//        return TaskResource::collection(Task::all());
//        return Task::all()->toResourceCollection();
        return request()->user()->tasks()->get()->toResourceCollection();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
//        $task = Task::create($request->validated() + ['user_id' => $request->user()->id]);
        $task = $request->user()->tasks()->create($request->validated());
        return $task->toResource();
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
//        return new TaskResource($task); posso fazer assim
//        return TaskResource::make($task); //ou assim de forma estática, ambas são validas trazem o mesmo resultado
        return $task->toResource(); //assim é de forma mais simples a partir do laravel 12
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return $task->toResource();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->noContent();
    }
}
