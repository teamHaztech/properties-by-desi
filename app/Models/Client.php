<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lead_id',
        'name',
        'phone',
        'email',
        'buying_type',
        'purpose',
        'buyer_profile',
        'urgency',
        'address',
        'pan_number',
        'aadhar_number',
        'notes',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function activityNotes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }
}
