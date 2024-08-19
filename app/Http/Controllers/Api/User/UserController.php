<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\userRequest;
use App\Services\UserService;
use App\Traits\APIResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use APIResponse;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->getAllUsers();

        return $this->responseSuccess(__('user.list'), ['data' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(userRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['role'] = 1;
            $user = $this->userService->createUser($data);
            DB::commit();

            return $this->responseCreated(
                __('user.created'),
                [
                    'data' => $user,
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->responseError(
                __('user.creation_failed'),
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
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return $this->responseNotFound(Response::HTTP_NOT_FOUND, __('user.not_found'));
        }

        return $this->responseSuccess(__('user.show'), ['data' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $user = $this->userService->updateUser($id, $request->all());
            if (!$user) {
                DB::rollBack();

                return $this->responseNotFound(Response::HTTP_NOT_FOUND, __('user.not_found'));
            }
            DB::commit();

            return $this->responseSuccess(__('user.updated'), ['data' => $user]);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->responseError(
                __('user.update_failed'),
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
        $user = $this->userService->deleteUser($id);
        if (!$user) {
            return $this->responseNotFound(Response::HTTP_NOT_FOUND, __('user.not_found'));
        }

        return $this->responseDeleted(null, Response::HTTP_NO_CONTENT);
    }
}
