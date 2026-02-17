<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'response_data' => 'array',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
}
