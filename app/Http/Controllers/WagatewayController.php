<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaturanumum;

class WagatewayController extends Controller
{
    public function index()
    {
        $data['generalsetting'] = Pengaturanumum::where('id', 1)->first();
        return view('wagateway.scanqr', $data);
    }
}
