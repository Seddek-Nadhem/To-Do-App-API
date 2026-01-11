<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
    public function index()
    {
        return TaskResource::collection(Auth::user()->tasks()->latest(0)->paginate(5));
    }

    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        
        $task = Auth::user()->tasks()->create($validated);
        
        return new TaskResource($task);
    }

    public function show($id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);    

        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);
        
        $task->update($request->validated());
        
        return new TaskResource($task);
    }

    public function destroy($id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);
        
        $task->delete();
        
        // 204 means "Success, but I have nothing to show you" (Standard for Delete)
        return response()->noContent();
    }
}