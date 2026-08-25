
@extends('layouts.master')
@section('title')
    REPORTE
@endsection
@section('css')

    <style>
    .botoncal{
        background: transparent;
        border: none;
        color: white;
    }
    .botoncal:hover{
         font-size: 13px;
    }
    </style>
@endsection
@section('content')

    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Reporte de Facturas  de CASHEA</h4>
            </div>

            <div class="card-body">
                <!-- Filtros -->
                <form method="GET" action="{{ route('reporte.facturas.pendientes') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control"
                                   value="{{ $fechaInicio }}">
                        </div>
                        <div class="col-md-3">
                            <label>Fecha Fin</label>
                            <input type="date" name="fecha_fin" class="form-control"
                                   value="{{ $fechaFin }}">
                        </div>
                        <div class="col-md-3">
                            <label>Sucursal</label>
                            <select name="fk_sucursal" class="form-control">
                                <option value="">Todas las sucursales</option>
                                @foreach($sucursales as $suc)
                                    <option value="{{ $suc->id }}"
                                        {{ $sucursalId == $suc->id ? 'selected' : '' }}>
                                        {{ $suc->descrip }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                        </div>
                    </div>
                </form>

                <!-- Totales -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 style="color: white">Total Facturado</h5>
                                <h4 style="color: white">$ {{ number_format($totales['monto_total'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 style="color: white">Total Abonado</h5>
                                <h4 style="color: white">$ {{ number_format($totales['abonado_total'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h5 style="color: white">Saldo Pendiente</h5>
                                <h4 style="color: white">$ {{ number_format($totales['restante_total'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de resultados -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>N° Doc</th>
                                <th>Cliente</th>
                                <th>Cédula/RIF</th>
                                <th>Monto   ($)</th>
                                <th>Abonado ($)</th>
                                <th>Saldo   ($)</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($facturas as $factura)
                            @php
                            $numeror = (isset($factura->numeror) and $factura->numeror !='')? $factura->numeror  : '';
                            @endphp
                            <tr @if($numeror !='') bgcolor="#ffd4d4" @endif>
                                <td>{{ Carbon\Carbon::parse($factura->fechat)->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('facturaver', [
                                        'tipofac' => $factura->tipofac,
                                        'numerod' => $factura->numerod,
                                        'fksucu' => $factura->fk_sucursal
                                    ]) }}" target="_blank">
                                        {{ $factura->tipofac }}-{{ $factura->numerod }}

                                        @if($numeror !='' and $factura->tipofac == 'A') FACT DEV @endif
                                        @if($numeror !='' and $factura->tipofac == 'B') DEVOLUCION @endif

                                    </a>
                                </td>
                                <td>{{ $factura->cliente }}</td>
                                <td>{{ $factura->cedula }}</td>
                                <td class="text-right">$ {{ number_format($factura->monto_factura, 2) }}</td>
                                <td class="text-right">$ {{ number_format($factura->abonado, 2) }}</td>
                                <td class="text-right text-danger font-weight-bold">
                                    $ {{ number_format($factura->restante, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    No se encontraron facturas con saldo pendiente
                                    para el período seleccionado.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



@endsection
@section('scripts')

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection
