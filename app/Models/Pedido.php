<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'cliente_id'
    ];

    protected $dates = [
        'data_criacao',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'pedido_produto')
            ->withPivot('quantidade')
            ->withTimestamps();
    }

    public function valorTotal()
    {
        $total = 0;
        foreach ($this->produtos as $produto) {
            $total += $produto->preco * $produto->pivot->quantidade;
        }
        return $total;
    }
}
