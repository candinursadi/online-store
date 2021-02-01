<?php

namespace App\Jobs;

use DB;
use App\Models\Order;
use App\Models\Product;

class OrderJob extends Job
{
    protected $order_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->order_id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // GET ORDER
        $order = Order::find($this->order_id);

        DB::beginTransaction();
        try{
            if(!$order) throw new \Exception('Order not found', 11);

            $order->status = 'SUCCESS';
            $order->save();

            foreach($order->detail as $d){
                $get_product = Product::find($d->product_id);
                if(!$get_product) throw new \Exception('Product not found', 12);
                
                // CHECK AVAILABLE STOCK
                if($d->qty > $get_product->stock) throw new \Exception('Out of stock', 14);

                // STOCK ADJUSTMENT
                $get_product->stock -= $d->qty;
                $get_product->save();
            }

            /*
            DO SOME LOGIC ABOUT ADJUSTING PAYMENTS
            */

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            if($order){

                /*
                DO SOME LOGIC ABOUT ADJUSTING PAYMENTS
                */

                // PAYMENT FAILED THEN SET STATUS INTO REFUND
                $order->status = 'REFUND';
                $order->save();
            }
        }
        
    }
}
