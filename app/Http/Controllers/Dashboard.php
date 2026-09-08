<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class dashboard extends Controller
{
    //========= Autenticación
        public function __construct()
    {
        $this->middleware('auth');
    }
    

    //========= Vista Dashboard
    public function index()
    {
        return view("dashboard.home");
    }
}
