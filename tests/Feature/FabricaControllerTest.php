<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Fabrica;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class FabricaControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::factory()->create());
    }

    private function dadosFabrica(): array
    {
        return [
            'razao_social' => fake()->company(),                   
            'cnpj'         => fake()->numerify('##############'),  
            'email'        => fake()->unique()->safeEmail(),       
            'telefone'     => fake()->numerify('86#########'),     
            'ativo'        => fake()->boolean(),                   
        ];
    }


    #[Test]
    public function listar_fabricas(): void
    {
        $antes = Fabrica::count();                 
        Fabrica::factory()->count(3)->create();

        $this->getJson('/api/fabrica')
            ->assertOk()
            ->assertJsonCount($antes + 3);
    }

    #[Test]
    public function cadastra_uma_fabrica(): void
    {
        $dados = $this->dadosFabrica();
 
        $this->postJson('/api/fabrica', $dados)
            ->assertCreated()                                   // 201
            ->assertJsonFragment(['cnpj' => $dados['cnpj']]);
 
        $this->assertDatabaseHas('fabrica', [
            'razao_social' => $dados['razao_social'],
            'cnpj'         => $dados['cnpj'],
            'email'        => $dados['email'],
        ]);
    }
}
