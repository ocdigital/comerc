<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;

class ClienteController extends Controller
{

    public function index()
    {
        $clientes = Cliente::all();
        return response()->json($clientes, 200);
    }

    public function store(StoreClienteRequest $request)
    {
        $cliente = Cliente::create($request->all());
        return response()->json($cliente, 201);

    }

    public function show(Cliente $cliente)
    {
        return response()->json($cliente, 200);
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        $cliente->update($request->all());
        return response()->json($cliente, 200);
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return response()->json(null, 204);
    }
}
