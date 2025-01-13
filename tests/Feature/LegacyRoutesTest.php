<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Election;
use App\Models\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegacyRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_results_routes(): void
    {
        $election = Election::default();

        $v0_response = $this->get('/api/results');
        $v1_response = $this->get('/api/en/results');
        $v2_response = $this->get("/api/en/$election->id/results");

        $v0_response->assertStatus(200);
        $v1_response->assertStatus(200);
        $v2_response->assertStatus(200);

        $v0_response->assertContent($v2_response->content());
        $v1_response->assertContent($v2_response->content());
    }

    public function test_responses_endpoint()
    {
        $result = $this->post('/api/responses', [
            'age' => null,
            'country_id' => Country::factory()->create()->id,
            'language_id' => Language::factory()->create()->id,
            'gender' => null,
            'answers' => [],
        ]);

        $result->assertStatus(201);

        $election = Election::default();
        $this->assertDatabaseCount('responses', 1);
        $this->assertDatabaseHas('responses', [
            'election_id' => $election->id,
        ]);
    }
}
