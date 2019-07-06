<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Building;

class DashboardController extends Controller
{
    public function view2()
    {
        $building = new Building();
        $response = $building->all();

        return $response;
    }

    public function view()
    {
        $block    = new Block();
        $response = $block->all();

        return $response;
    }

}
