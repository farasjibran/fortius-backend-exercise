<?php

namespace App\Utils;

use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

trait ExceptionUtil
{
    use ResponseUtil;

    public function handleException(Throwable $e)
    {
        if ($e instanceof InvalidArgumentException) {
            return $this->sendError($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } elseif ($e instanceof UnauthorizedException) {
            return $this->sendError($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            return $this->sendError($e->getMessage(), Response::HTTP_METHOD_NOT_ALLOWED);
        } elseif ($e instanceof ValidationException) {
            return $this->sendError($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } elseif ($e instanceof NotFoundHttpException) {
            return $this->sendError($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        return $this->sendError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
