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
        'momo_reference_id',
        'momo_status',
        'momo_amount_requested',
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

/**
 * The real outstanding amount, computed live rather than trusting the
 * `balance` column directly. `balance` is only updated by applyMomoResult()
 * on a confirmed successful payment — while a payment is pending, failed,
 * or just created, `balance` sits at its default (0), which looks like
 * "fully paid" even though nothing has been paid. This accessor is safe
 * to use anywhere "how much do they still owe" needs to be displayed.
 */
public function getOutstandingBalanceAttribute()
{
    if ($this->status === 'paid') {
        return 0;
    }

    return max((float) $this->amount_due - (float) $this->amount_paid, 0);
}
}