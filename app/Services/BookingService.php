<?php

namespace App\Services;

use App\Models\Base;
use App\Models\Booking;
use App\Traits\APIResponse;

class BookingService
{
    use APIResponse;

    public function getAllBases()
    {
        return Base::query()->get();

    }

    public function getBaseByID($id)
    {
        return Base::find($id);

    }

    public function getBookingById($id)
    {
        return Booking::find($id);

    }
}
