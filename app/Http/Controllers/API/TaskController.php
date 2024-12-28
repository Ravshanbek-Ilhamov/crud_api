<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function index()
    {
        $tasks = \App\Models\Task::all();
        return response()->json($tasks);
    }

    public function show($id)
    {
        $task = \App\Models\Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        return response()->json($task);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $task = \App\Models\Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->user()->id
        ]);
        return response()->json($task, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $task = \App\Models\Task::find($id);
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->user()->id
        ]);
        
        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = \App\Models\Task::find($id);
        $task->delete();
        return response()->json($task);
    }
}
