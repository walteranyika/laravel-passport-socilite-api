<?php

use Illuminate\Database\Seeder;

class AllTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create(['name'=>'John Doe','john@yahoo.com',bcrypt('password')]);//1
        \App\User::create(['name'=>'Mary Doe','mary@yahoo.com',bcrypt('password')]);//2
        \App\User::create(['name'=>'Driver Doe','driver@yahoo.com',bcrypt('password')]);//3
        \App\User::create(['name'=>'Customer Doe','driver@yahoo.com',bcrypt('password')]);//4

        \App\Customer::create(["user_id"=>4,"avatar"=>"customer.jpg","phone"=>"254723111222","address"=>"Nairobi Road"]);
        \App\Driver::create(["user_id"=>3,"avatar"=>"driver.jpg","phone"=>"254723111223","address"=>"Westlands","location"=>"-1.26,36.22"]);

        \App\Restaurant::created(["user_id"=>1,"name"=>"Best Will Meals","phone"=>"0723444555","address"=>"Mpaka Road","logo"=>"1.png"]);

    }
}
