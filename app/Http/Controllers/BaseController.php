<?php

namespace App\Http\Controllers;

use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{

    use Helpers;

    // comman method to set response
    public function dispatchResponse($statusCode = 200, $msg = "", $data = null)
    {
        $response = [];
        $response["status_code"] = $statusCode;
        $response["message"] = $msg;
        $response["data"] = $data;

        return new Response($response, $statusCode);
    }

    public function pp($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    public function pv($data)
    {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
    }

}
