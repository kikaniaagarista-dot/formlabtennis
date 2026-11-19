<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TennisClass;
use Illuminate\Http\Request;

class TennisClassController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');

        $query = TennisClass::query();

        // Pencarian nama kelas
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter jenis kelas
        if ($type) {
            $query->where('type', $type);
        }

        $classes = $query->latest()->paginate(10)->withQueryString();

        return view('admin.classes', compact('classes'));
    }


    public function create()
    {
        return view('admin.classes-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:regular,private',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
        ]);

        TennisClass::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'capacity' => $validated['capacity'],
            'is_active' => true,
        ]);

        return redirect('/admin/classes')
            ->with('success', 'Class added successfully!');
    }

    public function edit(TennisClass $class)
    {
        return view('admin.classes-edit', [
            'tennisClass' => $class
        ]);
    }

    public function update(Request $request, TennisClass $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:regular,private',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
        ]);

        $class->update($validated);

        return redirect('/admin/classes')
            ->with('success', 'Class updated successfully!');
    }

    public function destroy(TennisClass $class)
    {
        $class->delete();

        return redirect('/admin/classes')
            ->with('success', 'Class deleted successfully!');
    }
}
