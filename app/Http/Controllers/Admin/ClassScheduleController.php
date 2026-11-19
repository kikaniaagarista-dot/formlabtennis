<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\TennisClass;
use App\Models\TrainingLocation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClassScheduleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = ClassSchedule::with([
            'tennisClass',
            'location'
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('schedule_date', 'like', "%{$search}%")
                    ->orWhereHas('tennisClass', function ($class) use ($search) {
                        $class->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('location', function ($location) use ($search) {
                        $location->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $schedules = $query->latest('schedule_date')
            ->paginate(10)
            ->withQueryString();

        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $classes = TennisClass::where('is_active', true)->get();

        $locations = TrainingLocation::where('is_active', true)->get();

        return view('admin.schedules.create', compact(
            'classes',
            'locations'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'location_id' => 'required|exists:training_locations,id',
            'schedule_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'capacity' => 'required|integer|min:1|max:100',
            'note' => 'nullable|string|max:1000',
        ]);

        // Kelas Regular maksimal 6 peserta.
        $tennisClass = TennisClass::findOrFail($validated['class_id']);

        if (stripos($tennisClass->name, 'regular') !== false
            && $validated['capacity'] > 6) {
            return back()
                ->withInput()
                ->withErrors([
                    'capacity' => 'Kapasitas kelas Regular maksimal 6 peserta.'
                ]);
        }

        // Setiap pertemuan berlangsung selama 2 jam.
        $start = Carbon::createFromFormat(
            'H:i',
            $validated['start_time']
        );

        $end = $start->copy()->addHours(2);

        $validated['end_time'] = $end->format('H:i:s');
        $validated['is_available'] = true;

        // Cegah jadwal bertabrakan di lokasi yang sama.
        $overlap = ClassSchedule::where(
            'location_id',
            $validated['location_id']
        )
        ->where('schedule_date', $validated['schedule_date'])
        ->where('start_time', '<', $validated['end_time'])
        ->where('end_time', '>', $validated['start_time'])
        ->exists();

        if ($overlap) {
            return back()
                ->withInput()
                ->withErrors([
                    'start_time' => 'Jadwal tidak dapat disimpan karena waktu yang dipilih bertabrakan dengan jadwal lain pada lokasi yang sama. Silakan pilih waktu atau lokasi yang berbeda.'
                ]);
        }

        ClassSchedule::create($validated);

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal latihan berhasil ditambahkan!');
    }

    public function show(ClassSchedule $schedule)
    {
        return redirect()->route('admin.schedules.edit', $schedule);
    }

    public function edit(ClassSchedule $schedule)
    {
        $classes = TennisClass::where('is_active', true)->get();

        $locations = TrainingLocation::where('is_active', true)
            ->orWhere('id', $schedule->location_id)
            ->get();

        return view('admin.schedules.edit', compact(
            'schedule',
            'classes',
            'locations'
        ));
    }

    public function update(Request $request, ClassSchedule $schedule)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'location_id' => 'required|exists:training_locations,id',
            'schedule_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'capacity' => 'required|integer|min:1|max:100',
            'note' => 'nullable|string|max:1000',
            'is_available' => 'required|boolean',
        ]);

        $tennisClass = TennisClass::findOrFail($validated['class_id']);

        if (stripos($tennisClass->name, 'regular') !== false
            && $validated['capacity'] > 6) {
            return back()
                ->withInput()
                ->withErrors([
                    'capacity' => 'Kapasitas kelas Regular maksimal 6 peserta.'
                ]);
        }

        $start = Carbon::createFromFormat(
            'H:i',
            $validated['start_time']
        );

        $end = $start->copy()->addHours(2);

        $validated['end_time'] = $end->format('H:i:s');

        $overlap = ClassSchedule::where(
            'location_id',
            $validated['location_id']
        )
        ->where('schedule_date', $validated['schedule_date'])
        ->where('id', '!=', $schedule->id)
        ->where('start_time', '<', $validated['end_time'])
        ->where('end_time', '>', $validated['start_time'])
        ->exists();

        if ($overlap) {
            return back()
                ->withInput()
                ->withErrors([
                    'start_time' => 'Jadwal tidak dapat disimpan karena waktu yang dipilih bertabrakan dengan jadwal lain pada lokasi yang sama. Silakan pilih waktu atau lokasi yang berbeda.'
                ]);
        }

        $schedule->update($validated);

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy(ClassSchedule $schedule)
    {
        if ($schedule->bookings()->exists()) {
            return back()->with(
                'error',
                'Jadwal tidak bisa dihapus karena sudah memiliki booking.'
            );
        }

        $schedule->delete();

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus!');
    }
}
