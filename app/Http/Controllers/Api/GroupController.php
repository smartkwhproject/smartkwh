<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Validator;

class GroupController extends Controller
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
        $group    = new Group();
        $response = $group->paginate(1);

        return $response;
    }

    public function create(Request $request)
    {
        $response = array(
            'status'  => false,
            'message' => "Failed",
        );

        $validator = Validator::make($request->all(), [
            'name'        => 'required|unique:group',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            $response['error'] = $validator->errors();

            return $response;
        }

        $group              = new group();
        $group->name        = $request->name;
        $group->description = $request->description;
        $group->save();

        return $group;
    }

    public function delete(Request $request)
    {
        $group = new Group();
        $found = $group->where('id', $request->id)->first();

        if ($found) {
            $found->delete();
        }

        $response = array(
            'status'  => true,
            'message' => 'Success Delete',
        );

        return $response;
    }

    public function update(Request $request)
    {
        $group    = new Group();
        $found    = $group->where('id', $request->id)->first();
        $response = array(
            'status'  => false,
            'message' => 'Failed Update',
        );

        if ($found) {
            $found->name        = $request->name;
            $found->description = $request->description;
            $found->save();

            $response['status']  = true;
            $response['message'] = 'Success Update';
        }

        return $response;

    }

    //
}
