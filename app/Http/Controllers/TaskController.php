<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
    public function index()
    {
        return TaskResource::collection(Task::all());
    }

    public function store(StoreTaskRequest $request)
    {
        // Rule #1: This code ONLY runs if StoreTaskRequest validates the data successfully.
        // If not, Laravel automatically stops here and returns 422.
        
        $task = Task::create($request->validated());
        
        return new TaskResource($task);
    }

    public function show($id)
    {
        // Rule #2: findOrFail throws a 404 error automatically if not found.
        $task = Task::findOrFail($id);
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $task = Task::findOrFail($id);
        
        $task->update($request->validated());
        
        return new TaskResource($task);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        
        $task->delete();
        
        // 204 means "Success, but I have nothing to show you" (Standard for Delete)
        return response()->noContent();
    }
}