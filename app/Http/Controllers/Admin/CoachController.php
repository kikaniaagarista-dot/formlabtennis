<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use Illuminate\Http\Request;

class CoachController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Coach::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        $coaches = $query->latest()->paginate(10)->withQueryString();

        return view('admin.coaches', compact('coaches'));
    }


    public function create()
    {
        return view('admin.coaches-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);
        $photoName = null;
        if ($request->hasFile('photo')) {
            $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('images/coaches'), $photoName);
        }
        Coach::create([
            'name' => $request->name,
            'specialization' => $request->specialization,
            'bio' => $request->bio,
            'photo' => $photoName,
            'is_active' => true,
        ]);

        return redirect('/admin/coaches')
            ->with('success', 'Coach added successfully!');
    }

    public function edit(Coach $coach)
    {
        return view('admin.coaches-edit', compact('coach'));
    }

    public function update(Request $request, Coach $coach)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $photoName = $coach->photo;
        if ($request->hasFile('photo')) {
            $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('images/coaches'), $photoName);
        }

        $coach->update([
            'name' => $request->name,
            'specialization' => $request->specialization,
            'bio' => $request->bio,
            'photo' => $photoName,
        ]);

        return redirect('/admin/coaches')
            ->with('success', 'Coach updated successfully!');
    }


    public function destroy(Coach $coach)
    {
        if ($coach->photo) {
            $photoPath = public_path('images/coaches/' . basename($coach->photo));

            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }

        $coach->delete();

        return redirect('/admin/coaches')
            ->with('success', 'Coach and photo deleted successfully!');
    }

}
