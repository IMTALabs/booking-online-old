<?php

namespace App\Exceptions;

use App\Traits\APIResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as RequestAlias;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use APIResponse;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = ['password', 'password_confirmation'];

    /**
     * Render an exception into an HTTP response.
     *
     * @param  RequestAlias  $request
     * @param  Throwable  $e
     *
     * @throws Throwable
     */
    public function render($request, $e): Response
    {
        // dd($e);
        $this->log($e);

        if ($request->expectsJson()) {
            if ($e instanceof ThrottleRequestsException) {
                return $this->responseForThrottleRequestsException();
            }

            if ($e instanceof ValidationException) {
                return $this->responseForValidationException($e);
            }

            if ($e instanceof ModelNotFoundException) {
                return $this->responseForModelNotFoundException($e);
            }

            if ($e instanceof QueryException) {
                return $this->responseForQueryException($e);
            }

            if ($e instanceof AuthorizationException) {
                return $this->responseForAuthorizationException();
            }

            if ($e instanceof NotFoundHttpException) {
                return $this->responseForNotFoundHttpException($e);
            }

            if ($e instanceof UnprocessableEntityHttpException) {
                return $this->responseForUnprocessableEntityHttpException($e);
            }

            if ($e instanceof AuthenticationException) {
                return $this->responseForAuthenticationException($e);
            }

            if ($e instanceof BadRequestHttpException) {
                return $this->responseForBadRequestHttpException($e);
            }

            if ($e instanceof NotAcceptableHttpException) {
                return $this->responseForNotAcceptableHttpException($e);
            }
        }

        return parent::render($request, $e);
    }

    /**
     * Log the given exception.
     *
     * @throws BindingResolutionException
     */
    protected function log(Throwable $exception): void
    {
        $logger = $this->container->make(LoggerInterface::class);

        $logger->error($exception->getMessage(), array_merge($this->context(), [
            'exception' => $exception,
        ]));
    }

    /**
     * Response for NotAcceptableHttpException.
     */
    protected function responseForNotAcceptableHttpException(NotAcceptableHttpException $e): JsonResponse
    {
        return $this->responseWithCustomError(
            'Not Accessible !!',
            $e->getMessage(),
            Response::HTTP_NOT_ACCEPTABLE
        );
    }

    /**
     * Response for BadRequestHttpException.
     */
    protected function responseForBadRequestHttpException(BadRequestHttpException $e): JsonResponse
    {
        return $this->responseBadRequest(
            $e->getMessage(),
            Str::title(Str::snake(class_basename($e), ' '))
        );
    }

    /**
     * Response for AuthenticationException.
     */
    protected function responseForAuthenticationException(AuthenticationException $e): JsonResponse
    {
        return $this->responseUnAuthenticated($e->getMessage());
    }

    /**
     * Response for UnprocessableEntityHttpException.
     */
    protected function responseForUnprocessableEntityHttpException(UnprocessableEntityHttpException $e): JsonResponse
    {
        return $this->responseUnprocessable(
            $e->getMessage(),
            Str::title(Str::snake(class_basename($e), ' '))
        );
    }

    /**
     * Response for NotFoundHttpException.
     */
    protected function responseForNotFoundHttpException(NotFoundHttpException $e): JsonResponse
    {
        return $this->responseNotFound($e->getMessage());
    }

    /**
     * Response for AuthorizationException.
     */
    protected function responseForAuthorizationException(): JsonResponse
    {
        return $this->responseUnAuthorized();
    }

    /**
     * Response for QueryException.
     */
    protected function responseForQueryException(QueryException $e): JsonResponse
    {
        if (app()->isProduction()) {
            return $this->responseServerError();
        }

        return $this->responseNotFound(
            $e->getMessage(),
            Str::title(Str::snake(class_basename($e), ' '))
        );
    }

    /**
     * Response for ModelNotFoundException.
     */
    protected function responseForModelNotFoundException(ModelNotFoundException $e): JsonResponse
    {
        $id = $e->getIds() !== [] ? ' '.implode(', ', $e->getIds()) : '.';

        $model = class_basename($e->getModel());

        return $this->responseNotFound("{$model} with id {$id} not found", __('Record not found!'));
    }

    /**
     * Response for ValidationException.
     */
    protected function responseForValidationException(ValidationException $e): JsonResponse
    {
        return $this->ResponseValidationError($e);
    }

    /**
     * Response for ThrottleRequestsException.
     */
    protected function responseForThrottleRequestsException(): JsonResponse
    {
        return $this->responseWithCustomError(
            'Too Many Attempts.',
            'Too Many Attempts Please Try Again Later.',
            429
        );
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(
            [
                'error' => $errors,
                'status_code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ],
            JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        ));
    }
}
