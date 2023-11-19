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
        'codigo_cliente'
    ];

    protected $dates = [
        'data_criacao',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'codigo_cliente');
    }


    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'pedido_produto') // Alteração no nome da tabela pivot
            ->withPivot('quantidade')
            ->withTimestamps();
    }
}
