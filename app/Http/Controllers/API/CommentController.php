<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = \App\Models\Comment::all();
        return response()->json($comments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'task_id' => 'required|exists:tasks,id',
        ]);

        $comment = \App\Models\Comment::create($request->all());
        return response()->json($comment);
    }

    public function show($id)
    {
        $comment = \App\Models\Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        return response()->json($comment);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'text' => 'required|string',
            'task_id' => 'required|exists:tasks,id',
        ]);

        $comment = \App\Models\Comment::find($id);
        $comment->update($request->all());
        return response()->json($comment);
    }

    public function destroy($id)
    {
        $comment = \App\Models\Comment::find($id);
        $comment->delete();
        return response()->json($comment);
    }
}
