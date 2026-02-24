@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('dashboard.orders') }}" class="text-primary-600 hover:text-primary-700 flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to My Orders
            </a>
        </div>
        
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @if($order->status === 'completed') bg-green-100 text-green-800
                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                
                <div class="bg-gray-50 rounded-md p-4 mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-lg font-medium text-gray-900">Order Summary</h2>
                        <span class="text-sm text-gray-500">{{ $order->created_at->format('F j, Y g:i A') }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Payment Status</p>
                            <p class="font-medium">{{ ucfirst($order->payment_status) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Payment Method</p>
                            <p class="font-medium">{{ $order->payment_method ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Items</p>
                            <p class="font-medium">{{ $order->items_count }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Amount</p>
                            <p class="font-medium">৳{{ number_format($order->total, 2) }}</p>
                        </div>
                    </div>
                </div>
                
                <h2 class="text-lg font-medium text-gray-900 mb-4">Items</h2>
                
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="border border-gray-200 rounded-md overflow-hidden">
                            <div class="flex items-center p-4">
                                <div class="flex-shrink-0 h-16 w-16 bg-gray-200 rounded-md overflow-hidden">
                                    @if(!empty($item->listing->images) && count($item->listing->images) > 0)
                                        <img src="{{ $item->listing->images[0] }}" alt="{{ $item->listing->title }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center">
                                            <span class="text-xs text-gray-400">No image</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex justify-between">
                                        <h3 class="text-sm font-medium text-gray-900">{{ $item->listing->title }}</h3>
                                        <p class="text-sm font-medium text-gray-900">৳{{ number_format($item->price, 2) }}</p>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if(auth()->id() === $order->user_id && !$order->deliveryTask && $order->status !== 'completed')
                    <form action="{{ route('deliveries.create-for-order', $order->id) }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                            </svg>
                            Request Delivery
                        </button>
                    </form>
                @elseif($order->deliveryTask)
                    <div class="mt-4 bg-gray-50 p-4 rounded-md">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-900">
                                Delivery Status: 
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $order->deliveryTask->status === 'available' ? 'bg-green-100 text-green-800' : 
                                       ($order->deliveryTask->status === 'assigned' ? 'bg-blue-100 text-blue-800' : 
                                        ($order->deliveryTask->status === 'completed' ? 'bg-purple-100 text-purple-800' : 
                                         'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($order->deliveryTask->status) }}
                                </span>
                            </span>
                        </div>
                        
                        @if($order->deliveryTask->status === 'assigned')
                            <p class="mt-2 text-sm text-gray-500">
                                Delivery person: {{ $order->deliveryTask->deliveryPerson->name }}
                            </p>
                        @endif
                        
                        <a href="{{ route('deliveries.show', $order->deliveryTask->id) }}" class="mt-2 inline-flex items-center text-sm text-primary-600 hover:text-primary-700">
                            View delivery details
                            <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                @endif
                
                @if($order->notes)
                    <div class="mt-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-2">Order Notes</h2>
                        <div class="bg-gray-50 rounded-md p-4">
                            <p class="text-sm text-gray-700">{{ $order->notes }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection