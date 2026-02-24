<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Helpers\FoodTypeImages;
use Carbon\Carbon;
class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Listing::where('status', 'available')
            ->where('is_donation', false);
            
        // Apply filters if they exist
        if ($request->has('food_type') && !empty($request->food_type)) {
            $query->where('food_type', $request->food_type);
        }
        
        if ($request->has('location') && !empty($request->location)) {
            $query->where('address->city', 'like', '%' . $request->location . '%')
                ->orWhere('address->state', 'like', '%' . $request->location . '%');
        }
        
        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('discounted_price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('discounted_price', 'desc');
                    break;
                case 'expiry':
                    $query->orderBy('expiry_date', 'asc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }
        
        $listings = $query->paginate(12);
            
        return view('marketplace.index', compact('listings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'food_type' => 'required|in:meal,grocery,bakery,dairy,produce,meat,other',
            'quantity' => 'required|integer|min:1',
            'original_price' => 'required|numeric|min:0',
            'discounted_price' => 'required|numeric|min:0|lte:original_price',
            'preparation_date' => 'required|date',
            'expiry_date' => 'required|date|after:preparation_date',
            'dietary_info' => 'nullable|array',
            'address.street' => 'required|string',
            'address.city' => 'required|string',
            'address.state' => 'nullable|string',
            'address.postal_code' => 'nullable|string',
            'available_from' => 'required|date',
            'available_until' => 'required|date|after:available_from',
            'pickup_instructions' => 'nullable|string',
        ]);

        // Get image based on food type
        $imagePath = FoodTypeImages::getImagePath($request->food_type);

        // Create the listing
        $listing = Listing::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'food_type' => $request->food_type,
            'quantity' => $request->quantity,
            'original_price' => $request->original_price,
            'discounted_price' => $request->discounted_price,
            'images' => [$imagePath],
            'preparation_date' => $request->preparation_date,
            'expiry_date' => $request->expiry_date,
            'dietary_info' => $request->dietary_info ?? [],
            'address' => [
                'street' => $request->input('address.street'),
                'city' => $request->input('address.city'),
                'state' => $request->input('address.state'),
                'postal_code' => $request->input('address.postal_code'),
                'country' => 'Bangladesh', // Default country
            ],
            'available_from' => $request->available_from,
            'available_until' => $request->available_until,
            'pickup_instructions' => $request->pickup_instructions,
            'status' => 'available',
            'is_donation' => false,
        ]);

        return redirect()->route('dashboard.listings')
            ->with('success', 'Listing created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function show(Listing $listing)
    {
        if ($listing->status === 'expired' || $listing->status === 'sold') {
            return redirect()->route('marketplace')
                ->with('error', 'This listing is no longer available.');
        }
        
        return view('marketplace.show', compact('listing'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function edit(Listing $listing)
    {
        // Check if the user owns this listing
        if ($listing->user_id != auth()->id()) {
            return redirect()->route('dashboard.listings')
                ->with('error', 'You are not authorized to edit this listing.');
        }
        
        // Return the edit view with the listing data
        return view('listings.edit', compact('listing'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Listing $listing)
    {
        // Check if user owns this listing
        if ($listing->user_id != auth()->id()) {
            return redirect()->route('dashboard.listings')
                ->with('error', 'You are not authorized to update this listing.');
        }
        
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'food_type' => 'required|string|in:meal,grocery,bakery,dairy,produce,meat,other',
            'quantity' => 'required|integer|min:1',
            'original_price' => 'required|numeric|min:0',
            'discounted_price' => 'required|numeric|min:0|lte:original_price',
            'preparation_date' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:preparation_date',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'nullable|integer',
            'dietary_info' => 'nullable|array',
            'address.street' => 'required|string',
            'address.city' => 'required|string',
            'address.state' => 'nullable|string',
            'address.postal_code' => 'nullable|string',
            'available_from' => 'required|date',
            'available_until' => 'required|date|after:available_from',
            'pickup_instructions' => 'nullable|string'
        ]);
        
        // Get image based on food type
        $imagePath = FoodTypeImages::getImagePath($request->food_type);

        // Update the listing
        $listing->update([
            'title' => $request->title,
            'description' => $request->description,
            'food_type' => $request->food_type,
            'quantity' => $request->quantity,
            'original_price' => $request->original_price,
            'discounted_price' => $request->discounted_price,
            'images' => [$imagePath],
            'preparation_date' => $request->preparation_date,
            'expiry_date' => $request->expiry_date,
            'dietary_info' => $request->dietary_info ?? [],
            'address' => [
                'street' => $request->input('address.street'),
                'city' => $request->input('address.city'),
                'state' => $request->input('address.state'),
                'postal_code' => $request->input('address.postal_code'),
                'country' => 'Bangladesh', // Set default country
            ],
            'available_from' => $request->available_from,
            'available_until' => $request->available_until,
            'pickup_instructions' => $request->pickup_instructions,
        ]);
        
        return redirect()->route('dashboard.listings')
            ->with('success', 'Listing updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Listing $listing)
    {
        if ($listing->user_id !== Auth::id()) {
            return redirect()->route('dashboard.listings')
                ->with('error', 'You are not authorized to delete this listing.');
        }
        
        // Delete images from storage if they exist
        if (!empty($listing->images)) {
            foreach ($listing->images as $image) {
                $path = str_replace(asset('storage/'), '', $image);
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
        
        $listing->delete();

        return redirect()->route('dashboard.listings')
            ->with('success', 'Listing deleted successfully.');
    }
    
    /**
     * Mark a listing as a donation.
     *
     * @param  \App\Models\Listing  $listing
     * @return \Illuminate\Http\Response
     */
    public function donate(Listing $listing)
    {
        if ($listing->user_id !== Auth::id()) {
            return redirect()->route('dashboard.listings')
                ->with('error', 'You are not authorized to modify this listing.');
        }
        
        $listing->update([
            'is_donation' => true,
            'discounted_price' => 0,
        ]);
        
        // Create donation record
        Donation::create([
            'listing_id' => $listing->id,
            'user_id' => Auth::id(),
            'status' => 'available',
        ]);

        return redirect()->route('dashboard.donations')
            ->with('success', 'Listing converted to donation successfully.');
    }
}