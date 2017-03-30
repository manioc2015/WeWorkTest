<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //return API success call
    protected static function returnSuccess($data) {
        return array('success' => true, 'data' => $data);
    }

    //return API failure call
    protected static function returnFailure($message) {
        return response(array('success' => false, 'reason' => $message), 400);
    }
}
