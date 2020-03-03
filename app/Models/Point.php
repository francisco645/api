<?php

namespace App\Models;

use App\Graphs\Node;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Point extends Model implements Node
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
        'name'
    ];

    public function getId()
    {
        return $this->getKey();
    }

    public function addNeighbor(Node $node, int $cost)
    {
        if (is_null($this->getKeyName())) {
            throw new \Exception('Unsaved node error.');
        }

        if($cost < 0) {
            throw new \Exception('Cost needs be positive.');
        }

        return !!Line::createLink($this, $node, $cost);
    }

    public function getNeighbors()
    {
        $result = Line::select(
            'distance',
            'from_id', 
            'to_id'
        )->where(function ($query) {
            $query->where('from_id', '=', $this->getId())
                ->orWhere('to_id', '=', $this->getId());
        })->get();

        $neighbors = [];
        foreach ($result as $point) {
            $neighborId = $point->from_id === $this->getId() ? $point->to_id : $point->from_id;
            $neighbors[$neighborId] = $point->distance;
        }

        return $neighbors;
    }
}

