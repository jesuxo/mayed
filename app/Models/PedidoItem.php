<?php
// app/Models/PedidoSerial.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoSerial extends Model
{
    protected $table = 'pedido_seriales';

    protected $fillable = [
        'fk_itemid', 'serial', 'fk_sucursal'
    ];

    public function item()
    {
        return $this->belongsTo(PedidoItem::class, 'fk_itemid');
    }
}
