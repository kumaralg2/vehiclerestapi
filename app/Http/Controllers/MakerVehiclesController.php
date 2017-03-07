<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Maker;
use App\Vehicle;
use App\Http\Requests\CreateVehicleRequest;

class MakerVehiclesController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['only' => [
          'update', 'store', 'destroy'
        ]]);
    }

    //Index-gets a single maker:/makers/{maker_id}/vehicles
    public function index($id)
    {
        //Getting all the Makers
        $maker = Maker::find($id);
        if (!$maker) {
            return response()->json(['message'=>'This maker does not exist','code'=>404], 404);
        }
        //Getting all the vehicles
        $vehicles = Vehicle::with('makers')->get();
        if (!$vehicles) {
            return response()->json(['message'=>'No Vehicles exist','code'=>404], 404);
        }
        // return response() -> json(['data'=>$maker->vehicles],200);
        foreach ($vehicles as $vehicle) {
            $vehicle->view_vehicle = [
               'method'=>'GET',
               'href' => 'api/v1/makers/'.$maker->id.'/vehicles/'.$vehicle->serie,
            ];
        }
        $response =[
          'msg' => 'List of Vehicles',
          'vehicle'=>$vehicles,
          'code'=>200
        ];
        return response() -> json(['data'=>$response], 200);
    }
     
    //Show-gets a single vechile:/makers/{maker_id}/vehicles/{vehicle_id}
    public function show($id, $vehicleId)
    {
        $maker = Maker::find($id);
        if (!$maker) {
            return response()->json(['message'=>'This maker does not exist','code'=>404], 404);
        }
        $vehicle = $maker->vehicles->find($vehicleId);
        if (!$vehicle) {
            return response()->json(['message'=>'This vehicle does not exist','code'=>404], 404);
        }
        $vehicle->vehicle_maker = [
             'href' => 'api/v1/vehicles/'.$vehicle->serie,
             'method'=>'GET'
          ];
        $response =[
          'msg' => 'Vehicle Information',
          'vehicle'=>$vehicle,
          'code'=>200
        ];
        return response() -> json(['data'=>$response], 200);
       // return response() -> json(['data'=>$vehicle],200);
    }




      //Store- posts a single vehicle:/makers/{maker_id}/vehicles
    public function store(CreateVehicleRequest $request, $makerId)
    {
        $maker = Maker::find($makerId);
        //JWT Authentication        
         if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['msg' => 'User not found'], 404);
        }
        //Check whether the Maker exists
        if (!$maker) {
            return response()->json(['message'=>'This maker does not exist','code'=>404], 404);
        }
        $values = $request->all();
        // $maker->vehicles()->create($values);
        if ($maker->vehicles()->create($values)) {
            $maker->view_vehicle = [
            'href' => 'api/v1/maker/' . $maker->id.'/vehicles/'.$vehicle.serie,
            'method' => 'GET'
            ];
            $message = [
            'msg' => 'New Vehicle created',
            'vehicle' => $maker,
            'code'=>200
            ];
            return response()->json($message, 201);
        }
        $response = [
        'msg' => 'Error during creationg'
        ];

        return response()->json($response, 404);
        //return response()->json(['message'=>'The vehicle associated was created'],201);
    }

      //Update-update a vehicle:makers/{maker_id}/vehicles/2
    public function update(CreateVehicleRequest $request, $makerId, $vehicleId)
    {
        $maker = Maker::find($makerId);
        //JWT Authentication        
         if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['msg' => 'User not found'], 404);
        }

        if (!$maker) {
            return response()->json(['message'=>'This maker does not exist','code'=>404], 404);
        }
        
        $vehicle = $maker->vehicles->find($vehicleId);
        if (!$vehicle) {
            return response()->json(['message'=>'This vehicle does not exist','code'=>404], 404);
        }

        $color = $request->get('color');
        $power = $request->get('power');
        $capacity = $request->get('capacity');
        $speed = $request->get('speed');

        $vehicle->color = $color;
        $vehicle->power = $power;
        $vehicle->capacity = $capacity;
        $vehicle->speed = $speed;
        
        // $vehicle->save();
        if ($vehicle->save()) {
            $vehicle->view_vehicle = [
            'href' => 'api/v1/maker/' . $maker->id.'/vehicles/'.$vehicle.serie,
                'method' => 'GET'
            ];
            $response = [
             'msg' => 'Vehicle information updated',
             'vehicle' => $vehicle,
             'code'=>200
            ];
            return response()->json($response, 201);
        }
        $response = [
        'msg' => 'Error during creationg'
        ];

        return response()->json($response, 404);
        // return response()->json(['message'=>'The Maker has been updated','code'=>201],201);
    }




     //Delete Vehicle
    public function destroy($makerId, $vehicleId)
    {
        //Get Maker with the id
        $maker = Maker::find($makerId);
        //JWT Authentication        
         if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['msg' => 'User not found'], 404);
        }
        //Checking whether the Maker Exists
        if (!$maker) {
            return response()->json(['message'=>'This maker does not exist','code'=>404], 404);
        }
        //Get the vehicle associated with the maker
        $vehicle = $maker->vehicles->find($vehicleId);
        //Check whether the vehicle exists
        if (!$vehicle) {
            return response()->json(['message'=>'This vehicle does not exist','code'=>404], 404);
        }
       //Delete the maker
        // $vehicle -> delete();
        if (!$vehicle -> delete()) {
            return response()->json(['msg' => 'Deletion failed'], 404);
        }
        $response = [
              'msg' => 'Vehicle information deleted',
              'create' => [
                  'href' => 'api/v1/maker/{maker_id}/vehicles/serie',
                  'method' => 'POST',
                  'params' => 'color,capacity,power,speed',
                  'code'=> 200
              ]
          ];
        return response()->json($response, 200);
        // return response()->json(['message'=>'The Vehicle has been deleted','code'=>200],200);
    }
}
