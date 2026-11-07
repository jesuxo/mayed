<?php

namespace App\Exports;

use App\Models\Saprod;
use App\Models\Sasucursal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SaprodExport implements FromCollection, WithHeadings
{
    private $codalte;
    private $fksucursal;

    // Constructor acepta codalte y opcionalmente fksucursal
    public function __construct($codalte = null, $fksucursal = null)
    {
        $this->codalte    = $codalte;
        $this->fksucursal = $fksucursal;
    }

    public function headings(): array
    {
        $headings = [
            'codprod',
            'descrip',
            'precio1',
            'precio2',
            'precio3',
            'costo',
            'costopro',
            'referencia',
            'marca',
            'existencia',
            'codinst',
        ];

        // Si se filtró por sucursal, indicamos cuál es
        if ($this->fksucursal) {
            $sucursal = Sasucursal::find($this->fksucursal);
            $headings[] = 'sucursal';
        }

        return $headings;
    }

    public function collection()
    {
        $comercial  = session('comercialid');
        $codalte    = $this->codalte;
        $fksucursal = $this->fksucursal;

        $query = Saprod::selectRaw("
            codprod, descrip,
            costod as precio1, costod2 as precio2, costod3 as precio3,
            preciod as costo, preciodpro as costopro,
            refere as referencia, marca, existen, codinst
        ")
            ->where("comercial", $comercial)
            ->whereRaw("codinst in (select codinst from sainsta where codalte like ?)", [$codalte . '%']);

        // Si hay sucursal, filtramos solo productos con existencia > 0 ahí
        if ($fksucursal) {
            $query->whereExists(function ($q) use ($fksucursal) {
                $q->select(\DB::raw(1))
                    ->from('saexis')
                    ->whereColumn('saexis.codprod', 'saprod.codprod')
                    ->where('saexis.fk_sucursal', $fksucursal)
                    ->where('saexis.existen', '>', 0);
            });
        }

        $productos = $query->orderBy('marca')->get();

        // Si se filtró por sucursal, agregamos el nombre al final de cada fila
        if ($fksucursal) {
            $sucursal    = Sasucursal::find($fksucursal);
            $nombreSuc   = $sucursal ? str_replace('SARA', '', $sucursal->descrip) : '';

            $productos->each(function ($p) use ($nombreSuc) {
                $p->sucursal = $nombreSuc;
            });
        }

        return $productos;
    }
}
