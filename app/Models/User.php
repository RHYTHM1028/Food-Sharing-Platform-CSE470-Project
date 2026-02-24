<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // For MySQL JSON column type:
        //'address' => 'array',
        // If your address is stored as a TEXT/VARCHAR column containing JSON:
        // Don't cast address since we're manually handling JSON encoding/decoding
    ];

    /**
     * Get the tasks the user has volunteered for.
     */
    public function volunteeredTasks()
    {
        return $this->belongsToMany(Task::class, 'task_volunteers')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
