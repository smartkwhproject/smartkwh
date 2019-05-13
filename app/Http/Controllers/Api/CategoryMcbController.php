<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryMcb;
use Illuminate\Http\Request;
use Validator;

class CategoryMcbController extends Controller
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
        $category_mcb = new CategoryMcb();
        $response     = $category_mcb->paginate(1);

        return $response;
    }

    public function create(Request $request)
    {
        $response = array(
            'status'  => false,
            'message' => "Failed to Create Category MCB!",
        );

        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
            'min'           => 'required',
            'max'           => 'required',
        ]);

        if ($validator->fails()) {
            $response['error'] = $validator->errors();

            return $response;
        }

        $category_mcb                = new CategoryMcb();
        $category_mcb->category_name = $request->category_name;
        $category_mcb->min           = $request->min;
        $category_mcb->max           = $request->max;
        $category_mcb->save();

        return $category_mcb;
    }

    public function delete(Request $request)
    {
        $category_mcb = new CategoryMcb();
        $found        = $category_mcb->where('id', $request->id)->first();

        if ($found) {
            $found->delete();
        }

        $response = array(
            'status'  => true,
            'message' => 'Success to Delete Category MCB!',
        );

        return $response;
    }

    public function update(Request $request)
    {
        $category_mcb = new CategoryMcb();
        $found        = $category_mcb->where('id', $request->id)->first();
        $response     = array(
            'status'  => false,
            'message' => 'Failed to Update Category MCB!',
        );

        if ($found) {
            $found->category_name = $request->category_name;
            $found->min           = $request->min;
            $found->max           = $request->max;
            $found->save();

            $response['status']  = true;
            $response['message'] = 'Success to Update Category MCB!';
        }

        return $response;

    }

    //
}
