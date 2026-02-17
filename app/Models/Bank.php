<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $guarded = ['id'];

    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return asset('assets/images/logo.png'); // Fallback to app logo or no-image
        }

        if (str_starts_with($this->logo, 'http')) {
            return $this->logo;
        }

        if (str_starts_with($this->logo, 'assets/')) {
            return asset($this->logo);
        }

        return asset('storage/' . $this->logo);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
