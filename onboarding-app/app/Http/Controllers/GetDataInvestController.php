<?php

namespace App\Http\Controllers;

use App\Jobs\DispatchDataInvestProduct;
use Illuminate\Http\Request;

class GetDataInvestController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        DispatchDataInvestProduct::dispatch();
        return response()->json(['message' => 'job running in background']);
    }
}
