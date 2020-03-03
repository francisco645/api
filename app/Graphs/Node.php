<?php

namespace App\Graphs;

interface Node
{
    public function getId();
    public function addNeighbor(Node $node, int $cost);
    public function getNeighbors();
}
