<?php

namespace App\Http\Controllers;

use App\Models\DeliveryTask;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class DeliveryTaskController extends Controller
{
    /**
     * Display a listing of the delivery tasks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = DeliveryTask::where('status', 'available')
            ->latest()
            ->paginate(10);
            
        return view('deliveries.index', compact('tasks'));
    }

    /**
     * Display the specified delivery task.
     *
     * @param  \App\Models\DeliveryTask  $deliveryTask
     * @return \Illuminate\Http\Response
     */
    public function show(DeliveryTask $deliveryTask)
    {
        $deliveryTask->load(['order.user', 'order.items.listing', 'creator', 'deliveryPerson']);
        
        return view('deliveries.show', compact('deliveryTask'));
    }

    /**
     * Create a delivery task for an order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function createForOrder(Order $order)
    {
        // Check if a delivery task already exists for this order
        if ($order->deliveryTask) {
            return redirect()->route('dashboard.orders')
                ->with('error', 'A delivery task already exists for this order.');
        }
        
        // Get pickup location from the first listing in the order
        $firstOrderItem = $order->items->first();
        $listing = $firstOrderItem->listing;
        
        $pickupLocation = '';
        if (isset($listing->address)) {
            $address = $listing->address;
            $pickupParts = [];
            if (!empty($address['street'])) $pickupParts[] = $address['street'];
            if (!empty($address['city'])) $pickupParts[] = $address['city'];
            if (!empty($address['state'])) $pickupParts[] = $address['state'];
            if (!empty($address['postal_code'])) $pickupParts[] = $address['postal_code'];
            
            $pickupLocation = implode(', ', $pickupParts);
        }
        
        // Get delivery location from user address
        $user = $order->user;
        $deliveryLocation = '';
        if ($user->address) {
            if (is_string($user->address)) {
                $address = json_decode($user->address, true);
            } else {
                $address = $user->address;
            }
            
            $deliveryParts = [];
            if (!empty($address['street'])) $deliveryParts[] = $address['street'];
            if (!empty($address['city'])) $deliveryParts[] = $address['city'];
            if (!empty($address['state'])) $deliveryParts[] = $address['state'];
            if (!empty($address['postal_code'])) $deliveryParts[] = $address['postal_code'];
            
            $deliveryLocation = implode(', ', $deliveryParts);
        }
        
        // Create delivery task
        DeliveryTask::create([
            'order_id' => $order->id,
            'created_by' => Auth::id(),
            'status' => 'available',
            'pickup_location' => $pickupLocation,
            'delivery_location' => $deliveryLocation,
            'instructions' => $listing->pickup_instructions ?? '',
        ]);
        
        return redirect()->route('dashboard.orders')
            ->with('success', 'Delivery task created successfully.');
    }

    /**
     * Volunteer for a delivery task.
     *
     * @param  \App\Models\DeliveryTask  $deliveryTask
     * @return \Illuminate\Http\Response
     */
    public function acceptDelivery(DeliveryTask $deliveryTask)
    {
        if ($deliveryTask->status !== 'available') {
            return back()->with('error', 'This delivery task is not available.');
        }
        
        $deliveryTask->update([
            'status' => 'assigned',
            'delivery_person_id' => Auth::id(),
        ]);
        
        return redirect()->route('dashboard.deliveries')
            ->with('success', 'You have successfully accepted this delivery task.');
    }

    /**
     * Mark a delivery task as completed.
     *
     * @param  \App\Models\DeliveryTask  $deliveryTask
     * @return \Illuminate\Http\Response
     */
    public function complete(DeliveryTask $deliveryTask)
    {
        if ($deliveryTask->delivery_person_id !== Auth::id()) {
            return back()->with('error', 'You are not assigned to this delivery task.');
        }
        
        $deliveryTask->update([
            'status' => 'completed',
            'delivered_time' => now(),
        ]);
        
        // Also update the order status
        $deliveryTask->order->update(['status' => 'completed']);
        
        return redirect()->route('dashboard.deliveries')
            ->with('success', 'Delivery task marked as completed.');
    }

    /**
     * Cancel a delivery task assignment.
     *
     * @param  \App\Models\DeliveryTask  $deliveryTask
     * @return \Illuminate\Http\Response
     */
    public function cancel(DeliveryTask $deliveryTask)
    {
        if ($deliveryTask->delivery_person_id !== Auth::id()) {
            return back()->with('error', 'You are not assigned to this delivery task.');
        }
        
        $deliveryTask->update([
            'status' => 'available',
            'delivery_person_id' => null,
        ]);
        
        return redirect()->route('dashboard.deliveries')
            ->with('success', 'Delivery assignment cancelled successfully.');
    }
}