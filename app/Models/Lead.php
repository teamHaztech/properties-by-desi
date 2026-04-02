<?php

namespace App\Models;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'source',
        'status',
        'assigned_agent_id',
        'budget_min',
        'budget_max',
        'preferred_property_type',
        'location_preference',
        'urgency',
        'is_converted',
        'last_contacted_at',
    ];

    protected function casts(): array
    {
        return [
            'source' => LeadSource::class,
            'status' => LeadStatus::class,
            'budget_min' => 'decimal:2',
            'budget_max' => 'decimal:2',
            'is_converted' => 'boolean',
            'last_contacted_at' => 'datetime',
        ];
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'lead_property')
            ->withPivot(['status', 'feedback', 'shown_at', 'visited_at'])
            ->withTimestamps();
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class);
    }

    public function communications(): HasMany
    {
        return $this->hasMany(Communication::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'taggable');
    }

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'city_lead');
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    public function scopeByStatus($query, LeadStatus $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBySource($query, LeadSource $source)
    {
        return $query->where('source', $source);
    }

    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('assigned_agent_id', $userId);
    }

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) return $query;

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }
}
