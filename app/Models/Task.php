<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'location',
        'people_required',
        'status',
        'volunteer_id',
        'created_by',
    ];

    /**
     * Explicitly cast date fields to proper types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * The attributes that should be treated as dates.
     *
     * @var array<int, string>
     */
    protected $dates = ['date', 'created_at', 'updated_at'];

    /**
     * Get the user who created the task.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all volunteers for this task (many-to-many).
     */
    public function volunteers()
    {
        return $this->belongsToMany(User::class, 'task_volunteers')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    /**
     * Get confirmed volunteers count.
     *
     * @return int
     */
    public function getConfirmedVolunteersCountAttribute()
    {
        return $this->volunteers()
            ->wherePivot('status', 'confirmed')
            ->count();
    }

    /**
     * Check if the task has reached its volunteer capacity.
     *
     * @return bool
     */
    public function hasReachedCapacity()
    {
        return $this->confirmed_volunteers_count >= $this->people_required;
    }

    /**
     * Check if a specific user is a volunteer for this task.
     *
     * @param int $userId
     * @return bool
     */
    public function hasVolunteer($userId)
    {
        return $this->volunteers()
            ->where('users.id', $userId)
            ->wherePivot('status', 'confirmed')
            ->exists();
    }

    /**
     * Check if the task is available for volunteering.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->status === 'available' && !$this->hasReachedCapacity();
    }

    /**
     * Check if the task still needs more volunteers.
     *
     * @return bool
     */
    public function needsMoreVolunteers()
    {
        return $this->confirmed_volunteers_count < $this->people_required;
    }

    /**
     * Get available volunteer spots remaining.
     *
     * @return int
     */
    public function getAvailableSpotsAttribute()
    {
        return max(0, $this->people_required - $this->confirmed_volunteers_count);
    }

    /**
     * Get the formatted date and time together.
     *
     * @return string
     */
    public function getFormattedDateTimeAttribute()
    {
        if (!$this->date || !$this->time) {
            return 'Not specified';
        }
        
        $date = $this->date instanceof Carbon ? $this->date : Carbon::parse($this->date);
        $time = $this->time instanceof Carbon ? $this->time : Carbon::parse($this->time);
        
        return $date->format('F j, Y') . ' at ' . $time->format('g:i A');
    }

    /**
     * Accessor for formatted date.
     *
     * @return string
     */
    public function getFormattedDateAttribute()
    {
        if (!$this->date) {
            return 'Not specified';
        }
        
        $date = $this->date instanceof Carbon ? $this->date : Carbon::parse($this->date);
        return $date->format('F j, Y');
    }

    /**
     * Accessor for formatted time.
     *
     * @return string
     */
    public function getFormattedTimeAttribute()
    {
        if (!$this->time) {
            return 'Not specified';
        }
        
        return date('g:i A', strtotime($this->time));
    }
}