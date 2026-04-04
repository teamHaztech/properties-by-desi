<?php

namespace App\Models;

use App\Enums\PropertyStatus;
use App\Enums\PropertySubType;
use App\Enums\PropertyType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'type', 'sub_type',
        'location', 'city_id', 'area',
        'price', 'owner_expected_price', 'quoted_price',
        'commission_percent', 'commission_amount',
        'is_negotiable', 'negotiable_price', 'total_plot_price',
        'price_per_sqm', 'size_sqm', 'size_label',
        'bedrooms', 'bathrooms', 'description', 'status',
        'tags', 'amenities', 'images', 'map_link',
        'owner_name', 'owner_phone', 'added_by',
    ];

    protected function casts(): array
    {
        return [
            'type' => PropertyType::class,
            'sub_type' => PropertySubType::class,
            'status' => PropertyStatus::class,
            'price' => 'decimal:2',
            'owner_expected_price' => 'decimal:2',
            'quoted_price' => 'decimal:2',
            'commission_percent' => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'negotiable_price' => 'decimal:2',
            'total_plot_price' => 'decimal:2',
            'price_per_sqm' => 'decimal:2',
            'size_sqm' => 'decimal:2',
            'is_negotiable' => 'boolean',
            'tags' => 'array',
            'amenities' => 'array',
            'images' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Property $property) {
            if (empty($property->slug)) {
                $property->slug = Str::slug($property->title . '-' . Str::random(5));
            }
            static::calculatePricing($property);
        });

        static::updating(function (Property $property) {
            static::calculatePricing($property);
        });
    }

    protected static function calculatePricing(Property $property): void
    {
        // Auto-calc total plot price from size × price_per_sqm (plots only)
        if ($property->size_sqm && $property->price_per_sqm) {
            $property->total_plot_price = $property->size_sqm * $property->price_per_sqm;
        }

        // If both quoted_price and owner_expected_price are set, margin mode — calc commission from difference
        if ($property->quoted_price && $property->owner_expected_price) {
            $property->commission_amount = $property->quoted_price - $property->owner_expected_price;
            if ($property->quoted_price > 0) {
                $property->commission_percent = round(($property->commission_amount / $property->quoted_price) * 100, 2);
            }
        }
        // If only owner price + commission %, calc the rest
        elseif ($property->owner_expected_price && $property->commission_percent && !$property->quoted_price) {
            $property->commission_amount = $property->owner_expected_price * ($property->commission_percent / 100);
            $property->quoted_price = $property->owner_expected_price + $property->commission_amount;
        }

        // Set display price
        if ($property->quoted_price) {
            $property->price = $property->quoted_price;
        } elseif ($property->owner_expected_price) {
            $property->price = $property->owner_expected_price;
        }
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function leads(): BelongsToMany
    {
        return $this->belongsToMany(Lead::class, 'lead_property')
            ->withPivot(['status', 'feedback', 'shown_at', 'visited_at'])
            ->withTimestamps();
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', PropertyStatus::Available);
    }

    public function scopeByType($query, PropertyType $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCity($query, int $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) return $query;

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%")
              ->orWhere('area', 'like', "%{$search}%")
              ->orWhereHas('city', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
        });
    }

    public function scopePriceRange($query, ?float $min, ?float $max)
    {
        if ($min) $query->where('price', '>=', $min);
        if ($max) $query->where('price', '<=', $max);
        return $query;
    }

    public function getFormattedPriceAttribute(): string
    {
        return self::formatIndian($this->price);
    }

    public function getFormattedOwnerPriceAttribute(): string
    {
        return self::formatIndian($this->owner_expected_price);
    }

    public function getFormattedQuotedPriceAttribute(): string
    {
        return self::formatIndian($this->quoted_price);
    }

    public function getFormattedCommissionAttribute(): string
    {
        return self::formatIndian($this->commission_amount) . " ({$this->commission_percent}%)";
    }

    public function getProfitMarginAttribute(): float
    {
        if (!$this->quoted_price || !$this->owner_expected_price) return 0;
        return $this->quoted_price - $this->owner_expected_price;
    }

    public static function formatIndian(?float $amount): string
    {
        if (!$amount) return '₹0';
        if ($amount >= 10000000) {
            return '₹' . number_format($amount / 10000000, 2) . ' Cr';
        }
        if ($amount >= 100000) {
            return '₹' . number_format($amount / 100000, 2) . ' L';
        }
        return '₹' . number_format($amount, 0);
    }
}
