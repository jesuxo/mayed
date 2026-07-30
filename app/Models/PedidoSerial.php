<?php
// app/Models/Pedido.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'codprov', 'num', 'fk_usuario', 'fecha', 'fk_empresa', 'monto', 'total',
        'montoadicional', 'status', 'recepcion', 'unidades', 'observaciones',
        'porc1', 'porc2', 'porc3', 'fk_sucursal', 'sync'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'recepcion' => 'date',
        'monto' => 'decimal:2',
        'total' => 'decimal:2',
        'montoadicional' => 'decimal:2',
        'unidades' => 'decimal:2',
        'porc1' => 'decimal:2',
        'porc2' => 'decimal:2',
        'porc3' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(PedidoItem::class, 'fk_pedido');
    }

    public function proveedor()
    {
        return $this->belongsTo(Saprov::class, 'codprov', 'codprov');
    }

    public function costosAdicionales()
    {
        return $this->hasMany(CostoAdicional::class, 'fk_pedido');
    }

    public function compra()
    {
        return $this->hasOne(Sacomp::class, 'fk_pedido');
    }
}
