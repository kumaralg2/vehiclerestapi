<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\maker;
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
}
