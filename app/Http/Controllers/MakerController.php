<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Maker;
use App\Http\Requests\CreateMakerRequest;
use App\Vehicle;
class MakerController extends Controller
{
    //Index-gets all makers:/makers
    public function index(){
        $makers = Maker::all();
        return response() -> json(['data'=>$makers],200);
    }
    //Show-gets a single maker:/maker/{maker_id}
     public function show($id){
        $maker = Maker::find($id);
        if(!$maker){
            return response()->json(['message'=>'This maker does not exist','code'=>404],404);
        }
        return response() -> json(['data'=>$maker],200);
    }
    //Store-posts the name and phone data 
    public function store(CreateMakerRequest $request){
        $values = $request->only(['name','phone']);
        Maker::create($values);
        return response()->json(['message'=>'Maker correctly added'],201);
    }
     
     //Update-Update the Maker fields 
     public function update(CreateMakerRequest $request,$id){
        //Checking the maker 
        $maker = Maker::find($id);
        if(!$maker){
            return response()->json(['message'=>'This maker does not exist','code'=>404],404);
        }
        $name = $request->get('name');
        $phone = $request->get('phone');

        $maker->name = $name;
        $maker->phone = $phone;
        
        $maker->save();
        return response()->json(['message'=>'The Maker has been updated','code'=>201],201);
    }

    //Delete Maker 
    public function destroy($id){
        //Checking whether the Maker Exists
        $maker = Maker::find($id);
        if(!$maker){
            return response()->json(['message'=>'This maker does not exist','code'=>404],404);
        }
        $vehicles = $maker->vehicles;
        //Checking whether there are any vehicles for a maker
        if(sizeof($vehicles)>0){
            return response()->json(['message'=>'This maker has an associtated vehicle, delete vehicle first','code'=>409],409);
        }
       //Delete the maker
       $maker -> delete();
            return response()->json(['message'=>'Maker is deleted','code'=>200],200);
        }
}
