<?php

namespace App\Services;

use App\Models\Service;

class ServiceService
{
    public function getAllService()
    {
        return Service::with('category')->get();
    }

    public function getServiceById($id)
    {
        return Service::with('category')->find($id);
    }

    public function createService($data)
    {
        return Service::create($data);
    }

    public function updateService($id, $data)
    {
        $service = Service::find($id);
        if ($service) {
            $service->update($data);
        }

        return $service;
    }

    public function deleteService($id)
    {
        $service = Service::find($id);
        if ($service) {
            $service->delete();
        }

        return $service;
    }
}
