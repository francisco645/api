<?php

namespace App\Http\Controllers\Api;

use App\Graphs\Dijkstra;
use App\Http\Controllers\Controller;
use App\Models\Point;
use Illuminate\Http\Request;

class LineController extends Controller
{
    public function near(Point $from, Point $to)
    {
        $path = new Dijkstra(Point::all());
        
        $nearPath = $path->nearPath($from, $to);

        return response()->json($nearPath, 200);
    }
}
