<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllowanceEntry extends Model
{
    protected $fillable = [
        'trainer_id',
        'date',
        'type',
        'reason',
        'amount',
    ];

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }
}