<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Database\Factories\ProdutoFactory;


class ProdutoControllerTest extends TestCase
{
    use DatabaseTransactions;


    /**
     * Testa o metodo index.
     *
     * @return void
     */

    public function test_product_index()
    {
        $response = $this->call('GET', '/api/produtos');
        $this->assertEquals(200, $response->status());
    }

    /**
     * Testa se está utilizando softdeletes.
     *
     * @return void
     */

    public function test_uses_soft_deletes()
    {
        $product = ProdutoFactory::new()->create();

        $this->assertDatabaseHas('produtos', ['id' => $product->id]);

        $product->delete();

        $this->assertSoftDeleted('produtos', ['id' => $product->id]);
    }

    /**
     * Testa o metodo store.
     *
     * @return void
     */

    public function test_product_store()
    {
        $photo = UploadedFile::fake()->image('product_photo.jpg');

        $response = $this->json('POST', '/api/produtos', [
            'nome' => 'Produto Teste',
            'preco' => '10.00',
            'foto' => $photo,
        ]);

        $this->assertEquals(201, $response->status());
    }

    /**
     * Testa cadastro de produto sem foto.
     *
     * @return void
     */

    public function test_store_without_photo()
    {
        $response = $this->json('POST', '/api/produtos', [
            'nome' => 'Produto Teste',
            'preco' => '10.00'

        ]);

        $this->assertEquals(422, $response->status());
    }

    /**
     * Testa dados obrigatorios.
     *
     * @return void
     */

    public function test_store_with_required_fields()
    {
        $response = $this->json('POST', '/api/produtos', [
            'nome' => '',
            'preco' => '',
            'foto' => '',
        ]);

        $this->assertEquals(422, $response->status());
    }

    /**
     * Testa o método show.
     *
     * @return void
     */

    public function test_product_show()
    {
        $photo = UploadedFile::fake()->image('product_photo.jpg');

        $createResponse = $this->json('POST', '/api/produtos', [
            'nome' => 'Produto Teste',
            'preco' => '10.00',
            'foto' => $photo,
        ]);

        $this->assertEquals(201, $createResponse->status());

        $productId = json_decode($createResponse->getContent())->id;
        $showResponse = $this->call('GET', '/api/produtos/' . $productId);

        $this->assertEquals(200, $showResponse->status());

        $content = json_decode($showResponse->getContent())->data;

        $this->assertEquals('Produto Teste', $content->nome);
        $this->assertEquals('10.00', $content->preco);
        $this->assertStringContainsString('product_photo', $content->foto);
    }

    /**
     * Testa o método update.
     *
     * @return void
     */

    public function test_product_update()
    {
        $photo = UploadedFile::fake()->image('product_photo.jpg');

        $createResponse = $this->json('POST', '/api/produtos', [
            'nome' => 'Produto Teste',
            'preco' => '10.00',
            'foto' => $photo,
        ]);

        $this->assertEquals(201, $createResponse->status());

        $productId = json_decode($createResponse->getContent())->id;
        $updateResponse = $this->json('PUT', '/api/produtos/' . $productId, [
            'nome' => 'Produto Teste Atualizado',
            'preco' => '20.00',
        ]);

        $this->assertEquals(200, $updateResponse->status());

        $content = json_decode($updateResponse->getContent());

        $this->assertEquals('Produto Teste Atualizado', $content->nome);
        $this->assertEquals('20.00', $content->preco);
    }

    /**
     * Testa o método destroy.
     *
     * @return void
     */

    public function test_product_delete()
    {
        $photo = UploadedFile::fake()->image('product_photo.jpg');

        $createResponse = $this->json('POST', '/api/produtos', [
            'nome' => 'Produto Teste',
            'preco' => '10.00',
            'foto' => $photo,
        ]);

        $this->assertEquals(201, $createResponse->status());

        $productId = json_decode($createResponse->getContent())->id;
        $deleteResponse = $this->call('DELETE', '/api/produtos/' . $productId);

        $this->assertEquals(204, $deleteResponse->status());
    }
}
