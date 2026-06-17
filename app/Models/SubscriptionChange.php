<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionChange extends Model
{
    protected $fillable = [
        'user_id',
        'old_membership_id',
        'new_membership_id',
        'changed_by',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function oldMembership()
    {
        return $this->belongsTo(Membership::class, 'old_membership_id');
    }

    public function newMembership()
    {
        return $this->belongsTo(Membership::class, 'new_membership_id');
    }
}