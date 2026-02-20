<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fundraiser extends Model
{
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo_image ? asset('storage/' . $this->logo_image) : 'https://placehold.co/100x100/e2e8f0/475569?text=' . substr($this->foundation_name ?? 'F', 0, 1);
    }

    public function getOfficeImageUrlAttribute()
    {
        return $this->office_image ? asset('storage/' . $this->office_image) : null;
    }

    public function getLegalDocUrlAttribute()
    {
        return $this->legal_doc ? asset('storage/' . $this->legal_doc) : null;
    }

    public function getNotaryDocUrlAttribute()
    {
        return $this->notary_doc ? asset('storage/' . $this->notary_doc) : null;
    }

    public function getTaxIdUrlAttribute()
    {
        return $this->tax_id ? asset('storage/' . $this->tax_id) : null;
    }
}
