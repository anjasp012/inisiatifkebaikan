<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    protected $guarded = ['id'];

    public function fundraiser(): BelongsTo
    {
        return $this->belongsTo(Fundraiser::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function isInternal()
    {
        return is_null($this->fundraiser_id);
    }

    public function getRequesterNameAttribute()
    {
        return $this->fundraiser ? $this->fundraiser->foundation_name : 'Internal (Admin)';
    }

    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }
}
