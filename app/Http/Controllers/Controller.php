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
        Client secret: G9PFECpa0VHL4UEeRDyQPwHSD63Tk7dFLIRN4YXf
        Password grant client created successfully.
        Client ID: 2
        Client secret: Qlb1eDNFZkzXLhHT6w2ESD6PwtjBEICwppycFSZ0
*/
}
