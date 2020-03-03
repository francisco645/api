<?php

namespace Tests\Feature;

use App\Models\Line;
use App\Models\Point;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ApiPointsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIfCanCreatePointsAndFindNearPath()
    {
        $point1 = $this->post('/api/points', [ 'name' => 'Pau dos Ferros' ])->assertStatus(201)->decodeResponseJson();
        $point2 = $this->post('/api/points', [ 'name' => 'Rafael Fernandes' ])->assertStatus(201)->decodeResponseJson();
        $point3 = $this->post('/api/points', [ 'name' => 'Encanto' ])->assertStatus(201)->decodeResponseJson();
        $point4 = $this->post('/api/points', [ 'name' => 'São Miguel' ])->assertStatus(201)->decodeResponseJson();

        // Pau dos Ferros -> Rafael Fernandes
        $this->post("/api/points/{$point1['id']}/add-neighbor", [
            'neighbor_id' => $point2['id'], 'distance' => 20
        ])->assertStatus(204); 

        // Pau dos Ferros -> Encanto
        $this->post("/api/points/{$point1['id']}/add-neighbor", [
            'neighbor_id' => $point3['id'], 'distance' => 5
        ])->assertStatus(204); 

        // Rafael Fernandes -> Encanto
        $this->post("/api/points/{$point2['id']}/add-neighbor", [
            'neighbor_id' => $point3['id'], 'distance' => 200
        ])->assertStatus(204); 

        //  Encanto -> São Miguel
        $res = $this->post("/api/points/{$point3['id']}/add-neighbor", [
            'neighbor_id' => $point4['id'], 'distance' => 60
        ])->assertStatus(204); 

        $nearPath = $this->get("/api/path/near/{$point2['id']}/{$point4['id']}")->assertStatus(200)->decodeResponseJson();

        $this->assertCount(4, $nearPath);
        $this->assertEquals('Rafael Fernandes', $nearPath[0]['name']);
        $this->assertEquals('Pau dos Ferros', $nearPath[1]['name']);
        $this->assertEquals('Encanto', $nearPath[2]['name']);
        $this->assertEquals('São Miguel', $nearPath[3]['name']);
    }
}
