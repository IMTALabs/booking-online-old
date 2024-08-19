<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'describe',
        'name',
        'categorie_id',
        'time',

    ];

    // public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'service_bookings');
    }
    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_services', 'service_id', 'promotion_id');
    }
}
