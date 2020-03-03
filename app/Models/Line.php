<?php

namespace App\Models;

use App\Graphs\Node;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'from_id', 'to_id', 'distance'
    ];

    public static function costLink(Node $nodeA, Node $nodeB) 
    {
        $result = self::select('distance')->where(function ($query) use ($nodeA, $nodeB) {
            $query->where('from_id', '=', $nodeA->getId())
                  ->where('to_id', '=', $nodeB->getId());
        })->orWhere(function ($query) use ($nodeA, $nodeB) {
            $query->where('from_id', '=', $nodeB->getId())
                  ->where('to_id', '=', $nodeA->getId());
        })->first();

        return is_null($result) ? 0 : $result->distance;
    }

    public static function createLink(Node $nodeA, Node $nodeB, int $distance) 
    {
        if(self::costLink($nodeA, $nodeB) > 0) {
            throw new \Exception('This node edge link already exists.');
        }

        if($distance < 0) {
            throw new \Exception('Distance needs be positive.');
        }

        return self::create([
            'from_id' => $nodeA->getId(), 
            'to_id' => $nodeB->getId(), 
            'distance' => $distance
        ]);
    }
}
