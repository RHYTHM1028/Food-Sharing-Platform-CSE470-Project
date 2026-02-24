
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
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('dashboard') && !request()->is('*listings*') && !request()->is('*orders*') && !request()->is('*donations*') && !request()->is('*tasks*') && !request()->is('*profile*') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700' }}">
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
                            <a href="{{ route('dashboard.tasks') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('dashboard.tasks') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700' }}">
                                Volunteer Tasks
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.deliveries') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('dashboard.deliveries') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700' }}">
                                Delivery Tasks
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.reservations') }}" class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('dashboard.reservations') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700' }}">
                                My Reservations
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="w-full md:w-3/4 lg:w-4/5">
            @if(request()->routeIs('dashboard') && !request()->is('*listings*') && !request()->is('*orders*') && !request()->is('*donations*') && !request()->is('*tasks*'))
                <!-- Dashboard Home -->
                <div class="p-6 bg-white rounded-lg shadow">
                    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
                            <h2 class="text-lg font-semibold mb-2">Welcome Back!</h2>
                            <p class="text-gray-600">Manage your listings, orders, and account settings from here.</p>
                        </div>
                        
                        <!-- Stats Summary Cards -->
                        <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
                            <h2 class="text-lg font-semibold mb-2">Your Listings</h2>
                            <p class="text-3xl font-bold text-primary-600">{{ $listingsCount ?? 0 }}</p>
                            <p class="text-gray-600">Active listings in the marketplace</p>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
                            <h2 class="text-lg font-semibold mb-2">Your Orders</h2>
                            <p class="text-3xl font-bold text-primary-600">{{ $ordersCount ?? 0 }}</p>
                            <p class="text-gray-600">Total orders processed</p>
                        </div>
                    </div>
                </div>
            
            @elseif(request()->is('*listings/create'))
                <!-- Create Listing -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center mb-6">
                        <a href="{{ route('dashboard.listings') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Back to Listings
                        </a>
                        <h1 class="text-2xl font-bold ml-4">Create New Listing</h1>
                    </div>
                    
                    <form action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="space-y-4">
                                <h2 class="text-lg font-semibold">Basic Information</h2>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Title</label>
                                    <input type="text" name="title" required value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('title')
                                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" required rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Food Type</label>
                                    <select name="food_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="meal" {{ old('food_type') == 'meal' ? 'selected' : '' }}>Prepared Meal</option>
                                        <option value="grocery" {{ old('food_type') == 'grocery' ? 'selected' : '' }}>Grocery</option>
                                        <option value="bakery" {{ old('food_type') == 'bakery' ? 'selected' : '' }}>Bakery</option>
                                        <option value="dairy" {{ old('food_type') == 'dairy' ? 'selected' : '' }}>Dairy</option>
                                        <option value="produce" {{ old('food_type') == 'produce' ? 'selected' : '' }}>Produce</option>
                                        <option value="meat" {{ old('food_type') == 'meat' ? 'selected' : '' }}>Meat</option>
                                        <option value="other" {{ old('food_type') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('food_type')
                                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" name="quantity" required min="1" value="{{ old('quantity', 1) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('quantity')
                                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Original Price</label>
                                        <input type="number" name="original_price" required min="0" value="{{ old('original_price', 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        @error('original_price')
                                            <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Discounted Price</label>
                                        <input type="number" name="discounted_price" required min="0" value="{{ old('discounted_price', 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        @error('discounted_price')
                                            <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="space-y-4">
                                <h2 class="text-lg font-semibold">Additional Information</h2>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Preparation Date</label>
                                    <input type="datetime-local" name="preparation_date" required value="{{ old('preparation_date', now()->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('preparation_date')
                                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                                    <input type="datetime-local" name="expiry_date" required value="{{ old('expiry_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('expiry_date')
                                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Images</label>
                                    <div class="mt-1 bg-blue-50 p-4 rounded-md">
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-blue-400 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm text-blue-700">A default image will be automatically assigned based on the food type selected.</span>
                                        </div>
                                    </div>
                                    @error('images')
                                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Dietary Information</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="dietary_info[vegetarian]" value="1" {{ old('dietary_info.vegetarian') ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                            <span class="ml-2 text-sm text-gray-600">Vegetarian</span>
                                        </label>
                                        
                                        <label class="flex items-center">
                                            <input type="checkbox" name="dietary_info[vegan]" value="1" {{ old('dietary_info.vegan') ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                            <span class="ml-2 text-sm text-gray-600">Vegan</span>
                                        </label>
                                        
                                        <label class="flex items-center">
                                            <input type="checkbox" name="dietary_info[gluten_free]" value="1" {{ old('dietary_info.gluten_free') ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                            <span class="ml-2 text-sm text-gray-600">Gluten Free</span>
                                        </label>
                                        
                                        <label class="flex items-center">
                                            <input type="checkbox" name="dietary_info[contains_nuts]" value="1" {{ old('dietary_info.contains_nuts') ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                            <span class="ml-2 text-sm text-gray-600">Contains Nuts</span>
                                        </label>
                                        
                                        <label class="flex items-center">
                                            <input type="checkbox" name="dietary_info[dairy_free]" value="1" {{ old('dietary_info.dairy_free') ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                            <span class="ml-2 text-sm text-gray-600">Dairy Free</span>
                                        </label>
                                        
                                        <label class="flex items-center">
                                            <input type="checkbox" name="dietary_info[halal]" value="1" {{ old('dietary_info.halal') ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                            <span class="ml-2 text-sm text-gray-600">Halal</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Pickup Information -->
                            <div class="md:col-span-2 space-y-4">
                                <h2 class="text-lg font-semibold">Pickup Information</h2>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Street Address</label>
                                        <input type="text" name="address[street]" required value="{{ old('address.street') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        @error('address.street')
                                            <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">City</label>
                                        <input type="text" name="address[city]" required value="{{ old('address.city', 'Dhaka') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        @error('address.city')
                                            <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">State/Division</label>
                                        <input type="text" name="address[state]" value="{{ old('address.state') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        @error('address.state')
                                            <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Postal Code</label>
                                        <input type="text" name="address[postal_code]" value="{{ old('address.postal_code') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        @error('address.postal_code')
                                            <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Available From</label>
                                        <input type="datetime-local" name="available_from" required value="{{ old('available_from', now()->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        @error('available_from')
                                            <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Available Until</label>
                                        <input type="datetime-local" name="available_until" required value="{{ old('available_until') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        @error('available_until')
                                            <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Pickup Instructions</label>
                                    <textarea name="pickup_instructions" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Any special instructions for pickup...">{{ old('pickup_instructions') }}</textarea>
                                    @error('pickup_instructions')
                                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-4">
                            <a href="{{ route('dashboard.listings') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h1a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h1v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z" />
                                </svg>
                                Create Listing
                            </button>
                        </div>
                    </form>
                </div>
            
            @elseif(request()->is('*listings/edit*'))
                <!-- Edit Listing -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center mb-6">
                        <a href="{{ route('dashboard.listings') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Back to Listings
                        </a>
                        <h1 class="text-2xl font-bold ml-4">Edit Listing</h1>
                    </div>
                    
                    <form action="{{ route('listings.update', $listing->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="space-y-4">
                                <h2 class="text-lg font-semibold">Basic Information</h2>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Title</label>
                                    <input type="text" name="title" required value="{{ old('title', $listing->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('title')
                                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" required rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('description', $listing->description) }}</textarea>
                                    @error('description')
                                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Food Type</label>
                                    <select name="food_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="meal" {{ old('food_type', $listing->food_type) == 'meal' ? 'selected' : '' }}>Prepared Meal</option>
                                        <option value="grocery" {{ old('food_type', $listing->food_type) == 'grocery' ? 'selected' : '' }}>Grocery</option>
                                        <option value="bakery" {{ old('food_type', $listing->food_type) == 'bakery' ? 'selected' : '' }}>Bakery</option>
                                        <option value="dairy" {{ old('food_type', $listing->food_type) == 'dairy' ? 'selected' : '' }}>Dairy</option>
                                        <option value="produce" {{ old('food_type', $listing->food_type) == 'produce' ? 'selected' : '' }}>Produce</option>
                                        <option value="meat" {{ old('food_type', $listing->food_type) == 'meat' ? 'selected' : '' }}>Meat</option>
                                        <option value="other" {{ old('food_type', $listing->food_type) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('food_type')
                                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" name="quantity" required min="1" value="{{ old('quantity', $listing->quantity) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('quantity')
                                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Original Price</label>
                                        <input type="number" name="original_price" required min="0" step="0.01" value="{{ old('original_price', $listing->original_price) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        @error('original_price')
                                            <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Discounted Price</label>
                                        <input type="number" name="discounted_price" required min="0" step="0.01" value="{{ old('discounted_price', $listing->discounted_price) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        @error('discounted_price')
                                            <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="space-y-4">
                                <h2 class="text-lg font-semibold">Additional Information</h2>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Preparation Date</label>
                                    <input type="date" name="preparation_date" required value="{{ old('preparation_date', $listing->preparation_date->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('preparation_date')
                                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                                    <input type="date" name="expiry_date" required value="{{ old('expiry_date', $listing->expiry_date->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('expiry_date')
                                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Image</label>
                                    <div class="mt-1 bg-blue-50 p-4 rounded-md">
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-blue-400 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm text-blue-700">A default image will be automatically assigned based on the food type selected.</span>
                                        </div>
                                        
                                        @if(!empty($listing->images))
                                            <div class="mt-3 flex items-center">
                                                <img src="{{ $listing->images[0] }}" alt="{{ $listing->title }}" class="h-24 w-24 object-cover rounded-md">
                                                <p class="ml-3 text-xs text-blue-600">Current image (will be updated based on food type)</p>
                                            </div>
                                        @endif
                                    </div>
                                    @error('images')
                                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Dietary Information</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="dietary_info[vegetarian]" value="1" {{ isset($listing->dietary_info['vegetarian']) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                            <span class="ml-2 text-sm text-gray-600">Vegetarian</span>
                                        </label>
                                        
                                        <label class="flex items-center">
                                            <input type="checkbox" name="dietary_info[vegan]" value="1" {{ isset($listing->dietary_info['vegan']) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                            <span class="ml-2 text-sm text-gray-600">Vegan</span>
                                        </label>
                                        
                                        <label class="flex items-center">
                                            <input type="checkbox" name="dietary_info[gluten_free]" value="1" {{ isset($listing->dietary_info['gluten_free']) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                            <span class="ml-2 text-sm text-gray-600">Gluten Free</span>
                                        </label>
                                        
                                        <label class="flex items-center">
                                            <input type="checkbox" name="dietary_info[contains_nuts]" value="1" {{ isset($listing->dietary_info['contains_nuts']) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                            <span class="ml-2 text-sm text-gray-600">Contains Nuts</span>
                                        </label>
                                        
                                        <label class="flex items-center">
                                            <input type="checkbox" name="dietary_info[dairy_free]" value="1" {{ isset($listing->dietary_info['dairy_free']) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                            <span class="ml-2 text-sm text-gray-600">Dairy Free</span>
                                        </label>
                                        
                                        <label class="flex items-center">
                                            <input type="checkbox" name="dietary_info[halal]" value="1" {{ isset($listing->dietary_info['halal']) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                            <span class="ml-2 text-sm text-gray-600">Halal</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Pickup Information -->
                            <div class="md:col-span-2 space-y-4">
                                <h2 class="text-lg font-semibold">Pickup Information</h2>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Street Address</label>
                                        <input type="text" name="address[street]" required value="{{ old('address.street', $listing->address['street'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        @error('address.street')
                                            <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">City</label>
                                        <input type="text" name="address[city]" required value="{{ old('address.city', $listing->address['city'] ?? 'Dhaka') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        @error('address.city')
                                            <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">State/Division</label>
                                        <input type="text" name="address[state]" value="{{ old('address.state', $listing->address['state'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        @error('address.state')
                                            <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Postal Code</label>
                                        <input type="text" name="address[postal_code]" value="{{ old('address.postal_code', $listing->address['postal_code'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        @error('address.postal_code')
                                            <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Available From</label>
                                        <input type="datetime-local" name="available_from" required value="{{ old('available_from', $listing->available_from ? $listing->available_from->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        @error('available_from')
                                            <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Available Until</label>
                                        <input type="datetime-local" name="available_until" required value="{{ old('available_until', $listing->available_until ? $listing->available_until->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        @error('available_until')
                                            <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Pickup Instructions</label>
                                    <textarea name="pickup_instructions" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Any special instructions for pickup...">{{ old('pickup_instructions', $listing->pickup_instructions) }}</textarea>
                                    @error('pickup_instructions')
                                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-4">
                            <a href="{{ route('dashboard.listings') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Update Listing
                            </button>
                        </div>
                    </form>
                </div>
            
            @elseif(request()->routeIs('dashboard.listings'))
                <!-- My Listings -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">My Listings</h1>
                        <a href="{{ route('listings.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Create New Listing
                        </a>
                    </div>

                    @if(isset($listings) && count($listings) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($listings as $listing)
                                <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
                                    <div class="h-48 bg-gray-200 relative">
                                        @if(isset($listing->images) && count($listing->images) > 0)
                                            <img src="{{ $listing->images[0] }}" alt="{{ $listing->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                No image
                                            </div>
                                        @endif
                                        <div class="absolute top-2 right-2 px-2 py-1 rounded text-sm font-medium
                                            {{ $listing->status === 'available' ? 'bg-green-500 text-white' : 
                                               ($listing->status === 'reserved' ? 'bg-yellow-500 text-white' : 'bg-gray-500 text-white') }}">
                                            {{ ucfirst($listing->status) }}
                                        </div>
                                    </div>
                                    
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $listing->title }}</h3>
                                        <p class="text-gray-600 text-sm mb-4">
                                            {{ Str::limit($listing->description, 100) }}
                                        </p>
                                        
                                        <div class="flex justify-between items-center mb-4">
                                            <div>
                                                <span class="text-gray-400 line-through mr-2">৳{{ $listing->original_price }}</span>
                                                <span class="text-primary-600 font-bold">৳{{ $listing->discounted_price }}</span>
                                            </div>
                                            <span class="text-sm text-gray-500">Qty: {{ $listing->quantity }}</span>
                                        </div>
                                        
                                        <div class="flex gap-2">
                                            <a href="{{ route('listings.edit', $listing->id) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                                Edit
                                            </a>
                                            
                                            <form action="{{ route('listings.destroy', $listing->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Are you sure you want to delete this listing?')" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-error-500 bg-white hover:bg-error-50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                            
                                            @if($listing->status === 'available')
                                                <form action="{{ route('listings.donate', $listing->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-accent-500 bg-white hover:bg-accent-50">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                                        </svg>
                                                        Donate
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white rounded-lg shadow p-6 text-center">
                            <p class="text-gray-600 mb-4">You haven't created any listings yet.</p>
                            <a href="{{ route('listings.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                                Create Your First Listing
                            </a>
                        </div>
                    @endif
                </div>
                
            @elseif(request()->routeIs('dashboard.orders'))
                <!-- My Orders -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h1 class="text-2xl font-bold mb-6">My Orders</h1>
                    
                    @if(isset($orders) && count($orders) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($orders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->items_count }} items</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                       ($order->status === 'processing' ? 'bg-yellow-100 text-yellow-800' : 
                                                        'bg-gray-100 text-gray-800') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">৳{{ $order->total }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('orders.show', $order->id) }}" class="text-primary-600 hover:text-primary-900">View Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-white p-6">
                            <p class="text-gray-600">You don't have any orders yet.</p>
                        </div>
                    @endif
                </div>
                
            @elseif(request()->routeIs('dashboard.donations'))
                <!-- My Donations -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h1 class="text-2xl font-bold mb-6">My Donations</h1>
                    
                    @if(isset($donations) && count($donations) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($donations as $donation)
                                <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
                                    <div class="h-48 bg-gray-200 relative">
                                        @if(isset($donation->images) && count($donation->images) > 0)
                                            <img src="{{ $donation->images[0] }}" alt="{{ $donation->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                No image
                                            </div>
                                        @endif
                                        <div class="absolute top-2 right-2 px-2 py-1 rounded text-sm font-medium bg-accent-500 text-white">
                                            Donation
                                        </div>
                                    </div>
                                    
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $donation->title }}</h3>
                                        <p class="text-gray-600 text-sm mb-4">
                                            {{ Str::limit($donation->description, 100) }}
                                        </p>
                                        
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="text-sm text-gray-500">Qty: {{ $donation->quantity }}</span>
                                            <span class="text-sm text-gray-500">Status: {{ ucfirst($donation->status) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white p-6">
                            <p class="text-gray-600">You haven't made any donations yet.</p>
                        </div>
                    @endif
                </div>
                
            @elseif(request()->routeIs('dashboard.tasks'))
                <!-- Volunteer Tasks -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h1 class="text-2xl font-bold mb-6">Volunteer Tasks</h1>
                    
                    <!-- Tasks I've Created Section -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">Tasks I've Created</h2>
                        @if(isset($createdTasks) && $createdTasks->count() > 0)
                            <div class="space-y-4">
                                @foreach($createdTasks as $task)
                                    <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="text-lg font-semibold">{{ $task->title }}</h3>
                                                <p class="text-gray-600 mt-1">{{ $task->description }}</p>
                                                
                                                <div class="mt-3 flex items-center">
                                                    <svg class="h-5 w-5 text-gray-400 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-sm text-gray-500">
                                                        {{ $task->date->format('M d, Y') }} at {{ $task->formatted_time }}
                                                    </span>
                                                </div>
                                                
                                                <div class="mt-1 flex items-center">
                                                    <svg class="h-5 w-5 text-gray-400 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-sm text-gray-500">{{ $task->location }}</span>
                                                </div>
                                                
                                                <div class="mt-1 flex items-center">
                                                    <svg class="h-5 w-5 text-gray-400 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                                    </svg>
                                                    <span class="text-sm text-gray-500">
                                                        {{ $task->confirmed_volunteers_count }}/{{ $task->people_required }} volunteers
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $task->status === 'available' ? 'bg-green-100 text-green-800' : 
                                                       ($task->status === 'assigned' ? 'bg-yellow-100 text-yellow-800' : 
                                                        'bg-gray-100 text-gray-800') }}">
                                                    {{ ucfirst($task->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            <a href="{{ route('volunteer.show', $task->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                                                View Details
                                            </a>
                                            
                                            @if($task->status !== 'completed')
                                                <form action="{{ route('tasks.complete', $task->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                                                        Mark as Completed
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('tasks.delete', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-red-600 bg-white hover:bg-red-50">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                        Delete Task
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-white p-6 rounded-lg border border-gray-200">
                                <p class="text-gray-600">You haven't created any volunteer tasks yet.</p>
                                <a href="{{ route('tasks.create') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                                    Create a Task
                                </a>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Tasks I've Volunteered For Section -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Tasks I've Volunteered For</h2>
                        @if(isset($volunteeredTasks) && $volunteeredTasks->count() > 0)
                            <div class="space-y-4">
                                @foreach($volunteeredTasks as $task)
                                    <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="text-lg font-semibold">{{ $task->title }}</h3>
                                                <p class="text-gray-600 mt-1">{{ $task->description }}</p>
                                                
                                                <div class="mt-3 flex items-center">
                                                    <svg class="h-5 w-5 text-gray-400 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-sm text-gray-500">
                                                        {{ $task->date->format('M d, Y') }} at {{ $task->formatted_time }}
                                                    </span>
                                                </div>
                                                
                                                <div class="mt-1 flex items-center">
                                                    <svg class="h-5 w-5 text-gray-400 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-sm text-gray-500">{{ $task->location }}</span>
                                                </div>
                                                
                                                <div class="mt-1 flex items-center">
                                                    <svg class="h-5 w-5 text-gray-400 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                                    </svg>
                                                    <span class="text-sm text-gray-500">
                                                        {{ $task->confirmed_volunteers_count }}/{{ $task->people_required }} volunteers
                                                    </span>
                                                </div>
                                                
                                                <div class="mt-1 flex items-center">
                                                    <svg class="h-5 w-5 text-gray-400 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-sm text-gray-500">Organizer: {{ $task->creator->name }}</span>
                                                </div>
                                            </div>
                                            
                                            <div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $task->status === 'available' ? 'bg-green-100 text-green-800' : 
                                                       ($task->status === 'assigned' ? 'bg-yellow-100 text-yellow-800' : 
                                                        'bg-gray-100 text-gray-800') }}">
                                                    {{ ucfirst($task->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            <a href="{{ route('volunteer.show', $task->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                                                View Details
                                            </a>
                                            
                                            @if($task->status !== 'completed')
                                                <form action="{{ route('tasks.cancel', $task->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-red-600 bg-white hover:bg-red-50">
                                                        Cancel Volunteering
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-white p-6 rounded-lg border border-gray-200">
                                <p class="text-gray-600">You haven't volunteered for any tasks yet.</p>
                                <a href="{{ route('volunteer') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                                    Browse Volunteer Opportunities
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            
            @elseif(request()->routeIs('dashboard.deliveries'))
                <!-- Delivery Tasks -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h1 class="text-2xl font-bold mb-6">Delivery Tasks</h1>
                    
                    @if(isset($deliveryTasks) && count($deliveryTasks) > 0)
                        <div class="space-y-4">
                            @foreach($deliveryTasks as $task)
                                <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h2 class="text-lg font-semibold">Order #{{ $task->order_id }} Delivery</h2>
                                            
                                            <div class="mt-3 flex items-center">
                                                <svg class="h-5 w-5 text-gray-400 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="text-sm text-gray-500">Pickup: {{ $task->pickup_location }}</span>
                                            </div>
                                            
                                            <div class="mt-1 flex items-center">
                                                <svg class="h-5 w-5 text-gray-400 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="text-sm text-gray-500">Delivery: {{ $task->delivery_location }}</span>
                                            </div>
                                            
                                            @if($task->status === 'assigned' && $task->delivery_person_id)
                                                <div class="mt-1 flex items-center">
                                                    <svg class="h-5 w-5 text-gray-400 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-sm text-gray-500">Delivery Person: {{ $task->deliveryPerson->name }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $task->status === 'available' ? 'bg-green-100 text-green-800' : 
                                                   ($task->status === 'assigned' ? 'bg-yellow-100 text-yellow-800' : 
                                                    'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($task->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <a href="{{ route('deliveries.show', $task->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                                            View Details
                                        </a>
                                        
                                        @if($task->status === 'available' && auth()->id() !== $task->order->user_id)
                                            <form action="{{ route('deliveries.accept', $task->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700">
                                                    Accept Delivery
                                                </button>
                                            </form>
                                        @elseif($task->status === 'assigned' && $task->delivery_person_id === auth()->id())
                                            <form action="{{ route('deliveries.complete', $task->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                                                    Mark as Delivered
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('deliveries.cancel', $task->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                                                    Cancel Delivery
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white p-6">
                            <p class="text-gray-600">You don't have any delivery tasks.</p>
                            <a href="{{ route('deliveries') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                                Browse Available Deliveries
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
