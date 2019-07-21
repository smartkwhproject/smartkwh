<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TempFinal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class TempFinalController extends Controller
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

        $stats = DB::select(DB::raw(
            'SELECT * from temp_final'
        ));

        return $stats;
    }

    public function create(Request $request)
    {
        $response = array(
            'status'  => false,
            'message' => "Failed to Create a Temp Final!",
        );

        $validator = Validator::make($request->all(), [
            'hasil' => 'required',
            'bulan' => 'required',
        ]);

        if ($validator->fails()) {
            $response['error'] = $validator->errors();

            return $response;
        }

        $tempfinal        = new TempFinal();
        $tempfinal->hasil = $request->hasil;
        $tempfinal->bulan = $request->bulan;
        $tempfinal->save();

        return $tempfinal;
    }

    // public function delete(Request $request)
    // {
    //     $building = new Gedung();
    //     $found    = $building->where('id', $request->id)->first();

    //     if ($found) {
    //         $found->delete();
    //     }

    //     $response = array(
    //         'status'  => true,
    //         'message' => 'Success to Delete a Building!',
    //     );

    //     return $response;
    // }

    // public function update(Request $request)
    // {
    //     $building = new Gedung();
    //     $found    = $building->where('id', $request->id)->first();
    //     $response = array(
    //         'status'  => false,
    //         'message' => 'Failed to Update a Building!',
    //     );

    //     if ($found) {
    //         $found->nama_gedung = $request->nama_gedung;
    //         $found->deskripsi   = $request->deskripsi;
    //         $found->save();

    //         $response['status']  = true;
    //         $response['message'] = 'Success to Update a Building';
    //     }

    //     return $response;

    // }

    //
}
