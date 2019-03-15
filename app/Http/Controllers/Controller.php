<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    //https://medium.com/@hivokas/api-authentication-via-social-networks-for-your-laravel-application-d81cfc185e60
    //https://scotch.io/@neo/getting-started-with-laravel-passport

    /*Personal access client created successfully.
Client ID: 1
Client secret: 5gOcdT4BQsxsVW2AO4QrXgKqUd5NDPjY80OibZ3F
Password grant client created successfully.
Client ID: 2
Client secret: K7VZkX2DF70f8oF8U8HIzw9MbXxkbWMOJ26pIJEr
*/
}
