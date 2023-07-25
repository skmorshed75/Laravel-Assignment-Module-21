<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $todos = $user->todos()->latest()->get();

        return response()->json(['todos' => $todos]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $todo = new Todo([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);
        $user->todos()->save($todo);

        return response()->json(['message' => 'Todo created successfully', 'todo' => $todo], 201);
    }

    public function show($id)
    {
        $todo = Todo::findOrFail($id);
        $this->authorize('view', $todo);

        return response()->json(['todo' => $todo]);
    }

    public function update(Request $request, $id)
    {
        $todo = Todo::findOrFail($id);
        $this->authorize('update', $todo);

        $todo->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        return response()->json(['message' => 'Todo updated successfully', 'todo' => $todo]);
    }

    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $this->authorize('delete', $todo);

        $todo->delete();

        return response()->json(['message' => 'Todo deleted successfully']);
    }
}
