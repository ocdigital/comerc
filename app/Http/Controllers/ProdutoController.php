<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProdutoResource;
use App\Http\Requests\StoreProdutoRequest;
use App\Http\Requests\UpdateProdutoRequest;


class ProdutoController extends Controller
{
    /**
     *  Exibe todos os produtos com paginação.
     *  Utilizando ProdutoResource para formatar a resposta.
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $produtos = Produto::paginate(10);
        return ProdutoResource::collection($produtos);
    }

    /**
     *  Cria um novo produto.
     *  Utilizando ProdutoRequest para validar os dados recebidos.     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(StoreProdutoRequest $request)
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

    /**
     *  Exibe um produto específico.
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */

    public function show(Produto $produto)
    {
        return response()->json($produto, 200);
    }

    /**
     *  Atualiza um produto específico.
     *  Utilizando ProdutoRequest para validar os dados recebidos.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */

    public function update(UpdateProdutoRequest $request, Produto $produto)
    {
        try {
            $dadosProduto = $request->except('foto');

            if ($request->hasFile('foto')) {
                if ($produto->foto) {
                    Storage::disk('public')->delete($produto->foto);
                }

                $novaFoto = $request->file('foto');
                $nomeNovaFoto = time() . '_' . $novaFoto->getClientOriginalName();
                Storage::disk('public')->put($nomeNovaFoto, file_get_contents($novaFoto));
                $dadosProduto['foto'] = $nomeNovaFoto;
            }


            $produto->update($dadosProduto);

            return response()->json($produto, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Não é possível atualizar o produto'], 400);
        }
    }

    /**
     *  Exclui um produto específico.
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */

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
