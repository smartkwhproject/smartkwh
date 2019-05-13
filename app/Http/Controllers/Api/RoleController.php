<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Validator;

class RoleController extends Controller
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
        $role     = new Role();
        $response = $role->paginate(1);

        return $response;
    }

    public function create(Request $request)
    {
        $response = array(
            'status'  => false,
            'message' => "Failed to Create Role!",
        );

        $validator = Validator::make($request->all(), [
            'user_id'  => 'required',
            'group_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response['error'] = $validator->errors();

            return $response;
        }

        $role           = new Role();
        $role->user_id  = $request->user_id;
        $role->group_id = $request->group_id;
        $role->save();

        return $role;
    }

    public function delete(Request $request)
    {
        $role  = new Role();
        $found = $role->where('id', $request->id)->first();

        if ($found) {
            $found->delete();
        }

        $response = array(
            'status'  => true,
            'message' => 'Success to Delete Role!',
        );

        return $response;
    }

    public function update(Request $request)
    {
        $role     = new Role();
        $found    = $role->where('id', $request->id)->first();
        $response = array(
            'status'  => false,
            'message' => 'Failed to Update Role!',
        );

        if ($found) {
            $found->user_id  = $request->user_id;
            $found->group_id = $request->group_id;
            $found->save();

            $response['status']  = true;
            $response['message'] = 'Success to Update Role!';
        }

        return $response;

    }

    //
}
