<?php

namespace App\Services;

use App\Models\StoreInformation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreService
{
    public function getAllStore()
    {
        return StoreInformation::all();
    }

    public function getStoreById($id)
    {
        return StoreInformation::find($id);
    }

    public function createStore($data)
    {
        $this->uploadImageIfExists($data);

        return StoreInformation::create($data);
    }

    public function updateStore($id, $data)
    {
        $store = StoreInformation::find($id);
        if ($store) {
            $this->uploadImageIfExists($data, $store);
            $store->update($data);
        }

        return $store;
    }

    public function deleteStore($id)
    {
        $store = StoreInformation::find($id);
        if ($store) {
            if ($store->image) {
                // Lấy tên file từ URL của ảnh
                $imageName = basename($store->image);
                if (Storage::disk('public')->exists('images/store/'.$imageName)) {
                    Storage::disk('public')->delete('images/store/'.$imageName);
                }
            }
            $store->delete();
        }

        return $store;
    }

    protected function uploadImageIfExists(&$data, $store = null)
    {
        if (isset($data['image']) && $data['image']->isValid()) {
            $imageName = Str::random(12).'.'.$data['image']->getClientOriginalExtension();
            $data['image']->storeAs('public/images/store', $imageName);
            $newImageUrl = asset('storage/images/store/'.$imageName);

            if (isset($data['old_image']) && Storage::disk('public')->exists('images/store/'.$data['old_image'])) {
                Storage::disk('public')->delete('images/store/'.$data['old_image']);
            }
            $data['image'] = $newImageUrl;
        }
    }
}
