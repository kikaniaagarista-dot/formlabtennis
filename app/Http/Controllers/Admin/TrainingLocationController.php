<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingLocation;
use Illuminate\Http\Request;

class TrainingLocationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = TrainingLocation::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $locations = $query->latest()->paginate(10)->withQueryString();

        return view('admin.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'google_maps_link' => 'required|url|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        TrainingLocation::create($validated);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location added successfully!');
    }

    public function edit(TrainingLocation $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(Request $request, TrainingLocation $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'google_maps_link' => 'required|url|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $location->update($validated);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location updated successfully!');
    }

    public function destroy(TrainingLocation $location)
    {
        if ($location->schedules()->exists()) {
            return back()->with('error', 'Location is being used by a schedule.');
        }

        $location->delete();

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location deleted successfully!');
    }
}
