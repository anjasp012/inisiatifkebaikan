<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'is_anonymous' => 'boolean',
        'payment_instructions' => 'array',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function paymentProofs()
    {
        return $this->hasMany(PaymentProof::class);
    }
}
