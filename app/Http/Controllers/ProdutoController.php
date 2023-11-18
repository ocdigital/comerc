<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use App\Http\Requests\ProdutoRequest;
use Illuminate\Support\Facades\Storage;


class ProdutoController extends Controller
{

    public function index()
    {


        $produtos = Produto::all();
        return response()->json($produtos, 200);
    }

    public function store(ProdutoRequest $request)
    {
        try {
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $nomeFoto = time() . '_' . $foto->getClientOriginalName();

                Storage::disk('public')->put($nomeFoto, file_get_contents($foto));

                $dadosProduto = $request->all();
                $dadosProduto['foto'] = $nomeFoto;

                $produto = Produto::create($dadosProduto);
                return response()->json($produto, 201);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Não é possível criar o produto'], 400);
        }
    }


    public function show(Produto $produto)
    {
        return response()->json($produto, 200);
    }

    //TODO: Implementar o update
    // public function update(Request $request, Produto $produto)
    // {
    //     dd($request->all());
    //   try {
    //         if ($request->hasFile('foto')) {
    //         $foto = $request->file('foto');
    //         $nomeFoto = time() . '_' . $foto->getClientOriginalName();

    //         Storage::disk('public')->put($nomeFoto, file_get_contents($foto));

    //         $dadosProduto = $request->all();
    //         $dadosProduto['foto'] = $nomeFoto;

    //         $produto = Produto::create($dadosProduto);
    //         return response()->json($produto, 201);
    //     }
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Não é possível atualizar o produto'], 400);
    //     }
    // }


    public function destroy(Produto $produto)
    {
        try {
            $produto->delete();
            return response()->json(['message' => 'Produto excluído com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Não é possível excluir o produto'], 400);
        }
    }
}
