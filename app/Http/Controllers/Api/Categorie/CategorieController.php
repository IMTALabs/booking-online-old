<?php

namespace App\Http\Controllers\Api\Categorie;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategorieRequest;
use App\Http\Requests\UpdateCategorieRequest;
use App\Services\CategorieService;
use App\Traits\APIResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategorieController extends Controller
{
    use APIResponse;

    protected $categorieService;

    public function __construct(CategorieService $categorieService)
    {
        $this->categorieService = $categorieService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorie = $this->categorieService->getAllCategorie();

        return $this->responseSuccess(
            __('category.list'),
            [
                'data' => $categorie,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategorieRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->all();
            $categorie = $this->categorieService->createCategorie($data);

            DB::commit();

            return $this->responseCreated(__('category.created'), ['data' => $categorie]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Lỗi khi thêm danh mục: '.$e->getMessage());

            return $this->responseError(
                __('category.creation_failed'),
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
        $categorie = $this->categorieService->getCategorieById($id);
        if (! $categorie) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('category.not_found'),

            );
        } else {
            return $this->responseSuccess(
                __('category.show'),

                [
                    'data' => $categorie,

                ],
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategorieRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $categorie = $this->categorieService->updateCategorie($id, $data);
            if (! $categorie) {
                return $this->responseNotFound(Response::HTTP_NOT_FOUND, __('category.not_found'));
            }
            // Cập nhật dữ liệu
            $categorie->update($data);
            DB::commit();

            return $this->responseSuccess(__('category.updated'), ['data' => $categorie]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Lỗi khi cập nhật danh mục: '.$e->getMessage());

            return $this->responseError(
                __('category.update_failed'),
                [
                    'error' => $e->getMessage(),
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $categorie = $this->categorieService->deleteCategorie($id);
            if (! $categorie) {
                return $this->responseNotFound(Response::HTTP_NOT_FOUND, __('category.not_found'));
            }
            // Xóa danh mục
            $categorie->delete();
            DB::commit();

            return $this->responseDeleted(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Lỗi khi xóa danh mục: '.$e->getMessage());

            return $this->responseServerError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Đã xảy ra lỗi. Vui lòng thử lại sau.');
        }
    }
}
