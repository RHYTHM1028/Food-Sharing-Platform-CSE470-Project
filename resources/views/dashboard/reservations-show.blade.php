
@extends('layouts.app')

@section('content')
<div class="container py-6">
    <div class="flex flex-wrap">
        <!-- Sidebar -->
        <div class="w-full md:w-1/4 lg:w-1/5 pr-0 md:pr-4 mb-4 md:mb-0">
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-lg font-semibold mb-4">Dashboard Menu</h2>
                <nav>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('dashboard') && !request()->is('*listings*') && !request()->is('*orders*') && !request()->is('*donations*') && !request()->is('*tasks*') && !request()->is('*profile*') && !request()->is('*reservations*') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700' }}">
                                Dashboard Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('profile.show') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700' }}">
                                My Profile
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.listings') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->is('*listings*') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700' }}">
                                My Listings
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.orders') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('dashboard.orders') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700' }}">
                                My Orders
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.donations') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('dashboard.donations') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700' }}">
                                My Donations
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.reservations') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('dashboard.reservations') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700' }}">
                                My Reservations
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.tasks') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('dashboard.tasks') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700' }}">
                                Volunteer Tasks
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.deliveries') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('dashboard.deliveries') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700' }}">
                                Delivery Tasks
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="w-full md:w-3/4 lg:w-4/5">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-6">
                    <a href="{{ route('dashboard.reservations') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Reservations
                    </a>
                    <h1 class="text-2xl font-bold ml-4">Reservation Details</h1>
                </div>
                
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
                            <div class="h-64 bg-gray-200 relative">
                                @php
                                    $images = [];
                                    if (!empty($reservation->donation->listing->images)) {
                                        if (is_string($reservation->donation->listing->images)) {
                                            $images = json_decode($reservation->donation->listing->images);
                                        } else {
                                            $images = $reservation->donation->listing->images;
                                        }
                                    }
                                @endphp
                                
                                @if(!empty($images) && count($images) > 0)
                                    <img src="{{ $images[0] }}" alt="{{ $reservation->donation->listing->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                        <span class="text-gray-400">No image</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="p-4">
                                <h2 class="text-xl font-bold text-gray-900">{{ $reservation->donation->listing->title }}</h2>
                                <p class="mt-2 text-gray-600">{{ $reservation->donation->listing->description }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Reservation Information</h3>
                            
                            <div class="mb-4">
                                <div class="inline-flex px-2.5 py-0.5 rounded-full text-sm font-medium
                                    @if($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($reservation->status === 'approved') bg-green-100 text-green-800
                                    @elseif($reservation->status === 'completed') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800 @endif">
                                    Status: {{ ucfirst($reservation->status) }}
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Reservation ID</p>
                                    <p class="font-medium">#{{ $reservation->id }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">Quantity Reserved</p>
                                    <p class="font-medium">{{ $reservation->quantity }} items</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">Reserved On</p>
                                    <p class="font-medium">{{ $reservation->created_at->format('F j, Y g:i A') }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">Donor</p>
                                    <p class="font-medium">{{ $reservation->donation->user->name }}</p>
                                </div>
                            </div>
                            
                            @if($reservation->status === 'pending')
                                <div class="mt-6">
                                    <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Are you sure you want to cancel this reservation?')" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50">
                                            Cancel Reservation
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Donation Details</h3>
                            
                            <div class="grid grid-cols-1 gap-4">
                                @php
                                    $address = is_string($reservation->donation->listing->address) 
                                        ? json_decode($reservation->donation->listing->address, true) 
                                        : $reservation->donation->listing->address;
                                @endphp
                                
                                <div>
                                    <p class="text-sm text-gray-500">Pickup Location</p>
                                    <p class="font-medium">
                                        {{ $address['street'] ?? '' }}<br>
                                        {{ $address['city'] ?? '' }}, {{ $address['state'] ?? '' }} {{ $address['postal_code'] ?? '' }}
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">Pickup Instructions</p>
                                    <p class="font-medium">{{ $reservation->donation->listing->pickup_instructions ?? 'No specific instructions provided.' }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">Expiry Date</p>
                                    <p class="font-medium">{{ \Carbon\Carbon::parse($reservation->donation->listing->expiry_date)->format('F j, Y') }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">Available Until</p>
                                    <p class="font-medium">{{ \Carbon\Carbon::parse($reservation->donation->listing->available_until)->format('F j, Y g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection