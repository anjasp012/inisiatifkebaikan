<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Distribution extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'distribution_date' => 'date',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : 'https://placehold.co/600x400/e2e8f0/475569?text=Bukti+Penyaluran';
    }
}
