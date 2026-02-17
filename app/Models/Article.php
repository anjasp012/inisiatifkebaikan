<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use CyrildeWit\EloquentViewable\Contracts\Viewable;

class Article extends Model implements Viewable
{
    use InteractsWithViews;
    protected $guarded = ['id'];

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
