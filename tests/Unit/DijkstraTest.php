<?php

namespace Tests\Unit;

use App\Graphs\Dijkstra;
use App\Graphs\Node;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DijkstraTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        // Mock
        $nodes = array(
            new DijkstraNode(1),
            new DijkstraNode(2),
            new DijkstraNode(8),
            new DijkstraNode(7),
            new DijkstraNode(9),
            new DijkstraNode(12),
        );

        $nodes[0]->addNeighbor($nodes[1], 6);  // 1 -> 2
        $nodes[1]->addNeighbor($nodes[2], 8);  // 2 -> 8
        $nodes[2]->addNeighbor($nodes[3], 15); // 8 -> 7
        $nodes[2]->addNeighbor($nodes[4], 11); // 8 -> 9
        $nodes[2]->addNeighbor($nodes[5], 11); // 8 -> 12
        $nodes[4]->addNeighbor($nodes[5], 17); // 9 -> 12

        $pathAlg = new Dijkstra($nodes);
        
        $nearPath = $pathAlg->nearPath($nodes[5], $nodes[0]);

        $this->assertCount(4, $nearPath);
        $this->assertEquals(12, $nearPath[0]->getId());
        $this->assertEquals(8, $nearPath[1]->getId());
        $this->assertEquals(2, $nearPath[2]->getId());
        $this->assertEquals(1, $nearPath[3]->getId());
    }
}
