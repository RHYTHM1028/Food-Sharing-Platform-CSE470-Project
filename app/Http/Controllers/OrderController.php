<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Listing;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('orders.index', compact('orders'));
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
            'listing_id' => 'required|exists:listings,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $listing = Listing::findOrFail($request->listing_id);
        
        // Check if listing is available
        if ($listing->status !== 'available' || $listing->is_donation) {
            return back()->with('error', 'This listing is not available for purchase.');
        }
        
        // Check if quantity is available
        if ($request->quantity > $listing->quantity) {
            return back()->with('error', 'The requested quantity is not available.');
        }
        
        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $listing->discounted_price * $request->quantity,
            'status' => 'pending',
            'payment_status' => 'pending',
            'items_count' => $request->quantity,
        ]);
        
        // Create order item
        OrderItem::create([
            'order_id' => $order->id,
            'listing_id' => $listing->id,
            'quantity' => $request->quantity,
            'price' => $listing->discounted_price,
        ]);
        
        // Update listing quantity and status
        $remainingQuantity = $listing->quantity - $request->quantity;
        $listing->update([
            'quantity' => $remainingQuantity,
            'status' => $remainingQuantity > 0 ? 'available' : 'sold',
        ]);
        
        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Order placed successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('dashboard.orders')
                ->with('error', 'You are not authorized to view this order.');
        }
        
        $order->load('items.listing');
        
        return view('orders.show', compact('order'));
    }
}