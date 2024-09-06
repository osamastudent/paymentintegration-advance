<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;


    protected $fillable=[
        'title',
        'slug',
        'price',
        'trial_days',
        'description',
        'stripe_id',
    ];

    // Example: Make sure this is set correctly in your Plan model
public function getTrialDaysAttribute()
{
    return $this->attributes['trial_days'] ?? 14; // Default to 14 days if not set
}

}
