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

        $response        = 'User Not Found';
        $statusCode      = 404;
        $payloadResponse = array(
            'status'  => false,
            'message' => '',
            'token'   => '',
        );

        $user = User::where('username', $request->get('username'))->first();
        if ($user) {
            $user->makeVisible('password');
            $validate = app('hash')->check($request->get('password'), $user->password);
            if ($validate) {
                $response                   = $user->api_token;
                $payloadResponse['status']  = true;
                $payloadResponse['message'] = 'Success';
                $payloadResponse['token']   = $response;
                $statusCode                 = 200;
            }
        }

        return response()->json($payloadResponse, $statusCode);

    }
}
