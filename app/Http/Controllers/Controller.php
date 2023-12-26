<?php

namespace App\Http\Controllers;

use App\Utils\ExceptionUtil;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Exception;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;
    use ExceptionUtil;

    /**
     * Create default response success
     *
     * @param array|null $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function ok($data = null, $message = 'success', $code = 200)
    {
        return response()->json([
            'code'    => $code,
            'message' => $message,
            'data'    => $data
        ], $code);
    }

    /**
     * Create default response failed
     *
     * @param string|array $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function oops($message = '', $code = 400)
    {
        return response()->json([
            'code'    => $code,
            'message' => $message,
            'data'    => null
        ], $code);
    }

    /**
     * Create default response invalid input
     *
     * @param array $errors
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function invalid($errors = [], $code = 422)
    {
        return response()->json([
            'code'    => $code,
            'message' => 'Unprocessable Entities',
            'errors'  => $errors,
        ], $code);
    }

    /**
     *
     * @param Model $result
     * @param Request $request
     * @param integer $paginate
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function okPaginate($result, Request $request)
    {
        $total_data = $result->count();
        $data = $result->simplePaginate($request->rows ?? 10)->appends($request->query());

        $returnData = array();
        foreach ($data as $value) {
            $returnData[] = $value;
        }

        return response()->json([
            'succes' => true,
            'code' => 200,
            'message' => 'success',
            'data' => $returnData,
            'pagination' =>  [
                'current_page' => (int) ($request->page ?? 1),
                'total_rows' => $total_data,
            ]
        ], 200);
    }

    /**
     *
     * @param   callable  $callback
     *
     * @return  mixed
     */
    public function run(callable $callback)
    {
        try {
            return $callback();
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     *
     * @param   callable  $callback
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function handle(callable $callback)
    {
        return $this->run(function () use ($callback) {
            $response = $callback();
            return $this->ok($response);
        });
    }
}
