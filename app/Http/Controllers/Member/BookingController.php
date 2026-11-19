<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TennisClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassSchedule;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{

    public function create($id)
    {
        $class = TennisClass::where('is_active', true)->findOrFail($id);

        $schedules = ClassSchedule::with('location')
            ->withSum('bookingsMany as booked_participants', 'participants')
            ->where('class_id', $class->id)
            ->where('is_available', true)
            ->whereDate('schedule_date', '>=', today())
            ->orderBy('schedule_date')
            ->orderBy('start_time')
            ->get();

        return view('member.booking', compact('class', 'schedules'));
    }


    public function payment($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->findOrFail($id);

        $setting = \App\Models\PaymentSetting::first();

        return view('member.payment', compact('booking', 'setting'));
    }


    public function store(Request $request, $id)
    {
        $class = TennisClass::where('is_active', true)->findOrFail($id);

        if ($class->type !== 'regular') {
            abort(404);
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:per_meeting,monthly_4,monthly_6',
            'schedule_ids' => 'required|array',
            'schedule_ids.*' => 'required|integer|distinct|exists:class_schedules,id',
            'participants' => 'required|integer|min:1',
        ]);

        $price = 0;
        $package = null;
        $sessions = 1;

        if ($validated['payment_method'] === 'per_meeting') {
            $price = 95000;
        } elseif ($validated['payment_method'] === 'monthly_4') {
            $price = 360000;
            $package = 'Monthly Membership 4 Sessions';
            $sessions = 4;
        } elseif ($validated['payment_method'] === 'monthly_6') {
            $price = 530000;
            $package = 'Monthly Membership 6 Sessions';
            $sessions = 6;
        }

        if (count($validated['schedule_ids']) !== $sessions) {
            return back()
                ->withErrors([
                    'schedule_ids' => "Paket yang dipilih membutuhkan tepat {$sessions} jadwal.",
                ])
                ->withInput();
        }

        $participants = (int) $validated['participants'];
        $totalPrice = $price * $participants;

        $booking = DB::transaction(function () use (
            $validated,
            $class,
            $price,
            $package,
            $sessions,
            $participants,
            $totalPrice
        ) {
            $schedules = ClassSchedule::whereIn('id', $validated['schedule_ids'])
                ->where('class_id', $class->id)
                ->where('is_available', true)
                ->whereDate('schedule_date', '>=', today())
                ->orderBy('schedule_date')
                ->orderBy('start_time')
                ->lockForUpdate()
                ->get();

            if ($schedules->count() !== $sessions) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'schedule_ids' => 'Ada jadwal yang tidak tersedia atau tidak sesuai dengan kelas.',
                ]);
            }

            foreach ($schedules as $schedule) {
                $bookedParticipants = DB::table('booking_schedules')
                    ->join('bookings', 'booking_schedules.booking_id', '=', 'bookings.id')
                    ->where('booking_schedules.schedule_id', $schedule->id)
                    ->sum('bookings.participants');

                $remainingCapacity = $schedule->capacity - $bookedParticipants;

                if ($participants > $remainingCapacity) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'participants' => "Jadwal {$schedule->schedule_date->format('d-m-Y')} hanya memiliki sisa {$remainingCapacity} slot.",
                    ]);
                }
            }

            $firstSchedule = $schedules->first();

            $booking = Booking::create([
                'user_id' => Auth::id(),
                'class_name' => $class->name,
                'booking_date' => $firstSchedule->schedule_date->format('Y-m-d'),
                'booking_time' => $firstSchedule->start_time,
                'schedule_id' => $firstSchedule->id,
                'status' => 'pending',
                'total_price' => $totalPrice,
                'payment_status' => 'unpaid',
                'payment_method' => $validated['payment_method'],
                'membership_package' => $package,
                'total_sessions' => $sessions,
                'used_sessions' => 0,
                'participants' => $participants,
            ]);

            $booking->schedules()->attach($schedules->pluck('id'));

            return $booking;
        });

        return redirect()->route('member.booking.payment', $booking->id);
    }


    public function myBookings(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Booking::where('user_id', Auth::id())
            ->with(['schedules.location']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('class_name', 'like', "%{$search}%")
                    ->orWhere('membership_package', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $bookings = $query->latest()
            ->paginate(5)
            ->withQueryString();

        return view('member.my-bookings', compact('bookings'));
    }

    public function private($id)
    {
        $class = TennisClass::where('is_active', true)
            ->where('type', 'private')
            ->findOrFail($id);

        $regularSchedules = ClassSchedule::whereHas('tennisClass', function ($query) {
            $query->where('type', 'regular');
        })
        ->where('is_available', true)
        ->whereDate('schedule_date', '>=', today())
        ->get();

        $schedules = ClassSchedule::with('location')
            ->where('class_id', $class->id)
            ->whereDate('schedule_date', '>=', today())
            ->where('is_available', true)
            ->orderBy('schedule_date')
            ->orderBy('start_time')
            ->get()
            ->filter(function ($privateSchedule) use ($regularSchedules) {

                foreach ($regularSchedules as $regularSchedule) {

                    if (
                        $privateSchedule->schedule_date->format('Y-m-d') ===
                        $regularSchedule->schedule_date->format('Y-m-d')
                    ) {
                        if (
                            $privateSchedule->start_time < $regularSchedule->end_time &&
                            $privateSchedule->end_time > $regularSchedule->start_time
                        ) {
                            return false;
                        }
                    }
                }

                return true;
            });

        return view('member.private-booking', compact('class', 'schedules'));
    }

}