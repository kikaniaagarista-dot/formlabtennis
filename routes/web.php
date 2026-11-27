<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AuthController;
use App\Models\Booking;
use App\Models\TennisClass;
use App\Models\Coach;
use App\Http\Controllers\Admin\TennisClassController;
use App\Http\Controllers\Admin\CoachController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Member\BookingController as MemberBookingController;
use App\Http\Controllers\Admin\PaymentSettingController;
use App\Http\Controllers\Admin\TrainingLocationController;
use App\Http\Controllers\Admin\ClassScheduleController;
use App\Http\Controllers\Admin\AdminNoteController;
use App\Http\Controllers\Member\PaymentController as MemberPaymentController;
use App\Http\Controllers\MidtransNotificationController;

Route::get('/', function () {
    return view('home');
});
// Webhook Midtrans (dipanggil server Midtrans, bukan oleh member)
Route::post('/midtrans/notification', MidtransNotificationController::class)
    ->name('midtrans.notification');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth'])->group(function () {

    Route::get('/member', function () {
        $user = auth()->user();

        $totalBookings = Booking::where('user_id', $user->id)->count();

        $confirmedBookings = Booking::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->count();

        $paidBookings = Booking::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->count();

        $upcomingBookings = Booking::where('user_id', $user->id)
            ->whereHas('schedules', function ($query) {
                $query->whereDate('schedule_date', '>=', today());
            })
            ->with('schedules.location')
            ->latest()
            ->take(3)
            ->get();

        $recentBookings = Booking::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('member.dashboard', compact(
            'user',
            'totalBookings',
            'confirmedBookings',
            'paidBookings',
            'upcomingBookings',
            'recentBookings'
        ));
    })->name('member.dashboard');

    Route::get('/classes', function () {
        $classes = TennisClass::where('is_active', true)->get();

        return view('member.classes', compact('classes'));
    })->name('member.classes');

    Route::get('/classes/{id}/book', [MemberBookingController::class, 'create'])
        ->name('member.booking.create');

    Route::post('/classes/{id}/book', [MemberBookingController::class, 'store'])
        ->name('member.booking.store');

    Route::get('/bookings/{booking}/payment', [MemberBookingController::class, 'payment'])
        ->name('member.booking.payment');

    Route::post('/bookings/{booking}/snap-token', [MemberPaymentController::class, 'createSnapToken'])
        ->middleware('throttle:10,1')
        ->name('member.booking.snap-token');

    Route::get('/bookings/{booking}/download-qris', function (Booking $booking) {

        abort_unless($booking->user_id === auth()->id(), 403);

        $setting = \App\Models\PaymentSetting::first();

        abort_unless($setting && $setting->is_active && $setting->qris_image, 404);

        abort_unless(
            Storage::disk('public')->exists($setting->qris_image),
            404
        );

        return Storage::disk('public')->download(
            $setting->qris_image,
            'FORMLAB-QRIS.png'
        );

    })->name('member.booking.download-qris');
    Route::get('/classes/{id}/private', [MemberBookingController::class, 'private'])
        ->name('member.private.create');

    Route::get('/my-bookings', [MemberBookingController::class, 'myBookings'])
        ->name('member.bookings.index');

});


Route::middleware(['admin'])->group(function () {
    Route::get('/admin', function () {
        $totalMembers = User::where('role', 'member')->count();
        $totalBookings = Booking::count();
        $activeClasses = TennisClass::where('is_active', true)->count();
        $totalRevenue = Booking::where('payment_status', 'paid')
            ->sum('total_price');

        $pendingPayment = Booking::whereIn('payment_status', ['unpaid', 'pending'])
            ->sum('total_price');
        return view('admin.dashboard', compact(
            'totalMembers',
            'totalBookings',
            'activeClasses',
            'totalRevenue',
            'pendingPayment'
        ));
    });

    Route::resource('/admin/bookings', BookingController::class)
        ->only(['index', 'update', 'destroy'])
        ->names('admin.bookings');


    Route::get('/admin/members', function () {
        $search = request('search');

        $query = User::where('role', 'member');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $members = $query->latest()->paginate(10)->withQueryString();

        return view('admin.members', compact('members'));
    });


    Route::delete('/admin/members/{member}', function (User $member) {
    $member->delete();

    return redirect('/admin/members')
        ->with('success', 'Member deleted successfully!');
    });

    Route::resource('/admin/classes', TennisClassController::class)
        ->except(['show'])
        ->names('admin.classes');

    Route::resource('/admin/coaches', CoachController::class)
        ->except(['show'])
        ->names('admin.coaches');

    Route::get('/admin/payments', function () {
        $search = request('search');
        $status = request('status');

        $query = Booking::with('user');

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('payment_status', $status);
        }

        $payments = $query->latest()->paginate(10)->withQueryString();

        return view('admin.payments', compact('payments'));
    });

    Route::get('/admin/settings', [PaymentSettingController::class, 'edit']);

    Route::put('/admin/settings', [PaymentSettingController::class, 'update'])
        ->name('admin.settings.update');

    Route::resource('admin/locations', TrainingLocationController::class)
    ->names('admin.locations');

    Route::resource('admin/schedules', ClassScheduleController::class)
    ->names('admin.schedules');

    Route::resource('admin/notes', AdminNoteController::class)
    ->names('admin.notes');
});

