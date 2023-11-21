<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Database\Factories\ClienteFactory;

class ClienteControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Testa o metodo index.
     *
     * @return void
     */

    public function test_client_index()
    {
        $response = $this->call('GET', '/api/clientes');
        $this->assertEquals(200, $response->status());
    }

    /**
     * Testa se está utilizando softdeletes.
     *
     * @return void
     */

    public function test_uses_soft_deletes()
    {
        $client = ClienteFactory::new()->create();

        $this->assertDatabaseHas('clientes', ['id' => $client->id]);

        $client->delete();

        $this->assertSoftDeleted('clientes', ['id' => $client->id]);
    }

    /**
     * Testa o metodo store.
     *
     * @return void
     */

    public function test_client_store()
    {
        $reponse = $this->call('POST', '/api/clientes', [
            'nome' => 'Cliente Teste',
            'email' => 'teste@teste.com',
            'telefone' => '999999999',
            'data_nascimento' => '1990-01-01',
            'endereco' => 'Rua Teste, 123',
            'complemento' => 'Casa',
            'bairro' => 'Bairro Teste',
            'cep' => '99999999',
        ]);
        $this->assertEquals(201, $reponse->status());
    }

    /**
     * Testa dados obrigatorios.
     *
     * @return void
     */

    public function test_store_with_required_fields()
    {
        $response = $this->call('POST', '/api/clientes', [
            'nome' => '',
            'email' => '',
            'telefone' => '',
            'data_nascimento' => '',
            'endereco' => '',
            'bairro' => '',
            'cep' => '',

        ]);

        $this->assertEquals(422, $response->status());
    }

    /**
     * Testa a validação de email duplicado.
     *
     * @return void
     */

    public function test_store_with_existing_email()
    {
        $response = $this->call('POST', '/api/clientes', [
            'nome' => 'Cliente Teste',
            'email' => 'teste@teste.com',
            'telefone' => '999999999',
            'data_nascimento' => '1990-01-01',
            'endereco' => 'Rua Teste, 123',
            'complemento' => 'Casa',
            'bairro' => 'Bairro Teste',
            'cep' => '99999999',
        ]);

        $this->assertEquals(201, $response->status());

        $responseDuplicado = $this->call('POST', '/api/clientes', [
            'nome' => 'Cliente Teste 2',
            'email' => 'teste@teste.com',
            'telefone' => '999999999',
            'data_nascimento' => '1990-01-01',
            'endereco' => 'Rua Teste, 456',
            'complemento' => 'Apartamento',
            'bairro' => 'Bairro Teste',
            'cep' => '99999999',
        ]);

        $this->assertEquals(422, $responseDuplicado->status());
    }

    /**
     * Testa o metodo show.
     *
     * @return void
     */

    public function test_client_show()
    {
        $createResponse = $this->call('POST', '/api/clientes', [
            'nome' => 'Cliente Teste',
            'email' => 'teste@teste.com',
            'telefone' => '999999999',
            'data_nascimento' => '1990-01-01',
            'endereco' => 'Rua Teste, 123',
            'complemento' => 'Casa',
            'bairro' => 'Bairro Teste',
            'cep' => '99999999',
        ]);

        $clienteId = json_decode($createResponse->getContent())->id;
        $showResponse = $this->call('GET', '/api/clientes/' . $clienteId);

        $this->assertEquals(200, $showResponse->status());

        $content = json_decode($showResponse->getContent())->data;

        $this->assertEquals('teste@teste.com', $content->email);
        $this->assertEquals('999999999', $content->telefone);
        $this->assertEquals('1990-01-01', $content->data_nascimento);
        $this->assertEquals('Rua Teste, 123', $content->endereco);
        $this->assertEquals('Casa', $content->complemento);
        $this->assertEquals('Bairro Teste', $content->bairro);
        $this->assertEquals('99999999', $content->cep);
    }

    /**
     * Testa o metodo update.
     *
     * @return void
     */

    public function test_client_update()
    {
        $createResponse = $this->call('POST', '/api/clientes', [
            'nome' => 'Cliente Teste',
            'email' => 'teste@teste.com',
            'telefone' => '999999999',
            'data_nascimento' => '1990-01-01',
            'endereco' => 'Rua Teste, 123',
            'complemento' => 'Casa',
            'bairro' => 'Bairro Teste',
            'cep' => '99999999',
        ]);

        $this->assertEquals(201, $createResponse->status());

        $clienteId = json_decode($createResponse->getContent())->id;

        $updateResponse = $this->call('PUT', '/api/clientes/' . $clienteId, [
            'nome' => 'Novo Nome do Cliente',
            'email' => 'novoteste@teste.com',
            'telefone' => '888888888',
            'data_nascimento' => '1995-01-01',
            'endereco' => 'Nova Rua, 456',
            'complemento' => 'Apartamento',
            'bairro' => 'Novo Bairro',
            'cep' => '88888888',
        ]);

        $this->assertEquals(200, $updateResponse->status());

        $updatedCliente = json_decode($updateResponse->getContent());

        $this->assertEquals('Novo Nome do Cliente', $updatedCliente->nome);
        $this->assertEquals('novoteste@teste.com', $updatedCliente->email);
        $this->assertEquals('888888888', $updatedCliente->telefone);
        $this->assertEquals('1995-01-01', $updatedCliente->data_nascimento);
        $this->assertEquals('Nova Rua, 456', $updatedCliente->endereco);
        $this->assertEquals('Apartamento', $updatedCliente->complemento);
        $this->assertEquals('Novo Bairro', $updatedCliente->bairro);
        $this->assertEquals('88888888', $updatedCliente->cep);
    }

    /**
     * Testa o metodo destroy.
     *
     * @return void
     */

    public function test_client_delete()
    {
        $createResponse = $this->call('POST', '/api/clientes', [
            'nome' => 'Cliente Teste',
            'email' => 'teste@teste.com',
            'telefone' => '999999999',
            'data_nascimento' => '1990-01-01',
            'endereco' => 'Rua Teste, 123',
            'complemento' => 'Casa',
            'bairro' => 'Bairro Teste',
            'cep' => '99999999',
        ]);

        $this->assertEquals(201, $createResponse->status());

        $clienteId = json_decode($createResponse->getContent())->id;

        $deleteResponse = $this->call('DELETE', '/api/clientes/' . $clienteId);

        $this->assertEquals(204, $deleteResponse->status());

        $showAfterDeleteResponse = $this->call('GET', '/api/clientes/' . $clienteId);

        $this->assertEquals(404, $showAfterDeleteResponse->status());
    }
}
