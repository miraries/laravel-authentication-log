<?php

namespace Yadahan\AuthenticationLog\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AuthenticationLogController extends Controller
{
    public function __invoke()
    {
        return Auth::user()->authentications;
    }
}
