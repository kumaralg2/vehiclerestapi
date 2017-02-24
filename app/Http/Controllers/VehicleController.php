<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vehicle;

class VehicleController extends Controller
{
    //Index-gets all the vehicles:\vehicles
     public function index(){
        $vehicles = Vehicle::all();
        return response() -> json(['data'=>$vehicles],200);
    }
}
