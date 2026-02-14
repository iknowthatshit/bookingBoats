<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class Boat extends Model
{
    use HasFactory;
    protected $fillable = [ 'name', 'description', 'price_per_day', 'boat_type', 'capacity', 'availability', 'image'];

    public function bookings(){
        return $this->hasMany(Booking::class);
    }
    
    #[Scope]
    protected function ofType(Builder $query, $type){
        if ($type === '' || $type === null) {
            return $query;
        }
        $query->where('boat_type', $type);
    }
    #[Scope]
    protected function minCapacity(Builder $query, $capacity){
        if ($capacity === '' || $capacity === null) {
            return $query;
        }
        $query->where('capacity', '>=', (int)$capacity);
    }
    #[Scope]
    protected function minPrice(Builder $query,  $price){
        if ($price === '' || $price=== null) {
            return $query;
        }
        $query->where('price_per_day', '>=', (float)$price);
    }
    #[Scope]
    protected function maxPrice(Builder $query, $price){
        if ($price === '' || $price=== null) {
            return $query;
        }
        $query->where('price_per_day', '<=', (float)$price);
    }
    #[Scope]
    protected function search(Builder $query, string $search){
        if ($search=== '' || $search=== null) {
            return $query;
        }
        $search = "%{$search}%";
        $query->where(function(Builder $query) use($search){
            $query->where('name','like', $search)->orWhere('description','like', $search)->orWhere('boat_type',"like",$search);
        });
    }
   
}
