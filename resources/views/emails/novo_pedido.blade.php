<!DOCTYPE html>
<html>
<head>
    <title>Novo Pedido</title>
</head>
<body>
    <h1>Novo Pedido</h1>
    <h3>Detalhes do Pedido:</h3>
    <p>Cliente: {{ $pedido->cliente->nome }}</p>
    <hr>
    <h4>Produtos:</h4>
    <ul>
        @foreach ($pedido->produtos as $produto)
            <li>Nome: {{ $produto->nome }}, Quantidade: {{ $produto->pivot->quantidade }}, Preço: {{ $produto->preco }}</li>
        @endforeach
    </ul>
    <p>Valor Total: {{ $pedido->valor_total }}</p>

</body>
</html>
