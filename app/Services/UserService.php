<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserService
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserById($id)
    {
        return User::find($id);
    }

    public function createUser($data)
    {
        $this->uploadImageIfExists($data);

        return User::create($data);
    }

    public function updateUser($id, $data)
    {
        $user = User::find($id);
        if ($user) {
            $this->uploadImageIfExists($data, $user);
            $user->update($data);
        }

        return $user;
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user) {
            if ($user->image) {
                // Lấy tên file từ URL của ảnh
                $imageName = basename($user->image);
                if (Storage::disk('public')->exists('images/store/'.$imageName)) {
                    Storage::disk('public')->delete('images/store/'.$imageName);
                }
            }
            $user->delete();
        }

        return $user;
    }

    protected function uploadImageIfExists(&$data, $user = null)
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
