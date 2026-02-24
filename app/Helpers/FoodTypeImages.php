<?php

namespace App\Helpers;

class FoodTypeImages
{
    public static function getImagePath($foodType)
    {
        $foodTypeMappings = [
            'meal' => '/images/food_types/meal.jpg',
            'grocery' => '/images/food_types/grocery.jpg',
            'bakery' => '/images/food_types/bakery.jpg',
            'dairy' => '/images/food_types/dairy.jpg',
            'produce' => '/images/food_types/produce.jpg',
            'meat' => '/images/food_types/meat.jpg',
            'other' => '/images/food_types/other.jpg',
        ];
        
        return $foodTypeMappings[$foodType] ?? '/images/food_types/default.jpg';
    }
}