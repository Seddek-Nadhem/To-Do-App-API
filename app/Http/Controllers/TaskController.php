<?php

namespace App\Http\Controllers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
    public function index() : AnonymousResourceCollection
    {
        return TaskResource::collection(Auth::user()->tasks()->latest()->paginate(5));
    }

    public function store(StoreTaskRequest $request) : TaskResource
    {
        $validated = $request->validated();
        
        $task = Auth::user()->tasks()->create($validated);
        
        return new TaskResource($task);
    }

    public function show($id): TaskResource
    {
        $task = Auth::user()->tasks()->findOrFail($id);    

        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, $id): TaskResource
    {
        $task = Auth::user()->tasks()->findOrFail($id);
        
        $task->update($request->validated());
        
        return new TaskResource($task);
    }

    public function destroy($id): Response
    {
        $task = Auth::user()->tasks()->findOrFail($id);
        
        $task->delete();
        
        // 204 means "Success, but I have nothing to show you" (Standard for Delete)
        return response()->noContent();
    }
}