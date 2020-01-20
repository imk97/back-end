<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Cache;

class ModelIntervalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //ITEM
    public function store(Request $request)
    {
        $validate = $request->validate([
            'item' => 'required',
            'model_name' => 'required'
        ]);

        if ($validate) {
            DB::table('items')->insert($validate);
            return response()->json(['message' => 'Item stored!']);
        } else {
            return response()->json(['message' => 'Save failed!']);
        }
    }

    public function items($model)
    {
        return response()->json(['items' => DB::table('items')->where('model_name', $model)->get()]);
    }

    public function delItems($id)
    {

        $del = DB::table('items')->where('id', $id)->delete();
        if ($del) {
            return response()->json(['message' => 'success']);
        }
    }

    public function updateItem(Request $request)
    {
        $update = DB::table('items')->where('id', $request->input('id'))->update(['item' => $request->input('item')]);
        if ($update == 1) {
            return response()->json(['message' => 'successful']);
        } else {
            return response()->json(['message' => 'unsuccessful']);
        }
    }

    public function itemid($id)
    {
        $item = DB::table('items')->where('id', $id)->get();
        return response()->json(['item' => $item]);
    }

    //MODEL

    public function save(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
        ]);
        if ($validate) {
            DB::table('model')->insert($validate);
            return response()->json(['message' => 'Model saved']);
        } else {
            return response()->json(['message' => 'Save failed']);
        }
    }

    public function list()
    {
        $modelCar = DB::table('model')->get();
        return response()->json(['model' => $modelCar]);
    }

    public function updateModel(Request $request)
    {
        $updatemodel = DB::table('model')->where('id', $request->input('id'))->update(['name' => $request->input('name')]);
        if ($updatemodel != 0) {
            return response()->json(['message' => 'successful']);
        } else {
            return response()->json(['message' => 'not succesful']);
        }
    }

    public function deleteModel($id)
    {
        $deleted = DB::table('model')->where('id', $id)->delete();
        if ($deleted != 0) {
            return response()->json(['message' => 'successful']);
        } else {
            return response()->json(['message' => 'not successful']);
        }
    }






    public function search(Request $request)
    {

        echo DB::table('book')->where('plateNum', $request->input('model_name'))->get('plateNum');

        /*if($request->input('interval_model') <= '10,000km') {
            $search = DB::table('items')->select('item')->whereBetween('id',[4,6])->get();
            return response()->json(['search' => $search]);
        }
        //$search = DB::table('items')->select('item')->where([['interval_model', '<=', $request->input('interval_model')],
        //['model_name', '=', $request->input('model_name')]])->get();
        //echo $search;*/
    }

    public function storecacheProcess(Request $request)
    {
        $status = $request->input('status');
        if ( $status == 'end' ) {
            $item = $request->input('item');
            $end[] = explode(",",$item);
            Cache::put('item', $end);
            return response()->json([ 'message' => $end ]);
        } else {
            $boolean = false;
            Cache::put('bool', $boolean);
            return response()->json(['message' => $boolean]);
        }
    }

    public function getcacheProcess(Request $request)
    {
        if ( Cache::has('item') && Cache::get('bool') == false) {
            $item = Cache::pull('item');
            $boolean = true;
            return response()->json([ 'item' => $item , 'message' => true ]);
        } else {
            $boolean = Cache::pull('bool');
            return response()->json([ 'message' => $boolean ]);
        }
    }

    public function deletecacheProcess()
    {
        Cache::flush();
    }
}
