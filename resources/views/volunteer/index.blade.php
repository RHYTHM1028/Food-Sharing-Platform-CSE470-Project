@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Volunteer Opportunities</h1>
            
            @auth
                <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Create Task
                </a>
            @endauth
        </div>
        
        @if(isset($tasks) && $tasks->count() > 0)
            <div class="space-y-6">
                @foreach($tasks as $task)
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $task->title }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                {{ $task->formatted_date }} at {{ $task->formatted_time }}
                            </p>
                        </div>
                        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                            <p class="text-sm text-gray-500">{{ $task->description }}</p>
                            
                            <div class="mt-3 flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-1 text-sm text-gray-500">{{ $task->location }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center mt-4">
                                <div class="text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                    </svg>
                                    {{ $task->confirmed_volunteers_count }}/{{ $task->people_required }} volunteers
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
                        </div>
                        
                        @auth
                            <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-end space-x-3">
                                <a href="{{ route('tasks.show', $task->id) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    View Details
                                </a>
                                
                                @if($task->status === 'available' && $task->created_by !== auth()->id() && !$task->hasReachedCapacity())
                                    @if(!$task->hasVolunteer(auth()->id()))
                                        <form action="{{ route('tasks.volunteer', $task->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                                Volunteer
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('tasks.cancel', $task->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                @elseif($task->status === 'assigned' && $task->volunteer_id === auth()->id())
                                    <form action="{{ route('tasks.complete', $task->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            Mark Complete
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('tasks.cancel', $task->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            Cancel
                                        </button>
                                    </form>
                                @endif
                                
                                @if($task->created_by === auth()->id())
                                    <form action="{{ route('tasks.delete', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endauth
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $tasks->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No volunteer tasks available</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new volunteer task.</p>
                
                @auth
                    <div class="mt-6">
                        <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Create Task
                        </a>
                    </div>
                @endauth
            </div>
        @endif
    </div>
</div>
@endsection