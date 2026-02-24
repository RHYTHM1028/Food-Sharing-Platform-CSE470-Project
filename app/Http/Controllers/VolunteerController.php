<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VolunteerController extends Controller
{
    /**
     * Display a listing of volunteer tasks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get available tasks that still need volunteers
        $tasks = Task::where('status', 'available')
            ->withCount(['volunteers as confirmed_volunteers_count' => function($query) {
                $query->where('task_volunteers.status', 'confirmed');
            }])
            ->latest()
            ->paginate(12);
            
        return view('volunteer.index', compact('tasks'));
    }
    
    /**
     * Show the form for creating a new volunteer task.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('volunteer.create');
    }
    
    /**
     * Display the specified volunteer task.
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
     * Display user's volunteer dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        // Tasks user is volunteering for
        $volunteeredTasks = auth()->user()->volunteeredTasks()
            ->wherePivot('status', 'confirmed')
            ->with(['creator', 'volunteers' => function($query) {
                $query->wherePivot('status', 'confirmed');
            }])
            ->withCount(['volunteers as confirmed_volunteers_count' => function($query) {
                $query->where('task_volunteers.status', 'confirmed');
            }])
            ->latest()
            ->get();
            
        // Tasks created by user
        $createdTasks = Task::where('created_by', auth()->id())
            ->with(['volunteers' => function($query) {
                $query->wherePivot('status', 'confirmed');
            }])
            ->withCount(['volunteers as confirmed_volunteers_count' => function($query) {
                $query->where('task_volunteers.status', 'confirmed');
            }])
            ->latest()
            ->get();
            
        return view('dashboard.volunteer', compact('volunteeredTasks', 'createdTasks'));
    }
}