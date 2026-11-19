<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNote;
use Illuminate\Http\Request;

class AdminNoteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = AdminNote::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $notes = $query->latest()->paginate(10)->withQueryString();

        return view('admin.notes.index', compact('notes'));
    }

    public function create()
    {
        return view('admin.notes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'content' => 'required|string|max:5000',
            'color' => 'required|in:yellow,pink,blue,green,purple',
        ]);

        AdminNote::create($validated);

        return redirect()
            ->route('admin.notes.index')
            ->with('success', 'Catatan berhasil ditambahkan!');
    }

    public function edit(AdminNote $note)
    {
        return view('admin.notes.edit', compact('note'));
    }

    public function update(Request $request, AdminNote $note)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'content' => 'required|string|max:5000',
            'color' => 'required|in:yellow,pink,blue,green,purple',
        ]);

        $note->update($validated);

        return redirect()
            ->route('admin.notes.index')
            ->with('success', 'Catatan berhasil diperbarui!');
    }

    public function destroy(AdminNote $note)
    {
        $note->delete();

        return redirect()
            ->route('admin.notes.index')
            ->with('success', 'Catatan berhasil dihapus!');
    }
}
