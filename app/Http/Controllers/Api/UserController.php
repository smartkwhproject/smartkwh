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
        $response = $user->paginate(20);

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

  public function dataSetKMean()
    {
        $dataSets = array(
            array('no' => 4, 'kwh' => 9, 'timestamp' => date('Y-m-d H:i:s', strtotime("+ 1 minute"))),
            array('no' => 7, 'kwh' => 10, 'timestamp' => date('Y-m-d H:i:s', strtotime("+ 2 minute"))),
            array('no' => 3, 'kwh' => 8, 'timestamp' => date('Y-m-d H:i:s', strtotime("+ 3 minute"))),
            array('no' => 5, 'kwh' => 2, 'timestamp' => date('Y-m-d H:i:s', strtotime("+ 4 minute"))),
            array('no' => 1, 'kwh' => 6, 'timestamp' => date('Y-m-d H:i:s', strtotime("+ 5 minute"))),

        );

        return $dataSets;
    }

    public function generateSentroids($dataSets)
    {
        $min = 0;
        $max = count($dataSets) - 1;

        $sentroids = array();

        // for ($i = 0; $i < 3; $i++) {
        //     $random = rand($min, $max);
        //     array_push($sentroids, $dataSets[$random]);
        // }

        array_push($sentroids, $dataSets[0]);
        array_push($sentroids, $dataSets[2]);
        array_push($sentroids, $dataSets[4]);

        return $sentroids;

    }

    public function clusteringKmean($sentroidDatasets)
    {
        $sentroids  = $sentroidDatasets['sentroids'];
        $sentroids1 = $sentroids[0]['cluster'];
        $sentroids2 = $sentroids[1]['cluster'];
        $sentroids3 = $sentroids[2]['cluster'];
        $temp       = 0;
        $temp1      = 0;
        $final      = array();

        for ($i = 0; $i < 5; $i++) {
            if ($sentroids1[$i] < $sentroids2[$i]) {
                $temp = $sentroids1[$i];
            } elseif ($sentroids1[$i] > $sentroids2[$i]) {
                $temp = $sentroids2[$i];
            }

            if ($temp < $sentroids3[$i]) {
                $temp1 = $temp;
            } else {
                $temp1 = $sentroids3[$i];
            }

            array_push($final, $temp1);
        }

        return $final;
    }

    public function kmeans()
    {
        $dataSets  = $this->dataSetKMean();
        $sentroids = $this->generateSentroids($dataSets);
        $cluster   = array();
        // karena data sentroid sudah pasti 3
        for ($i = 0; $i < 3; $i++) {
            $ctrDatasets              = count($dataSets);
            $sentroids[$i]['cluster'] = array();
            for ($j = 0; $j < $ctrDatasets; $j++) {
                $mutationKwh  = pow($dataSets[$j]['kwh'] - $sentroids[$i]['kwh'], 2);
                $mutationNo   = pow($dataSets[$j]['no'] - $sentroids[$i]['no'], 2);
                $mutationSum  = $mutationKwh + $mutationNo;
                $mutationSqrt = sqrt($mutationSum);
                // array_push($sentroids[$i]['cluster'], array(
                //     'sentroidsKwh'  => $mutationKwh,
                //     'sentroidsNo'   => $mutationNo,
                //     'sentroidsSum'  => $mutationSum,
                //     'sentroidsSqrt' => $mutationSqrt,
                // ));
                array_push($sentroids[$i]['cluster'], $mutationSqrt);
            }
        }

        $sentroidDatasets = array('dataSets' => $dataSets, 'sentroids' => $sentroids);

        $final = $this->clusteringKmean($sentroidDatasets);

        return $final;
    }

    //
}
