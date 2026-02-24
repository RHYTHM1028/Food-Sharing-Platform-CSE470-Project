@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Delivery Tasks</h1>
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
        
        @if(isset($tasks) && $tasks->count() > 0)
            <div class="space-y-6">
                @foreach($tasks as $task)
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Order #{{ $task->order_id }} Delivery</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                Created {{ $task->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Pickup Location</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $task->pickup_location }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Delivery Location</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $task->delivery_location }}</dd>
                                </div>
                                
                                @if($task->instructions)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Instructions</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $task->instructions }}</dd>
                                </div>
                                @endif
                            </dl>
                            
                            @auth
                                <div class="mt-6 flex justify-end space-x-3">
                                    <a href="{{ route('deliveries.show', $task->id) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        View Details
                                    </a>
                                    
                                    @if($task->status === 'available' && auth()->id() !== $task->order->user_id)
                                        <form action="{{ route('deliveries.accept', $task->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                                Accept Delivery
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $tasks->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No delivery tasks available</h3>
                <p class="mt-1 text-sm text-gray-500">There are currently no available delivery tasks.</p>
            </div>
        @endif
    </div>
</div>
@endsection