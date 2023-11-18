<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Http\Resources\ClienteResource;
use GuzzleHttp\Client;

class ClienteController extends Controller
{

    public function index()
    {
        $clientes = Cliente::with('pedidos')->get();
        return ClienteResource::collection($clientes);
    }

    public function store(StoreClienteRequest $request)
    {
        try {
            $cliente = Cliente::create($request->all());
            return response()->json($cliente, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Não é possível criar o cliente'], 400);
        }
    }

    public function show(Cliente $cliente)
    {
        $cliente = Cliente::with('pedidos')->find($cliente->id);

        return new ClienteResource($cliente);
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        try {
            $cliente->update($request->all());
            return response()->json($cliente, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Não é possível atualizar o cliente'], 400);
        }
    }

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
