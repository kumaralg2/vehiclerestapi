<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Maker;
use App\Vehicle;

class MakerVehiclesController extends Controller
{
    //Show-gets a single maker:/maker/{maker_id}/vehicles
     public function index($id){
        $maker = Maker::find($id);
        if(!$maker){
            return response()->json(['message'=>'This maker does not exist','code'=>404],404);
        }
        return response() -> json(['data'=>$maker->vehicles],200);
    }

     public function show($id,$vehicleId){
        $maker = Maker::find($id);
        if(!$maker){
            return response()->json(['message'=>'This maker does not exist','code'=>404],404);
        } 
        $vehicle = $maker->vehicles->find($vehicleId);
        if(!$vehicle){
            return response()->json(['message'=>'This vehicle does not exist','code'=>404],404);
        }
        return response() -> json(['data'=>$vehicle],200);
    }
}
