<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function test(){
        view('auth.passwords.reset');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index1()
    {
        //
        $usr = DB::table('users')->where('role',1)->get();

        return UserResource::collection($usr);
    }

    public function index2()
    {
        //
        $usr = DB::table('users')->where('role',2)->get();

        return UserResource::collection($usr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validateData = $request->validate([
            'name' => 'required | max:255',
            'ic' => 'required | max:12',
            'address' => 'required | max:255',
            'username' => 'required | max:20 | unique:users',
            'email' => 'required | max:50',
            'password' =>  'required|min:8|max:12|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#_.$^+=!*()@%&]).{8,12}$/',
            'phone' => 'required | max:11',
            'role' => 'required'
        ]);

        $validateData['password'] = Hash::make($request->password);

        if ($validateData) {
            if($request->input('username') == 'admin' && $request->input('password') == 'Admin@123') {
                $validateData['role'] = '3';
                $user = DB::table('users')->insert([$validateData]);
                return response()->json(['message' => 'Successfully saved']);
            } else {
                $user = DB::table('users')->insert([$validateData]);
                return response()->json(['message' => 'Successfully saved']);
            }
        } else {
            return response()->json(['message' => 'Invalid format or no saved data']);
        }




        //$accessToken = $user->createToken('authToken')->accessToken;
        return response(['user' => $user]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $show = DB::table('users')->where('id', $id)->get();
        if (!$show) {
            return response()->json(["message" => "No data found", 401]);  //don't work
        } else {
            return response()->json(['user' => $show], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $updateData = $request->validate([
            'name' => 'required | max:255',
            'ic' => 'required | max:12',
            'address' => 'required | max:255',
            'username' => 'required | max:20',
            'email' => 'required | max: 50',
            'phone' => 'required | max:11',
        ]);

        if ($updateData) {
            $name = $request->input('name');
            $ic = $request->input('ic');
            $address = $request->input('address');
            $username = $request->input('username');
            $email = $request->input('email');
            $phone = $request->input('phone');

            $update = DB::table('users')->where('id', $id)->update([
                'name' => $name,
                'ic' => $ic,
                'address' => $address,
                'username' => $username,
                'email' => $email,
                'phone' => $phone
            ]);

            if ($update) {
                $Username = DB::table('users')->where('id', $id)->value('username');
                return response()->json(['message' => 'Success updated!', 'username' => $Username]);
            } else {
                return response()->json(['message' => 'No update!']);
            }
        }
    }

    public function updateRole($id) {
        $update = DB::table('users')->where('id',$id)->update(['role' => 2]);
        if($update != 0) {
            return response()->json(['message' => 'successful']);
        } else {
            return response()->json(['message' => 'not successful']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $delete = DB::table('users')->where('id', $id)->delete();
        if ($delete) {
            return response()->json(['message' => 'Success']);
        } else {
            return response()->json(['message' => 'Id does not exist']);
        }
    }

    public function change(Request $request)
    {
        $id = $request->input('id');
        $curr_password = $request->input('curr_password');
        $hashed = DB::table('users')->where('id', $id)->value('password');
        if (Hash::check($curr_password, $hashed) == 1) {
            $updatepassword = DB::table('users')->where('id', $id)
                ->update(['password' => Hash::make($request->input('new_password'))]);
            if($updatepassword == 1) {
                return response()->json(['message' => 'updated']);
            } else {
                return response()->json(['message' => 'not updated']);
            }
        } else {
            return response()->json(['message' => 'password not match']);
        }
    }
}
