<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function sendResponse($result, $message,$success=true)
    {
        $response=[
            'success'=>$success,
            'data'=> $result,
            'message'=>$message,
        ];
        return response()->json($response,200);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response=[
            'success'=>false,
            'message'=>$error,
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}