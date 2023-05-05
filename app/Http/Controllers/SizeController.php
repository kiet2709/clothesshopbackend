<?php

namespace App\Http\Controllers;

use App\Service\SizeService;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function __construct()
    {
        $this->middleware('custom_auth');
    }

    public function getAllSizes()
    {
        $sizeResponse =  SizeService::getAllSizes();
        return response()->json($sizeResponse);
    }

    public function getSizeById($id)
    {
        $sizeResponse =  SizeService::getSizeById($id);
        return response()->json($sizeResponse);
    }
}
