<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $paymentStatus = $request->input('payment_status');

        $query = Booking::with('user');

        // Pencarian nama member, kelas, dan tanggal
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('class_name', 'like', "%{$search}%")
                    ->orWhere('booking_date', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter status booking
        if ($status) {
            $query->where('status', $status);
        }

        // Filter status pembayaran
        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();

        return view('admin.bookings', compact('bookings'));
    }


    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
            'payment_status' => 'required|in:unpaid,paid',
        ]);

        $booking->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        return redirect('/admin/bookings')
            ->with('success', 'Booking updated successfully!');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect('/admin/bookings')
            ->with('success', 'Booking deleted successfully!');
    }
}
