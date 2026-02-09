<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Boat extends Model
{
    use HasFactory;
    protected $fillable = [ 'name', 'description', 'price_per_day', 'boat_type', 'capacity', 'availability', 'image'];

    public function bookings(){
        return $this->hasMany(Booking::class);
    }
    
}
