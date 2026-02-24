<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;
class ReservationController extends Controller
{
    /**
     * Display a listing of the user's reservations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservations = Reservation::with(['donation.listing', 'donation.user'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
            
        return view('dashboard.reservations', compact('reservations'));
    }

    /**
     * Display the specified reservation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reservation = Reservation::with(['donation.listing', 'donation.user'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);
            
        return view('dashboard.reservations-show', compact('reservation'));
    }

    /**
     * Cancel a reservation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $reservation = Reservation::where('user_id', auth()->id())->findOrFail($id);
        
        // Only pending reservations can be cancelled
        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Only pending reservations can be cancelled.');
        }
        
        // Update reservation status
        $reservation->update(['status' => 'cancelled']);
        
        // Return the quantity to the listing
        $donation = $reservation->donation;
        if ($donation && $donation->listing) {
            $donation->listing->update([
                'quantity' => $donation->listing->quantity + $reservation->quantity
            ]);
            
            // If listing was fully reserved, update status
            if ($donation->listing->status === 'reserved') {
                $donation->listing->update(['status' => 'available']);
                $donation->update(['status' => 'available']);
            }
        }
        
        return redirect()->route('dashboard.reservations')
            ->with('success', 'Reservation cancelled successfully.');
    }
}