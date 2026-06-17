<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_id',
        'amount_due',
        'amount_paid',
        'balance',
        'status',
        'payment_method',
        'transaction_id',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function markedByTrainer()
{
    return $this->belongsTo(User::class, 'marked_by_trainer_id');
}

public function markedByAdmin()
{
    return $this->belongsTo(User::class, 'marked_by_admin_id');
}
}