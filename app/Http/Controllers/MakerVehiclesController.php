<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Maker;
use App\Vehicle;
use App\Http\Requests\CreateVehicleRequest;

class MakerVehiclesController extends Controller
{
    //Index-gets a single maker:/makers/{maker_id}/vehicles
     public function index($id){
        $maker = Maker::find($id);
        if(!$maker){
            return response()->json(['message'=>'This maker does not exist','code'=>404],404);
        }
        return response() -> json(['data'=>$maker->vehicles],200);
    }
     
    //Show-gets a single vechile:/makers/{maker_id}/vehicles/{vehicle_id}
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
      //Store- posts a single vehicle:/makers/{maker_id}/vehicles
       public function store(CreateVehicleRequest $request,$makerId){
        $maker = Maker::find($makerId);
        if(!$maker){
            return response()->json(['message'=>'This maker does not exist','code'=>404],404);
        } 
        $values = $request->all();
        $maker->vehicles()->create($values);
        return response()->json(['message'=>'The vehicle associated was created'],201);
       }
}
