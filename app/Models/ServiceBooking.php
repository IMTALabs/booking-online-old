<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceBooking extends Model
{
    protected $fillable = [

        'service_id',
        'booking_id',
    ];

    // public $timestamps = false;

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
