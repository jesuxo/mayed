<style>
    /* ========================================== */
    /* ESTILOS OPTIMIZADOS PARA EXISTENCIAS POR DEPÓSITO */
    /* ========================================== */
    .table-existencias {
        font-size: 0.82rem;
        width: 100%;
        border-collapse: collapse;
    }

    .table-existencias thead th {
        background: #0072c5 !important;
        color: #fff !important;
        padding: 8px 6px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border: 1px solid #0056a7;
        position: sticky;
        top: 0;
        z-index: 10;
        white-space: nowrap;
    }

    .table-existencias thead th:first-child {
        border-radius: 6px 0 0 0;
    }

    .table-existencias thead th:last-child {
        border-radius: 0 6px 0 0;
    }

    .table-existencias tbody td {
        padding: 5px 6px;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
        font-size: 0.8rem;
    }

    .table-existencias tbody tr:hover {
        background-color: #e6f3ff !important;
        transition: background 0.2s ease;
    }

    .table-existencias tbody tr:last-child td {
        border-bottom: none;
    }

    .table-existencias .fila-total {
        background: #0072c5 !important;
        color: #fff !important;
        font-weight: 700;
        border-top: 2px solid #0056a7;
    }

    .table-existencias .fila-total td {
        padding: 6px 6px;
        font-size: 0.85rem;
        border-top: 2px solid #0056a7;
    }

    .table-existencias .fila-total td:first-child {
        border-radius: 0 0 0 6px;
    }

    .table-existencias .fila-total td:last-child {
        border-radius: 0 0 6px 0;
    }

    .table-existencias .badge-cantidad {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        min-width: 30px;
        text-align: center;
    }

    .badge-cantidad.positivo {
        background: #d4edda;
        color: #155724;
    }

    .badge-cantidad.cero {
        background: #f8f9fa;
        color: #6c757d;
    }

    .badge-cantidad.destacado {
        background: #cce5ff;
        color: #004085;
        font-weight: 700;
    }

    .table-existencias .codigo-prod {
        font-weight: 600;
        color: #0072c5;
        font-size: 0.75rem;
    }

    .table-existencias .nombre-prod {
        font-weight: 500;
        color: #2c3e50;
    }

    .table-existencias .descrip2-prod {
        font-size: 0.7rem;
        color: #6c757d;
    }

    .table-existencias .total-unds {
        font-weight: 700;
        color: #0072c5;
        text-align: center;
    }

    .table-existencias .total-costo {
        font-weight: 700;
        color: #28a745;
        text-align: right;
    }

    .table-existencias .total-unds-footer {
        font-weight: 700;
        color: #fff;
        text-align: center;
    }

    .table-existencias .total-costo-footer {
        font-weight: 700;
        color: #ffd700;
        text-align: right;
        font-size: 0.9rem;
    }

    .table-existencias .celda-vacia {
        color: #ddd;
        font-size: 0.7rem;
    }

    /* Scroll personalizado */
    .scroll-existencias::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .scroll-existencias::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .scroll-existencias::-webkit-scrollbar-thumb {
        background: #0072c5;
        border-radius: 4px;
    }

    .scroll-existencias::-webkit-scrollbar-thumb:hover {
        background: #0056a7;
    }

    /* Resumen rápido */
    .resumen-cards {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }

    .resumen-card {
        background: #fff;
        border-radius: 10px;
        padding: 12px 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border-left: 4px solid #0072c5;
        flex: 1;
        min-width: 150px;
    }

    .resumen-card .label {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #6c757d;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .resumen-card .value {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2c3e50;
    }

    .resumen-card .value.primary { color: #0072c5; }
    .resumen-card .value.success { color: #28a745; }
    .resumen-card .value.warning { color: #ffc107; }
    .resumen-card .value.danger { color: #dc3545; }

    .resumen-card.border-primary { border-left-color: #0072c5; }
    .resumen-card.border-success { border-left-color: #28a745; }
    .resumen-card.border-warning { border-left-color: #ffc107; }
    .resumen-card.border-danger { border-left-color: #dc3545; }

    /* Responsive */
    @media (max-width: 768px) {
        .table-existencias {
            font-size: 0.7rem;
        }

        .table-existencias thead th,
        .table-existencias tbody td {
            padding: 3px 4px;
        }

        .table-existencias .badge-cantidad {
            padding: 1px 6px;
            font-size: 0.65rem;
            min-width: 20px;
        }

        .resumen-cards {
            gap: 8px;
        }

        .resumen-card {
            padding: 8px 12px;
            min-width: 80px;
        }

        .resumen-card .value {
            font-size: 0.9rem;
        }
    }

    /* Modo oscuro para impresión */
    @media print {
        .table-existencias thead th {
            background: #0072c5 !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .table-existencias .fila-total {
            background: #0072c5 !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .table-existencias .badge-cantidad.positivo {
            background: #d4edda !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .resumen-card {
            border: 1px solid #ddd !important;
        }
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-height-100">
            <div class="card-header align-items-center d-flex justify-content-between">
                <h4 class="card-title mb-0">
                    <i class="bi bi-box-seam text-primary me-2"></i>
                    EXISTENCIAS POR DEPÓSITO
                </h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary" onclick="exportarExcel()">
                        <i class="bi bi-download"></i> Excel
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
                </div>
            </div>

            <!-- Resumen Rápido -->
            <div class="card-body pb-0">
                <div class="resumen-cards">
                    <div class="resumen-card border-primary">
                        <div class="label">Total Productos</div>
                        <div class="value primary">{{ count($productos) }}</div>
                    </div>
                    <div class="resumen-card border-success">
                        <div class="label">Total Unidades</div>
                        <div class="value success">{{ number_format($existdepstt ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="resumen-card border-warning">
                        <div class="label">Depósitos</div>
                        <div class="value warning">{{ count($deposito) }}</div>
                    </div>
                    <div class="resumen-card border-danger">
                        <div class="label">Costo Total</div>
                        <div class="value danger">$ {{ number_format($totalcost ?? 0, 2, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <!-- Tabla -->
            <div class="card-body pt-2">
                <div class="scroll-existencias" style="max-height: 490px; overflow: auto;">
                    <table class="table-existencias table table-borderless table-centered align-middle table-nowrap mb-0">
                        <thead>
                        <tr>
                            <th width="4%">CÓD</th>
                            <th width="20%">PRODUCTO</th>
                            <th width="9%"> </th>
                            @foreach($deposito as $indexdep => $descripdepo)
                                <th width="13%" class="text-center">
                                        <span data-bs-toggle="tooltip" title="{{ $descripdepo }}">
                                            {{ Str::limit($descripdepo, 12) }}
                                        </span>
                                </th>
                            @endforeach
                            <th width="9%" class="text-center">UNDS</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $tantos = 0;
                            $totalcost = 0;
                            $existdepstt = 0;
                            $arraycantdep = array_fill(0, count($deposito), 0);
                            $maxUnidades = 0;
                        @endphp

                        @foreach($productos as $index => $producto)
                            @php
                                $tantos++;
                                $bgcolor = ($tantos % 2 == 0) ? '#ffffff' : '#f8f9fa';
                                $existdeps = 0;
                                $tieneStock = false;
                            @endphp

                            <tr bgcolor="{{ $bgcolor }}" style="color:#2c3e50;">
                                <td align="center" class="codigo-prod">{{ $index }}</td>
                                <td align="left" class="nombre-prod">{{ $producto['descrip'] ?? '' }}</td>
                                <td align="right" class="descrip2-prod">{{ $producto['descrip2'] ?? '' }}</td>

                                @foreach($deposito as $indexdep => $descripdepo)
                                    @php
                                        $cantidad = $existencias[$index][$indexdep] ?? 0;
                                        if($cantidad > 0) {
                                            $arraycantdep[$indexdep] += $cantidad;
                                            $existdeps += $cantidad;
                                            $existdepstt += $cantidad;
                                            $totalcost += $cantidad * ($producto['preciodpro'] ?? 0);
                                            $tieneStock = true;
                                            if($cantidad > $maxUnidades) $maxUnidades = $cantidad;
                                        }
                                    @endphp
                                    <td align="center">
                                        @if($cantidad > 0)
                                            <span class="badge-cantidad {{ $cantidad > 10 ? 'destacado' : 'positivo' }}">
                                                    {{ number_format($cantidad, 0, ',', '.') }}
                                                </span>
                                        @else
                                            <span class="celda-vacia">-</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td align="center" class="total-unds">{{ number_format($existdeps, 0, ',', '.') }}</td>

                            </tr>
                        @endforeach

                        <!-- FILA DE TOTALES -->
                        <tr class="fila-total">
                            <td colspan="3" align="right">
                                <strong>TOTALES</strong>
                            </td>
                            @foreach($deposito as $indexdep => $descripdepo)
                                <td align="center" class="total-unds-footer">
                                    {{ isset($arraycantdep[$indexdep]) && $arraycantdep[$indexdep] > 0
                                        ? number_format($arraycantdep[$indexdep], 0, ',', '.')
                                        : '-' }}
                                </td>
                            @endforeach
                            <td align="center" class="total-unds-footer">
                                {{ number_format($existdepstt, 0, ',', '.') }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // ==========================================
    // FUNCIONES ADICIONALES
    // ==========================================

    // Exportar a Excel
    function exportarExcel() {
        var table = document.querySelector('.table-existencias');
        var html = table.outerHTML;

        // Agregar estilos para Excel
        var styles = `
            <style>
                th { background: #0072c5 !important; color: #fff !important; }
                .fila-total { background: #0072c5 !important; color: #fff !important; }
                .badge-cantidad.positivo { background: #d4edda !important; }
                .badge-cantidad.destacado { background: #cce5ff !important; }
            </style>
        `;

        var fullHtml = `
            <html>
                <head>
                    <meta charset="UTF-8">
                    ${styles}
                </head>
                <body>
                    <h3>EXISTENCIAS POR DEPÓSITO</h3>
                    <p>Fecha: ${new Date().toLocaleDateString('es-VE')}</p>
                    ${html}
                </body>
            </html>
        `;

        var blob = new Blob([fullHtml], { type: 'application/vnd.ms-excel' });
        var link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'existencias_deposito_' + new Date().toISOString().slice(0,10) + '.xls';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Resaltar filas con stock
    document.querySelectorAll('.table-existencias tbody tr').forEach(function(row) {
        var celdas = row.querySelectorAll('td');
        var tieneStock = false;
        // Verificar celdas de depósitos (excluyendo las primeras 3 y las últimas 2)
        for(var i = 3; i < celdas.length - 2; i++) {
            if(celdas[i].textContent.trim() !== '-' && celdas[i].textContent.trim() !== '') {
                tieneStock = true;
                break;
            }
        }
        if(tieneStock) {
            row.style.borderLeft = '3px solid #28a745';
        }
    });
</script>
