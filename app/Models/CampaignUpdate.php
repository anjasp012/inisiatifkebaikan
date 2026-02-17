<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CampaignUpdate extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }

        return 'https://placehold.co/600x400/e2e8f0/475569?text=Update+Program';
    }
}
