<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Services\ServiceService;
use App\Traits\APIResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    use APIResponse;

    protected $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = $this->serviceService->getAllService();

        return $this->responseSuccess(__('service.list'), ['data' => $services]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceRequest $request)
    {
        DB::beginTransaction();

        try {
            $service = $this->serviceService->createService($request->all());
            DB::commit();

            return $this->responseCreated(__('service.created'), ['data' => $service]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Lỗi khi thêm dịch vụ: '.$e->getMessage());

            return $this->responseError(
                __('service.creation_failed'),
                [
                    'error' => $e->getMessage(),
                ]
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $service = $this->serviceService->getServiceById($id);
        if (! $service) {
            return $this->responseNotFound(Response::HTTP_NOT_FOUND, __('service.not_found'));
        }

        return $this->responseSuccess(__('service.show'), ['data' => $service]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, string $id)
    {
        DB::beginTransaction();

        try {
            $service = $this->serviceService->updateService($id, $request->all());
            if (! $service) {
                return $this->responseNotFound(Response::HTTP_NOT_FOUND, __('service.not_found'));
            }

            DB::commit();

            return $this->responseSuccess(__('service.updated'), ['data' => $service]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Lỗi khi cập nhật dịch vụ: '.$e->getMessage());

            return $this->responseError(
                __('service.update_failed'),
                [
                    'error' => $e->getMessage(),
                ]
            );        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $service = $this->serviceService->deleteService($id);
            if (! $service) {
                return $this->responseNotFound(Response::HTTP_NOT_FOUND, __('service.not_found'));
            }
            $service->delete();
            DB::commit();

            return $this->responseDeleted(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Lỗi khi xóa dịch vụ: '.$e->getMessage());

            return $this->responseServerError(Response::HTTP_INTERNAL_SERVER_ERROR, __('service.error'));
        }
    }
}
