<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Restaurant extends Model
{
    use SoftDeletes;

    protected static function booted(): void
    {
        static::saving(function (Restaurant $restaurant) {
            if (blank($restaurant->slug) || $restaurant->isDirty('slug')) {
                $restaurant->slug = static::generateUniqueSlug(
                    $restaurant->slug ?: $restaurant->name,
                    $restaurant->getKey()
                );
            }
        });
    }

    protected $fillable = [
        'client_id',
        'city_id',
        'category_id',
        'restaurant_request_id',
        'name',
        'slug',
        'description',
        'phone',
        'email',
        'address',
        'status',
        'approved_by',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($value) ?: 'restaurant';
        $slug = $baseSlug;
        $suffix = 2;

        while (static::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
