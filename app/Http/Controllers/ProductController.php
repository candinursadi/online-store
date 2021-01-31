<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Response;
use App\Models\Product;

class ProductController extends Controller
{
    protected $response;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->response = new Response;
    }

    public function get_data(Request $request){
        $data = Product::select('id','name','price','stock')->get();
        $response = $this->response->get_response('00',$data->toArray());

        return response()->json($response, 200);
    }
    
}
