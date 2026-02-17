<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignCategory extends Model
{
    //

    protected $fillable = [
        'icon',
        'name',
        'slug',
    ];

    public function getIconUrlAttribute()
    {
        if ($this->icon && !str_starts_with($this->icon, 'bi-')) {
            return asset('storage/' . $this->icon);
        }

        // If it's a bootstrap icon, return null or handle differently in view
        if (str_starts_with($this->icon, 'bi-')) {
            return null;
        }

        return 'https://placehold.co/600x400?text=' . urlencode($this->name);
    }

    public function getIsBootstrapIconAttribute()
    {
        return str_starts_with($this->icon, 'bi-');
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'category_id');
    }
}
