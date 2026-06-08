<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'admin_id',
        'title',
        'message',
        'recipient_type',
        'recipient_ids',
    ];

    protected $casts = [
        'recipient_ids' => 'array',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}