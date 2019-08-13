<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
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

    public function view()
    {
        $user     = new User();
        $response = $user->all();

        return $response;
    }

    public function create(Request $request)
    {
        $response = array(
            'status'  => false,
            'message' => "Failed to Create an User!",
        );

        $validator = Validator::make($request->all(), [
            'nama'     => 'required',
            'username' => 'required|unique:user',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $response['error'] = $validator->errors();

            return $response;
        }

        $user            = new User();
        $user->nama      = $request->nama;
        $user->username  = $request->username;
        $user->password  = app('hash')->make($request->password);
        $user->api_token = \base64_encode($request->username) . '.' . \base64_encode(date('Y-m-d H:i:s')) . '.' . \base64_encode(\str_random(5));
        $user->save();

        return $user;
    }


    public function delete(Request $request)
    {
        $user  = new User();
        $found = $user->where('id', $request->id)->first();

        if ($found && $request->id != 1) {
            $found->delete();
        }

        $response = array(
            'status'  => true,
            'message' => 'Success to Delete an User!',
        );

        return $response;
    }
    
    public function update(Request $request)
    {
        $user     = new User();
        $found    = $user->where('id', $request->id)->first();
        $response = array(
            'status'  => false,
            'message' => 'Failed to Update an User!',
        );

        if ($found) {
            $found->nama      = $request->nama;
            $found->username  = $request->username;
            $found->password  = app('hash')->make($request->password);
            $found->api_token = \base64_encode($request->username) . '.' . \base64_encode(date('Y-m-d H:i:s')) . '.' . \base64_encode(\str_random(5));
            $found->save();

            $response['status']  = true;
            $response['message'] = 'Success to Update an User!';
        }

        return $response;

    }

    public function resetPassword(Request $request)
    {
        $response = array('status' => false, 'message' => 'Gagal Reset Password');
        $userId   = $request->id;
        $password = $request->password;
        $user     = User::where('id', $userId)->first();

        if ($user) {
            $user->password = app('hash')->make($password);
            $isSuccess      = $user->save();
            if ($isSuccess) {
                $response['status']  = true;
                $response['message'] = 'Success Reset Password';
            }
        }

        return response()->json($response, 200);

    }

}
