<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Str;

class Campaign extends Model implements Viewable
{
    use InteractsWithViews;
    use HasSEO;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_emergency' => 'boolean',

        'is_priority' => 'boolean',
        'is_optimized' => 'boolean',
        'is_slider' => 'boolean',
    ];

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: $this->title,
            description: $this->short_description ?? Str::limit(strip_tags($this->description), 150),
            image: $this->thumbnail_url,
        );
    }

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
