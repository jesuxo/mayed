{{-- resources/views/proveedores/productos-panel.blade.php --}}
@extends('layouts.master')
@section('title')
    Análisis de Compras - {{ $proveedor->descrip }}
@endsection
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.css">
    <style>
        .panel-header {
            background: #0072c5;
            color: white;
            padding: 10px 16px;
            border-radius: 10px;
            margin-bottom: 12px;
        }
        .panel-header h5 { font-size: 18px; margin: 0; line-height: 1.2; }
        .panel-header .meta { font-size: 12px; opacity: .85; }
        .panel-header .cod-badge {
            background: rgba(255,255,255,.18);
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 12px;
        }
        .kpi-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform 0.3s;
            border-left: 4px solid #667eea;
        }
        .kpi-card:hover { transform: translateY(-5px); box-shadow: 0 5px 20px rgba(0,0,0,0.15); }
        .kpi-card.primary { border-left-color: #667eea; }
        .kpi-card.success { border-left-color: #28a745; }
        .kpi-card.warning { border-left-color: #ffc107; }
        .kpi-card.info { border-left-color: #17a2b8; }
        .kpi-valor { font-size: 28px; font-weight: bold; margin: 10px 0; }
        .table-analisis { font-size: 13px; }
        .table-analisis th { background: #f8f9fa; font-weight: 600; }
        .filtros-panel {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .chart-container { position: relative; height: 300px; width: 100%; }
        .progress { height: 8px; margin-top: 5px; }
        .stock-bajo { color: #dc3545; font-weight: bold; }
        .stock-normal { color: #28a745; }
        .text-primary-custom { color: #667eea; }
        .text-success-custom { color: #28a745; }
        .text-warning-custom { color: #ffc107; }
        .enlace-stock { text-decoration: none; color: inherit; }
        .enlace-stock:hover { text-decoration: none; }
        .btn-group .btn { padding: 2px 6px; font-size: 12px; }
        .ver-compra .ri-shopping-cart-line,
        .ver-existencias .ri-stack-line { font-size: 14px; }
        .btn-editar-producto { opacity: 0.3; transition: opacity 0.2s; }
        tr:hover .btn-editar-producto { opacity: 1; }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            {{-- Header compacto --}}
            <div class="panel-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <a href="/proveedores/{{$proveedor->codprov}}/tab4" class="text-white text-decoration-none">
                        <h5 class="text-white mb-0">{{ $proveedor->descrip }}</h5>
                    </a>
                    <span class="meta">
                        RIF: {{ $proveedor->id3 ?? 'N/A' }}
                    </span>
                    <span class="meta">
                        <i class="ri-phone-line"></i> {{ $proveedor->telef ?? 'N/A' }}
                    </span>
                </div>
                <a href="/proveedores/{{$proveedor->codprov}}/tab4" class="cod-badge text-white text-decoration-none">
                    Código: {{ $proveedor->codprov }}
                </a>
            </div>

            {{-- Alertas de filtro --}}
            @if(isset($filtro) && $filtro == 'con_stock')
                <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                    <i class="ri-stack-line"></i>
                    <strong>Filtro activo:</strong> Mostrando solo productos con existencia en stock.
                    <a href="{{ route('proveedores.productos-panel', ['codprov' => $proveedor->codprov, 'fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta, 'orden' => $orden]) }}" class="alert-link">Quitar filtro</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @elseif(isset($filtro) && $filtro == 'sin_stock')
                <div class="alert alert-warning alert-dismissible fade show py-2" role="alert">
                    <i class="ri-close-line"></i>
                    <strong>Filtro activo:</strong> Mostrando solo productos sin stock.
                    <a href="{{ route('proveedores.productos-panel', ['codprov' => $proveedor->codprov, 'fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta, 'orden' => $orden]) }}" class="alert-link">Quitar filtro</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Filtros --}}
            <div class="filtros-panel">
                <form method="GET" action="{{ route('proveedores.productos-panel', $proveedor->codprov) }}" class="row g-3" id="filtrosForm">
                    <input type="hidden" name="filtro" value="{{ $filtro ?? 'todos' }}">
                    <div id="comprasIdsContainer"></div>

                    <div class="col-md-2">
                        <label class="form-label">Fecha Desde</label>
                        <input type="date" name="fecha_desde" class="form-control" value="{{ $fecha_desde }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Fecha Hasta</label>
                        <input type="date" name="fecha_hasta" class="form-control" value="{{ $fecha_hasta }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Ordenar por</label>
                        <select name="orden" class="form-select">
                            <option value="vendido_desc" {{ $orden == 'vendido_desc' ? 'selected' : '' }}>Más Vendido</option>
                            <option value="vendido_asc" {{ $orden == 'vendido_asc' ? 'selected' : '' }}>Menos Vendido</option>
                            <option value="comprado_desc" {{ $orden == 'comprado_desc' ? 'selected' : '' }}>Más Comprado</option>
                            <option value="comprado_asc" {{ $orden == 'comprado_asc' ? 'selected' : '' }}>Menos Comprado</option>
                            <option value="existencia_desc" {{ $orden == 'existencia_desc' ? 'selected' : '' }}>Mayor Stock</option>
                            <option value="existencia_asc" {{ $orden == 'existencia_asc' ? 'selected' : '' }}>Menor Stock</option>
                            <option value="rotacion_desc" {{ $orden == 'rotacion_desc' ? 'selected' : '' }}>Mayor Rotación</option>
                            <option value="rentabilidad_desc" {{ $orden == 'rentabilidad_desc' ? 'selected' : '' }}>Mayor Rentabilidad</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Compras a analizar</label>
                        <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#selectorComprasModal">
                            <i class="ri-shopping-cart-2-line"></i>
                            <span id="comprasSelectedCount">
                                {{ count($compras_ids ?? []) > 0 ? count($compras_ids) . ' seleccionada(s)' : 'Todas' }}
                            </span>
                        </button>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="ri-filter-3-line"></i> Aplicar
                        </button>
                        <a href="{{ route('proveedores.productos-panel', ['codprov' => $proveedor->codprov, 'filtro' => $filtro ?? 'todos']) }}" class="btn btn-secondary">
                            <i class="ri-refresh-line"></i> Limpiar
                        </a>
                    </div>
                </form>

                @if(!empty($compras_ids))
                    <div class="mt-2">
                        <span class="badge bg-info text-dark">
                            <i class="ri-filter-3-line"></i>
                            Análisis limitado a <strong>{{ count($compras_ids) }}</strong> compra(s)
                        </span>
                        <a href="{{ route('proveedores.productos-panel', [
                                'codprov' => $proveedor->codprov,
                                'fecha_desde' => $fecha_desde,
                                'fecha_hasta' => $fecha_hasta,
                                'orden' => $orden,
                                'filtro' => $filtro,
                            ]) }}"
                           class="badge bg-secondary text-decoration-none ms-2">
                            <i class="ri-close-line"></i> Quitar filtro de compras
                        </a>
                    </div>
                @endif
            </div>

            {{-- KPIs --}}
            <div class="row">
                <div class="col-md-3">
                    <div class="kpi-card primary">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted">Total Compras</h6>
                                <h3 class="kpi-valor">${{ number_format($kpi['total_compras'] ?? 0, 2,',','.') }}</h3>
                                <small class="text-muted">
                                    @if(!empty($compras_ids))
                                        <span class="text-info fw-bold">
                                            <i class="ri-filter-3-line"></i> {{ count($compras_ids) }} compra(s)
                                        </span>
                                    @else
                                        Período seleccionado
                                    @endif
                                </small>
                            </div>
                            <div class="fs-1 text-primary-custom">
                                <i class="ri-shopping-cart-line"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-success">
                                <i class="ri-arrow-up-line"></i> Últimos 30 días: ${{ number_format($kpi['compras_30dias'] ?? 0, 2,',','.') }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="kpi-card success">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted">Total Ventas</h6>
                                <h3 class="kpi-valor">${{ number_format($kpi['total_ventas'] ?? 0, 2,',','.') }}</h3>
                                <small class="text-muted">
                                    @if(!empty($compras_ids))
                                        <span class="text-info fw-bold">
                                            <i class="ri-filter-3-line"></i> Productos de {{ count($compras_ids) }} compra(s)
                                        </span>
                                    @else
                                        Período seleccionado
                                    @endif
                                </small>
                            </div>
                            <div class="fs-1 text-success-custom">
                                <i class="ri-bar-chart-line"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-success">
                                <i class="ri-arrow-up-line"></i> Últimos 30 días: ${{ number_format($kpi['ventas_30dias'] ?? 0, 2,',','.') }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('proveedores.productos-panel', ['codprov' => $proveedor->codprov, 'fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta, 'orden' => $orden, 'filtro' => 'con_stock', 'compras_ids' => $compras_ids]) }}" class="enlace-stock">
                        <div class="kpi-card warning">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="text-muted">Valor Inventario</h6>
                                    <h3 class="kpi-valor">${{ number_format($kpi['valor_inventario'] ?? 0, 2,',','.') }}</h3>
                                    <small class="text-muted">{{ $kpi['existencia_total'] ?? 0 }} unidades</small>
                                </div>
                                <div class="fs-1 text-warning-custom">
                                    <i class="ri-stack-line"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small>Total productos: {{ $kpi['total_productos'] ?? 0 }}</small>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <div class="kpi-card" style="border-left-color: #dc3545; cursor: pointer;"
                         id="cardCuentasPorPagar"
                         data-bs-toggle="modal"
                         data-bs-target="#cxpProveedorModal"
                         title="Ver facturas pendientes">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted">Cuentas por Pagar</h6>
                                <h3 class="kpi-valor text-danger">
                                    ${{ number_format($cxpKpi['deuda_total'] ?? 0, 2, ',', '.') }}
                                </h3>
                                <small class="text-muted">
                                    {{ $cxpKpi['total_facturas'] ?? 0 }} factura(s) pendiente(s)
                                </small>
                            </div>
                            <div class="fs-1" style="color:#dc3545;">
                                <i class="ri-bank-card-line"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            @if(($cxpKpi['deuda_vencida'] ?? 0) > 0)
                                <small class="text-danger fw-bold">
                                    <i class="ri-alarm-warning-line"></i>
                                    Vencido: ${{ number_format($cxpKpi['deuda_vencida'], 2, ',', '.') }}
                                    ({{ $cxpKpi['facturas_vencidas'] }} fact.)
                                </small>
                            @elseif(($cxpKpi['total_facturas'] ?? 0) > 0)
                                <small class="text-success"><i class="ri-check-line"></i> Sin vencimientos</small>
                            @else
                                <small class="text-muted"><i class="ri-check-double-line"></i> Sin deuda</small>
                            @endif
                            <span class="badge bg-danger float-end">Ver detalle</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal CxP --}}
            <div class="modal fade" id="cxpProveedorModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <div>
                                <h5 class="modal-title text-white mb-0">
                                    <i class="ri-bank-card-line"></i> Cuentas por Pagar — {{ $proveedor->descrip }}
                                </h5>
                                <small class="text-white-50" id="cxpProveedorSubtitle"></small>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-2 mb-3">
                                <div class="col-md-3 col-6">
                                    <div class="p-2 rounded text-center" style="background:#ffeaea;">
                                        <small class="text-muted d-block">Deuda Total</small>
                                        <strong class="fs-5 text-danger" id="cxpKpiDeuda">$0</strong>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="p-2 rounded text-center" style="background:#fff3e0;">
                                        <small class="text-muted d-block">Total Facturado</small>
                                        <strong class="fs-5" style="color:#fd7e14;" id="cxpKpiMonto">$0</strong>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="p-2 rounded text-center" style="background:#e8f8f0;">
                                        <small class="text-muted d-block">Total Abonado</small>
                                        <strong class="fs-5 text-success" id="cxpKpiAbonado">$0</strong>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="p-2 rounded text-center" style="background:#eef2ff;">
                                        <small class="text-muted d-block">Facturas</small>
                                        <strong class="fs-5 text-primary" id="cxpKpiFacturas">0</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="cxpSoloVencidos">
                                    <label class="form-check-label" for="cxpSoloVencidos">Solo vencidos</label>
                                </div>
                                <div class="input-group input-group-sm" style="width: 280px;">
                                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                                    <input type="text" id="cxpBusqueda" class="form-control" placeholder="Buscar factura o concepto...">
                                </div>
                            </div>
                            <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                                <table class="table table-sm table-hover mb-0">
                                    <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                                    <tr>
                                        <th style="width: 90px;">Emisión</th>
                                        <th style="width: 90px;">Vence</th>
                                        <th style="width: 110px;">Documento</th>
                                        <th>Concepto</th>
                                        <th class="text-end" style="width: 100px;">Monto</th>
                                        <th class="text-end" style="width: 100px;">Abonado</th>
                                        <th class="text-end" style="width: 100px;">Saldo</th>
                                        <th class="text-center" style="width: 110px;">Estado</th>
                                    </tr>
                                    </thead>
                                    <tbody id="cxpProveedorTableBody">
                                    <tr><td colspan="8" class="text-center text-muted py-3">Cargando...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <a href="{{ route('proveedores.index', ['codprov' => $proveedor->codprov, 'tab' => 'tab6']) }}"
                               class="btn btn-outline-danger btn-sm">
                                <i class="ri-external-link-line"></i> Ir a Cuentas por Pagar completo
                            </a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Gráficos --}}
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="ri-bar-chart-2-line"></i>
                                Top 10 Productos Más Vendidos (Unidades)
                                <small class="text-muted ms-2">{{ \Carbon\Carbon::parse($fecha_desde)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fecha_hasta)->format('d/m/Y') }}</small>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="topProductosChart"></canvas>
                            </div>
                            @if(isset($top_productos) && count($top_productos) > 0)
                                <div class="mt-3">
                                    <table class="table table-sm table-borderless">
                                        <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th class="text-end">Unidades Vendidas</th>
                                            <th class="text-end">% del Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $total_unidades = $top_productos->sum('unidades_vendidas'); @endphp
                                        @foreach($top_productos as $item)
                                            <tr>
                                                <td style="font-size: 10px">{{ $item->descrip }}</td>
                                                <td class="text-center">{{ number_format($item->unidades_vendidas, 0) }}</td>
                                                <td class="text-end">
                                                    @if($total_unidades > 0)
                                                        {{ number_format(($item->unidades_vendidas / $total_unidades) * 100, 1) }}%
                                                    @else
                                                        0%
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tfoot style="border-top: 1px solid #eeeeee">
                                        <tr class="fw-bold">
                                            <td>TOTAL</td>
                                            <td class="text-center">{{ number_format($total_unidades, 0) }}</td>
                                            <td class="text-end">100%</td>
                                        </tr>
                                        </tfoot>
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="ri-pie-chart-2-line"></i>
                                Distribución de Ventas ($)
                                <small class="text-muted ms-2">{{ \Carbon\Carbon::parse($fecha_desde)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fecha_hasta)->format('d/m/Y') }}</small>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="distribucionChart"></canvas>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-bordered">
                                    <thead class="table-light">
                                    <tr>
                                        <th width="8%" class="text-center">Fecha</th>
                                        <th width="10%">Documento</th>
                                        <th width="10%" class="text-center">Unidades</th>
                                        <th width="12%" class="text-center">Monto</th>
                                        <th width="15%">Sucursal</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($compras ?? [] as $compra)
                                        @php
                                            $tipoTexto = $compra->tipocom == 'H' ? 'Compra' : 'Dev ';
                                            $tipoColor = $compra->tipocom == 'H' ? 'success' : 'danger';
                                        @endphp
                                        <tr>
                                            <td class="text-center" style="font-size: 11px">{{ \Carbon\Carbon::parse($compra->fechae)->format('d/m/Y') }}</td>
                                            <td style="font-size: 11px">
                                                <button type="button"
                                                        class="btn btn-sm btn-{{$tipoColor}} ver-compra"
                                                        data-id="{{ $compra->id }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#compraModal">
                                                    Doc: {{ $compra->numerod }}
                                                </button>
                                            </td>
                                            <td style="font-size: 11px" class="text-center {{ $compra->tipocom == 'I' ? 'text-danger' : '' }}">
                                                {{ number_format($compra->total_unidades_calculado ?? $compra->totalprd, 0) }}
                                            </td>
                                            <td style="font-size: 11px" class="text-end {{ $compra->tipocom == 'I' ? 'text-danger' : '' }}">
                                                {{ number_format($compra->total_monto_calculado ?? $compra->monto, 2) }}
                                            </td>
                                            <td style="font-size: 11px">{{ (isset($compra->sucursal->descrip))? str_replace("SARA","",$compra->sucursal->descrip) : 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="ri-inbox-line fs-1 text-muted"></i>
                                                <p class="text-muted mt-2">No hay compras registradas para este proveedor</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabla de Productos --}}
            <div class="card mt-3">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="ri-list-check"></i> Análisis Detallado de Productos</h6>
                    <div class="d-flex align-items-center gap-2">
                        <div class="input-group input-group-sm" style="width: 300px;">
                            <span class="input-group-text"><i class="ri-search-line"></i></span>
                            <input type="text" id="busquedaProductos" class="form-control"
                                   placeholder="Buscar código, producto o marca...">
                            <button class="btn btn-outline-secondary" type="button" id="limpiarBusqueda">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                        @if(isset($filtro) && $filtro == 'con_stock')
                            <span class="badge bg-success">Solo con stock</span>
                        @elseif(isset($filtro) && $filtro == 'sin_stock')
                            <span class="badge bg-warning">Solo sin stock</span>
                        @endif
                        @if(!empty($compras_ids))
                            <span class="badge bg-info text-dark">
                                <i class="ri-filter-3-line"></i> {{ count($compras_ids) }} compra(s)
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-analisis" id="productosTable">
                            <thead>
                            <tr>
                                <th class="sortable" data-sort="codprod">Código <i class="ri-arrow-up-down-line"></i></th>
                                <th class="sortable" data-sort="descrip">Producto <i class="ri-arrow-up-down-line"></i></th>
                                <th class="sortable" data-sort="marca">Marca <i class="ri-arrow-up-down-line"></i></th>
                                <th class="sortable text-end" data-sort="stock">Stock Total <i class="ri-arrow-up-down-line"></i></th>
                                <th class="sortable text-end" data-sort="unidades_compradas">Unidades Compradas <i class="ri-arrow-up-down-line"></i></th>
                                <th class="sortable text-end" data-sort="unidades_vendidas">Unidades Vendidas <i class="ri-arrow-up-down-line"></i></th>
                                <th class="sortable text-end" data-sort="dias_sin_venta">Días sin Venta <i class="ri-arrow-up-down-line"></i></th>
                                <th class="sortable text-end" data-sort="dias_stock">Días Stock <i class="ri-arrow-up-down-line"></i></th>
                                <th class="sortable text-end" data-sort="ganancia">Ganancia <i class="ri-arrow-up-down-line"></i></th>
                                <th class="sortable text-end" data-sort="margen">Margen % <i class="ri-arrow-up-down-line"></i></th>
                                <th class="sortable text-end" data-sort="ultima_compra">Última Compra <i class="ri-arrow-up-down-line"></i></th>
                                <th class="text-center">Depósitos</th>
                            </tr>
                            </thead>
                            <tbody id="productosTableBody">
                            @forelse($productos ?? [] as $producto)
                                <tr id="tr{{ $producto->codprod }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-sm btn-outline-primary btn-editar-producto"
                                                    style="width: 20px; height: 20px; padding: 0px; margin-right: 8px;"
                                                    data-id="{{ $producto->codprod }}"
                                                    title="Editar producto"
                                                    onclick="$('#tr{{ $producto->codprod }}').css('background','#e0f2ff')">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <span class="fw-medium">{{ $producto->codprod }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $producto->descrip }}</td>
                                    <td>{{ $producto->marca }}</td>
                                    <td class="text-end {{ $producto->existencia_actual < 10 ? 'stock-bajo' : 'stock-normal' }}">
                                        {{ number_format($producto->existencia_actual+0 ?? 0, 0) }}
                                    </td>
                                    <td class="text-end">
                                        @if(($producto->unidades_compradas ?? 0) > 0)
                                            <button type="button"
                                                    class="badge border-0 ver-compras-diarias"
                                                    style="cursor: pointer; background:#fd7e14; color:white;"
                                                    data-codprod="{{ $producto->codprod }}"
                                                    data-descrip="{{ $producto->descrip }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#comprasDiariasModal"
                                                    title="Ver detalle diario de compras">
                                                <i class="ri-shopping-cart-line"></i>
                                                {{ number_format($producto->unidades_compradas, 0) }}
                                            </button>
                                        @else
                                            <span class="badge bg-secondary">0</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if(($producto->unidades_vendidas ?? 0) > 0)
                                            <button type="button"
                                                    class="badge bg-primary border-0 ver-ventas-diarias"
                                                    style="cursor: pointer;"
                                                    data-codprod="{{ $producto->codprod }}"
                                                    data-descrip="{{ $producto->descrip }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ventasDiariasModal"
                                                    title="Ver detalle diario de ventas">
                                                <i class="ri-line-chart-line"></i>
                                                {{ number_format($producto->unidades_vendidas, 0) }}
                                            </button>
                                        @else
                                            <span class="badge bg-secondary">0</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($producto->dias_sin_venta === null)
                                            <span class="badge bg-secondary">Nunca</span>
                                        @elseif($producto->dias_sin_venta > 90)
                                            <span class="badge bg-danger">{{ $producto->dias_sin_venta }} días</span>
                                        @elseif($producto->dias_sin_venta > 30)
                                            <span class="badge bg-warning">{{ $producto->dias_sin_venta }} días</span>
                                        @else
                                            <span class="badge bg-success">{{ $producto->dias_sin_venta }} días</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($producto->dias_stock > 90)
                                            <span class="badge bg-danger">{{ number_format($producto->dias_stock, 0) }}</span>
                                        @elseif($producto->dias_stock > 30)
                                            <span class="badge bg-warning">{{ number_format($producto->dias_stock, 0) }}</span>
                                        @elseif($producto->dias_stock > 0)
                                            <span class="badge bg-success">{{ number_format($producto->dias_stock, 0) }}</span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    @php
                                        $costo = (float) ($producto->preciod ?? 0);
                                        $precioVenta = (float) ($producto->costod3 ?? 0);
                                        $ganancia = $precioVenta - $costo;
                                        $margen = $costo > 0 ? (($precioVenta - $costo) / $costo) * 100 : 0;
                                    @endphp
                                    <td class="text-end {{ $ganancia < 0 ? 'text-danger' : 'text-success' }} fw-bold">
                                        ${{ number_format($ganancia, 2, ',', '.') }}
                                    </td>
                                    <td class="text-end">
                                        @if($margen > 0)
                                            <span class="badge bg-success">{{ number_format($margen, 1) }}%</span>
                                        @elseif($margen < 0)
                                            <span class="badge bg-danger">{{ number_format($margen, 1) }}%</span>
                                        @else
                                            <span class="badge bg-secondary">0%</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex flex-column align-items-end gap-1">
                                            @if(isset($producto->ultima_compra_fecha) && $producto->ultima_compra_fecha)
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($producto->ultima_compra_fecha)->format('d/m/Y') }}
                                                    Doc: {{ $producto->ultima_compra_documento ?? 'N/A' }}
                                                </small>
                                                <div class="d-flex gap-1">
                                                    @if($producto->ultima_compra_dias > 30)
                                                        <button type="button" class="btn btn-sm btn-danger ver-compra"
                                                                data-id="{{ $producto->ultima_compra_id }}"
                                                                data-bs-toggle="modal" data-bs-target="#compraModal">
                                                            {{ $producto->ultima_compra_dias }} días
                                                        </button>
                                                    @elseif($producto->ultima_compra_dias > 15)
                                                        <button type="button" class="btn btn-sm btn-warning ver-compra"
                                                                data-id="{{ $producto->ultima_compra_id }}"
                                                                data-bs-toggle="modal" data-bs-target="#compraModal">
                                                            {{ $producto->ultima_compra_dias }} días
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-success ver-compra"
                                                                data-id="{{ $producto->ultima_compra_id }}"
                                                                data-bs-toggle="modal" data-bs-target="#compraModal">
                                                            {{ $producto->ultima_compra_dias }} días
                                                        </button>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="badge bg-secondary">Sin compras</span>
                                            @endif
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-secondary ver-compras-historicas"
                                                    data-codprod="{{ $producto->codprod }}"
                                                    data-descrip="{{ $producto->descrip }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#comprasHistoricasModal"
                                                    title="Ver historial completo de compras">
                                                <i class="ri-history-line"></i> Ver todas
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-info ver-existencias"
                                                data-codprod="{{ $producto->codprod }}"
                                                data-descrip="{{ $producto->descrip }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#existenciasModal"
                                                title="Ver existencias por depósito">
                                            <i class="ri-stack-line"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr id="filaSinProductos">
                                    <td colspan="12" class="text-center py-4">
                                        <i class="ri-inbox-line fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">
                                            No hay productos para mostrar
                                            @if(!empty($compras_ids))
                                                en las compras seleccionadas
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Modales existentes --}}
            <div class="modal fade" id="compraModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title text-white"><i class="ri-file-list-line"></i> Detalle de Compra</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="compraModalBody">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2">Cargando información de la compra...</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="existenciasModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title text-white"><i class="ri-stack-line"></i> Existencias por Depósito</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-3" id="modalProductoInfo"><strong>Cargando...</strong></div>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Depósito</th>
                                        <th class="text-end">Existencia</th>
                                    </tr>
                                    </thead>
                                    <tbody id="existenciasTableBody">
                                    <tr><td colspan="2" class="text-center">Cargando existencias...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal ventas diarias --}}
    <div class="modal fade" id="ventasDiariasModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <div>
                        <h5 class="modal-title text-white mb-0"><i class="ri-line-chart-line"></i> Detalle Diario de Ventas</h5>
                        <small class="text-white-50" id="ventasDiariasSubtitulo"></small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <div class="p-2 rounded text-center" style="background:#eef2ff;">
                                <small class="text-muted d-block">Total Vendido</small>
                                <strong class="fs-5 text-primary" id="kpiTotal">0</strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded text-center" style="background:#e8f8f0;">
                                <small class="text-muted d-block">Promedio Diario</small>
                                <strong class="fs-5 text-success" id="kpiPromedio">0</strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded text-center" style="background:#fff8e1;">
                                <small class="text-muted d-block">Mejor Día</small>
                                <strong class="fs-5 text-warning" id="kpiMejor">-</strong>
                            </div>
                        </div>
                    </div>
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="tab-por-dia" data-bs-toggle="tab" data-bs-target="#pane-por-dia" type="button">
                                <i class="ri-calendar-line"></i> Por Día
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="tab-por-sucursal" data-bs-toggle="tab" data-bs-target="#pane-por-sucursal" type="button">
                                <i class="ri-store-2-line"></i> Por Sucursal
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="pane-por-dia" role="tabpanel">
                            <div style="position: relative; height: 240px;">
                                <canvas id="ventasDiariasChart"></canvas>
                            </div>
                            <div class="table-responsive mt-3" style="max-height: 220px; overflow-y: auto;">
                                <table class="table table-sm table-hover mb-0">
                                    <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                                    <tr>
                                        <th style="width: 30%;">Fecha</th>
                                        <th style="width: 15%;" class="text-center">Día</th>
                                        <th style="width: 15%;" class="text-end">Unidades</th>
                                        <th style="width: 40%;">Participación</th>
                                    </tr>
                                    </thead>
                                    <tbody id="ventasDiariasTableBody">
                                    <tr><td colspan="4" class="text-center text-muted py-3">Cargando...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pane-por-sucursal" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <div style="position: relative; height: 240px;">
                                        <canvas id="ventasSucursalChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="table-responsive" style="max-height: 240px; overflow-y: auto;">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                                            <tr>
                                                <th>#</th>
                                                <th>Sucursal</th>
                                                <th class="text-end">Unidades</th>
                                                <th class="text-end">%</th>
                                            </tr>
                                            </thead>
                                            <tbody id="ventasSucursalTableBody">
                                            <tr><td colspan="4" class="text-center text-muted py-3">Cargando...</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="mejorSucursalBox" class="alert alert-success mt-3 mb-0 d-none">
                                <i class="ri-trophy-line"></i> <strong>Top sucursal:</strong>
                                <span id="mejorSucursalTexto"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal compras diarias --}}
    <div class="modal fade" id="comprasDiariasModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #fd7e14 0%, #e83e8c 100%); color: white;">
                    <div>
                        <h5 class="modal-title text-white mb-0"><i class="ri-shopping-cart-line"></i> Detalle Diario de Compras</h5>
                        <small class="text-white-50" id="comprasDiariasSubtitulo"></small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <div class="p-2 rounded text-center" style="background:#fff3e0;">
                                <small class="text-muted d-block">Total Comprado</small>
                                <strong class="fs-5" style="color:#fd7e14;" id="kpiCompraTotal">0</strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded text-center" style="background:#e8f8f0;">
                                <small class="text-muted d-block">Promedio Diario</small>
                                <strong class="fs-5 text-success" id="kpiCompraPromedio">0</strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded text-center" style="background:#fff8e1;">
                                <small class="text-muted d-block">Mejor Día</small>
                                <strong class="fs-5 text-warning" id="kpiCompraMejor">-</strong>
                            </div>
                        </div>
                    </div>
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="tab-compra-por-dia" data-bs-toggle="tab" data-bs-target="#pane-compra-por-dia" type="button">
                                <i class="ri-calendar-line"></i> Por Día
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="tab-compra-por-sucursal" data-bs-toggle="tab" data-bs-target="#pane-compra-por-sucursal" type="button">
                                <i class="ri-store-2-line"></i> Por Sucursal
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="pane-compra-por-dia" role="tabpanel">
                            <div style="position: relative; height: 240px;">
                                <canvas id="comprasDiariasChart"></canvas>
                            </div>
                            <div class="table-responsive mt-3" style="max-height: 220px; overflow-y: auto;">
                                <table class="table table-sm table-hover mb-0">
                                    <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                                    <tr>
                                        <th style="width: 30%;">Fecha</th>
                                        <th style="width: 15%;" class="text-center">Día</th>
                                        <th style="width: 15%;" class="text-end">Unidades</th>
                                        <th style="width: 40%;">Participación</th>
                                    </tr>
                                    </thead>
                                    <tbody id="comprasDiariasTableBody">
                                    <tr><td colspan="4" class="text-center text-muted py-3">Cargando...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pane-compra-por-sucursal" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <div style="position: relative; height: 240px;">
                                        <canvas id="comprasSucursalChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="table-responsive" style="max-height: 240px; overflow-y: auto;">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                                            <tr>
                                                <th>#</th>
                                                <th>Sucursal</th>
                                                <th class="text-end">Unidades</th>
                                                <th class="text-end">%</th>
                                            </tr>
                                            </thead>
                                            <tbody id="comprasSucursalTableBody">
                                            <tr><td colspan="4" class="text-center text-muted py-3">Cargando...</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="mejorSucursalCompraBox" class="alert alert-warning mt-3 mb-0 d-none">
                                <i class="ri-trophy-line"></i> <strong>Top sucursal:</strong>
                                <span id="mejorSucursalCompraTexto"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal compras históricas --}}
    <div class="modal fade" id="comprasHistoricasModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #6f42c1 0%, #667eea 100%); color: white;">
                    <div>
                        <h5 class="modal-title text-white mb-0"><i class="ri-history-line"></i> Historial Completo de Compras</h5>
                        <small class="text-white-50" id="comprasHistoricasSubtitulo"></small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-6 col-md-3">
                            <div class="p-2 rounded text-center" style="background:#f3e8ff;">
                                <small class="text-muted d-block">Total Comprado</small>
                                <strong class="fs-5" style="color:#6f42c1;" id="kpiHistComprado">0</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2 rounded text-center" style="background:#fff3e0;">
                                <small class="text-muted d-block">Monto Total</small>
                                <strong class="fs-5" style="color:#fd7e14;" id="kpiHistMonto">$0</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2 rounded text-center" style="background:#e8f8f0;">
                                <small class="text-muted d-block">Compras</small>
                                <strong class="fs-5 text-success" id="kpiHistCompras">0</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2 rounded text-center" style="background:#ffeaea;">
                                <small class="text-muted d-block">Devoluciones</small>
                                <strong class="fs-5 text-danger" id="kpiHistDevoluciones">0</strong>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th style="width: 90px;">Fecha</th>
                                <th style="width: 110px;">Documento</th>
                                <th style="width: 100px;" class="text-center">Tipo</th>
                                <th class="text-end" style="width: 90px;">Cantidad</th>
                                <th class="text-end" style="width: 110px;">Precio Unit.</th>
                                <th class="text-end" style="width: 120px;">Monto</th>
                                <th>Sucursal</th>
                            </tr>
                            </thead>
                            <tbody id="comprasHistoricasTableBody">
                            <tr><td colspan="7" class="text-center text-muted py-3">Cargando...</td></tr>
                            </tbody>
                            <tfoot class="table-light" style="position: sticky; bottom: 0;">
                            <tr class="fw-bold">
                                <td colspan="3" class="text-end">TOTALES:</td>
                                <td class="text-end" id="totalHistUnidades">0</td>
                                <td></td>
                                <td class="text-end" id="totalHistMonto">$0</td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal selector de compras --}}
    <div class="modal fade" id="selectorComprasModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background:linear-gradient(135deg,#fd7e14,#e83e8c);color:#fff;">
                    <div>
                        <h5 class="modal-title text-white mb-0">
                            <i class="ri-shopping-cart-2-line"></i> Seleccionar compras para el análisis
                        </h5>
                        <small class="text-white-50">
                            Marca facturas de compra. El análisis se limitará a los productos que aparezcan en ellas.
                        </small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-primary" id="selTodasCompras">Todas</button>
                            <button type="button" class="btn btn-outline-secondary" id="selNingunaCompras">Ninguna</button>
                            <button type="button" class="btn btn-outline-warning" id="selUltimas5">Últimas 5</button>
                        </div>
                        <div class="input-group input-group-sm" style="width:300px;">
                            <span class="input-group-text"><i class="ri-search-line"></i></span>
                            <input type="text" id="buscarCompra" class="form-control" placeholder="Documento, sucursal, fecha, notas...">
                        </div>
                    </div>
                    <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                        <table class="table table-sm table-hover mb-0 align-middle">
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th style="width: 40px;">
                                    <input type="checkbox" id="checkAllCompras">
                                </th>
                                <th style="width: 85px;">Fecha</th>
                                <th style="width: 100px;">Documento</th>
                                <th style="width: 85px;">Tipo</th>
                                <th style="min-width: 130px;">Sucursal</th>
                                <th style="min-width: 170px;">Notas 1</th>
                                <th style="min-width: 170px;">Notas 2</th>
                                <th class="text-end" style="width: 65px;">Ítems</th>
                                <th class="text-end" style="width: 80px;">Unid.</th>
                                <th class="text-end" style="width: 95px;">Monto</th>
                            </tr>
                            </thead>
                            <tbody id="comprasSelectorTableBody">
                            @forelse($comprasSelector as $c)
                                @php
                                    $notas1 = trim($c['notas1'] ?? '');
                                    $notas2 = trim($c['notas2'] ?? '');
                                    $searchBlob = strtolower($c['numerod'].' '.$c['sucursal'].' '.$c['fecha_fmt'].' '.$c['tipo_texto'].' '.$notas1.' '.$notas2);
                                @endphp
                                <tr data-compra-row data-id="{{ $c['id'] }}" data-search="{{ $searchBlob }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input check-compra"
                                               value="{{ $c['id'] }}"
                                               data-id="{{ $c['id'] }}"
                                            {{ in_array($c['id'], $compras_ids ?? []) ? 'checked' : '' }}>
                                    </td>
                                    <td><small>{{ $c['fecha_fmt'] }}</small></td>
                                    <td><strong>{{ $c['numerod'] }}</strong></td>
                                    <td>
                                        @if($c['tipocom'] === 'H')
                                            <span class="badge bg-success">Compra</span>
                                        @else
                                            <span class="badge bg-danger">Devolución</span>
                                        @endif
                                    </td>
                                    <td><small>{{ $c['sucursal'] }}</small></td>
                                    <td>
                                        @if($notas1 !== '')
                                            <small class="text-truncate d-inline-block" style="max-width:160px;" title="{{ $notas1 }}">
                                                {{ $notas1 }}
                                            </small>
                                        @else
                                            <small class="text-muted">—</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($notas2 !== '')
                                            <small class="text-truncate d-inline-block" style="max-width:160px;" title="{{ $notas2 }}">
                                                {{ $notas2 }}
                                            </small>
                                        @else
                                            <small class="text-muted">—</small>
                                        @endif
                                    </td>
                                    <td class="text-end"><small>{{ $c['total_items'] }}</small></td>
                                    <td class="text-end"><small>{{ number_format($c['unidades'], 0, ',', '.') }}</small></td>
                                    <td class="text-end"><small>${{ number_format($c['monto'], 2, ',', '.') }}</small></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-3">
                                        <i class="ri-inbox-line fs-3 d-block"></i>
                                        No hay compras en el período seleccionado
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <span class="badge bg-info text-dark" id="contadorCompras">
                        {{ count($compras_ids ?? []) }} seleccionada(s)
                    </span>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="aplicarComprasBtn">
                            <i class="ri-filter-3-line"></i> Aplicar filtro
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('proveedores.partials.producto_edit_modal')
@endsection

@section('scripts')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const codprovActual = '{{ $proveedor->codprov }}';

        // ============================================================
        // MODAL CX P
        // ============================================================
        let cxpDataCache = [];
        let cxpCargado = false;

        $('#cxpProveedorModal').on('show.bs.modal', function() {
            if (cxpCargado) return;

            $('#cxpProveedorTableBody').html(
                '<tr><td colspan="8" class="text-center text-muted py-3">' +
                '<div class="spinner-border spinner-border-sm text-danger me-2"></div>Cargando facturas...</td></tr>'
            );

            $.ajax({
                url: `/proveedores/${codprovActual}/cxp-simple`,
                type: 'GET',
                success: function(response) {
                    if (!response.success) {
                        $('#cxpProveedorTableBody').html('<tr><td colspan="8" class="text-center text-danger py-3">Error al cargar datos</td></tr>');
                        return;
                    }
                    cxpDataCache = response.facturas || [];
                    cxpCargado = true;
                    const fmt = (v) => Number(v || 0).toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    $('#cxpKpiDeuda').text('$' + fmt(response.total_deuda));
                    $('#cxpKpiMonto').text('$' + fmt(response.total_monto));
                    $('#cxpKpiAbonado').text('$' + fmt(response.total_abonado));
                    $('#cxpKpiFacturas').text(response.total_facturas);
                    $('#cxpProveedorSubtitle').text(response.total_facturas + ' factura(s) — Total: $' + fmt(response.total_deuda));
                    renderCxpProveedor();
                },
                error: function(xhr) {
                    console.error(xhr);
                    $('#cxpProveedorTableBody').html('<tr><td colspan="8" class="text-center text-danger py-3">Error al cargar datos</td></tr>');
                }
            });
        });

        function renderCxpProveedor() {
            const soloVencidos = $('#cxpSoloVencidos').is(':checked');
            const busqueda     = $('#cxpBusqueda').val().trim().toLowerCase();
            const tokens       = busqueda.split(/\s+/).filter(t => t.length > 0);
            const fmt = (v) => Number(v || 0).toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2});

            let rows = '', visibles = 0, totalFiltrado = 0;

            cxpDataCache.forEach(function(f) {
                if (soloVencidos && f.dias_vencidos <= 0) return;
                if (tokens.length > 0) {
                    const texto = ((f.numerod||'') + ' ' + (f.concepto||'') + ' ' + (f.fechav_fmt||'')).toLowerCase();
                    if (!tokens.every(t => texto.includes(t))) return;
                }
                visibles++;
                totalFiltrado += f.deuda;

                let estadoBadge;
                if (f.dias_vencidos > 30) estadoBadge = `<span class="badge bg-danger">${f.dias_vencidos}d vencido</span>`;
                else if (f.dias_vencidos > 0) estadoBadge = `<span class="badge bg-warning text-dark">${f.dias_vencidos}d vencido</span>`;
                else if (f.dias_por_vencer <= 7) estadoBadge = `<span class="badge bg-warning text-dark">Vence en ${f.dias_por_vencer}d</span>`;
                else estadoBadge = `<span class="badge bg-success">${f.dias_por_vencer}d</span>`;

                rows += `
                    <tr class="${f.dias_vencidos > 0 ? 'table-danger' : ''}">
                        <td><small>${f.fecha_fmt}</small></td>
                        <td><small>${f.fechav_fmt}</small></td>
                        <td><strong>${f.numerod || '-'}</strong></td>
                        <td><small>${f.concepto || '-'}</small></td>
                        <td class="text-end">$${fmt(f.monto)}</td>
                        <td class="text-end text-success">$${fmt(f.abonado)}</td>
                        <td class="text-end fw-bold text-danger">$${fmt(f.deuda)}</td>
                        <td class="text-center">${estadoBadge}</td>
                    </tr>`;
            });

            if (visibles === 0) {
                rows = '<tr><td colspan="8" class="text-center text-muted py-3"><i class="ri-inbox-line fs-3 d-block"></i>Sin facturas que coincidan</td></tr>';
            }

            rows += `
                <tr class="fw-bold table-light">
                    <td colspan="6" class="text-end">TOTAL FILTRADO:</td>
                    <td class="text-end text-danger">$${fmt(totalFiltrado)}</td>
                    <td class="text-center">${visibles} fact.</td>
                </tr>`;

            $('#cxpProveedorTableBody').html(rows);
        }

        $('#cxpSoloVencidos').on('change', renderCxpProveedor);
        $('#cxpBusqueda').on('input', renderCxpProveedor);

        // ============================================================
        // SELECTOR DE COMPRAS
        // ============================================================
        (function() {
            const $modal    = $('#selectorComprasModal');
            const $tbody    = $('#comprasSelectorTableBody');
            const $contador = $('#contadorCompras');

            function updateContador() {
                const n = $tbody.find('.check-compra:checked').length;
                $contador.text(n + ' seleccionada(s)');
                $('#selNingunaCompras').prop('disabled', n === 0);
                $('#comprasSelectedCount').text(n > 0 ? n + ' seleccionada(s)' : 'Todas');
            }

            $tbody.on('change', '.check-compra', updateContador);
            updateContador();

            $('#selTodasCompras').on('click', function() {
                $tbody.find('tr[data-compra-row]:visible .check-compra').prop('checked', true);
                updateContador();
            });
            $('#selNingunaCompras').on('click', function() {
                $tbody.find('.check-compra').prop('checked', false);
                updateContador();
            });
            $('#selUltimas5').on('click', function() {
                $tbody.find('.check-compra').prop('checked', false);
                $tbody.find('tr[data-compra-row]').slice(0, 5).find('.check-compra').prop('checked', true);
                updateContador();
            });
            $('#checkAllCompras').on('change', function() {
                $tbody.find('tr[data-compra-row]:visible .check-compra').prop('checked', this.checked);
                updateContador();
            });

            $('#buscarCompra').on('input', function() {
                const term = $(this).val().trim().toLowerCase();
                const tokens = term.split(/\s+/).filter(t => t.length > 0);
                $tbody.find('tr[data-compra-row]').each(function() {
                    const txt = ($(this).data('search') || '').toString();
                    $(this).toggle(tokens.length === 0 || tokens.every(t => txt.includes(t)));
                });
            });

            $('#aplicarComprasBtn').on('click', function() {
                const $form = $('#filtrosForm');
                const $container = $('#comprasIdsContainer').empty();
                $tbody.find('.check-compra:checked').each(function() {
                    $('<input>', { type: 'hidden', name: 'compras_ids[]', value: $(this).val() }).appendTo($container);
                });
                $form.trigger('submit');
            });

            $modal.on('shown.bs.modal', updateContador);
        })();

        $(document).ready(function() {

            // COMPRAS HISTÓRICAS
            $(document).on('click', '.ver-compras-historicas', function () {
                const codprod = $(this).data('codprod');
                const descrip = $(this).data('descrip');
                const codprov = '{{ $proveedor->codprov }}';

                $('#comprasHistoricasSubtitulo').text(descrip + ' — Proveedor: {{ $proveedor->descrip }}');
                $('#kpiHistComprado, #kpiHistMonto, #kpiHistCompras, #kpiHistDevoluciones').text('...');
                $('#comprasHistoricasTableBody').html('<tr><td colspan="7" class="text-center text-muted py-3">Cargando...</td></tr>');

                $.ajax({
                    url: '{{ route("proveedores.compras-historicas-producto") }}',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}', codprod: codprod, codprov: codprov },
                    success: function (response) {
                        if (!response.success) {
                            $('#comprasHistoricasTableBody').html('<tr><td colspan="7" class="text-center text-danger py-3">Error al cargar datos</td></tr>');
                            return;
                        }
                        const registros = response.registros || [];
                        const resumen   = response.resumen || {};

                        $('#kpiHistComprado').text(Number(resumen.total_unidades || 0).toLocaleString('es-VE'));
                        $('#kpiHistMonto').text('$' + Number(resumen.total_monto || 0).toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                        $('#kpiHistCompras').text(Number(resumen.total_compras || 0).toLocaleString('es-VE'));
                        $('#kpiHistDevoluciones').text(Number(resumen.total_devoluciones || 0).toLocaleString('es-VE'));

                        let html = '', totalUnidades = 0, totalMonto = 0;
                        if (registros.length === 0) {
                            html = '<tr><td colspan="7" class="text-center text-muted py-3"><i class="ri-inbox-line fs-3 d-block"></i>Sin compras registradas</td></tr>';
                        } else {
                            registros.forEach(function (r) {
                                const esDev = r.tipocom === 'I';
                                const tipoColor = esDev ? 'danger' : 'success';
                                const cantColor = esDev ? 'text-danger' : '';
                                totalUnidades += r.cantidad;
                                totalMonto    += r.monto;
                                html += `
                                    <tr>
                                        <td><small>${r.fecha_fmt}</small></td>
                                        <td><button type="button" class="btn btn-sm btn-${tipoColor} ver-compra" data-id="${r.compra_id}" data-bs-toggle="modal" data-bs-target="#compraModal">${r.documento}</button></td>
                                        <td class="text-center"><span class="badge bg-${tipoColor}">${r.tipo_texto}</span></td>
                                        <td class="text-end ${cantColor}"><strong>${Number(r.cantidad).toLocaleString('es-VE')}</strong></td>
                                        <td class="text-end">$${Number(r.preciod).toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                                        <td class="text-end ${cantColor}">$${Number(r.monto).toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                                        <td><small>${r.sucursal}</small></td>
                                    </tr>`;
                            });
                        }
                        $('#comprasHistoricasTableBody').html(html);
                        $('#totalHistUnidades').text(totalUnidades.toLocaleString('es-VE'));
                        $('#totalHistMonto').text('$' + totalMonto.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    },
                    error: function (xhr) {
                        console.error(xhr);
                        $('#comprasHistoricasTableBody').html('<tr><td colspan="7" class="text-center text-danger py-3">Error al cargar datos</td></tr>');
                    }
                });
            });

            // COMPRAS DIARIAS
            let comprasDiariasChartInstance = null;
            let comprasSucursalChartInstance = null;

            $(document).on('click', '.ver-compras-diarias', function () {
                const codprod = $(this).data('codprod');
                const descrip = $(this).data('descrip');
                const codprov = '{{ $proveedor->codprov }}';
                const fecha_desde = '{{ $fecha_desde }}';
                const fecha_hasta = '{{ $fecha_hasta }}';

                $('#comprasDiariasSubtitulo').text(descrip + ' — ' + new Date(fecha_desde).toLocaleDateString('es-VE') + ' al ' + new Date(fecha_hasta).toLocaleDateString('es-VE'));
                $('#kpiCompraTotal, #kpiCompraPromedio, #kpiCompraMejor').text('...');
                $('#comprasDiariasTableBody').html('<tr><td colspan="4" class="text-center text-muted py-3">Cargando...</td></tr>');
                $('#comprasSucursalTableBody').html('<tr><td colspan="4" class="text-center text-muted py-3">Cargando...</td></tr>');
                $('#mejorSucursalCompraBox').addClass('d-none');

                if (typeof bootstrap !== 'undefined') {
                    const t = document.querySelector('#tab-compra-por-dia');
                    if (t) new bootstrap.Tab(t).show();
                }
                if (comprasDiariasChartInstance) { comprasDiariasChartInstance.destroy(); comprasDiariasChartInstance = null; }
                if (comprasSucursalChartInstance) { comprasSucursalChartInstance.destroy(); comprasSucursalChartInstance = null; }

                $.ajax({
                    url: '{{ route("proveedores.compras-diarias-producto") }}',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}', codprod, codprov, fecha_desde, fecha_hasta },
                    success: function (response) {
                        if (!response.success) {
                            $('#comprasDiariasTableBody').html('<tr><td colspan="4" class="text-center text-danger py-3">Error al cargar datos</td></tr>');
                            return;
                        }
                        const dias = response.dias || [];
                        const sucursales = response.sucursales || [];
                        const resumen = response.resumen || {};

                        $('#kpiCompraTotal').text(Number(resumen.total_unidades || 0).toLocaleString('es-VE'));
                        $('#kpiCompraPromedio').text(Number(resumen.promedio_diario || 0).toLocaleString('es-VE'));
                        $('#kpiCompraMejor').text(resumen.mejor_dia ? (resumen.mejor_dia.unidades + ' (' + resumen.mejor_dia.fecha_fmt + ')') : '-');

                        const labels = dias.map(d => d.fecha_fmt);
                        const valores = dias.map(d => d.unidades);
                        const ctx = document.getElementById('comprasDiariasChart').getContext('2d');
                        const gradient = ctx.createLinearGradient(0, 0, 0, 240);
                        gradient.addColorStop(0, 'rgba(253, 126, 20, 0.6)');
                        gradient.addColorStop(1, 'rgba(253, 126, 20, 0.05)');

                        comprasDiariasChartInstance = new Chart(ctx, {
                            type: 'line',
                            data: { labels, datasets: [{
                                    label: 'Unidades Compradas', data: valores,
                                    borderColor: '#fd7e14', backgroundColor: gradient, borderWidth: 2, fill: true, tension: 0.35,
                                    pointBackgroundColor: '#fd7e14', pointBorderColor: '#fff', pointBorderWidth: 2,
                                    pointRadius: 4, pointHoverRadius: 7
                                }]},
                            options: {
                                responsive: true, maintainAspectRatio: false, legend: { display: false },
                                tooltips: { backgroundColor: 'rgba(0,0,0,0.8)', callbacks: { label: function (item) { return ' ' + item.yLabel + ' unidades'; } } },
                                scales: {
                                    yAxes: [{ beginAtZero: true, ticks: { precision: 0, fontColor: '#666' }, gridLines: { color: 'rgba(0,0,0,0.05)' } }],
                                    xAxes: [{ ticks: { fontColor: '#666', autoSkip: true, maxTicksLimit: 12 }, gridLines: { display: false } }]
                                }
                            }
                        });

                        const maxU = Math.max(...valores, 1);
                        const totalPP = resumen.total_unidades || 0;
                        let htmlDias = '';
                        dias.forEach(function (d) {
                            const pct = totalPP > 0 ? (d.unidades / totalPP) * 100 : 0;
                            const bpct = (d.unidades / maxU) * 100;
                            let cb = 'bg-secondary';
                            if (d.unidades > 0) { if (bpct >= 70) cb = 'bg-success'; else if (bpct >= 30) cb = 'bg-warning'; else cb = 'bg-info'; }
                            htmlDias += `
                                <tr>
                                    <td><strong>${d.fecha_fmt}</strong></td>
                                    <td class="text-center text-muted text-capitalize">${d.dia_semana}</td>
                                    <td class="text-end"><span class="badge ${d.unidades > 0 ? 'bg-warning text-dark' : 'bg-light text-muted'}">${Number(d.unidades).toLocaleString('es-VE')}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 6px;"><div class="progress-bar ${cb}" style="width: ${bpct}%"></div></div>
                                            <small class="text-muted" style="min-width: 40px; text-align: right;">${pct.toFixed(1)}%</small>
                                        </div>
                                    </td>
                                </tr>`;
                        });
                        $('#comprasDiariasTableBody').html(htmlDias);

                        const colores = ['#fd7e14','#e83e8c','#6f42c1','#17a2b8','#20c997','#ffc107','#dc3545','#667eea','#28a745','#6c757d'];
                        if (sucursales.length > 0) {
                            const labelsSuc = sucursales.map(s => s.nombre);
                            const dataSuc   = sucursales.map(s => s.unidades);
                            const colorsSuc = sucursales.map((s, i) => colores[i % colores.length]);

                            const ctxSuc = document.getElementById('comprasSucursalChart').getContext('2d');
                            comprasSucursalChartInstance = new Chart(ctxSuc, {
                                type: 'doughnut',
                                data: { labels: labelsSuc, datasets: [{ data: dataSuc, backgroundColor: colorsSuc, borderWidth: 2, borderColor: '#fff' }] },
                                options: {
                                    responsive: true, maintainAspectRatio: false, cutoutPercentage: 55,
                                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10, fontSize: 11 } },
                                    tooltips: { backgroundColor: 'rgba(0,0,0,0.8)', callbacks: { label: function (item, data) {
                                                const t = data.datasets[0].data.reduce((a,b)=>a+b,0);
                                                const v = item.xLabel || item.yLabel;
                                                const p = t > 0 ? ((v/t)*100).toFixed(1) : 0;
                                                return ' ' + item.label + ': ' + v + ' und (' + p + '%)';
                                            }}}
                                }
                            });

                            const totalSuc = dataSuc.reduce((a,b)=>a+b,0);
                            let htmlSuc = '';
                            sucursales.forEach(function (s, i) {
                                const pct = totalSuc > 0 ? (s.unidades / totalSuc) * 100 : 0;
                                const bpct = totalSuc > 0 ? (s.unidades / Math.max(...dataSuc)) * 100 : 0;
                                const colorBarra = colores[i % colores.length];
                                htmlSuc += `
                                    <tr>
                                        <td><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:${colorBarra};margin-right:6px;"></span>${i+1}</td>
                                        <td><strong>${s.nombre}</strong></td>
                                        <td class="text-end">${Number(s.unidades).toLocaleString('es-VE')}</td>
                                        <td class="text-end">
                                            <div class="d-flex align-items-center justify-content-end gap-2">
                                                <div class="progress" style="height: 6px; width: 60px;"><div class="progress-bar" style="width: ${bpct}%; background:${colorBarra};"></div></div>
                                                <small class="text-muted">${pct.toFixed(1)}%</small>
                                            </div>
                                        </td>
                                    </tr>`;
                            });
                            $('#comprasSucursalTableBody').html(htmlSuc);

                            if (resumen.mejor_sucursal) {
                                const ms = resumen.mejor_sucursal;
                                const pctMs = totalSuc > 0 ? ((ms.unidades / totalSuc) * 100).toFixed(1) : 0;
                                $('#mejorSucursalCompraTexto').text(ms.nombre + ' con ' + Number(ms.unidades).toLocaleString('es-VE') + ' unidades (' + pctMs + '% del total)');
                                $('#mejorSucursalCompraBox').removeClass('d-none');
                            }
                        } else {
                            const ctxSuc = document.getElementById('comprasSucursalChart').getContext('2d');
                            ctxSuc.clearRect(0, 0, ctxSuc.canvas.width, ctxSuc.canvas.height);
                            $('#comprasSucursalTableBody').html('<tr><td colspan="4" class="text-center text-muted py-3"><i class="ri-inbox-line fs-3 d-block"></i>Sin compras por sucursal en el período</td></tr>');
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr);
                        $('#comprasDiariasTableBody').html('<tr><td colspan="4" class="text-center text-danger py-3">Error al cargar datos</td></tr>');
                        $('#comprasSucursalTableBody').html('<tr><td colspan="4" class="text-center text-danger py-3">Error al cargar datos</td></tr>');
                    }
                });
            });

            // VENTAS DIARIAS
            let ventasDiariasChartInstance = null;
            let ventasSucursalChartInstance = null;

            $(document).on('click', '.ver-ventas-diarias', function () {
                const codprod = $(this).data('codprod');
                const descrip = $(this).data('descrip');
                const codprov = '{{ $proveedor->codprov }}';
                const fecha_desde = '{{ $fecha_desde }}';
                const fecha_hasta = '{{ $fecha_hasta }}';

                $('#ventasDiariasSubtitulo').text(descrip + ' — ' + new Date(fecha_desde).toLocaleDateString('es-VE') + ' al ' + new Date(fecha_hasta).toLocaleDateString('es-VE'));
                $('#kpiTotal, #kpiPromedio, #kpiMejor').text('...');
                $('#ventasDiariasTableBody').html('<tr><td colspan="4" class="text-center text-muted py-3">Cargando...</td></tr>');
                $('#ventasSucursalTableBody').html('<tr><td colspan="4" class="text-center text-muted py-3">Cargando...</td></tr>');
                $('#mejorSucursalBox').addClass('d-none');

                if (typeof bootstrap !== 'undefined') {
                    const t = document.querySelector('#tab-por-dia');
                    if (t) new bootstrap.Tab(t).show();
                }
                if (ventasDiariasChartInstance) { ventasDiariasChartInstance.destroy(); ventasDiariasChartInstance = null; }
                if (ventasSucursalChartInstance) { ventasSucursalChartInstance.destroy(); ventasSucursalChartInstance = null; }

                $.ajax({
                    url: '{{ route("proveedores.ventas-diarias-producto") }}',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}', codprod, codprov, fecha_desde, fecha_hasta },
                    success: function (response) {
                        if (!response.success) {
                            $('#ventasDiariasTableBody').html('<tr><td colspan="4" class="text-center text-danger py-3">Error al cargar datos</td></tr>');
                            return;
                        }
                        const dias = response.dias || [];
                        const sucursales = response.sucursales || [];
                        const resumen = response.resumen || {};

                        $('#kpiTotal').text(Number(resumen.total_unidades || 0).toLocaleString('es-VE'));
                        $('#kpiPromedio').text(Number(resumen.promedio_diario || 0).toLocaleString('es-VE'));
                        $('#kpiMejor').text(resumen.mejor_dia ? (resumen.mejor_dia.unidades + ' (' + resumen.mejor_dia.fecha_fmt + ')') : '-');

                        const labels = dias.map(d => d.fecha_fmt);
                        const valores = dias.map(d => d.unidades);
                        const ctx = document.getElementById('ventasDiariasChart').getContext('2d');
                        const gradient = ctx.createLinearGradient(0, 0, 0, 240);
                        gradient.addColorStop(0, 'rgba(102, 126, 234, 0.6)');
                        gradient.addColorStop(1, 'rgba(102, 126, 234, 0.05)');

                        ventasDiariasChartInstance = new Chart(ctx, {
                            type: 'line',
                            data: { labels, datasets: [{
                                    label: 'Unidades Vendidas', data: valores,
                                    borderColor: '#667eea', backgroundColor: gradient, borderWidth: 2, fill: true, tension: 0.35,
                                    pointBackgroundColor: '#667eea', pointBorderColor: '#fff', pointBorderWidth: 2,
                                    pointRadius: 4, pointHoverRadius: 7
                                }]},
                            options: {
                                responsive: true, maintainAspectRatio: false, legend: { display: false },
                                tooltips: { backgroundColor: 'rgba(0,0,0,0.8)', callbacks: { label: function (item) { return ' ' + item.yLabel + ' unidades'; } } },
                                scales: {
                                    yAxes: [{ beginAtZero: true, ticks: { precision: 0, fontColor: '#666' }, gridLines: { color: 'rgba(0,0,0,0.05)' } }],
                                    xAxes: [{ ticks: { fontColor: '#666', autoSkip: true, maxTicksLimit: 12 }, gridLines: { display: false } }]
                                }
                            }
                        });

                        const maxU = Math.max(...valores, 1);
                        const totalPP = resumen.total_unidades || 0;
                        let htmlDias = '';
                        dias.forEach(function (d) {
                            const pct = totalPP > 0 ? (d.unidades / totalPP) * 100 : 0;
                            const bpct = (d.unidades / maxU) * 100;
                            let cb = 'bg-secondary';
                            if (d.unidades > 0) { if (bpct >= 70) cb = 'bg-success'; else if (bpct >= 30) cb = 'bg-primary'; else cb = 'bg-info'; }
                            htmlDias += `
                                <tr>
                                    <td><strong>${d.fecha_fmt}</strong></td>
                                    <td class="text-center text-muted text-capitalize">${d.dia_semana}</td>
                                    <td class="text-end"><span class="badge ${d.unidades > 0 ? 'bg-primary' : 'bg-light text-muted'}">${Number(d.unidades).toLocaleString('es-VE')}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 6px;"><div class="progress-bar ${cb}" style="width: ${bpct}%"></div></div>
                                            <small class="text-muted" style="min-width: 40px; text-align: right;">${pct.toFixed(1)}%</small>
                                        </div>
                                    </td>
                                </tr>`;
                        });
                        $('#ventasDiariasTableBody').html(htmlDias);

                        const colores = ['#667eea','#28a745','#ffc107','#17a2b8','#dc3545','#6f42c1','#fd7e14','#20c997','#e83e8c','#6c757d'];
                        if (sucursales.length > 0) {
                            const labelsSuc = sucursales.map(s => s.nombre);
                            const dataSuc   = sucursales.map(s => s.unidades);
                            const colorsSuc = sucursales.map((s, i) => colores[i % colores.length]);

                            const ctxSuc = document.getElementById('ventasSucursalChart').getContext('2d');
                            ventasSucursalChartInstance = new Chart(ctxSuc, {
                                type: 'doughnut',
                                data: { labels: labelsSuc, datasets: [{ data: dataSuc, backgroundColor: colorsSuc, borderWidth: 2, borderColor: '#fff' }] },
                                options: {
                                    responsive: true, maintainAspectRatio: false, cutoutPercentage: 55,
                                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10, fontSize: 11 } },
                                    tooltips: { backgroundColor: 'rgba(0,0,0,0.8)', callbacks: { label: function (item, data) {
                                                const t = data.datasets[0].data.reduce((a,b)=>a+b,0);
                                                const v = item.xLabel || item.yLabel;
                                                const p = t > 0 ? ((v/t)*100).toFixed(1) : 0;
                                                return ' ' + item.label + ': ' + v + ' und (' + p + '%)';
                                            }}}
                                }
                            });

                            const totalSuc = dataSuc.reduce((a,b)=>a+b,0);
                            let htmlSuc = '';
                            sucursales.forEach(function (s, i) {
                                const pct = totalSuc > 0 ? (s.unidades / totalSuc) * 100 : 0;
                                const bpct = totalSuc > 0 ? (s.unidades / Math.max(...dataSuc)) * 100 : 0;
                                const colorBarra = colores[i % colores.length];
                                htmlSuc += `
                                    <tr>
                                        <td><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:${colorBarra};margin-right:6px;"></span>${i+1}</td>
                                        <td><strong>${s.nombre}</strong></td>
                                        <td class="text-end">${Number(s.unidades).toLocaleString('es-VE')}</td>
                                        <td class="text-end">
                                            <div class="d-flex align-items-center justify-content-end gap-2">
                                                <div class="progress" style="height: 6px; width: 60px;"><div class="progress-bar" style="width: ${bpct}%; background:${colorBarra};"></div></div>
                                                <small class="text-muted">${pct.toFixed(1)}%</small>
                                            </div>
                                        </td>
                                    </tr>`;
                            });
                            $('#ventasSucursalTableBody').html(htmlSuc);

                            if (resumen.mejor_sucursal) {
                                const ms = resumen.mejor_sucursal;
                                const pctMs = totalSuc > 0 ? ((ms.unidades / totalSuc) * 100).toFixed(1) : 0;
                                $('#mejorSucursalTexto').text(ms.nombre + ' con ' + Number(ms.unidades).toLocaleString('es-VE') + ' unidades (' + pctMs + '% del total)');
                                $('#mejorSucursalBox').removeClass('d-none');
                            }
                        } else {
                            const ctxSuc = document.getElementById('ventasSucursalChart').getContext('2d');
                            ctxSuc.clearRect(0, 0, ctxSuc.canvas.width, ctxSuc.canvas.height);
                            $('#ventasSucursalTableBody').html('<tr><td colspan="4" class="text-center text-muted py-3"><i class="ri-inbox-line fs-3 d-block"></i>Sin ventas por sucursal en el período</td></tr>');
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr);
                        $('#ventasDiariasTableBody').html('<tr><td colspan="4" class="text-center text-danger py-3">Error al cargar datos</td></tr>');
                        $('#ventasSucursalTableBody').html('<tr><td colspan="4" class="text-center text-danger py-3">Error al cargar datos</td></tr>');
                    }
                });
            });

            if (typeof Chart === 'undefined') {
                console.error('Chart.js no se cargó correctamente');
                $('#topProductosChart').parent().html('<div class="alert alert-warning">Error al cargar Chart.js. Recargue la página.</div>');
                $('#distribucionChart').parent().html('<div class="alert alert-warning">Error al cargar Chart.js. Recargue la página.</div>');
                return;
            }

            // BÚSQUEDA PRODUCTOS
            function filtrarProductos() {
                const termino = $('#busquedaProductos').val().trim().toLowerCase();
                const tokens = termino.split(/\s+/).filter(t => t.length > 0);
                let visibles = 0;

                $('#productosTableBody tr').each(function() {
                    const $fila = $(this);
                    if ($fila.attr('id') === 'filaSinProductos' || $fila.attr('id') === 'sinResultados') return;
                    if ($fila.find('td[colspan]').length > 0) return;

                    const codigo  = $fila.find('td').eq(0).text().toLowerCase();
                    const descrip = $fila.find('td').eq(1).text().toLowerCase();
                    const marca   = $fila.find('td').eq(2).text().toLowerCase();
                    const textoCompleto = codigo + ' ' + descrip + ' ' + marca;

                    const coincide = tokens.length === 0 || tokens.every(token => textoCompleto.includes(token));
                    if (coincide) { $fila.show(); visibles++; } else { $fila.hide(); }
                });

                $('#sinResultados').remove();
                if (visibles === 0 && tokens.length > 0) {
                    $('#productosTableBody').append(
                        '<tr id="sinResultados"><td colspan="12" class="text-center py-4">' +
                        '<i class="ri-search-eye-line fs-1 text-muted"></i>' +
                        '<p class="text-muted mt-2">No se encontraron productos para: <strong>' +
                        termino + '</strong></p></td></tr>'
                    );
                }
            }

            let busquedaTimer;
            $('#busquedaProductos').on('input', function() {
                clearTimeout(busquedaTimer);
                busquedaTimer = setTimeout(filtrarProductos, 200);
            });
            $('#limpiarBusqueda').on('click', function() {
                $('#busquedaProductos').val('');
                filtrarProductos();
            });
            filtrarProductos();

            // DETALLE COMPRA
            $('.ver-compra').click(function() {
                const compraId = $(this).data('id');
                const $modalBody = $('#compraModalBody');
                $modalBody.html(`<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Cargando...</p></div>`);

                $.ajax({
                    url: '{{ route("compras.documento-ajax") }}',
                    type: 'POST',
                    data: { id: compraId, _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) mostrarDetalleCompra(response.compra, response.items);
                        else $modalBody.html('<div class="alert alert-danger"><i class="ri-error-warning-line"></i> Error al cargar el detalle</div>');
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        $modalBody.html('<div class="alert alert-danger"><i class="ri-error-warning-line"></i> Error al cargar el detalle</div>');
                    }
                });
            });

            function mostrarDetalleCompra(compra, items) {
                const signo = compra.tipocom == 'I' ? -1 : 1;
                const tipoTexto = compra.tipocom == 'H' ? 'Compra' : 'Devolución';
                const tipoColor = compra.tipocom == 'H' ? 'success' : 'danger';

                let totalCalculado = 0, totalUnidades = 0;
                items.forEach(function(item) {
                    totalCalculado += item.cantidad * item.preciod;
                    totalUnidades += item.cantidad;
                });

                let itemsHtml = '';
                items.forEach(function(item) {
                    const itemTotal = item.cantidad * item.preciod;
                    itemsHtml += `
                        <tr>
                            <td><small>${item.coditem}</small></td>
                            <td>${item.descrip1 || 'Sin descripción'}</td>
                            <td class="text-center">${item.cantidad}</td>
                            <td class="text-end">$${Number(item.preciod).toFixed(2)}</td>
                            <td class="text-end">$${Number(item.costod).toFixed(2)}</td>
                            <td class="text-end">$${Number(item.costod2).toFixed(2)}</td>
                            <td class="text-end">$${Number(item.costod3).toFixed(2)}</td>
                            <td class="text-end">$${itemTotal.toFixed(2)}</td>
                        </tr>`;
                });

                const html = `
                    <div class="mb-3 p-3 bg-light rounded">
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Documento:</strong> ${compra.numerod}</p>
                                <p><strong>Fecha:</strong> ${new Date(compra.fechae).toLocaleDateString('es-VE')}</p>
                                <p><strong>Tipo:</strong> <span class="badge bg-${tipoColor}">${tipoTexto}</span></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Proveedor:</strong> ${compra.codprov}</p>
                                <p><strong>Sucursal:</strong> ${compra.sucursal ? compra.sucursal.descrip : 'N/A'}</p>
                                <p><strong>Total Unidades:</strong> <span class="fw-bold">${totalUnidades}</span></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Total monto:</strong> <span class="fw-bold">$${totalCalculado.toFixed(2)}</span></p>
                            </div>
                        </div>
                        ${compra.descrip ? `<div class="mt-2"><strong>Observaciones:</strong> ${compra.descrip}</div>` : ''}
                    </div>
                    <h6 class="mb-2">Productos</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-end">Costo</th>
                                    <th class="text-end">Costo2</th>
                                    <th class="text-end">Costo3</th>
                                    <th class="text-end">Total Item</th>
                                </tr>
                            </thead>
                            <tbody>${itemsHtml}</tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="3" class="text-end">TOTALES:</td>
                                    <td class="text-end">-</td>
                                    <td class="text-end">-</td>
                                    <td class="text-end">-</td>
                                    <td class="text-end">-</td>
                                    <td class="text-end ${signo < 0 ? 'text-danger' : 'text-success'}">$${totalCalculado.toFixed(2)}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>`;
                $('#compraModalBody').html(html);
            }

            // GRÁFICOS
            @if(isset($top_productos) && count($top_productos) > 0)
                try {
                var ctx = document.getElementById('topProductosChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [@foreach($top_productos as $item) '{{ Str::limit($item->descrip, 15) }}', @endforeach],
                        datasets: [{
                            label: 'Unidades Vendidas',
                            data: [@foreach($top_productos as $item) {{ $item->unidades_vendidas ?? 0 }}, @endforeach],
                            backgroundColor: '#667eea', borderColor: '#5a67d8', borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        scales: { y: { beginAtZero: true, ticks: { callback: function(v) { return v.toLocaleString() + ' und'; } } } },
                        plugins: { tooltip: { callbacks: { label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.raw || 0;
                                        let total = {{ $top_productos->sum('unidades_vendidas') }};
                                        let p = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value.toLocaleString() + ' und (' + p + '%)';
                                    }}}}
                    }
                });
            } catch (e) {
                console.error(e);
                $('#topProductosChart').parent().html('<div class="alert alert-danger">Error al generar el gráfico</div>');
            }
            @else
            $('#topProductosChart').parent().html('<div class="alert alert-info">No hay ventas en el período seleccionado</div>');
            @endif

                @if(isset($kpi) && ($kpi['total_ventas'] > 0 || $kpi['valor_inventario'] > 0 || $kpi['total_compras'] > 0))
                try {
                var ctx2 = document.getElementById('distribucionChart').getContext('2d');
                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: ['Ventas', 'Stock', 'Compras'],
                        datasets: [{
                            data: [{{ $kpi['total_ventas'] ?? 0 }}, {{ $kpi['valor_inventario'] ?? 0 }}, {{ $kpi['total_compras'] ?? 0 }}],
                            backgroundColor: ['#28a745', '#ffc107', '#667eea'], borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { tooltip: { callbacks: { label: function(context) {
                                        let label = context.label || '';
                                        let value = context.raw || 0;
                                        return label + ': $' + value.toLocaleString();
                                    }}}}
                    }
                });
            } catch (e) {
                console.error(e);
                $('#distribucionChart').parent().html('<div class="alert alert-danger">Error al generar el gráfico</div>');
            }
            @else
            $('#distribucionChart').parent().html('<div class="alert alert-info">No hay datos suficientes para mostrar el gráfico</div>');
            @endif

            // ORDENAMIENTO
            let currentSort = { column: 'stock', direction: 'desc' };

            $('.sortable').click(function() {
                const column = $(this).data('sort');
                const isAsc = currentSort.column === column && currentSort.direction === 'asc';
                const direction = isAsc ? 'desc' : 'asc';
                $('.sortable i').removeClass('ri-arrow-up-line ri-arrow-down-line').addClass('ri-arrow-up-down-line');
                $(this).find('i').removeClass('ri-arrow-up-down-line').addClass(direction === 'asc' ? 'ri-arrow-up-line' : 'ri-arrow-down-line');
                sortTable(column, direction);
                currentSort.column = column;
                currentSort.direction = direction;
            });

            function getColumnIndexBySort(column) {
                let idx = -1;
                $('#productosTable thead th').each(function(i) {
                    if ($(this).data('sort') === column) { idx = i; return false; }
                });
                return idx;
            }

            function sortTable(column, direction) {
                const tbody = $('#productosTableBody');
                const rows = tbody.find('tr').get();
                const idx = getColumnIndexBySort(column);
                if (idx === -1) return;

                rows.sort(function(a, b) {
                    if ($(a).find('td[colspan]').length > 0) return 0;
                    if ($(b).find('td[colspan]').length > 0) return 0;

                    let aVal, bVal;
                    switch(column) {
                        case 'codprod':
                            aVal = $(a).find('td').eq(idx).text().trim();
                            bVal = $(b).find('td').eq(idx).text().trim();
                            break;
                        case 'descrip':
                        case 'marca':
                            aVal = $(a).find('td').eq(idx).text().trim().toLowerCase();
                            bVal = $(b).find('td').eq(idx).text().trim().toLowerCase();
                            break;
                        case 'unidades_compradas':
                        case 'unidades_vendidas':
                        case 'stock':
                            aVal = parseFloat($(a).find('td').eq(idx).text().replace(/\./g, '').replace(',', '.')) || 0;
                            bVal = parseFloat($(b).find('td').eq(idx).text().replace(/\./g, '').replace(',', '.')) || 0;
                            break;
                        case 'dias_sin_venta':
                            let aT = $(a).find('td').eq(idx).text().trim();
                            let bT = $(b).find('td').eq(idx).text().trim();
                            aVal = aT === 'Nunca' ? 999999 : (parseInt(aT) || 0);
                            bVal = bT === 'Nunca' ? 999999 : (parseInt(bT) || 0);
                            break;
                        case 'dias_stock':
                            aVal = parseInt($(a).find('td').eq(idx).text()) || 0;
                            bVal = parseInt($(b).find('td').eq(idx).text()) || 0;
                            break;
                        case 'ganancia':
                            aVal = parseFloat($(a).find('td').eq(idx).text().replace(/[$,]/g, '')) || 0;
                            bVal = parseFloat($(b).find('td').eq(idx).text().replace(/[$,]/g, '')) || 0;
                            break;
                        case 'margen':
                            aVal = parseFloat($(a).find('td').eq(idx).text().replace(/[%,]/g, '')) || 0;
                            bVal = parseFloat($(b).find('td').eq(idx).text().replace(/[%,]/g, '')) || 0;
                            break;
                        case 'ultima_compra':
                            const aFe = $(a).find('td').eq(idx).find('small.text-muted').first();
                            const bFe = $(b).find('td').eq(idx).find('small.text-muted').first();
                            let aS = aFe.text().trim().split(' ')[0];
                            let bS = bFe.text().trim().split(' ')[0];
                            aVal = 0; bVal = 0;
                            if (aS && aS.match(/\d{2}\/\d{2}\/\d{4}/)) {
                                const [aD, aM, aY] = aS.split('/');
                                aVal = new Date(aY, aM - 1, aD).getTime();
                            }
                            if (bS && bS.match(/\d{2}\/\d{2}\/\d{4}/)) {
                                const [bD, bM, bY] = bS.split('/');
                                bVal = new Date(bY, bM - 1, bD).getTime();
                            }
                            break;
                        default: return 0;
                    }
                    if (direction === 'asc') return aVal > bVal ? 1 : aVal < bVal ? -1 : 0;
                    else return aVal < bVal ? 1 : aVal > bVal ? -1 : 0;
                });

                $.each(rows, function(index, row) { tbody.append(row); });
            }

            // EXISTENCIAS
            $('.ver-existencias').click(function() {
                const codprod = $(this).data('codprod');
                const descrip = $(this).data('descrip');
                $('#modalProductoInfo').html(`<strong>Producto: ${descrip} (${codprod})</strong>`);
                $('#existenciasTableBody').html('<tr><td colspan="2" class="text-center">Cargando existencias...</td></tr>');

                $.ajax({
                    url: '{{ route("saprod.listprodubiccompany") }}',
                    type: 'POST',
                    data: { codprod: codprod, _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success && response.existencias.length > 0) {
                            let html = '';
                            response.existencias.forEach(function(item) {
                                html += `<tr><td>${item.deposito.descrip}</td><td class="text-end">${Number(item.existen).toLocaleString('es-VE', {minimumFractionDigits: 0, maximumFractionDigits: 0})}</td></tr>`;
                            });
                            $('#existenciasTableBody').html(html);
                        } else {
                            $('#existenciasTableBody').html('<tr><td colspan="2" class="text-center text-muted">No hay existencias en ningún depósito</td></tr>');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        $('#existenciasTableBody').html('<tr><td colspan="2" class="text-center text-danger">Error al cargar existencias</td></tr>');
                    }
                });
            });

            if (typeof bootstrap !== 'undefined') {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(el) { return new bootstrap.Tooltip(el); });
            }

            // MODAL EDICIÓN PRODUCTO
            const modal = new bootstrap.Modal(document.getElementById('productoEditModal'));

            $(document).on('click', '.btn-editar-producto', function(e) {
                e.preventDefault();
                const codprod = $(this).data('id');
                Swal.fire({ title: 'Cargando...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

                $.ajax({
                    url: '/productos/' + codprod + '/datos-edit',
                    type: 'GET',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        Swal.close();
                        if (response.success) {
                            const producto = response.producto;
                            $('#editProductoId').val(producto.id);
                            $('#editProductoCodigo').text(producto.codprod);
                            $('#modalProductoNombre').text(producto.descrip);
                            $('#editDescrip').val(producto.descrip);
                            $('#editRefere').val(producto.refere || '');
                            $('#editMarca').val(producto.marca || '');
                            $('#editPreciodant').val(producto.preciodant || 0);
                            $('#editPreciodpro').val(producto.preciodpro || 0);
                            $('#editPreciod').val(producto.preciod || 0);
                            $('#editCostod').val(producto.costod || 0);
                            $('#editCostod2').val(producto.costod2 || 0);
                            $('#editCostod3').val(producto.costod3 || 0);
                            $('#editInstancia').val(producto.instancia_descrip || 'Sin instancia');
                            $('#editExistencia').val(producto.existencia_total || 0);
                            const pv = parseFloat(producto.costod3 || 0);
                            const pc = parseFloat(producto.preciod || 0);
                            const mc = pc > 0 ? ((pv - pc) / pc * 100) : 0;
                            $('#editMargen').val(mc.toFixed(1) + '%');
                            modal.show();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: response.message || 'No se pudieron cargar los datos' });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Error al cargar los datos' });
                        console.error(xhr);
                    }
                });
            });

            $('#productoEditForm').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                const submitBtn = $('#btnGuardarProducto');
                const originalText = submitBtn.html();
                submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Guardando...');

                $.ajax({
                    url: '{{ route("proveedores.producto.quick-update") }}',
                    type: 'POST',
                    data: formData,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({ icon: 'success', title: '¡Actualizado!', text: response.message, timer: 2000, showConfirmButton: false });
                            const codprod = $('#editProductoCodigo').text();
                            const fila = $('.btn-editar-producto[data-id="' + codprod + '"]').closest('tr');
                            if (fila.length) {
                                fila.find('td').eq(1).text($('#editDescrip').val());
                                const costo = parseFloat($('#editPreciod').val() || 0);
                                const precioVenta = parseFloat($('#editCostod3').val() || 0);
                                const ganancia = precioVenta - costo;
                                const margen = costo > 0 ? ((precioVenta - costo) / costo) * 100 : 0;
                                fila.find('td').eq(8)
                                    .removeClass('text-danger text-success')
                                    .addClass(ganancia < 0 ? 'text-danger' : 'text-success')
                                    .text('$' + ganancia.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                                let badgeClass = 'bg-secondary';
                                if (margen > 0) badgeClass = 'bg-success';
                                else if (margen < 0) badgeClass = 'bg-danger';
                                fila.find('td').eq(9).html('<span class="badge ' + badgeClass + '">' + margen.toFixed(1) + '%</span>');
                            }
                            setTimeout(() => modal.hide(), 500);
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: response.message });
                        }
                    },
                    error: function(xhr) {
                        let mensaje = 'Error al actualizar';
                        if (xhr.responseJSON && xhr.responseJSON.message) mensaje = xhr.responseJSON.message;
                        Swal.fire({ icon: 'error', title: 'Error', text: mensaje });
                        console.error(xhr);
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            $('#productoEditModal').on('hidden.bs.modal', function() {
                $('#productoEditForm')[0].reset();
                $('#editProductoId').val('');
                $('#editProductoCodigo').text('');
                $('#modalProductoNombre').text('');
                $('#editInstancia').val('');
            });
        });
    </script>
@endsection
