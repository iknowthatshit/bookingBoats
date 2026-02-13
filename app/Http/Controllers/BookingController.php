<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boat;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class BookingController extends Controller
{
    public function create(Boat $boat, Request $request)
    {
        if (!$boat->availability) {
            return redirect()->route('home')
                ->with('error', 'Эта лодка временно недоступна для бронирования');
        }
        
        $bookedDates = [];
        $bookings = $boat->bookings()->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->where('end_date', '>=', Carbon::today())
            ->get();

        foreach ($bookings as $booking) {
            $period = CarbonPeriod::create($booking->start_date, $booking->end_date);
            foreach ($period as $date) {
                $bookedDates[] = $date->format('Y-m-d');
            }
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        if ($startDate && $endDate) {
            $validator = Validator::make(
                ['start_date' => $startDate, 'end_date' => $endDate],
                [
                    'start_date' => 'date|after_or_equal:today',
                    'end_date' => 'date|after_or_equal:start_date',
                ]
            );
            
            if ($validator->fails()) {
                $startDate = null;
                $endDate = null;
            }
        }
        
        return view('booking.create', compact('boat', 'startDate', 'endDate', 'bookedDates'));
    }

    public function store(Request $request, Boat $boat)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        
        $isAvailable = $this->checkBoatAvailability($boat, $startDate, $endDate);
        
        if (!$isAvailable) {
            return back()->withErrors([
                'dates' => 'К сожалению, лодка уже забронирована на выбранные даты. Пожалуйста, выберите другие даты.'
            ])->withInput();
        }

        $daysCount = $startDate->diffInDays($endDate) + 1;
        $totalPrice = $boat->price_per_day * $daysCount;

        $booking = Booking::create([
            'boat_id' => $boat->id,
            'user_id' => Auth::id(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'days_count' => $daysCount,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Бронирование успешно создано! Ожидайте подтверждения.');
    }

    public function index()
    {
        $bookings = Auth::user()->bookings()->with('boat')->orderBy('created_at', 'desc')->get();
            
        return view('booking.index', compact('bookings'));
    }

    public function cancel(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Недостаточно прав для отмены этого бронирования');
        }

        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.index')
                ->with('error', 'Можно отменить только бронирования со статусом "Ожидание"');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('bookings.index')
            ->with('success', 'Бронирование успешно отменено');
    }

    private function checkBoatAvailability(Boat $boat, $startDate, $endDate)
    {
        $conflictingBookings = $boat->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<', $startDate)
                            ->where('end_date', '>', $endDate);
                      });
            })
            ->exists();
            
        return !$conflictingBookings;
    }

    public function pay(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Недостаточно прав для оплаты этого бронирования');
        }

        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.index')
                ->with('error', 'Оплата доступна только для бронирований со статусом "Ожидание"');
        }

        $booking->update(['status' => 'completed']);

        return redirect()->route('bookings.index')
            ->with('success', 'Оплата прошла успешно! Бронирование оплачено.');
    }
}