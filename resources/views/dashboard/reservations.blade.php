
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
                <h1 class="text-2xl font-bold mb-6">My Reservations</h1>
                
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
                
                @if(count($reservations) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($reservations as $reservation)
                            <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
                                <div class="h-48 bg-gray-200 relative">
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
                                    
                                    <div class="absolute top-2 right-2 px-2 py-1 rounded text-sm font-medium
                                        @if($reservation->status === 'pending') bg-yellow-500 text-white
                                        @elseif($reservation->status === 'approved') bg-green-500 text-white
                                        @elseif($reservation->status === 'completed') bg-blue-500 text-white
                                        @else bg-red-500 text-white @endif">
                                        {{ ucfirst($reservation->status) }}
                                    </div>
                                </div>
                                
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $reservation->donation->listing->title }}</h3>
                                    <p class="text-gray-600 text-sm mb-4">
                                        {{ Str::limit($reservation->donation->listing->description, 100) }}
                                    </p>
                                    
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-sm text-gray-500">Reserved: {{ $reservation->quantity }} items</span>
                                        <span class="text-sm text-gray-500">{{ $reservation->created_at->format('M d, Y') }}</span>
                                    </div>
                                    
                                    <div class="flex gap-2">
                                        <a href="{{ route('reservations.show', $reservation->id) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            View Details
                                        </a>
                                        
                                        @if($reservation->status === 'pending')
                                            <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST" class="flex-1">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Are you sure you want to cancel this reservation?')" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50">
                                                    Cancel
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white p-6 rounded-lg shadow text-center">
                        <p class="text-gray-600 mb-4">You don't have any reservations yet.</p>
                        <a href="{{ route('donations') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                            Browse Donations
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection