<?php

namespace App\Services;

use App\Models\OpeningHour;
use App\Models\StoreInformation;
use App\Traits\APIResponse;

class OpeningService
{
    use APIResponse;

    public function getAllOpeningHours()
    {
        return OpeningHour::with('storeInformation:id,name,address')->get();
    }

    public function getOpeningHour($storeid)
    {
        return StoreInformation::find($storeid)->openingHours()->get(['day', 'opening_time', 'closing_time']);
    }

    public function createOpeningHours($storeid)
    {
        return StoreInformation::where('id', $storeid)->first();

    }

    public function updateOpeningHours($storeid)
    {
        return StoreInformation::where('id', $storeid)->first();

    }

    public function deleteOpeningHour($storeid)
    {
        return StoreInformation::find($storeid);

    }
}
