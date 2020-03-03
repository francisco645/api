<?php

namespace Tests\Unit;

use App\Graphs\Node;

class DijkstraNode implements Node
{
    private $_id;
    private $_neighbors;

    public function __construct(int $id)
    {
        $this->_id = $id;
        $this->_neighbors = [];
    }

    public function getId()
    {
        return $this->_id;
    }

    public function addNeighbor(Node $node, int $cost)
    {
        $this->_neighbors[$node->getId()] = $cost;
        $node->_neighbors[$this->getId()] = $cost;
    }
    
    public function getNeighbors()
    {
        return $this->_neighbors;
    }
}
