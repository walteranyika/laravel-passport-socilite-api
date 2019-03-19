<?php

namespace App\Http\Controllers;

use App\Driver;
use App\Http\Controllers\BaseController;
use App\Meal;
use App\Order;
use App\OrderDetail;
use App\Restaurant;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Charge;
use Stripe\Stripe;

class MainApiController extends BaseController
{
    /*COOKING = 1
    READY = 2
    ONTHEWAY = 3
    DELIVERED = 4
*/
    //https://github.com/toanhtran/foodtasker/blob/master/foodtaskerapp/apis.py
    public function  getRestaurants(){
      $restaurants= Restaurant::all();
      return $this->sendResponse($restaurants,"restaurants");
    }

    public function  getMeals(Request $request){
        $meals= Restaurant::find($request->input('restaurant_id'))->meals;
        return $this->sendResponse($meals,"meals");
    }

    public function  addOrder(Request $request){

        /* access_token
            restaurant_id
            address
            order_details (json format), example:
                [{"meal_id": 1, "quantity": 2},{"meal_id": 2, "quantity": 3}]
            stripe_token
        return:
            {"status": "success"}*/

        $customer = Auth::user()->customer;
        $stripe_token = $request->input("stripe_token");
        $restaurant_id = $request->input("restaurant_id");
        $orders = Order::whereIn('status',array(1,2,3))->get();
        $address =$request->input("address");

        if ($orders!=null){
            return $this->sendError("You have pending orders that must be completed",[],200);
        }
        $order_details = $request->input("order_details");
        $total = 0;
        foreach ($order_details as $order_detail)
        {
           $meal = Meal::find($order_detail["meal_id"]) ;
           if ($meal!=null)
               $total+=$meal->cost*$order_detail["quantity"] ;
        }
        if ($total>0){
            Stripe::setApiKey("sk_test_22XWvG5s4hRY4mYuNVlsdT8p");
            $charge = Charge::create([
                'amount' => $total * 100,
                'currency' => 'usd',
                'description' => 'FoodTasker Order',
                'source' => $stripe_token
                ]);
            if ($charge->status !="failed"){
                $order =Order::create(["restaurant_id"=>$restaurant_id,"customer_id"=>$customer->id,"address"=>$address,"total"=>$total,"status"=>1]);
                foreach ($order_details as $order_detail)
                {
                    $meal = Meal::find($order_detail["meal_id"]) ;
                    $sub_total =$meal->cost * $order_detail["quantity"] ;
                    OrderDetail::create(["order_id"=>$order->id, "meal_id"=>$order_detail['meal_id'], "quantity"=>$order_detail["quantity"], "sub_total"=>$sub_total]);
                }
                return $this->sendResponse("Order Created","Successfully created order");
            }

        }
        return  $this->sendResponse("Failed","Failed to create order");
    }

    public function getLatestOrder(){
        $user = Auth::user();
        $order = $user->customer->orders->latest()->first();
        return $order;
    }

    public function customer_driver_location(){
        $customer=Auth::user()->customer;
        $order =Order::where(["customer_id"=>$customer->id, "status"=>3])->latest()->first();
        $driver = Driver::find($order->driver_id);
        return $this->sendResponse($driver.location,"Driver Location");
    }


    /***
      RESTAURANT
     */
    public function restaurant_order_notification(Request $request)
    {
        $last_request_time=$request->input('last_request_time');
        $count = Auth::user()->restaurant->where('created_at','>',$last_request_time)->count();
        return $this->sendResponse($count,"Notification");
    }

    /**
    DRIVER
    */

    public  function driver_get_ready_orders(){
        $orders= Order::where(["status"=>2,"driver_id"=>null])->get();
        return $this->sendResponse($orders,"Notification");
    }

    public function driver_pick_orders(Request $request){
       $driver =Auth::user()->driver;
       $order_id=$request->input('order_id');
       $pending = Order::where(["driver_id"=>$driver->id,'status'=>3])->count();
       if ($pending>0)
           return $this->sendError("You can only pick one order at a time",[],200);

        $pending = Order::where(['driver_id'=>null,'status'=>2, 'order_id'=>$order_id])->first();
        if ($pending !=null){
            $pending->driver_id =$driver->driver_id;
            $pending->status =3;
            $pending->picked_at = Carbon::now();
            $pending->save();
            return $this->sendResponse($pending,"Order Picked");
        }
        return $this->sendError("Error While picking orders");
    }

    public function driver_get_latest_orders(){
        $driver =Auth::user()->driver;
        $latest = Order::where(["driver_id"=>$driver->id])->orderBy('picked_at','desc')->first();
        return $this->sendResponse($latest,"Latest Order");
    }

    public function  driver_complete_orders(Request $request){
        $driver_id =Auth::user()->driver->id;
        $order_id = $request->input('order_id');
        $latest = Order::where(['driver_id'=>$driver_id,'id'=>$order_id])->first();
        $latest->status=4;
        return $this->sendResponse($latest,'Order Completed');
    }

    public  function  driver_get_revenue()
    {
        $driver_id =Auth::user()->driver->id;

        $results = DB::select( DB::raw("SELECT sum(total) FROM orders WHERE driver_id = $driver_id GROUP BY DATE(created_at)") );

        return $this->sendResponse($results,"Earnings");
    }

    public function driver_update_location(Request $request){
        $location= $request->input("location");
        $driver=Auth::user()->driver;
        $driver->location=$location;
        $driver->save();
        return $this->sendResponse($driver,"Earnings");
    }









}
