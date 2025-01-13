<?php

namespace Tests\Feature;

use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegacyRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_results_routes(): void
    {
        /** @var Election $election */
        $election = Election::query()->orderBy('id')->firstOrFail();

        $v0_response = $this->get('/api/results');
        $v1_response = $this->get('/api/en/results');
        $v2_response = $this->get("/api/en/$election->id/results");

        $v0_response->assertStatus(200);
        $v1_response->assertStatus(200);
        $v2_response->assertStatus(200);

        $v0_response->assertContent($v2_response->content());
        $v1_response->assertContent($v2_response->content());
    }
}
