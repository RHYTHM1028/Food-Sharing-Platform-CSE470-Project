<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Listing;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Listing::where('is_donation', true)
            ->where('status', 'available');
            
        // Apply filters if they exist
        if ($request->has('food_type') && !empty($request->food_type)) {
            $query->where('food_type', $request->food_type);
        }
        
        if ($request->has('location') && !empty($request->location)) {
            $query->where('address->city', 'like', '%' . $request->location . '%')
                ->orWhere('address->state', 'like', '%' . $request->location . '%');
        }
        
        $donations = $query->latest()->paginate(12);
            
        return view('donations.index', compact('donations'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function show(Listing $listing)
    {
        if (!$listing->is_donation) {
            abort(404);
        }
        
        if ($listing->status !== 'available') {
            return redirect()->route('donations')
                ->with('error', 'This donation is no longer available.');
        }
        
        return view('donations.show', compact('listing'));
    }

    /**
     * Reserve a donation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reserve(Request $request, $id)
    {
        $listing = Listing::findOrFail($id);
        $donation = Donation::where('listing_id', $listing->id)->first();
        
        // Check if donation exists
        if (!$donation) {
            return back()->with('error', 'This donation is not available.');
        }
        
        // Check if user is trying to reserve their own donation
        if ($donation->user_id === auth()->id()) {
            return back()->with('error', 'You cannot reserve your own donation.');
        }
        
        // Validate quantity
        $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:' . $listing->quantity
            ]
        ]);
        
        $quantity = $request->input('quantity');
        
        // Create the reservation
        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'donation_id' => $donation->id,
            'quantity' => $quantity,
            'status' => 'pending'
        ]);
        
        // Update the listing quantity
        $listing->update([
            'quantity' => $listing->quantity - $quantity
        ]);
        
        // If all quantity is reserved, update status
        if ($listing->quantity <= 0) {
            $listing->update(['status' => 'reserved']);
            $donation->update(['status' => 'claimed']);
        }
        
        return redirect()->route('dashboard.reservations')
            ->with('success', 'You have successfully reserved ' . $quantity . ' items from this donation.');
    }
}