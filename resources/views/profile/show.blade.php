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
                    </ul>
                </nav>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="w-full md:w-3/4 lg:w-4/5">
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-2xl font-bold mb-6">My Profile</h1>
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                
                <div class="bg-white overflow-hidden sm:rounded-lg">
                    <!-- Profile Information -->
                    <div class="p-6 bg-white border-b border-gray-200 sm:px-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Profile Information</h3>
                            <span class="inline-flex rounded-md shadow-sm">
                                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">  
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Edit
                                </a>
                            </span>
                        </div>
                        
                        <!-- Profile Display -->
                        <div id="profile-display" class="mt-4">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->phone ?? 'Not provided' }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $formattedAddress ?: 'Not provided' }}</dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Member since</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('F j, Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    
                    <!-- Password Update Section -->
                    <div class="p-6 bg-white sm:px-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Password</h3>
                            <span class="inline-flex rounded-md shadow-sm">
                                <a href="#" id="show-password-form" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                    Change Password
                                </a>
                            </span>
                        </div>
                        
                        <div id="password-form" class="mt-4 hidden">
                            <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')
                                
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                                    <input type="password" name="current_password" id="current_password" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('current_password')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                                    <input type="password" name="password" id="password" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                
                                <div class="flex justify-end space-x-3">
                                    <button type="button" id="cancel-password-change" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        Cancel
                                    </button>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password form
        const showPasswordFormBtn = document.getElementById('show-password-form');
        const passwordForm = document.getElementById('password-form');
        const cancelPasswordBtn = document.getElementById('cancel-password-change');
        
        if (showPasswordFormBtn && passwordForm) {
            showPasswordFormBtn.addEventListener('click', function(e) {
                e.preventDefault();
                passwordForm.classList.remove('hidden');
                showPasswordFormBtn.classList.add('hidden');
            });
        }
        
        if (cancelPasswordBtn && passwordForm && showPasswordFormBtn) {
            cancelPasswordBtn.addEventListener('click', function() {
                passwordForm.classList.add('hidden');
                showPasswordFormBtn.classList.remove('hidden');
            });
        }
    });
</script>
@endpush
@endsection