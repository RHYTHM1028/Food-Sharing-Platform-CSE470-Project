<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::where('status', 'available')
            ->latest()
            ->paginate(10);
            
        return view('volunteer.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('volunteer.create');
    }

    /**
     * Store a newly created volunteer task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'location' => 'required|string|max:255',
            'people_required' => 'required|integer|min:1|max:20',
        ]);
        
        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'location' => $validated['location'],
            'people_required' => $validated['people_required'],
            'status' => 'available',
            'created_by' => auth()->id(),
        ]);
        
        return redirect()->route('volunteer.show', $task->id)
            ->with('success', 'Volunteer task created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::with(['creator', 'volunteers' => function($query) {
                $query->wherePivot('status', 'confirmed');
            }])
            ->withCount(['volunteers as confirmed_volunteers_count' => function($query) {
                $query->where('task_volunteers.status', 'confirmed');
            }])
            ->findOrFail($id);
        
        $isVolunteering = $task->hasVolunteer(auth()->id());
        $isCreator = $task->created_by === auth()->id();
        
        return view('volunteer.show', compact('task', 'isVolunteering', 'isCreator'));
    }

    /**
     * Volunteer for a task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function volunteer(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        
        // Check if the user created this task
        if ($task->created_by === auth()->id()) {
            return back()->with('error', 'You cannot volunteer for your own task.');
        }
        
        // Check if task is available
        if ($task->status !== 'available') {
            return back()->with('error', 'This task is no longer available for volunteering.');
        }
        
        // Check if we've reached the required number of volunteers
        if ($task->hasReachedCapacity()) {
            return back()->with('error', 'This task has already reached the required number of volunteers.');
        }
        
        // Check if user is already volunteering for this task
        if ($task->hasVolunteer(auth()->id())) {
            return back()->with('error', 'You are already volunteering for this task.');
        }
        
        // Add user as volunteer
        $task->volunteers()->attach(auth()->id(), ['status' => 'confirmed']);
        
        // Update task status if we've reached capacity
        if ($task->hasReachedCapacity()) {
            $task->update(['status' => 'assigned']);
        }
        
        return redirect()->route('volunteer.show', $task->id)
            ->with('success', 'You have successfully volunteered for this task!');
    }

    /**
     * Mark a task as completed.
     * Note: Only task creator can mark as completed
     */
    public function complete(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        
        // Only task creator can mark as completed
        if ($task->created_by !== auth()->id()) {
            return back()->with('error', 'Only the task creator can mark this task as completed.');
        }
        
        // Update all volunteer statuses to completed
        if ($task->volunteers()->count() > 0) {
            $task->volunteers()->updateExistingPivot(
                $task->volunteers()->pluck('users.id')->toArray(),
                ['status' => 'completed']
            );
        }
        
        $task->update(['status' => 'completed']);
        
        return redirect()->route('volunteer.show', $task->id)
            ->with('success', 'Task marked as completed. Thank you to all volunteers!');
    }

    /**
     * Cancel volunteering for a task.
     */
    public function cancel(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        
        // Check if user is volunteering for this task
        if (!$task->hasVolunteer(auth()->id())) {
            return back()->with('error', 'You are not volunteering for this task.');
        }
        
        // Check if the task is already completed
        if ($task->status === 'completed') {
            return back()->with('error', 'You cannot cancel volunteering for a completed task.');
        }
        
        // Remove user from volunteers
        $task->volunteers()->updateExistingPivot(auth()->id(), ['status' => 'cancelled']);
        
        // Update volunteer count
        $confirmedCount = $task->volunteers()
            ->wherePivot('status', 'confirmed')
            ->count();
        
        // If task was fully assigned but now has fewer volunteers, set it back to available
        if ($confirmedCount < $task->people_required && $task->status === 'assigned') {
            $task->update(['status' => 'available']);
        }
        
        return redirect()->route('dashboard.tasks')
            ->with('success', 'You have cancelled your volunteering for this task.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        if ($task->created_by !== Auth::id()) {
            return back()->with('error', 'You are not authorized to delete this task.');
        }
        
        $task->delete();
        
        return redirect()->route('volunteer')
            ->with('success', 'Task deleted successfully.');
    }

    /**
     * Delete a task.
     */
    public function delete($id)
    {
        $task = Task::findOrFail($id);
        
        // Only task creator can delete
        if ($task->created_by !== auth()->id()) {
            return back()->with('error', 'Only the task creator can delete this task.');
        }
        
        $task->delete();
        
        return redirect()->route('volunteer')
            ->with('success', 'Task deleted successfully.');
    }
}