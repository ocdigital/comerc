<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NotificarNovoPedido;
use Carbon\Factory;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Facades\Notification;
use Database\Factories\ClienteFactory;
use Database\Factories\ProdutoFactory;
use Database\Factories\PedidoFactory;

class PedidoControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Testa o metodo index.
     *
     * @return void
     */

    public function test_order_index()
    {
        $response = $this->call('GET', '/api/pedidos');
        $this->assertEquals(200, $response->status());
    }

    /**
     * Testa se está utilizando softdeletes.
     *
     * @return void
     */

    public function test_uses_soft_deletes()
    {
        $order = PedidoFactory::new()->create();

        $this->assertDatabaseHas('pedidos', ['id' => $order->id]);

        $order->delete();

        $this->assertSoftDeleted('pedidos', ['id' => $order->id]);
    }

    /**
     * Testa o metodo store.
     *
     * @return void
     */

    public function test_order_store()
    {
        $client = ClienteFactory::new()->create();
        $product1 = ProdutoFactory::new()->create();
        $product2 = ProdutoFactory::new()->create();

        $orderData = [
            'codigo_cliente' => $client->id,
            'produtos' => [
                ['produto_id' => $product1->id, 'quantidade' => 2],
                ['produto_id' => $product2->id, 'quantidade' => 3],
            ],
        ];

        $orderResponse = $this->json('POST', '/api/pedidos', $orderData);

        $this->assertEquals(201, $orderResponse->status());
        $this->assertEquals(2, count(json_decode($orderResponse->getContent())->produtos));
    }

    /**
     * Testa o envio de notificação.
     *
     * @return void
     */

    public function test_send_notification()
    {
        Notification::fake();

        $cliente = ClienteFactory::new()->create();
        $order = Pedido::factory()->create();

        $notification = new NotificarNovoPedido($order);

        $cliente->notify($notification);

        Notification::assertSentTo(
            $cliente,
            NotificarNovoPedido::class,
            function ($notification) use ($order) {
                return $notification->getPedido()->id === $order->id;
            }
        );
    }

    /**
     * Testa o método show.
     *
     * @return void
     */

    public function test_order_show()
    {
        $client = ClienteFactory::new()->create();
        $product1 = ProdutoFactory::new()->create();
        $product2 = ProdutoFactory::new()->create();

        $orderData = [
            'codigo_cliente' => $client->id,
            'produtos' => [
                ['produto_id' => $product1->id, 'quantidade' => 2],
                ['produto_id' => $product2->id, 'quantidade' => 3],
            ],
        ];

        $orderResponse = $this->json('POST', '/api/pedidos', $orderData);

        $this->assertEquals(201, $orderResponse->status());

        $orderId = json_decode($orderResponse->getContent())->pedido->id;

        $response = $this->call('GET', '/api/pedidos/' . $orderId);

        $this->assertEquals(200, $response->status());
        $this->assertEquals(2, count(json_decode($response->getContent())->data->produtos));
    }

    /**
     * Testa o método update.
     *
     * @return void
     */

    public function test_order_update()
    {
        $client = ClienteFactory::new()->create();
        $product1 = ProdutoFactory::new()->create();
        $product2 = ProdutoFactory::new()->create();

        $orderData = [
            'codigo_cliente' => $client->id,
            'produtos' => [
                ['produto_id' => $product1->id, 'quantidade' => 2],
                ['produto_id' => $product2->id, 'quantidade' => 3],
            ],
        ];

        $orderResponse = $this->json('POST', '/api/pedidos', $orderData);

        $this->assertEquals(201, $orderResponse->status());

        $orderId = json_decode($orderResponse->getContent())->pedido->id;

        $updateResponse = $this->json('PUT', '/api/pedidos/' . $orderId, [
            'codigo_cliente' => $client->id,
            'produtos' => [
                ['produto_id' => $product1->id, 'quantidade' => 3],
                ['produto_id' => $product2->id, 'quantidade' => 4],
            ],
        ]);

        $this->assertEquals(200, $updateResponse->status());
        $this->assertEquals(2, count(json_decode($updateResponse->getContent())->produtos));
    }

    /**
     * Testa o método destroy.
     *
     * @return void
     */

    public function test_order_delete()
    {
        $client = ClienteFactory::new()->create();
        $product1 = ProdutoFactory::new()->create();
        $product2 = ProdutoFactory::new()->create();

        $orderData = [
            'codigo_cliente' => $client->id,
            'produtos' => [
                ['produto_id' => $product1->id, 'quantidade' => 2],
                ['produto_id' => $product2->id, 'quantidade' => 3],
            ],
        ];

        $orderResponse = $this->json('POST', '/api/pedidos', $orderData);

        $this->assertEquals(201, $orderResponse->status());

        $orderId = json_decode($orderResponse->getContent())->pedido->id;

        $response = $this->call('DELETE', '/api/pedidos/' . $orderId);

        $this->assertEquals(200, $response->status());
    }
}
