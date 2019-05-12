<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function username()
    {
        return 'username';
    }
    public function login(Request $request)
    {
        $statusCode      = 400;
        $payloadResponse = array(
            'status'  => false,
            'message' => 'Failed',
            'token'   => '',
        );
        // Generate token
        $iat   = encrypt(strtotime(date('Y-m-d')));
        $exp   = encrypt(strtotime(date('Y-m-d')) + 3600);
        $token = $iat . Str::random(40) . $exp;
        $user  = User::where('username', $request->get('username'))->first();
        if ($user) {
            $user->makeVisible('password');
            $validate = app('hash')->check($request->get('password'), $user->password);
            if ($validate) {
                // update user token
                $user->api_token = $token;
                $user->save();
                $payloadResponse['status']  = true;
                $payloadResponse['message'] = 'Success';
                $payloadResponse['token']   = $token;
                $statusCode                 = 200;
            }
        }
        return response()->json($payloadResponse, $statusCode);
    }
}
