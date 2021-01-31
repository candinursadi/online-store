<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;

class CartController extends Controller
{
    protected $user;
    protected $response;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->user = $request->user;
        $this->response = $request->response;
    }

    public function get_data(Request $request){
        try{
            // GET CART
            $cart = Cart::where('user_id',$this->user->id)->whereNull('is_paid')->first();
            if(!$cart) throw new \Exception(null, 5);

            // GET CART DETAIL
            $cart_detail = CartDetail::select('products.id','products.name','cart_details.price','qty',DB::raw('cart_details.price*qty as total'))
                ->leftjoin('products','cart_details.product_id','products.id')
                ->where('cart_id',$cart->id)
                ->whereNull('is_deleted')
                ->get();

            $data = null;
            if($cart_detail) $data = $cart_detail->toArray();

            $response = $this->response->get_response('00',$data);

        } catch (\Exception $e) {
            
            $response = $this->response->get_response((string) str_pad($e->getCode(), 2, "0", STR_PAD_LEFT),null);
        }
        
        return response()->json($response, 200);
        
    }

    public function add_data(Request $request){
        DB::beginTransaction();
        try{
            // CHECK AVAILABLE CART
            $cart = Cart::where('user_id',$this->user->id)
                ->whereNull('is_paid')
                ->first();
            if(!$cart){
                // INSERT INTO CART
                $cart = new Cart;
                $cart->user_id = $this->user->id;
                $cart->save();
            }

            // UPDATE CART DETAIL, ASSUMING PRODUCT HAS BEEN DELETED
            $update_cart_detail = CartDetail::where('cart_id',$cart->id)
                ->whereNull('is_deleted')
                ->update(['is_deleted' => 1]);
            
            // INSERT NEW PRODUCT INTO CART DETAIL
            foreach($request->data as $index => $value){
                $get_product = Product::find($value['id']);
                if(!$get_product) throw new \Exception(null, 2);

                // CHECK AVAILABLE STOCK
                if($value['qty'] > $get_product->stock) throw new \Exception(null, 4);

                $cart_detail = new CartDetail;
                $cart_detail->cart_id = $cart->id;
                $cart_detail->product_id = $value['id'];
                $cart_detail->price = $get_product->price;
                $cart_detail->qty = $value['qty'];
                $cart_detail->save();

                // SET RESPONSE DATA
                $data[] = array(
                    'id' => $cart_detail->product_id,
                    'name' => $get_product->name,
                    'price' => $cart_detail->price,
                    'qty' => $cart_detail->qty,
                    'total' => $cart_detail->price * $cart_detail->qty
                );
            }

            $response = $this->response->get_response('00',$data);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            $response = $this->response->get_response((string) str_pad($e->getCode(), 2, "0", STR_PAD_LEFT),null);
        }
        
        return response()->json($response, 200);
    }

    public function payment(Request $request, $cart_id = null){
        
        
    }
    
}
