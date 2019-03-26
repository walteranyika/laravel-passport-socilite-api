<?php

namespace App\Http\Controllers;

use App\Meal;
use App\Restaurant;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class RestaurantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add_hotel()
    {
       return view('restaurant');
    }

    public function save_hotel(Request $request)
    {
        //"user_id","name","phone","address","logo"
        $this->validate($request,[
            'name'=>'required|min:3',
            'logo'=>'required|mimes:png,jpg,jpeg',
            'phone'=>'required|regex:/(07)[0-9]{8}/',
            'address'=>'required'
        ], [
            'phone.regex'=>'Your Phone number is invalid. Use format 07xxxxxxxx.'
        ]);


        $id=Auth::user()->id;
        $destinationPath = "images";

        $img_1=$this->getUniqueImgName($request,'logo');

        Input::file('logo')->move($destinationPath, $img_1);

        $rest = new Restaurant();
        $rest->name = $request->name;
        $rest->user_id = $id;
        $rest->logo = $img_1;
        $rest->phone = $request->phone;
        $rest->address = $request->address;

        $rest->save();


        return back()->with("success","Restaurant $rest->name Added Successfully");
    }


    public function add_meal()
    {
       $restaurants = Auth::user()->restaurants;

       return view('meal',compact('restaurants')) ;
    }

    public function save_meal(Request $request)
    {
        //"restaurant_id","name","short_description","image","price"
        $this->validate($request,[
            'name'=>'required|min:3',
            'image'=>'required|mimes:png,jpg,jpeg',
            'restaurant_id'=>'required|numeric',
            'price'=>'required|numeric',
            'short_description'=>'required'
        ]);
        $destinationPath = "images";

        $img_1=$this->getUniqueImgName($request,'image');

        Input::file('image')->move($destinationPath, $img_1);

        $meal = new Meal();
        $meal->name = $request->name;
        $meal->price = $request->price;
        $meal->image = $img_1;
        $meal->restaurant_id = $request->restaurant_id;
        $meal->short_description = $request->short_description;

        $meal->save();
        return back()->with("success","Meal
         
         $meal->name Added Successfully");
    }

    public function show_restaurants()
    {
       $hotels = Auth::user()->restaurants;
       return view('restaurants',compact('hotels'));
    }

    public function show_meals(Restaurant $restaurant)
    {
        $meals=$restaurant->meals;
        return view('meals',compact('meals'));
    }

    public function remove_meal(Meal $meal)
    {
        $meal->delete();
        return back();
    }



    private function getUniqueImgName(Request $request,$img)
    {

        $newName = rand(1000000,10000000)."_".rand(1000000,10000000);
        $guessFileExtension = $request->file($img)->guessExtension();
        $full_name = $newName . '.' . $guessFileExtension;
        return $full_name;
    }

}
