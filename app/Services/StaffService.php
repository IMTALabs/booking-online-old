<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StaffService
{
    public function staffService()
    {
        return Auth::user();
    }

    public function uploadImageIfExists(&$data, $user = null)
    {
        if (isset($data['image']) && $data['image']->isValid()) {
            $imageName = Str::random(12).'.'.$data['image']->getClientOriginalExtension();
            $data['image']->storeAs('public/images/user', $imageName);
            $newImageUrl = asset('storage/images/user/'.$imageName);

            if (isset($data['old_image']) && Storage::disk('public')->exists('images/user/'.$data['old_image'])) {
                Storage::disk('public')->delete('images/user/'.$data['old_image']);
            }
            $data['image'] = $newImageUrl;
        }
    }
}
