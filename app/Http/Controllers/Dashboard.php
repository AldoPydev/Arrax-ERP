<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


//======= IMPORTAR MODELO USER
use App\Models\User;



class dashboard extends Controller
{
    //========= Autenticación
        public function __construct()
    {
        $this->middleware('auth');
    }
    

    //========= Vista Dashboard
    public function index(Request $request)
    {
        $users = User::all();

        return view("dashboard.home", compact("users"));
    }
}
