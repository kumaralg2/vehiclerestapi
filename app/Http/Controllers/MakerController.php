<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Maker;
use App\Http\Requests\CreateMakerRequest;
use App\Vehicle;
use JWTAuth;

class MakerController extends Controller
{
     //Construct for JWT Authentication
     public function __construct()
    {
        $this->middleware('jwt.auth', ['only' => [
          'update', 'store', 'destroy'
        ]]);
    }
    //Index-gets all makers:/makers
    public function index(){
        $makers = Maker::all();
        foreach($makers as $maker){
        $maker->view_maker = [
            'href' => 'api/v1/maker'.$maker->id,
            'method'=>'GET'
            ];
        }
        $response =[
            'msg' => 'List of Makers',
            'makers'=> $makers,
            'code'=>200
        ];
        return response() -> json(['data'=>$response],200);
    }



    //Show-gets a single maker:/maker/{maker_id}
     public function show($id){
        $maker = Maker::find($id);
        if(!$maker){
            return response()->json(['message'=>'This maker does not exist','code'=>404],404);
        }
        $maker->view_maker = [
            'href' => 'api/v1/maker'.$maker->id,
            'method'=>'GET'
            ];
        $response =[
            'msg' => 'Maker Information',
            'maker'=> $maker,
            'code'=> 200
        ];
        return response() -> json(['data'=>$response],200);
    }




    //Store-posts the name and phone data 
    public function store(CreateMakerRequest $request){
       //JWT Authentication
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['msg' => 'User not found'], 404);
        }
        $name = $request->input('name');
        $phone = $request->input('phone');
        $maker = new Maker([
           'name' => $name,
           'phone' => $phone
        ]);
        if ($maker->save()) {
        $maker->view_meeting = [
            'href' => 'api/v1/maker/' . $maker->id,
            'method' => 'GET'
            ];
        $response = [
            'msg' => 'Maker created',
            'maker' => $maker,
            'code'=> 200
            ];
            return response()->json($response, 201);
        }
        $response = [
            'msg' => 'Error during creationg'
        ];

        return response()->json($response, 404);    
        // $values = $request->only(['name','phone']);
        // Maker::create($values);
        // return response()->json(['message'=>'Maker correctly added'],201);
    }
    

     
     //Update-Update the Maker fields 
     public function update(CreateMakerRequest $request,$id){
        //Checking the maker 
        $maker = Maker::find($id);
        //JWT Authentication
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['msg' => 'User not found'], 404);
        } 
        if(!$maker){
            return response()->json(['message'=>'This maker does not exist','code'=>404],404);
        }
        $name = $request->get('name');
        $phone = $request->get('phone');

        $maker->name = $name;
        $maker->phone = $phone;
        //$maker->save();
        if(!$maker->save()){
         return response()->json(['msg' => 'Error during updating'], 404);
        }
        $maker->view_maker = [
            'href' => 'api/v1/makers/' . $maker->id,
            'method' => 'GET'
        ];
        $response = [
            'msg' => 'Maker updated',
            'maker' => $maker,
            'code' => 200
        ];
        return response()->json($response, 200);
        //return response()->json(['message'=>'The Maker has been updated','code'=>201],201);
    }




    //Delete Maker 
    public function destroy($id){
        //Checking whether the Maker Exists
        $maker = Maker::find($id);
        //JWT Authentication        
         if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['msg' => 'User not found'], 404);
        }
        
        if(!$maker){
            return response()->json(['message'=>'This maker does not exist','code'=>404],404);
        }
        $vehicles = $maker->vehicles;
        //Checking whether there are any vehicles for a maker
        if(sizeof($vehicles)>0){
            return response()->json(['message'=>'This maker has an associtated vehicle, delete vehicle first','code'=>409],409);
        }

        if (!$maker->delete()) {
            return response()->json(['msg' => 'deletion failed'], 404);
        }
        $response = [
            'msg' => 'Maker deleted',
            'create' => [
                'href' => 'api/v1/maker',
                'method' => 'POST',
                'params' => 'name, phone',
                'code'=>200
            ]
        ];

        return response()->json($response, 200);
    }
    //    //Delete the maker
    //    $maker -> delete();
    //         return response()->json(['message'=>'Maker is deleted','code'=>200],200);
    //     }
}
