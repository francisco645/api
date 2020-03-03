<?php

namespace App\Graphs;

use Exception;
use Illuminate\Database\Eloquent\Collection;

/**
 * @link https://www.youtube.com/watch?v=DCJcH6LRkJk
 */
class Dijkstra
{
    /**
     * @var \App\Graphs\Node[] $_nodes
     */
    private $_nodes;

    public function __construct($nodes)
    {
        if(!is_array($nodes) && !($nodes instanceof Collection)) {
            throw new Exception("Invalid nodes");
        }

        $this->_nodes = array();
        foreach ($nodes as $node) {
            if ($node instanceof Node) {
                $this->_nodes[$node->getId()] = $node;
            }
        }
    }

    public function nearPath(Node $from, Node $to) 
    {
        //Initialization
        $unvisited = [];
        $prev = [];
        $cost = [];

        //Initialize variables
        foreach ($this->_nodes as $node) {
            $unvisited[$node->getId()] = $node;

            if($from->getId() === $node->getId()) {
                //The initial node has cost 0 and no previous vertex
                $cost[$node->getId()] = 0;
            } else {
                //All other nodes will have its cost set to MAXIMUM
                $cost[$node->getId()] = 0x7FFFFFFF;
            } 

            //All other nodes will have its undefined previous
            $prev[$node->getId()] = null;
        }

        // Graph search
        while (count($unvisited) > 0) {
            //
            $nearId = $this->closest($cost, $unvisited);
            $neighbors = $unvisited[$nearId]->getNeighbors();
            unset($unvisited[$nearId]);

            foreach ($neighbors as $neighborId => $neighborCost) {
                $totalCost = $cost[$nearId] + $neighborCost;

                if ($totalCost < $cost[$neighborId]) {
                    $cost[$neighborId] = $totalCost;
                    $prev[$neighborId] = $nearId;
                }
            }

            // Found?
            if ($nearId == $to->getId()) {
                return $this->makePathList($prev, $nearId);
            }
        }

        // No path found
        return [];
    }

    private function closest($dist, $unvisited) {
        $minDist = 0x7FFFFFFF;
        $minId = 0;

        foreach ($unvisited as $node) {
            $currentDistance = $dist[$node->getId()];
            if ($currentDistance < $minDist) {
                $minDist = $currentDistance;
                $minId = $node->getId();
            }
        }

        return $minId;
    }

    private function makePathList($prev, int $u) {
        $path = [$u];

        while ($prev[$u] != null) {
            $path[] = $prev[$u];
            $u = $prev[$u];
        }

        $path = array_reverse($path);
        $nodes = [];
        foreach ($path as $nodeId) {
            $nodes[] = $this->_nodes[$nodeId];
        }

        return $nodes;
    }
}
