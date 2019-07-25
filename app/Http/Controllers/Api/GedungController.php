<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use App\Models\Blok;
use Illuminate\Http\Request;
use Validator;

class GedungController extends Controller
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
        $building = new Gedung();
        $response = $building->all();
        $index = 1;
        $comments = Blok::find(1);
        foreach($response as $data){
            $comments = Blok::where('gedung_id', $data->id)->get();
            $data->namablock = $comments;
            $index++;
        }
        
        // $res = DB::table('gedung')
        // ->join('block', 'block.id','block.gedung_id')
        return response()->json($response, 200);
    }

    public function listgedung()
    {
        $building = new Gedung();
        $response = $building->select('id', 'nama_gedung')->get();
        return response()->json($response, 200);
    }

    public function create(Request $request)
    {
        $response = array(
            'status'  => false,
            'message' => "Failed to Create a Building!",
        );
        $validator = Validator::make($request->all(), [
            'nama_gedung' => 'required',
            'deskripsi'   => 'required',
            // 'listblok' => {
            //     'xx': 'required'
            // }
        ]);
        if ($validator->fails()) {
            $response['error'] = $validator->errors();
            return $response;
        }
        $building              = new Gedung();
        $building->nama_gedung = $request->nama_gedung;
        $building->deskripsi   = $request->deskripsi;
        $building->save();
        $datablok = $request->get('listblok');
        foreach($datablok as $blocklist) {
            $block = new Blok();
            $block->gedung_id = $building->id;
            $block->nama_blok = $blocklist['nama_blok'];
            $block->deskripsi = $blocklist['deskripsi'];
            $block->save();
        };
        $result = array(
            'code' => 200,
            'status' => true,
            'message' => 'Success'
        );
        return response()->json( $result, $result['code']);
    }


    public function delete(Request $request)
    {
        $building = new Gedung();
        $blok = new Blok();
        $found    = $building->where('id', $request->id)->first();
        $foundblok = $blok->where('gedung_id', $request->id)->first();
        $this->transStatus = false;
        if ($found) {
            $found->delete();
            $this->transStatus = true;
        }
        if ($foundblok) {
            $foundblok->delete();
            $this->transStatus = true;
        }
        
        $result = array(
            'code' => 200,
            'status' => true,
            'message' => 'Success to Delete a Building!'
        );
        return $result;
    }

    public function deleteblok(Request $request)
    {
        // $building = new Gedung();
        $blok = new Blok();
        // $found    = $building->where('id', $request->id)->first();
        $found = $blok->where('gedung_id', $request->gedung_id)->first();
        $foundblok = $found->where('id', $request->id)->first();
        if ($foundblok) {
            $foundblok->delete();
        }
        
        $result = array(
            'code' => 200,
            'status' => true,
            'message' => 'Success to Delete a Block!'
        );
        return $result;
    }

    public function update(Request $request)
    {
        $building = new Gedung();
        $found    = $building->where('id', $request->id)->first();
        $response = array(
            'status'  => false,
            'message' => 'Failed to Update a Building!',
        );

        if ($found) {
            $found->nama_gedung = $request->nama_gedung;
            $found->deskripsi   = $request->deskripsi;
            $found->save();

            $response['status']  = true;
            $response['message'] = 'Success to Update a Building';
        }

        return $response;

    }

    //
}
