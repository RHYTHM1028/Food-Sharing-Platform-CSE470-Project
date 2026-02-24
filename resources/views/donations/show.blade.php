@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('donations') }}" class="text-accent-600 hover:text-accent-700 flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Donations
            </a>
        </div>
        
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <!-- Image Gallery -->
                <div class="relative">
                    @if(!empty($listing->images) && count($listing->images) > 0)
                        <div class="h-96 bg-gray-200">
                            <img src="{{ $listing->images[0] }}" alt="{{ $listing->title }}" class="h-full w-full object-cover">
                        </div>
                        @if(count($listing->images) > 1)
                            <div class="mt-2 grid grid-cols-4 gap-2 px-4">
                                @foreach(array_slice($listing->images, 0, 4) as $index => $image)
                                    <div class="h-24 bg-gray-200 rounded-md overflow-hidden">
                                        <img src="{{ $image }}" alt="{{ $listing->title }} image {{ $index + 1 }}" class="h-full w-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="h-96 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">No image available</span>
                        </div>
                    @endif
                </div>
                
                <!-- Listing Details -->
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $listing->title }}</h1>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-accent-100 text-accent-800">
                                    Free Donation
                                </span>
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ ucfirst($listing->food_type) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <p class="mt-4 text-gray-700">{{ $listing->description }}</p>
                    
                    <dl class="mt-6 space-y-4">
                        <div class="flex items-center">
                            <dt class="text-sm font-medium text-gray-500 w-32">Quantity:</dt>
                            <dd class="text-sm text-gray-900">{{ $listing->quantity }} unit(s)</dd>
                        </div>
                        
                        <div class="flex items-center">
                            <dt class="text-sm font-medium text-gray-500 w-32">Preparation Date:</dt>
                            <dd class="text-sm text-gray-900">{{ $listing->preparation_date->format('M d, Y') }}</dd>
                        </div>
                        
                        <div class="flex items-center">
                            <dt class="text-sm font-medium text-gray-500 w-32">Expiry Date:</dt>
                            <dd class="text-sm text-gray-900">{{ $listing->expiry_date->format('M d, Y') }}</dd>
                        </div>
                        
                        @if(!empty($listing->formatted_dietary_info))
                            <div class="flex items-start">
                                <dt class="text-sm font-medium text-gray-500 w-32">Dietary Info:</dt>
                                <dd class="text-sm text-gray-900">
                                    <div class="flex flex-wrap gap-1">
                                        @if(count($listing->formatted_dietary_info) > 0)
                                            @foreach($listing->formatted_dietary_info as $info)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $info }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-gray-500">Not specified</span>
                                        @endif
                                    </div>
                                </dd>
                            </div>
                        @endif
                        
                        <div class="flex items-center">
                            <dt class="text-sm font-medium text-gray-500 w-32">Available From:</dt>
                            <dd class="text-sm text-gray-900">{{ $listing->available_from->format('M d, Y g:i A') }}</dd>
                        </div>
                        
                        <div class="flex items-center">
                            <dt class="text-sm font-medium text-gray-500 w-32">Available Until:</dt>
                            <dd class="text-sm text-gray-900">{{ $listing->available_until->format('M d, Y g:i A') }}</dd>
                        </div>
                        
                        <div class="flex items-start">
                            <dt class="text-sm font-medium text-gray-500 w-32">Pickup Location:</dt>
                            <dd class="text-sm text-gray-900">
                                {{ $listing->address['street'] ?? '' }}<br>
                                {{ $listing->address['city'] ?? '' }}{{ !empty($listing->address['state']) ? ', ' . $listing->address['state'] : '' }} {{ $listing->address['postal_code'] ?? '' }}<br>
                                {{ $listing->address['country'] ?? '' }}
                            </dd>
                        </div>
                        
                        @if(!empty($listing->pickup_instructions))
                            <div class="flex items-start">
                                <dt class="text-sm font-medium text-gray-500 w-32">Pickup Instructions:</dt>
                                <dd class="text-sm text-gray-900">{{ $listing->pickup_instructions }}</dd>
                            </div>
                        @endif
                    </dl>
                    
                    @auth
                        @if($listing->user_id !== auth()->id())
                            <div class="mt-8">
                                <form action="{{ route('donations.reserve', $listing->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="quantity" class="block text-sm font-medium text-gray-700">
                                            Quantity to Reserve (Available: {{ $listing->quantity }})
                                        </label>
                                        <input 
                                            type="number" 
                                            name="quantity" 
                                            id="quantity" 
                                            min="1" 
                                            max="{{ $listing->quantity }}" 
                                            value="1" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent-500 focus:ring-accent-500 sm:text-sm"
                                        >
                                    </div>
                                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent-600 hover:bg-accent-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500">
                                        Reserve Items
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="mt-8">
                                <div class="bg-gray-100 p-4 rounded-md">
                                    <p class="text-gray-700">This is your donation. You can manage it from your dashboard.</p>
                                    <a href="{{ route('dashboard.donations') }}" class="mt-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-accent-600 hover:bg-accent-700">
                                        Go to Dashboard
                                    </a>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="mt-8">
                            <div class="bg-gray-100 p-4 rounded-md">
                                <p class="text-gray-700">Please log in to reserve this donation.</p>
                                <a href="{{ route('login') }}" class="mt-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-accent-600 hover:bg-accent-700">
                                    Log In
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
            
            <!-- Donor Info -->
            <div class="border-t border-gray-200 px-6 py-5">
                <h3 class="text-lg font-medium text-gray-900">About the Donor</h3>
                <div class="mt-3 flex items-center">
                    <div class="flex-shrink-0">
                        @if($listing->user->profile_image)
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $listing->user->profile_image }}" alt="{{ $listing->user->name }}">
                        @else
                            <div class="h-10 w-10 rounded-full bg-accent-100 flex items-center justify-center">
                                <span class="text-accent-800 font-medium text-sm">{{ strtoupper(substr($listing->user->name, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ $listing->user->name }}</p>
                        <p class="text-sm text-gray-500">Member since {{ $listing->user->created_at->format('F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection