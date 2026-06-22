<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
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

protected static function booted()
{
    // Keeps a permanent record of who/what changes subscription status,
    // since an unexplained active-flip was observed once during MoMo
    // testing and never reproduced. Cheap to keep; only fires on actual
    // status changes, not on every save.
    static::updating(function ($subscription) {
        if ($subscription->isDirty('status')) {
            Log::channel('single')->error('SUBSCRIPTION STATUS CHANGE', [
                'subscription_id' => $subscription->id,
                'from'             => $subscription->getOriginal('status'),
                'to'               => $subscription->status,
                'trace'            => (new \Exception())->getTraceAsString(),
            ]);
        }
    });
}
}