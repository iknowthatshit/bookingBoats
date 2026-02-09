<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = ['boat_id', 'user_id', 'start_date', 'end_date', 'days_count', 'total_price', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function boat()
    {
        return $this->belongsTo(Boat::class);
    }

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}