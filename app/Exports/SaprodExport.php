<?php

namespace App\Exports;

use App\Models\Saprod;
use App\Models\Sasucursal;
use App\Models\Saexis;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SaprodExport implements FromCollection, WithHeadings
{
    private $codalte;
    private $fksucursal;

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

        if ($this->fksucursal) {
            $headings[] = 'sucursal';
        }

        return $headings;
    }

    public function collection()
    {
        $comercial  = session('comercialid');
        $codalte    = $this->codalte;
        $fksucursal = $this->fksucursal;

        // Si hay sucursal, traemos la existencia real de saexis para esa sucursal
        // y la usamos como columna "existencia". Si no, usamos saprod.existen.
        if ($fksucursal) {
            $query = Saprod::selectRaw("
                saprod.codprod,
                saprod.descrip,
                saprod.costod as precio1,
                saprod.costod2 as precio2,
                saprod.costod3 as precio3,
                saprod.preciod as costo,
                saprod.preciodpro as costopro,
                saprod.refere as referencia,
                saprod.marca,
                COALESCE((
                    SELECT SUM(saexis.existen)
                    FROM saexis
                    WHERE saexis.codprod = saprod.codprod
                      AND saexis.fk_sucursal = ?
                ), 0) as existen,
                saprod.codinst
            ", [$fksucursal])
                ->where('saprod.comercial', $comercial)
                ->whereRaw("saprod.codinst in (select codinst from sainsta where codalte like ?)", [$codalte . '%'])
                ->whereExists(function ($q) use ($fksucursal) {
                    $q->select(\DB::raw(1))
                        ->from('saexis')
                        ->whereColumn('saexis.codprod', 'saprod.codprod')
                        ->where('saexis.fk_sucursal', $fksucursal)
                        ->where('saexis.existen', '>', 0);
                });
        } else {
            $query = Saprod::selectRaw("
                codprod, descrip,
                costod as precio1, costod2 as precio2, costod3 as precio3,
                preciod as costo, preciodpro as costopro,
                refere as referencia, marca, existen, codinst
            ")
                ->where("comercial", $comercial)
                ->whereRaw("codinst in (select codinst from sainsta where codalte like ?)", [$codalte . '%']);
        }

        $productos = $query->orderBy('marca')->get();

        if ($fksucursal) {
            $sucursal  = Sasucursal::find($fksucursal);
            $nombreSuc = $sucursal ? str_replace('SARA', '', $sucursal->descrip) : '';

            $productos->each(function ($p) use ($nombreSuc) {
                $p->sucursal = $nombreSuc;
            });
        }

        return $productos;
    }
}
