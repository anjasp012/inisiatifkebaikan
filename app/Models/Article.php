<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Str;

class Article extends Model implements Viewable
{
    use InteractsWithViews;
    use HasSEO;

    protected $guarded = ['id'];

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: $this->title,
            description: Str::limit(strip_tags($this->content), 150),
            image: $this->thumbnail_url,
            author: $this->author->name,
            published_time: $this->created_at,
        );
    }

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : asset('img/no-image.jpg');
    }
}
