<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const PERSONAL_ACCESS_TOKEN_NAME = "DEMO-APP";
    const SUCCESS_STATUS_CODE = 200;
    const UNAUTHORISED_STATUS_CODE = 401;
    const NOTFOUND_STATUS_CODE = 404;
    const FORBIDDEN_STATUS_CODE = 403;
    const BADREQUEST_STATUS_CODE = 400;
    const UNPROCESSABLE_ENTITY_STATUS_CODE = 422;
    const GENERAL_SERVER_ERROR_STATUS_CODE = 500;

    /*
	 * @param $status, $message, $data, $code
	 * Description : Send json response to mobile
	 */
    public function sendResponse($status, $message, $data = [], $code = 200)
    {
        $response = [
            'statusCode' => $code,
            'success'  => $status,
            'message' => $message,
            'data'    => $data
        ];
        return response()->json($response, $code);
    }

    public function sendErrorResponse($status, $error, $data = [], $code = 200)
    {
        $response = [
            'statusCode' => $code,
            'success'  => $status,
            'error' => $error,
            'data'    => $data
        ];
        return response()->json($response, $code);
    }

    /*
	 * @param $exception
	 * Description : Handle exceptions and send json response to mobile
	 */
    public function handleException($exception)
    {
        $exceptionMessage = $exception->getMessage();
        $response = [
            'statusCode' => 500,
            'success'  => false,
            'message' => __("messages.500"),
            'error' => $exceptionMessage
        ];

        return response()->json($response, 500);
    }
}
