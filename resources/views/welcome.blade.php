@extends('layouts.app')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="relative">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1506484381205-f7945653044d?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1770&q=80" alt="Food sharing">
            <div class="absolute inset-0 bg-gray-900 opacity-70"></div>
        </div>
        <div class="relative mx-auto max-w-7xl py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Reduce Food Waste, Help Others</h1>
            <p class="mt-6 max-w-3xl text-xl text-gray-300">Buy discounted food, donate surplus, or volunteer to make a difference in your community. Together we can reduce food waste and fight hunger.</p>
            <div class="mt-10 flex gap-4">
                <a href="{{ route('marketplace') }}" class="inline-block rounded-md border border-transparent bg-primary-600 px-8 py-3 text-base font-medium text-white hover:bg-primary-700">Browse Marketplace</a>
                <a href="{{ route('donations') }}" class="inline-block rounded-md border border-white bg-transparent px-8 py-3 text-base font-medium text-white hover:bg-white hover:text-gray-900">View Donations</a>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="bg-white py-16 px-4 sm:px-6 lg:py-24 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center">How It Works</h2>
            <p class="mt-4 max-w-3xl mx-auto text-center text-xl text-gray-500">Our platform makes it easy to reduce food waste and help those in need.</p>
            
            <div class="mt-12 grid gap-8 md:grid-cols-3">
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xl font-bold">1</div>
                    <h3 class="mt-4 text-xl font-medium text-gray-900">List Surplus Food</h3>
                    <p class="mt-2 text-base text-gray-500">Restaurants, grocery stores, and individuals can list surplus food at a discounted price or donate it.</p>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xl font-bold">2</div>
                    <h3 class="mt-4 text-xl font-medium text-gray-900">Purchase or Reserve</h3>
                    <p class="mt-2 text-base text-gray-500">Users can purchase discounted food or reserve donated items for pickup.</p>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xl font-bold">3</div>
                    <h3 class="mt-4 text-xl font-medium text-gray-900">Pickup & Enjoy</h3>
                    <p class="mt-2 text-base text-gray-500">Visit the specified location during the pickup window to collect your food and reduce waste.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-primary-700">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                <span class="block">Ready to join our mission?</span>
                <span class="block text-primary-200">Sign up today and start making a difference.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                <div class="inline-flex rounded-md shadow">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-primary-600 bg-white hover:bg-primary-50">
                        Sign Up
                    </a>
                </div>
                <div class="ml-3 inline-flex rounded-md shadow">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
