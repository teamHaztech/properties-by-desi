<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class City extends Model
{
    protected $fillable = ['name', 'state', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function leads(): BelongsToMany
    {
        return $this->belongsToMany(Lead::class, 'city_lead');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
