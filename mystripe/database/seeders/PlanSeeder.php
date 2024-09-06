<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'title' => 'yearly',
            'slug' => 'yearly',
            'price'=>5,
            'description'=>'this yearly descrition',
            'stripe_id' => 'price_1PbJuGHiJ6pxhnUHhzG9z4kp',
        ]);
    }
}
