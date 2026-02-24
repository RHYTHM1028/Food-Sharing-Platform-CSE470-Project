@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('dashboard.listings') }}" class="text-primary-600 hover:text-primary-700 flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to My Listings
            </a>
        </div>
        
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Listing</h1>
                
                <form action="{{ route('listings.update', $listing->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $listing->title) }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="sm:col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description', $listing->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="sm:col-span-3">
                            <label for="food_type" class="block text-sm font-medium text-gray-700">Food Type</label>
                            <select name="food_type" id="food_type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                <option value="meal" {{ old('food_type', $listing->food_type) === 'meal' ? 'selected' : '' }}>Prepared Meal</option>
                                <option value="grocery" {{ old('food_type', $listing->food_type) === 'grocery' ? 'selected' : '' }}>Grocery</option>
                                <option value="bakery" {{ old('food_type', $listing->food_type) === 'bakery' ? 'selected' : '' }}>Bakery</option>
                                <option value="dairy" {{ old('food_type', $listing->food_type) === 'dairy' ? 'selected' : '' }}>Dairy</option>
                                <option value="produce" {{ old('food_type', $listing->food_type) === 'produce' ? 'selected' : '' }}>Produce</option>
                                <option value="meat" {{ old('food_type', $listing->food_type) === 'meat' ? 'selected' : '' }}>Meat</option>
                                <option value="other" {{ old('food_type', $listing->food_type) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('food_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="sm:col-span-3">
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                            <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity', $listing->quantity) }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="sm:col-span-3">
                            <label for="original_price" class="block text-sm font-medium text-gray-700">Original Price (BDT)</label>
                            <input type="number" name="original_price" id="original_price" min="0" step="0.01" value="{{ old('original_price', $listing->original_price) }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('original_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="sm:col-span-3">
                            <label for="discounted_price" class="block text-sm font-medium text-gray-700">Discounted Price (BDT)</label>
                            <input type="number" name="discounted_price" id="discounted_price" min="0" step="0.01" value="{{ old('discounted_price', $listing->discounted_price) }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('discounted_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="sm:col-span-3">
                            <label for="preparation_date" class="block text-sm font-medium text-gray-700">Preparation Date</label>
                            <input type="date" name="preparation_date" id="preparation_date" value="{{ old('preparation_date', $listing->preparation_date->format('Y-m-d')) }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('preparation_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="sm:col-span-3">
                            <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                            <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', $listing->expiry_date->format('Y-m-d')) }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('expiry_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="sm:col-span-6">
                            <label for="images" class="block text-sm font-medium text-gray-700">
                                Images
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                            <span>Upload new images</span>
                                            <input id="images" name="images[]" type="file" class="sr-only" multiple>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, GIF up to 2MB
                                    </p>
                                </div>
                            </div>
                            @error('images')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        @if($listing->images && count($listing->images) > 0)
                            <div class="sm:col-span-6">
                                <label class="block text-sm font-medium text-gray-700">Current Images</label>
                                <div class="mt-2 grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-4">
                                    @foreach($listing->images as $index => $image)
                                        <div class="relative">
                                            <img src="{{ $image }}" alt="Listing Image" class="h-24 w-full object-cover rounded-md">
                                            <div class="absolute top-0 right-0 p-1">
                                                <input type="checkbox" name="delete_images[]" id="delete_image_{{ $index }}" value="{{ $index }}" class="hidden">
                                                <label for="delete_image_{{ $index }}" class="bg-white rounded-full p-1 cursor-pointer shadow-sm hover:bg-red-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                    </svg>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Check the images you want to delete</p>
                            </div>
                        @endif
                        
                        <div class="sm:col-span-6 border-t border-gray-200 pt-5">
                            <div class="text-lg font-medium text-gray-700 mb-4">Dietary Information</div>
                            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="vegetarian" name="dietary_info[vegetarian]" type="checkbox" value="1" {{ isset($listing->dietary_info['vegetarian']) ? 'checked' : '' }} class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="vegetarian" class="font-medium text-gray-700">Vegetarian</label>
                                    </div>
                                </div>
                                
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="vegan" name="dietary_info[vegan]" type="checkbox" value="1" {{ isset($listing->dietary_info['vegan']) ? 'checked' : '' }} class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="vegan" class="font-medium text-gray-700">Vegan</label>
                                    </div>
                                </div>
                                
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="gluten_free" name="dietary_info[gluten_free]" type="checkbox" value="1" {{ isset($listing->dietary_info['gluten_free']) ? 'checked' : '' }} class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="gluten_free" class="font-medium text-gray-700">Gluten Free</label>
                                    </div>
                                </div>
                                
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="dairy_free" name="dietary_info[dairy_free]" type="checkbox" value="1" {{ isset($listing->dietary_info['dairy_free']) ? 'checked' : '' }} class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="dairy_free" class="font-medium text-gray-700">Dairy Free</label>
                                    </div>
                                </div>
                                
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="nut_free" name="dietary_info[nut_free]" type="checkbox" value="1" {{ isset($listing->dietary_info['nut_free']) ? 'checked' : '' }} class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="nut_free" class="font-medium text-gray-700">Nut Free</label>
                                    </div>
                                </div>
                                
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="halal" name="dietary_info[halal]" type="checkbox" value="1" {{ isset($listing->dietary_info['halal']) ? 'checked' : '' }} class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="halal" class="font-medium text-gray-700">Halal</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="sm:col-span-6 border-t border-gray-200 pt-5">
                            <div class="text-lg font-medium text-gray-700 mb-4">Location & Pickup Details</div>
                            
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-6">
                                    <label for="address_street" class="block text-sm font-medium text-gray-700">Street Address</label>
                                    <input type="text" name="address[street]" id="address_street" value="{{ old('address.street', $listing->address['street'] ?? '') }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('address.street')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="sm:col-span-3">
                                    <label for="address_city" class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" name="address[city]" id="address_city" value="{{ old('address.city', $listing->address['city'] ?? '') }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('address.city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="sm:col-span-3">
                                    <label for="address_state" class="block text-sm font-medium text-gray-700">State/Division</label>
                                    <input type="text" name="address[state]" id="address_state" value="{{ old('address.state', $listing->address['state'] ?? '') }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('address.state')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="sm:col-span-3">
                                    <label for="address_postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                                    <input type="text" name="address[postal_code]" id="address_postal_code" value="{{ old('address.postal_code', $listing->address['postal_code'] ?? '') }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('address.postal_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="sm:col-span-3">
                                    <label for="available_from" class="block text-sm font-medium text-gray-700">Available From</label>
                                    <input type="datetime-local" name="available_from" id="available_from" value="{{ old('available_from', $listing->available_from ? $listing->available_from->format('Y-m-d\TH:i') : '') }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('available_from')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="sm:col-span-3">
                                    <label for="available_until" class="block text-sm font-medium text-gray-700">Available Until</label>
                                    <input type="datetime-local" name="available_until" id="available_until" value="{{ old('available_until', $listing->available_until ? $listing->available_until->format('Y-m-d\TH:i') : '') }}" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('available_until')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="sm:col-span-6">
                                    <label for="pickup_instructions" class="block text-sm font-medium text-gray-700">Pickup Instructions</label>
                                    <textarea name="pickup_instructions" id="pickup_instructions" rows="3" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('pickup_instructions', $listing->pickup_instructions) }}</textarea>
                                    @error('pickup_instructions')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-5">
                        <div class="flex justify-end">
                            <a href="{{ route('dashboard.listings') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Cancel
                            </a>
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection