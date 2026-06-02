<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    protected $fillable = [
        'trainer_id',
        'title',
        'description',
    ];

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function assignments()
    {
        return $this->hasMany(WorkoutAssignment::class);
    }
}