<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Order;
use App\Models\Donation;
use App\Models\Task;
use App\Models\DeliveryTask;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class DashboardController extends Controller
{
    /**
     * Display the dashboard home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listingsCount = Listing::where('user_id', Auth::id())
            ->where('status', 'available')
            ->count();
            
        $ordersCount = Order::where('user_id', Auth::id())
            ->count();
            
        $donationsCount = Listing::where('user_id', Auth::id())
            ->where('is_donation', true)
            ->count();
            
        $tasksCount = Task::where('volunteer_id', Auth::id())
            ->where('status', 'assigned')
            ->count();
            
        return view('dashboard', compact('listingsCount', 'ordersCount', 'donationsCount', 'tasksCount'));
    }
    
    /**
     * Display the user's listings.
     *
     * @return \Illuminate\Http\Response
     */
    public function listings()
    {
        $listings = Listing::where('user_id', Auth::id())
            ->where('is_donation', false)
            ->latest()
            ->get();
            
        return view('dashboard', compact('listings'));
    }
    
    /**
     * Display the user's orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->get();
            
        return view('dashboard', compact('orders'));
    }
    
    /**
     * Display the user's donations.
     *
     * @return \Illuminate\Http\Response
     */
    public function donations()
    {
        $donations = Listing::where('user_id', Auth::id())
            ->where('is_donation', true)
            ->latest()
            ->get();
            
        return view('dashboard', compact('donations'));
    }
    
    /**
     * Display volunteer tasks.
     *
     * @return \Illuminate\Http\Response
     */
    public function tasks()
    {
        // Tasks created by the authenticated user
        $createdTasks = Task::where('created_by', Auth::id())
            ->with(['volunteers' => function($query) {
                $query->wherePivot('status', 'confirmed');
            }])
            ->withCount(['volunteers as confirmed_volunteers_count' => function($query) {
                $query->where('task_volunteers.status', 'confirmed');
            }])
            ->latest()
            ->get();
        
        // Tasks the user is volunteering for
        $volunteeredTasks = auth()->user()->volunteeredTasks()
            ->wherePivot('status', 'confirmed')
            ->with(['creator', 'volunteers' => function($query) {
                $query->wherePivot('status', 'confirmed');
            }])
            ->withCount(['volunteers as confirmed_volunteers_count' => function($query) {
                $query->where('task_volunteers.status', 'confirmed');
            }])
            ->latest()
            ->get();
            
        return view('dashboard', compact('createdTasks', 'volunteeredTasks'));
    }
    
    /**
     * Display delivery tasks.
     *
     * @return \Illuminate\Http\Response
     */
    public function deliveries()
    {
        $myDeliveries = DeliveryTask::where('delivery_person_id', Auth::id())->latest()->get();
        $createdDeliveries = DeliveryTask::where('created_by', Auth::id())->latest()->get();
        
        $deliveryTasks = $myDeliveries->merge($createdDeliveries)->sortByDesc('created_at');
            
        return view('dashboard', compact('deliveryTasks'));
    }
}