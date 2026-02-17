<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentProof extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'claimed_amount' => 'decimal:2',
    ];

    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }

    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }
}
