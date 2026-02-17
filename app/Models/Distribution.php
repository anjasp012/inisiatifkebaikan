<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Str;

class Distribution extends Model
{
    use HasSEO;

    protected $guarded = ['id'];

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: 'Penyaluran: ' . $this->recipient_name . ' - ' . $this->campaign->title,
            description: Str::limit(strip_tags($this->description), 150),
            image: $this->file_url,
            published_time: $this->distribution_date,
        );
    }

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
