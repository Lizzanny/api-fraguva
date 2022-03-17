<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        if ($request->isJson()) {
            $users = User::all();
            return response()->json($users,  200);
        }
        return response()->json(['error' => 'Unauthorized', 401, []]);
    }

    function create(Request $request)
    {
        if ($request->isJson()) {
            //TODO:Crear el usuario en la bd
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'rol_id' => $request->rol_id,
                'isactivo' => $request->isactivo,
                'password' => Hash::make($request->password),
                'api_token' => Str::random(60),
            ]);
            return response()->json($user,  201);
        }
        return response()->json(['error' => 'Unauthorized', 401, []]);
    }

    function getToken(Request $request)
    {
        if ($request->isJson()) {
            try {
                $data = $request->json()->all();
                $user = User::where('username', $data['username'])->first();
                if ($user && Hash::check($data['password'], $user->password)) {
                    return response()->json($user, 200);
                } else {
                    return response()->json(['error' => 'No content'], 406);
                }
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'Unauthorized'], 406);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 406);
        }
    }
}
