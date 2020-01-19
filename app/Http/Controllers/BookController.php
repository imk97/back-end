<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BookResource;

class BookController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = DB::table('book')->get();
        return BookResource::collection($books);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'plateNum' => 'required',
            'date' => 'required ',
            'time' => 'required',
            'user_id' => 'required',
            'model_name' => 'required',
            'model_interval' => 'required',
        ]);

        //$validate['item'] = DB::table('items')->select('id')->where('item', $request->input('item')->get());
        $validate['plateNum'] = strtoupper($validate['plateNum']);
        $validate['date'] = substr($validate['date'], 0, 10);
        $validate['time'] = substr($validate['time'], 0, 4);
        if ($validate) {
            $stored = DB::table('book')->insert($validate);
            if ($stored == 1) {
                return response()->json(['message' => 'Appointment successfully saved'], 201);
            } else {
                return response()->json(['message' => 'Appointment can not be made'], 500);
            }
        } else {
            $plateNumber = DB::table('book')->select('plateNum')->where('plateNum', $validate['plateNum'])->get();
            if ($validate['plateNum'] == $plateNumber) {
                return response()->json(['message' => 'Plate Number is duplicated'], 500);
            } else {
                return response()->json(['message' => 'Invalid format'], 400);
            }
        }
    }

    public function update(Request $request)
    {
        $validate = $request->validate([
            'id' => 'required',
            'plateNum' => 'required',
            'date' => 'required ',
            'time' => 'required',
            'user_id' => 'required',
            'model_name' => 'required',
            'model_interval' => 'required',
        ]);
        $validate['plateNum'] = strtoupper($validate['plateNum']);
        $validate['date'] = substr($validate['date'], 0, 10);
        $validate['time'] = substr($validate['time'], 0, 4);
        if ($validate) {
            $updateDetails = [
                'plateNum' => $request->input('plateNum'),
                'date' => $request->input('date'),
                'time' => $request->input('time'),
                'user_id' => $request->input('user_id'),
                'model_name' => $request->input('model_name'),
                'model_interval' => $request->input('model_interval'),
            ];
            $update = DB::table('book')->where('id', $request->input('id'))->update($updateDetails);
            if ($update == 1) {
                return response()->json(['message' => 'Appointment successfully updated'], 201);
            } else {
                return response()->json(['message' => 'Appointment not successfully updated'], 500);
            }
        };
        //$validate['item'] = DB::table('items')->select('id')->where('item', $request->input('item')->get());

        if ($validate) {
        } else {
            $plateNumber = DB::table('book')->select('plateNum')->where('plateNum', $validate['plateNum'])->get();
            if ($validate['plateNum'] == $plateNumber) {
                return response()->json(['message' => 'Plate Number is duplicated'], 500);
            } else {
                return response()->json(['message' => 'Invalid format'], 400);
            }
        }
    }

    public function show($id)
    {
        $show = DB::table('book')->where('user_id', $id)->get();
        if (!$show) {
            return response()->json("No data found", 401);
        } else {
            return response()->json(['book' => $show], 200);
        }
    }

    public function delete($plateNum)
    {
        $deleted = DB::table('book')->where('plateNum', $plateNum)->delete();
        if (!$deleted) {
            return response()->json("No data found", 401);
        } else {
            return response()->json(["message" => "deleted"], 200);
        }
    }

    public function availableHours(Request $request)
    {

        $reqDate = substr($request->input('date'), 0, 10);
        $currentTime = date_default_timezone_set("Singapore");
        $a1 = array("09:00:00", "10:00:00", "11:00:00", "12:00:00", "13:00:00", "14:00:00", "15:00:00", "16:00:00");
        $a2 = array();
        $a3 = array();

        $time = DB::table('book')->where('date', $reqDate)->pluck('time');
        foreach ($time as $t) {
            $a2[] = $t;
        }

        /*for($i = 0; $i < count($a1); $i++) {
            $curr = $a1[$i];
            if($curr < $currentTime) {
                unset($a1[$i]);
            }
        }
        print_r($a1);*/

        $result = array_values(array_diff($a1, $a2));

        if (count($result) <= 8) {
            return response()->json(['availabelHours' => $result]);
        } else if (count($result) == 0) {
            return response()->json(['availabelHours' => $result]);
        }
    }

    public function search(Request $request)
    {
        $plateNum = $request->input('plateNum');
        $date = $request->input('date');
        $searchplatenum = DB::table('book')->where([
            ['plateNum', 'like', '%' . $plateNum . '%'],
            ['date', '=', $date]
        ])->get();
        if ($searchplatenum != null) {
            return response()->json(['message' => $searchplatenum]);
        } else {
            return response()->json(['message' => 'No data']);
        }
    }

    public function date($date)
    {

        $plateNumber = DB::table('book')->select('plateNum', 'time')->where('date', $date)->get();

        $arr = array();

        foreach ($plateNumber as $plate) {
            $arr[] = $plate;
        }
        //print_r($arr);
        //echo $arr[2]->plateNum;
        for ($i = 0; $i < count($arr) - 1; $i++) {
            $current = $arr[$i];
            $next = $arr[$i + 1];
            if (($next->time) < ($current->time)) {
                $arr[$i] = $next;
                $arr[$i + 1] = $current;
            }
        }
        //print_r($arr);
        if (!empty($arr)) {
            return response()->json(['plateInfo' => $arr]);
        } else {
            return response()->json(['plateInfo' => 'No data']);
        }
    }
}
