<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    //
    public function store(Request $request)
    {
        $validate = $request->validate([
            's_id' => 'required',
            'b_plateNum' => 'required',
            'type' => 'required',
            'time' => 'required',
            'date' => 'required'          
        ]);

        if ($validate) {
            $numID = DB::table('book')->where('plateNum',$request->input('b_plateNum'))->count();
            if($numID == 1) {
                DB::table('service')->insert($validate);
                return response()->json(['message' => 'success', 201]);
            } else {
                return response()->json(['message' => 'not save', 500]);
            }
        } else {
            return response()->json(['message' => 'Invalid format', 'status' => 400]);
        }
    }

    public function update(Request $request)
    {
        $validate = $request->validate([
            'id' => 'required',
            's_id' => 'required',
            'b_plateNum' => 'required',
            'type' => '',
            'time' => 'required',
            'date' => 'required'          
        ]);

        if ($validate) {
            $updateDetails = [
                's_id'=>$request->input('s_id'),
                'b_plateNum'=>$request->input('b_plateNum'),
                'type'=>$request->input('type'),
                'time'=>$request->input('time'),
                'date'=>$request->input('date')
            ];
            $update = DB::table('service')->where('id', $request->input('id'))->update($updateDetails);
            if($update == 1) {
                return response()->json(['message' => 'success', 201]);
            } else {
                return response()->json(['message' => 'not save', 500]);
            }
        } else {
            return response()->json(['message' => 'Invalid format', 'status' => 400]);
        }
    }

    public function view(Request $request)
    {
        $found = DB::table('service')->where([ ['b_plateNum', $request->input('plateNum')],
        ['date', $request->input('date')] ])->value('type');
        //$count = $found->count();
        //print_r(explode(',',$found));
        if($found == null) {
            return response()->json(['message'=>'no data']);
        } else {
            //print_r(explode(',',$found));
            return response()->json([ 'message'=>explode(',',$found) ]);
        }
    }

    public function id(Request $request) {
        $found = DB::table('service')->where([ ['b_plateNum', $request->input('plateNum')],
        ['date', $request->input('date')] ])->value('id');
        //$count = $found->count();
        //print_r(explode(',',$found));
        if($found == null) {
            return response()->json(['message'=>'no data']);
        } else {
            return response()->json([ 'message'=>explode(',',$found) ]);
        }
    }

    public function delete($id) {
        $delete = DB::table('service')->where('id',$id)->delete();
        if($delete != 0) {
            return response()->json(['message' => 'succesful']);
        } else {
            return response()->json(['message' => 'not succesful']);
        }
    }

    public function search($plateNum) {
        $model = DB::table('service')->where('b_plateNum', $plateNum)->value('model_name');
        $interval = DB::table('service')->where('b_plateNum', $plateNum)->value('interval');
        if($model != null) {
            $item = DB::table('item')->select('item1','item2','item3')->where([['model_name', $model], ['interval', $interval]])->get();
            return response()->json(['item' => $item]);
        }
    }
}
