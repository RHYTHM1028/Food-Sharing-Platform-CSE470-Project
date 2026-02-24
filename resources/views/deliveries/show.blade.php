@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('deliveries') }}" class="text-primary-600 hover:text-primary-700 flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Delivery Tasks
            </a>
        </div>
        
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delivery Task for Order #{{ $deliveryTask->order_id }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Created {{ $deliveryTask->created_at->diffForHumans() }}
                </p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if($deliveryTask->status === 'available')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Available
                                </span>
                            @elseif($deliveryTask->status === 'assigned')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Assigned
                                </span>
                            @elseif($deliveryTask->status === 'completed')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    Completed
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($deliveryTask->status) }}
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Pickup Location</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $deliveryTask->pickup_location }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Delivery Location</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $deliveryTask->delivery_location }}</dd>
                    </div>
                    @if($deliveryTask->instructions)
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Instructions</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $deliveryTask->instructions }}</dd>
                    </div>
                    @endif
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Order Created By</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $deliveryTask->order->user->name }}</dd>
                    </div>
                    
                    @if($deliveryTask->status === 'assigned' || $deliveryTask->status === 'completed')
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Delivery Person</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $deliveryTask->deliveryPerson->name }}
                            </dd>
                        </div>
                    @endif
                    
                    @if($deliveryTask->delivered_time)
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Delivered At</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $deliveryTask->delivered_time->format('F j, Y, g:i a') }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
            
            <div class="px-4 py-5 sm:px-6">
                <h4 class="text-lg font-medium text-gray-900">Order Items</h4>
                <div class="mt-4 space-y-4">
                    @foreach($deliveryTask->order->items as $item)
                        <div class="flex items-center p-4 border border-gray-200 rounded-md">
                            <div class="flex-shrink-0 h-16 w-16 bg-gray-200 rounded-md overflow-hidden">
                                @if(isset($item->listing->images) && count($item->listing->images) > 0)
                                    <img src="{{ $item->listing->images[0] }}" alt="{{ $item->listing->title }}" class="h-full w-full object-cover">
                                @endif
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="text-sm font-medium text-gray-900">{{ $item->listing->title }}</div>
                                <div class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            @auth
                <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-end space-x-3">
                    @if($deliveryTask->status === 'available' && auth()->id() !== $deliveryTask->order->user_id)
                        <form action="{{ route('deliveries.accept', $deliveryTask->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Accept Delivery
                            </button>
                        </form>
                    @elseif($deliveryTask->status === 'assigned' && $deliveryTask->delivery_person_id === auth()->id())
                        <form action="{{ route('deliveries.complete', $deliveryTask->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Mark as Delivered
                            </button>
                        </form>
                        <form action="{{ route('deliveries.cancel', $deliveryTask->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Cancel Delivery
                            </button>
                        </form>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection