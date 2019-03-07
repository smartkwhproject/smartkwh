<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Block;
use Illuminate\Http\Request;
use Validator;

class BlockController extends Controller
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
        $block    = new Block();
        $response = $block->paginate(1);

        return $response;
    }

    public function create(Request $request)
    {
        $response = array(
            'status'  => false,
            'message' => "Failed",
        );

        $validator = Validator::make($request->all(), [
            'block_name'  => 'required',
            'description' => 'required',
            'building_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response['error'] = $validator->errors();

            return $response;
        }

        $block              = new Block();
        $block->block_name  = $request->block_name;
        $block->description = $request->description;
        $block->building_id = $request->building_id;
        $block->save();

        return $block;
    }

    public function delete(Request $request)
    {
        $block = new Block();
        $found = $block->where('id', $request->id)->first();

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
        $block    = new Block();
        $found    = $block->where('id', $request->id)->first();
        $response = array(
            'status'  => false,
            'message' => 'Failed Update',
        );

        if ($found) {
            $found->block_name  = $request->block_name;
            $found->description = $request->description;
            $found->building_id = $request->building_id;
            $found->save();

            $response['status']  = true;
            $response['message'] = 'Success Update';
        }

        return $response;

    }

    //
}
