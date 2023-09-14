<?php

namespace Tests\Feature;

use App\Models\Despesa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DespesaModelTest extends TestCase
{
    use RefreshDatabase;
    public function test_can_create_despesa()
    {
        $despesaData = [
            'descricao' => 'Test Despesa',
            'data' => now(),
            'valor' => 100.0,
        ];

        $despesa = Despesa::create($despesaData);
        $this->assertInstanceOf(Despesa::class, $despesa);
        $this->assertEquals($despesaData['descricao'], $despesa->descricao);
        $this->assertEquals($despesaData['valor'], $despesa->valor);
    }

    public function test_can_update_despesa()
    {
        $despesaData = [
            'descricao' => 'Test Despesa',
            'data' => now(),
            'valor' => 100.0,
        ];

        $despesa = Despesa::create($despesaData);

        $updatedData = [
            'descricao' => 'Updated Despesa',
            'data' => now()->subDay(),
            'valor' => 200.0,
        ];

        $despesa->update($updatedData);

        $this->assertEquals($updatedData['descricao'], $despesa->descricao);
        $this->assertEquals($updatedData['valor'], $despesa->valor);
    }
}
