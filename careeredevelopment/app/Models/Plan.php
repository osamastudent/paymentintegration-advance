<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Subscription;

class Plan extends Model
{
    use HasFactory;
    protected $table = 'plans';
    protected $fillable=[
       'plan_id',
       'name',
       'billing_method',
       'interval_count',
       'price',
       'currency',
    ];

  
}
