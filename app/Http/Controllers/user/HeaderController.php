<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class HeaderController extends Controller
{
    public function load()
    {
        $brands = DB::select('select * from brand where brand_active = 1');
        return $brands;
    }
}
