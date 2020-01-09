<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Cache;

class ModelIntervalController extends Controller
{
    //ITEM
    public function store(Request $request) {
        $validate = $request->validate([
            'item' => 'required',
            'model_name' => 'required'
        ]);

        if($validate) {
            DB::table('items')->insert($validate);
            return response()->json(['message' => 'Item stored!']);
        } else {
            return response()->json(['message' => 'Save failed!']);
        }
    }

    public function items($model) {
        return response()->json(['items' => DB::table('items')->where('model_name',$model)->get()]);
    }

    public function delItems($id) {

        $del = DB::table('items')->where('id', $id )->delete();
        if($del) {
            return response()->json(['message' => 'success']);
        }

    }

    public function updateItem(Request $request) {
        $update = DB::table('items')->where('id', $request->input('id'))->update(['item' => $request->input('item')]);
        if($update == 1) {
            return response()->json(['message' => 'successful']);
        } else {
            return response()->json(['message' => 'unsuccessful']);
        }
    }

    public function itemid($id) {
        $item = DB::table('items')->where('id',$id)->get();
        return response()->json(['item' => $item]);
    }

    //MODEL

    public function save(Request $request) {
        $validate = $request->validate([
            'name' => 'required',
        ]);
        if($validate) {
            DB::table('model')->insert($validate);
            return response()->json(['message' => 'Model saved']);
        } else {
            return response()->json(['message' => 'Save failed']);
        }
    }

    public function list() {
        $modelCar = DB::table('model')->get();
        return response()->json(['model' => $modelCar]);
    }

    public function updateModel(Request $request) {
        $updatemodel = DB::table('model')->where('id', $request->input('id'))->update(['name' => $request->input('name')]);
        if($updatemodel != 0) {
            return response()->json(['message' => 'successful']);
        } else {
            return response()->json(['message' => 'not succesful']);
        }
    }

    public function deleteModel($id) {
        $deleted = DB::table('model')->where('id',$id)->delete();
        if($deleted != 0) {
            return response()->json(['message' => 'successful']);
        } else {
            return response()->json(['message' => 'not successful']);
        }
    }

    

    
    

    public function search(Request $request) {

        echo DB::table('book')->where('plateNum', $request->input('model_name'))->get('plateNum');

        /*if($request->input('interval_model') <= '10,000km') {
            $search = DB::table('items')->select('item')->whereBetween('id',[4,6])->get();
            return response()->json(['search' => $search]);
        }
        //$search = DB::table('items')->select('item')->where([['interval_model', '<=', $request->input('interval_model')],
        //['model_name', '=', $request->input('model_name')]])->get();
        //echo $search;*/
    }

    public function storecacheProcess(Request $request) {

        $item = $request->input('item');
        $status = $request->input('status');
        Cache::put('item', $item, 30);
        Cache::put('status', $status, 30);
        /*if($process == 'start') {
            session(['process' => 'loading']);
            return session()->all();
        } else {
            session()->forget('process');
            session()->put('process', 'stop');
            //return session('process');
        }*/
    }

    public function getcacheProcess(Request $request) {

        if(Cache::has('item') && Cache::has('status')) {
            $item = Cache::pull('item');
            $status = Cache::pull('status');
            return response()->json(['item' => $item, 'status' => $status]);
        } 
    }

    public function deletecacheProcess() {
        if(Cache::has('item')) {
            $delete = Cache::forget('item');
            return response()->json(['item' => 'delete']);
        }
    }

}
