<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Http\Resources\ClienteResource;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Notification;

class ClienteController extends Controller
{
    /**
     *  Exibe todos os clientes com paginação.
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $clientes = Cliente::with('pedidos')->paginate(10);
        return ClienteResource::collection($clientes);
    }

    /**
     *  Cria um novo cliente.
     *  Utilizando StoreClienteRequest para validar os dados recebidos.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(StoreClienteRequest $request)
    {
        try {
            $cliente = Cliente::create($request->all());
            return response()->json($cliente, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Não é possível criar o cliente',$e], 400);
        }
    }

    /**
     *  Exibe um cliente específico.
     * Utilizando ClienteResource para formatar a resposta.
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */

    public function show(Cliente $cliente)
    {
        $cliente = Cliente::with('pedidos')->find($cliente->id);
        return new ClienteResource($cliente);
    }

    /**
     *  Atualiza um cliente específico.
     *  Utilizando UpdateClienteRequest para validar os dados recebidos.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */

    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        try {
            $cliente->update($request->all());
            return response()->json($cliente, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Não é possível atualizar o cliente'], 400);
        }
    }

    /**
     *  Exclui um cliente específico.
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */

    public function destroy(Cliente $cliente)
    {
        try {
            $cliente->delete();
            return response()->json(['message' => 'Cliente excluído com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Não é possível excluir o cliente'], 400);
        }
    }
}
