<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;
use DB;

class UserController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|max:10|min:10',
            'role_id' =>  'required',
            'description' =>  'required'
        ]);
        if($validator->fails()){
            return response()->json(['status' => false,'msg' => $validator->errors()->all()]);
        }

        $user = User::insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'description' => $request->description,
            'profile_image' => null,
            'password' => '$2a$12$meDJPAETBGqp0Aaz2IUZA.rAfbR1Zhl.jg5DVyKqgnRgZD67gp.SK'
        ]);
        if($user > 0){
            foreach($request->role_id as $role){
                $role = DB::table('role_user')->insert([
                    'user_id' => $user,
                    'role_id' => $role,
                ]);
            }
        }

        return response()->json(['status' => true,'msg' => 'registered']);
    }

    public function load_users(){
        $users = User::with('roles')->get();
        return response()->json(['status' => true,'msg' => $users]);
    }
}
