<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();
        return response()->json($user, 200);
    }

    public function indexUser()
    {
        $user = Auth::user();
        return response()->json($user, 200);
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->cedula = $request->cedula;
        $user->email = $request->email;
        $user->rol_id = 2;
        $user->password =bcrypt($request->password);
        $user->save();
        return response()->json($user, 200);

    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->cedula = $request->cedula;
        $user->email = $request->email;
         $user->save();
        return response()->json($user);

    }

    /**
     * Remove the specified resource from storage.  
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json($user);
    }
}