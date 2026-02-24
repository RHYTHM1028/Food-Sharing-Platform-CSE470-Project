<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'food_type',
        'quantity',
        'original_price',
        'discounted_price',
        'images',
        'preparation_date',
        'expiry_date',
        'dietary_info',
        'address',
        'available_from',
        'available_until',
        'pickup_instructions',
        'status',
        'is_donation',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'images' => 'array',
        'dietary_info' => 'array',
        'address' => 'array',
        'preparation_date' => 'datetime',
        'expiry_date' => 'datetime',
        'available_from' => 'datetime',
        'available_until' => 'datetime',
        'is_donation' => 'boolean',
    ];

    /**
     * Get the user that owns the listing.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the orders that belong to the listing.
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    /**
     * Get the donation associated with the listing.
     */
    public function donation()
    {
        return $this->hasOne(Donation::class);
    }

    /**
     * Get the reviews for the listing.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * The users that favorited this listing.
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    /**
     * Get the dietary information attribute.
     *
     * @param  mixed  $value
     * @return array
     */
    public function getDietaryInfoAttribute($value)
    {
        if (empty($value)) {
            return [];
        }
        
        // If it's already an array, return it
        if (is_array($value)) {
            return $value;
        }
        
        // Try to decode JSON string
        if (is_string($value)) {
            try {
                $decoded = json_decode($value, true);
                if (is_array($decoded)) {
                    return $decoded;
                }
            } catch (\Exception $e) {
                // Failed to parse JSON
            }
            
            // If it's a comma-separated string
            return array_map('trim', explode(',', $value));
        }
        
        // Default fallback
        return [$value];
    }

    /**
     * Get formatted dietary information.
     *
     * @return array
     */
    public function getFormattedDietaryInfoAttribute()
    {
        $dietaryInfo = $this->dietary_info;
        
        if (empty($dietaryInfo)) {
            return [];
        }
        
        // Map possible keys to human-readable values
        $dietaryLabels = [
            'vegetarian' => 'Vegetarian',
            'vegan' => 'Vegan',
            'gluten_free' => 'Gluten-Free',
            'dairy_free' => 'Dairy-Free',
            'nut_free' => 'Nut-Free',
            'contains_nuts' => 'Contains Nuts',
            'low_sugar' => 'Low Sugar',
            'organic' => 'Organic',
            'halal' => 'Halal',
            'kosher' => 'Kosher'
        ];
        
        $formatted = [];
        
        foreach ($dietaryInfo as $key => $value) {
            // If value is true/1/"1"
            if ($value === true || $value === 1 || $value === '1') {
                if (isset($dietaryLabels[$key])) {
                    $formatted[] = $dietaryLabels[$key];
                } elseif (!is_numeric($key)) {
                    // If key is not a numeric index
                    $formatted[] = ucfirst(str_replace('_', ' ', $key));
                }
            }
        }
        
        return $formatted;
    }
}