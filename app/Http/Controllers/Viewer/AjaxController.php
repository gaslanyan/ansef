<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Country;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AjaxController extends Controller
{

    public function city(Request $request)
    {
        $resp = [];
        $cites = City::where('cc_fips', '=', $request['cc_fips'])->pluck('name', 'id');
        $code = Country::where('cc_fips', '=', $request['cc_fips'])->pluck('country_phone_code');
        $resp[$code[0]] = json_encode($cites);
        echo  json_encode($resp); exit;
    }
}
