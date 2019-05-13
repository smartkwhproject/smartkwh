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
        $response = $user->paginate(1);

        return $response;
    }

    public function create(Request $request)
    {
        $response = array(
            'status'  => false,
            'message' => "Failed to Create an User!",
        );

        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'username' => 'required|unique:user',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $response['error'] = $validator->errors();

            return $response;
        }

        $user            = new User();
        $user->name      = $request->name;
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

        if ($found) {
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
            $found->name      = $request->name;
            $found->username  = $request->username;
            $found->password  = $request->password;
            $found->api_token = '-';
            $found->save();

            $response['status']  = true;
            $response['message'] = 'Success to Update an User!';
        }

        return $response;

    }

    //
}
