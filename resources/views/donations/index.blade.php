@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Food Donations</h1>
        
        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-8">
            <form action="{{ route('donations') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label for="food_type" class="block text-sm font-medium text-gray-700 mb-1">Food Type</label>
                    <select id="food_type" name="food_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                        <option value="">All Types</option>
                        <option value="meal" {{ request('food_type') == 'meal' ? 'selected' : '' }}>Prepared Meals</option>
                        <option value="grocery" {{ request('food_type') == 'grocery' ? 'selected' : '' }}>Groceries</option>
                        <option value="bakery" {{ request('food_type') == 'bakery' ? 'selected' : '' }}>Bakery</option>
                        <option value="dairy" {{ request('food_type') == 'dairy' ? 'selected' : '' }}>Dairy</option>
                        <option value="produce" {{ request('food_type') == 'produce' ? 'selected' : '' }}>Produce</option>
                        <option value="meat" {{ request('food_type') == 'meat' ? 'selected' : '' }}>Meat</option>
                        <option value="other" {{ request('food_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" id="location" name="location" value="{{ request('location') }}" placeholder="City or area" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Donations Grid -->
        @if(isset($donations) && $donations->count() > 0)
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($donations as $donation)
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="relative h-48 w-full">
                            @if(!empty($donation->images) && count($donation->images) > 0)
                                <img src="{{ $donation->images[0] }}" alt="{{ $donation->title }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">No image</span>
                                </div>
                            @endif
                            <div class="absolute top-0 right-0 p-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-accent-100 text-accent-800">
                                    Free
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-medium text-gray-900 truncate">{{ $donation->title }}</h3>
                            <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $donation->description }}</p>
                            <div class="mt-3 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $donation->address['city'] ?? 'Location not specified' }}</span>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                <span>Expires: {{ $donation->expiry_date->format('M d, Y') }}</span>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('donations.show', $donation->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-accent-600 hover:bg-accent-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $donations->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No donations available</h3>
                <p class="mt-1 text-sm text-gray-500">Check back later or try changing your search filters.</p>
                
                @auth
                    <div class="mt-6">
                        <p class="text-sm text-gray-500">Have food to donate?</p>
                        <a href="{{ route('listings.create') }}" class="mt-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-accent-600 hover:bg-accent-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500">
                            Create a Donation
                        </a>
                    </div>
                @endauth
            </div>
        @endif
    </div>
</div>
@endsection