<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'paypal_id',
        'status',
        'plan_id',
        'start_time',
        'expire_date',
    ];


    public function users(){
        return $this->belongsTo(User::class);
    }
}

