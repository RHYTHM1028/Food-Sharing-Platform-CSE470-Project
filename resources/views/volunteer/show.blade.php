@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('volunteer') }}" class="text-primary-600 hover:text-primary-700 flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Volunteer Opportunities
            </a>
        </div>
        
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $task->title }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Created {{ $task->created_at->diffForHumans() }} by {{ $task->creator->name ?? 'Unknown' }}
                </p>
            </div>
            
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $task->description }}
                        </dd>
                    </div>
                    
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Date & Time</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $task->formatted_date_time }}
                        </dd>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Location</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $task->location }}
                        </dd>
                    </div>
                    
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Number of People Required</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $task->people_required }}
                        </dd>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if($task->status === 'available')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Available
                                </span>
                            @elseif($task->status === 'assigned')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Assigned
                                </span>
                            @elseif($task->status === 'completed')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    Completed
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($task->status) }}
                                </span>
                            @endif
                        </dd>
                    </div>
                    
                    @if($task->status === 'assigned' && $task->volunteer)
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Volunteer</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $task->volunteer->name }}
                            </dd>
                        </div>
                    @endif

                    <!-- Volunteer status section -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Volunteers</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="flex items-center">
                                <span class="text-sm font-medium mr-2">{{ $task->confirmed_volunteers_count }}/{{ $task->people_required }}</span>
                                
                                <!-- Progress bar -->
                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mr-2">
                                    <div class="bg-primary-600 h-2.5 rounded-full" style="width: {{ ($task->confirmed_volunteers_count / $task->people_required) * 100 }}%"></div>
                                </div>
                                
                                <span class="text-sm text-gray-500">
                                    {{ $task->available_spots }} {{ Str::plural('spot', $task->available_spots) }} left
                                </span>
                            </div>
                            
                            <!-- List of volunteers -->
                            @if($task->volunteers->isNotEmpty())
                                <div class="mt-3">
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Current Volunteers:</h4>
                                    <ul class="divide-y divide-gray-200">
                                        @foreach($task->volunteers as $volunteer)
                                            <li class="py-2 flex items-center">
                                                @if($volunteer->profile_image)
                                                    <img src="{{ $volunteer->profile_image }}" alt="{{ $volunteer->name }}" class="h-8 w-8 rounded-full mr-2">
                                                @else
                                                    <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center mr-2">
                                                        <span class="text-primary-800 font-medium">{{ strtoupper(substr($volunteer->name, 0, 1)) }}</span>
                                                    </div>
                                                @endif
                                                <span>{{ $volunteer->name }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
            
            <!-- Action buttons section -->
            @auth
                <!-- Action buttons section for the creator -->
                @if($isCreator)
                    <div class="mt-6 flex flex-wrap gap-2">
                        @if($task->status !== 'completed')
                            <form action="{{ route('tasks.complete', $task->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Mark as Completed
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('tasks.delete', $task->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this task?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Delete Task
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Action buttons section for volunteers -->
                @if(!$isCreator)
                    <div class="mt-6 flex flex-wrap gap-2">
                        @if($task->status === 'available' && !$isVolunteering && !$task->hasReachedCapacity())
                            <form action="{{ route('tasks.volunteer', $task->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    Volunteer
                                </button>
                            </form>
                        @elseif($isVolunteering && $task->status !== 'completed')
                            <form action="{{ route('tasks.cancel', $task->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Cancel Volunteering
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            @endauth
        </div>
    </div>
</div>
@endsection