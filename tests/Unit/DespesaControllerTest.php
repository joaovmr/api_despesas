<?php

namespace Tests\Feature;

use App\Models\Despesa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Support\Facades\Factory;

class DespesaControllerTest extends TestCase
{
    public function test_can_create_despesa()
    {
        $user = User::factory()->create();

        $token = $user->createToken('TestToken')->accessToken;

        Sanctum::actingAs($user);

        $despesaData = [
            'descricao' => 'Test Despesa',
            'data' => now()->format('Y-m-d'),
            'valor' => 100.0,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/despesas', $despesaData);

        $response->assertStatus(201);
    }

    public function test_can_update_despesa()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $despesa = Despesa::factory()->create(['user_id' => $user->id]);

        $updatedData = [
            'descricao' => 'Updated Despesa',
            'data' => now()->format('Y-m-d'),
            'valor' => 150.0,
        ];

        $response = $this->put("/api/despesas/{$despesa->id}", $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('despesas', $updatedData);
    }

    public function test_can_delete_despesa()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $despesa = Despesa::factory()->create(['user_id' => $user->id]);

        $response = $this->delete("/api/despesas/{$despesa->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('despesas', ['id' => $despesa->id]);
    }

    public function test_can_fetch_single_despesa()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $despesa = Despesa::factory()->create(['user_id' => $user->id]);

        $response = $this->get("/api/despesas/{$despesa->id}");

        $response->assertStatus(200);
        $response->assertJson(['data' => $despesa->toArray()]);
    }

    public function test_cannot_fetch_other_users_despesas()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Sanctum::actingAs($user1);

        $despesa = Despesa::factory()->create(['user_id' => $user2->id]);

        $response = $this->get("/api/despesas/{$despesa->id}");

        $response->assertStatus(403);
    }

    public function test_validation_fails_on_create()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $token = $user->createToken('TestToken')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/despesas', [
                    'descricao' => '',
                    'data' => '',
                    'valor' => '',
                ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['descricao', 'data', 'valor']);
    }

}