<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use CyrildeWit\EloquentViewable\Contracts\Viewable;

class Campaign extends Model implements Viewable
{
    use InteractsWithViews;
    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_emergency' => 'boolean',

        'is_priority' => 'boolean',
        'is_optimized' => 'boolean',
        'is_slider' => 'boolean',
    ];

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : 'https://placehold.co/600x400/e2e8f0/475569?text=' . urlencode($this->title);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function fundraiser()
    {
        return $this->belongsTo(Fundraiser::class);
    }

    public function category()
    {
        return $this->belongsTo(CampaignCategory::class);
    }

    public function updates()
    {
        return $this->hasMany(CampaignUpdate::class)->orderByDesc('published_at');
    }

    public function prayers()
    {
        return $this->hasMany(Donation::class)
            ->whereNotNull('message')
            ->where('message', '!=', '')
            ->where('status', 'success')
            ->latest();
    }
}
