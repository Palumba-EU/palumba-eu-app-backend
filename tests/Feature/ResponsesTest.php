<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Language;
use App\Models\Statement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class ResponsesTest extends TestCase
{
    use RefreshDatabase;

    public function test_responses_can_be_updated_within_editable_time_only(): void
    {
        Config::set('responses.editableTime', 1);

        $statements = Statement::factory()->count(3)->create();

        $response = $this->postJson('/api/responses', [
            'age' => null,
            'country_id' => Country::factory()->create()->id,
            'language_id' => Language::factory()->create()->id,
            'gender' => null,
            'answers' => [
                ['statement_id' => $statements[0]->id, 'answer' => -1],
                ['statement_id' => $statements[1]->id, 'answer' => 1],
            ],
        ]);

        $response->assertStatus(201);

        $uuid = $response->json('id');
        $this->assertDatabaseCount('response_statement', 2);
        $this->assertDatabaseHas('response_statement', [
            'response_uuid' => $uuid,
            'statement_id' => $statements[0]->id,
            'answer' => -2,
        ]);

        // Update existing answer and add new one
        $updateResponse1 = $this->postJson('/api/responses/'.$uuid.'/answers', [
            'answers' => [
                ['statement_id' => $statements[0]->id, 'answer' => 0.5],
                ['statement_id' => $statements[2]->id, 'answer' => 0],
            ],
        ]);

        $updateResponse1->assertStatus(200);
        $this->assertDatabaseCount('response_statement', 3);
        $this->assertDatabaseHas('response_statement', [
            'response_uuid' => $response->json('id'),
            'statement_id' => $statements[0]->id,
            'answer' => 1,
        ]);

        // Travel 2 hours into the future (editableTime is set to 1 hour)
        $this->travel(2)->hours();

        $updateResponse2 = $this->postJson('/api/responses/'.$uuid.'/answers', [
            'answers' => [
                ['statement_id' => $statements[0]->id, 'answer' => -1],
            ],
        ]);

        $updateResponse2->assertStatus(403);
        $this->assertDatabaseCount('response_statement', 3);
        $this->assertDatabaseHas('response_statement', [
            'response_uuid' => $response->json('id'),
            'statement_id' => $statements[0]->id,
            'answer' => 1,
        ]);
    }

    public function test_level_of_education()
    {
        // level_of_education
        $response = $this->postJson('/api/responses', [
            'age' => null,
            'gender' => null,
            'country_id' => Country::factory()->create()->id,
            'language_id' => Language::factory()->create()->id,
            'level_of_education' => 0,
            'answers' => [],
        ]);
        $response->assertStatus(201);

        $response = $this->postJson('/api/responses', [
            'age' => null,
            'gender' => null,
            'country_id' => Country::factory()->create()->id,
            'language_id' => Language::factory()->create()->id,
            'level_of_education' => -1,
            'answers' => [],
        ]);
        $response->assertStatus(422);

        $response = $this->postJson('/api/responses', [
            'age' => null,
            'gender' => null,
            'country_id' => Country::factory()->create()->id,
            'language_id' => Language::factory()->create()->id,
            'level_of_education' => 9,
            'answers' => [],
        ]);
        $response->assertStatus(422);

        $response = $this->postJson('/api/responses', [
            'age' => null,
            'gender' => null,
            'country_id' => Country::factory()->create()->id,
            'language_id' => Language::factory()->create()->id,
            'level_of_education' => null,
            'answers' => [],
        ]);
        $response->assertStatus(201);
    }
}
