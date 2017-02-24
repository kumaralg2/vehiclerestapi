<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\maker;
use App\Http\Requests\CreateMakerRequest;

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
    //Store 
    public function store(CreateMakerRequest $request){
        $values = $request->only(['name','phone']);
        Maker::create($values);
        return response()->json(['message'=>'Maker correctly added'],201);
    }
}
