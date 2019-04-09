<?php

namespace App\Http\Controllers;

use App\Customer;
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
use Illuminate\Support\Facades\DB;
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
    public function getRestaurants()
    {
        $restaurants = Restaurant::all();
        return $this->sendResponse($restaurants, "restaurants");
    }

    public function getMeals(Request $request)
    {
        $meals = Restaurant::find($request->input('restaurant_id'))->meals;
        return $this->sendResponse($meals, "meals");
    }

    public function createUser(Request $request)
    {
        $user = Auth::user();
        $type = $request->type;
        $phone = $request->phone;
        $avatar = $request->avatar;
        if ($type == "customer") {
            $customer = $user->customer;
            if ($customer == null) {
                $customer = Customer::create(["user_id" => $user->id, "avatar" => $avatar, "phone" => $phone, "address" => "Unknown"]);
            }
            return $this->sendResponse($customer, "success");
        } else if ($type == "driver") {
            $driver = $user->driver;
            if ($driver == null) {
                $driver = Driver::create(["user_id" => $user->id, "avatar" => $avatar, "phone" => $phone, "address" => "Unknown", "location" => "0.00"]);

            }
            return $this->sendResponse($driver, "success");
        }
        return $this->sendError("failed", ["Errors"], 200);

    }

    public function addOrder(Request $request)
    {
        $customer = Auth::user()->customer;


        $stripe_token = $request->input("stripe_token");
        $restaurant_id = $request->input("restaurant_id");
        $orders = Order::whereIn('status', array(1, 2, 3))->where(['customer_id' => $customer->id])->get();
        $address = $request->input("address");



        if (sizeof($orders)>0) {
            return $this->sendError("You have pending orders that must be completed", [], 200);
        }

        $order_details = json_decode($request->input("order_details"), true);

        //return $order_details;
        $total = 0;


        foreach ($order_details as $order_detail) {
            $meal = Meal::find($order_detail["meal_id"]);
            if ($meal != null)
            {
                $total += $meal->price * $order_detail["quantity"];
            }

        }
        //return $total;
        if ($total > 0) {
            Stripe::setApiKey("sk_test_22XWvG5s4hRY4mYuNVlsdT8p");
            $charge = Charge::create([
                'amount' => $total * 100,
                'currency' => 'usd',
                'description' => 'FoodTasker Order',
                'source' => $stripe_token
            ]);

            if ($charge->status != "failed") {
                $order = Order::create(["restaurant_id" => $restaurant_id, "customer_id" => $customer->id, "address" => $address, "total" => $total, "status" => 1]);
                foreach ($order_details as $order_detail) {
                    $meal = Meal::find($order_detail["meal_id"]);
                    $sub_total = $meal->price * $order_detail["quantity"];
                    OrderDetail::create(["order_id" => $order->id, "meal_id" => $order_detail['meal_id'], "meal_name"=>$meal->name, "quantity" => $order_detail["quantity"], "sub_total" => $sub_total]);
                }
                return $this->sendResponse("Order Created", "Successfully created order");
            } else {
                return $this->sendError("Failed", ["Could not pay. Card Error"], 200);
            }

        }
        return $this->sendError("Failed", ["Could not pay"], 200);
    }

    public function getLatestOrder()
    {
        $user = Auth::user();
        $order = $user->customer->orders()->with('order_details','restaurant')->latest()->first();
        $restaurant_address = Restaurant::where(["id"=>$order->restaurant_id])->first()->address;
        $order->rest_address=$restaurant_address;
        return $order;
    }

    public function customer_driver_location()
    {
        $customer = Auth::user()->customer;
        $order = Order::where(["customer_id" => $customer->id, "status" => 3])->latest()->first();
        if ($order!=null)
        {
            $driver = Driver::find($order->driver_id);

            return $this->sendResponse($driver->location, "Driver Location");
        }
        return $this->sendResponse("Error","Order Not Assigned Yet");

    }


    /***
     * RESTAURANT
     */
    public function restaurant_order_notification(Request $request)
    {
        $last_request_time = $request->input('last_request_time');
        $count = Auth::user()->restaurant->where('created_at', '>', $last_request_time)->count();
        return $this->sendResponse($count, "Notification");
    }

    /**
     * DRIVER
     */

    public function driver_get_ready_orders()
    {
        $orders = Order::where(["status" => 2, "driver_id" => null])->get();

        $all=[];
        foreach ($orders as  $order){
          $data=[];
          $data["id"]=$order->id;
          $data["customer_address"]=$order->address;
          $customer = Customer::find($order->customer_id);
          if ($customer!=null)
          {
              $data["customer_name"]=$customer->user->name;
              $data["customer_image"]=$customer->avatar;
          }

          $data["restaurant_name"]= Restaurant::find($order->restaurant_id)->name;

          $all[]=$data;

        }
        return $this->sendResponse($all, "Notification");
    }

    public function driver_pick_orders(Request $request)
    {
        $driver = Auth::user()->driver;
        $order_id = $request->input('order_id');
        $pending = Order::where(["driver_id" => $driver->id, 'status' => 3])->count();
        if ($pending > 0)
        {
            return $this->sendError("You can only pick one order at a time", [], 200);
        }

        $pending = Order::where(['driver_id' => null, 'status' => 2, 'order_id' => $order_id])->first();

        if ($pending != null) {
            $pending->driver_id = $driver->id;
            $pending->status = 3;
            $pending->picked_at = Carbon::now();
            $pending->save();
            return $this->sendResponse($pending, "Order Picked");
        }
        return $this->sendError("Error While picking orders");
    }

    public function driver_get_latest_orders()
    {
        $driver = Auth::user()->driver;
        $latest = Order::where(["driver_id" => $driver->id])->orderBy('picked_at', 'desc')->first();
        $customer_name = Customer::find($latest->customer_id)->user->name;
        $customer_image = Customer::find($latest->customer_id)->avatar;
        $rest_address= Restaurant::find($latest->restaurant_id)->address;

        $latest["customer_image"]=$customer_image;
        $latest["customer_name"]=$customer_name;
        $latest["restaurant_address"]=$rest_address;
        return $this->sendResponse($latest, "Latest Order");
    }

    public function driver_complete_orders(Request $request)
    {
        $driver_id = Auth::user()->driver->id;
        $order_id = $request->input('order_id');
        $latest = Order::where(['driver_id' => $driver_id, 'id' => $order_id])->first();
        $latest->status = 4;
        return $this->sendResponse($latest, 'Order Completed');
    }

    public function driver_get_revenue()
    {
        $driver_id = Auth::user()->driver->id;

        $results = DB::select(DB::raw("SELECT created_at as created, sum(total) as total   FROM orders WHERE driver_id = $driver_id GROUP BY DATE(created_at) ORDER  BY created_at DESC  LIMIT 7"));

        return $this->sendResponse($results, "Earnings");
    }

    public function driver_update_location(Request $request)
    {
        $location = $request->input("location");
        $driver = Auth::user()->driver;
        $driver->location = $location;
        $driver->save();
        return $this->sendResponse($driver, "Earnings");
    }

}
