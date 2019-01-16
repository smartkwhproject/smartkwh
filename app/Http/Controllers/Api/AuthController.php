<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {

        $response   = 'User Not Found';
        $statusCode = 404;

        $user = User::where('username', $request->get('username'))->first();
        if ($user) {
            $user->makeVisible('password');
            $validate = app('hash')->check($request->get('password'), $user->password);
            if ($validate) {
                $response   = $user->api_token;
                $statusCode = 200;
            }
        }

        return response()->json($response, $statusCode);

    }
}
