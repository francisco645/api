<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Point;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public function index()
    {
        return Point::all();
    }

    public function show(Point $point)
    {
        return $point;
    }

    public function store(Request $request)
    {
        $point = Point::create($request->all());

        return response()->json($point, 201);
    }

    public function update(Request $request, Point $point)
    {
        $point->update($request->all());

        return response()->json($point, 200);
    }

    public function delete(Point $point)
    {
        $point->delete();

        return response()->json(null, 204);
    }

    public function addNeighbor(Request $request, Point $point)
    {
        $neighbor = Point::findOrFail($request->input('neighbor_id'));
        $distance = intval($request->input('distance', 0));

        if(!$point->addNeighbor($neighbor, $distance)) {
            return response()->json(null, 500);
        }

        return response()->json(null, 204);
    }
}
