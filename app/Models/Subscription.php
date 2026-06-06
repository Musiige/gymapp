<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
   protected $fillable = [
    'user_id',
    'membership_id',
    'start_date',
    'end_date',
    'status',
    'custom_price',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }
    public function payment()
{
    return $this->hasOne(Payment::class);
}

public function getEffectivePriceAttribute()
{
    return $this->custom_price ?? $this->membership->price;
}
}