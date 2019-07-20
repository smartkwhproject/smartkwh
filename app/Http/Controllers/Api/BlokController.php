<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blok;
use Illuminate\Http\Request;
use Validator;

class BlokController extends Controller
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
    public function getBlockByBuildingId($buildingId)
    {
        $blockObj  = new Blok();
        $blockData = $blockObj->where('gedung_id', $buildingId)->get();
        return $blockData;
    }

    public function view()
    {
        $block    = new Blok();
        $response = $block->all();

        return $response;
    }

    public function create(Request $request)
    {

        $response = array(
            'status'  => false,
            'message' => "Failed to Create a Block!",
        );

        $validator = Validator::make($request->all(), [
            'nama_blok' => 'required',
            'deskripsi' => 'required',
            'gedung_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response['error'] = $validator->errors();

            return $response;
        }

        $block            = new Blok();
        $block->nama_blok = $request->nama_blok;
        $block->deskripsi = $request->deskripsi;
        $block->gedung_id = $request->gedung_id;

        $block->save();

        return $block;
    }

    public function delete(Request $request)
    {
        $block = new Blok();
        $found = $block->where('id', $request->id)->first();

        if ($found) {
            $found->delete();
        }

        $response = array(
            'status'  => true,
            'message' => 'Success to Delete a Block!',
        );

        return $response;
    }

    public function update(Request $request)
    {
        $block    = new Blok();
        $found    = $block->where('id', $request->id)->first();
        $response = array(
            'status'  => false,
            'message' => 'Failed to Update a Block!',
        );

        if ($found) {
            $found->nama_blok = $request->nama_blok;
            $found->deskripsi = $request->deskripsi;
            $found->gedung_id = $request->gedung_id;
            $found->save();

            $response['status']  = true;
            $response['message'] = 'Success to Update a Block!';
        }

        return $response;

    }

    //
}
