@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow sm:rounded-lg">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="p-6 space-y-6">
                    <h3 class="text-lg font-medium text-gray-900">Edit Profile</h3>
                    
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" disabled
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm">
                        <p class="mt-2 text-sm text-gray-500">Email cannot be changed.</p>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address Fields -->
                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-700">Address</h4>
                        
                        <!-- Street -->
                        <div>
                            <label for="address_street" class="block text-sm font-medium text-gray-700">Street Address</label>
                            <input type="text" name="address[street]" id="address_street" 
                                value="{{ old('address.street', $addressComponents['street']) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('address.street')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- City -->
                        <div>
                            <label for="address_city" class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" name="address[city]" id="address_city" 
                                value="{{ old('address.city', $addressComponents['city']) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('address.city')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Division -->
                            <div>
                                <label for="address_state" class="block text-sm font-medium text-gray-700">Division</label>
                                <input type="text" name="address[state]" id="address_state" 
                                    value="{{ old('address.state', $addressComponents['state']) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                @error('address.state')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Postal Code -->
                            <div>
                                <label for="address_postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                                <input type="text" name="address[postal_code]" id="address_postal_code" 
                                    value="{{ old('address.postal_code', $addressComponents['postal_code']) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                @error('address.postal_code')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Country -->
                        <div>
                            <label for="address_country" class="block text-sm font-medium text-gray-700">Country</label>
                            <input type="text" name="address[country]" id="address_country" 
                                value="{{ old('address.country', $addressComponents['country'] ?? 'Bangladesh') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('address.country')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="px-6 py-3 bg-gray-50 flex justify-end space-x-3 rounded-b-lg">
                    <a href="{{ route('profile.show') }}" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Save Changes
                    </button>
                </div>

                <!-- Comment out or remove this debugging section when no longer needed -->
                <!--
                <div class="mt-4 p-4 bg-gray-100 rounded">
                    <h4 class="text-sm font-medium text-gray-700">Debug info:</h4>
                    <pre class="text-xs mt-2">User address: {{ var_export($user->address, true) }}</pre>
                    <pre class="text-xs mt-2">Address components: {{ var_export($addressComponents, true) }}</pre>
                </div>
                -->
            </form>
        </div>
    </div>
</div>
@endsection