<style>
    /* ========================================== */
    /* ESTILOS MODERNOS PARA EXISTENCIAS POR DEPÓSITO */
    /* ========================================== */
    .card-existencias {
        border-radius: 12px;
        overflow: hidden;
    }

    .card-existencias .card-header {
        color: #fff;
        padding: 0px;
        border-bottom: none;
    }

    .card-existencias .card-header h4 {
        font-weight: 700;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
    }

    .card-existencias .card-header h4 i {
        font-size: 1.3rem;
        margin-right: 10px;
    }

    .table-existencias {
        font-size: 0.82rem;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-existencias thead th {
        background: #0072c5 !important;
        color: #fff !important;
        padding: 10px 8px;
        font-weight: 600;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        position: sticky;
        top: 0;
        z-index: 10;
        white-space: nowrap;
    }

    .table-existencias thead th:first-child {
        border-radius: 8px 0 0 0;
        padding-left: 16px;
    }

    .table-existencias thead th:last-child {
        border-radius: 0 8px 0 0;
        padding-right: 16px;
    }

    .table-existencias tbody td {
        padding: 8px 8px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f2f5;
        font-size: 0.8rem;
        transition: background 0.15s ease;
    }

    .table-existencias tbody td:first-child {
        padding-left: 16px;
    }

    .table-existencias tbody td:last-child {
        padding-right: 16px;
    }

    .table-existencias tbody tr {
        transition: background 0.15s ease;
    }

    .table-existencias tbody tr:hover {
        background: #f0f7ff !important;
    }

    .table-existencias tbody tr:last-child td {
        border-bottom: none;
    }

    /* Fila de totales */
    .table-existencias .fila-total {
        background: linear-gradient(135deg, #0072c5 0%, #0056a7 100%) !important;
        color: #fff !important;
        font-weight: 700;
    }

    .table-existencias .fila-total td {
        padding: 10px 8px;
        font-size: 0.85rem;
        border-top: 2px solid #0056a7;
        color: #fff !important;
    }

    .table-existencias .fila-total td:first-child {
        border-radius: 0 0 0 8px;
    }

    .table-existencias .fila-total td:last-child {
        border-radius: 0 0 8px 0;
    }

    /* Badges de cantidad */
    .badge-cantidad {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        min-width: 32px;
        text-align: center;
        transition: all 0.2s ease;
    }

    .badge-cantidad.positivo {
        background: #d4edda;
        color: #155724;
    }

    .badge-cantidad.cero {
        background: #f8f9fa;
        color: #adb5bd;
    }

    .badge-cantidad.alto {
        background: #cce5ff;
        color: #004085;
        font-weight: 700;
    }

    .badge-cantidad.muy-alto {
        background: #fff3cd;
        color: #856404;
        font-weight: 700;
        animation: pulse-badge 2s infinite;
    }

    @keyframes pulse-badge {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    /* Código de producto */
    .codigo-prod {
        font-weight: 700;
        color: #0072c5;
        font-size: 0.75rem;
        font-family: 'Courier New', monospace;
    }

    .nombre-prod {
        font-weight: 500;
        color: #2c3e50;
    }

    .descrip2-prod {
        font-size: 0.7rem;
        color: #6c757d;
    }

    .total-unds {
        font-weight: 700;
        color: #0072c5;
        text-align: center;
        font-size: 0.85rem;
    }

    .total-unds-footer {
        font-weight: 700;
        color: #ffd700;
        text-align: center;
        font-size: 0.95rem;
    }

    .total-costo-footer {
        font-weight: 700;
        color: #ffd700;
        text-align: right;
        font-size: 0.95rem;
    }

    .celda-vacia {
        color: #dee2e6;
        font-size: 0.7rem;
    }

    /* Scroll personalizado */
    .scroll-existencias::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .scroll-existencias::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 8px;
    }

    .scroll-existencias::-webkit-scrollbar-thumb {
        background: #0072c5;
        border-radius: 8px;
    }

    .scroll-existencias::-webkit-scrollbar-thumb:hover {
        background: #0056a7;
    }

    /* Resumen rápido */
    .resumen-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }

    .resumen-item {
        background: #fff;
        border-radius: 10px;
        padding: 12px 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        border: 1px solid #e9ecef;
        text-align: center;
        transition: all 0.2s ease;
    }

    .resumen-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .resumen-item .label {
        font-size: 0.65rem;
        text-transform: uppercase;
        color: #6c757d;
        font-weight: 600;
        letter-spacing: 0.5px;
        display: block;
    }

    .resumen-item .value {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2c3e50;
        margin-top: 2px;
    }

    .resumen-item .value.primary { color: #0072c5; }
    .resumen-item .value.success { color: #28a745; }
    .resumen-item .value.warning { color: #ffc107; }
    .resumen-item .value.danger { color: #dc3545; }

    /* Botones de acción */
    .btn-accion {
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        border: none;
        transition: all 0.2s ease;
        background: rgba(255,255,255,0.15);
        color: #fff;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-accion:hover {
        background: rgba(255,255,255,0.25);
        color: #fff;
        transform: translateY(-1px);
    }

    /* Depósito nombre abreviado con tooltip */
    .deposito-tooltip {
        cursor: help;
        border-bottom: 1px dashed rgba(255,255,255,0.3);
        display: inline-block;
    }

    /* ========================================== */
    /* FILTRO DE DEPÓSITOS */
    /* ========================================== */
    .deposito-filter {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 15px;
        border: 1px solid #e9ecef;
    }

    .deposito-filter .filter-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        display: block;
    }

    .deposito-filter .deposito-check {
        display: inline-flex;
        align-items: center;
        margin-right: 10px;
        margin-bottom: 5px;
        cursor: pointer;
        padding: 4px 12px;
        border-radius: 20px;
        background: #fff;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease;
        font-size: 0.75rem;
    }

    .deposito-filter .deposito-check:hover {
        background: #e9ecef;
    }

    .deposito-filter .deposito-check input[type="checkbox"] {
        margin-right: 6px;
        accent-color: #0072c5;
        width: 14px;
        height: 14px;
        cursor: pointer;
    }

    .deposito-filter .deposito-check.checked {
        background: #cce5ff;
        border-color: #0072c5;
    }

    .deposito-filter .btn-aplicar {
        background: #0072c5;
        color: #fff;
        border: none;
        padding: 5px 20px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .deposito-filter .btn-aplicar:hover {
        background: #0056a7;
    }

    .deposito-filter .btn-seleccionar-todos {
        background: #6c757d;
        color: #fff;
        border: none;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.7rem;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-right: 5px;
    }

    .deposito-filter .btn-seleccionar-todos:hover {
        background: #5a6268;
    }

    .deposito-filter .btn-seleccionar-todos.verde {
        background: #28a745;
    }

    .deposito-filter .btn-seleccionar-todos.verde:hover {
        background: #218838;
    }

    .deposito-filter .btn-seleccionar-todos.rojo {
        background: #dc3545;
    }

    .deposito-filter .btn-seleccionar-todos.rojo:hover {
        background: #c82333;
    }

    .deposito-filter .badge-depositos {
        background: #0072c5;
        color: #fff;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 0.7rem;
        margin-left: 8px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .table-existencias {
            font-size: 0.7rem;
        }

        .table-existencias thead th,
        .table-existencias tbody td {
            padding: 4px 4px;
        }

        .table-existencias thead th:first-child,
        .table-existencias tbody td:first-child {
            padding-left: 8px;
        }

        .table-existencias thead th:last-child,
        .table-existencias tbody td:last-child {
            padding-right: 8px;
        }

        .badge-cantidad {
            padding: 2px 6px;
            font-size: 0.65rem;
            min-width: 20px;
        }

        .resumen-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .resumen-item {
            padding: 8px 10px;
        }

        .resumen-item .value {
            font-size: 1rem;
        }

        .card-existencias .card-header {
            padding: 12px 16px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .card-existencias .card-header h4 {
            font-size: 0.95rem;
        }

        .btn-accion {
            padding: 3px 10px;
            font-size: 0.65rem;
        }

        .deposito-filter {
            padding: 8px 10px;
        }

        .deposito-filter .deposito-check {
            font-size: 0.65rem;
            padding: 2px 8px;
            margin-right: 5px;
        }

        .deposito-filter .btn-aplicar,
        .deposito-filter .btn-seleccionar-todos {
            font-size: 0.65rem;
            padding: 3px 12px;
        }
    }

    /* Modo impresión */
    @media print {
        .card-existencias {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }

        .card-existencias .card-header {
            background: #0072c5 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

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

        .badge-cantidad.positivo {
            background: #d4edda !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .badge-cantidad.alto {
            background: #cce5ff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .badge-cantidad.muy-alto {
            background: #fff3cd !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .resumen-item {
            border: 1px solid #ddd !important;
        }

        .btn-accion {
            display: none !important;
        }

        .scroll-existencias {
            max-height: none !important;
            overflow: visible !important;
        }

        .deposito-filter {
            display: none !important;
        }
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-existencias">
            <div class="card-header align-items-center d-flex justify-content-between flex-wrap">
                <div class="d-flex gap-2 flex-wrap" style="margin-bottom: 20px">
                    <span class="badge bg-primary">
                        <i class="bi bi-tag"></i> {{ count($productos) }} Productos
                    </span>
                    <span class="badge bg-success">
                        <i class="bi bi-boxes"></i>
                        <span id="totalDepositos">{{ count($depositoValues) }}</span> Depósitos
                    </span>
                    <span class="badge bg-warning text-dark" id="htmlunds">
                        <i class="bi bi-box"></i> {{ $existdepstt+0 }} Unds
                    </span>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- FILTRO DE DEPÓSITOS -->
            <!-- ========================================== -->
            <div class="card-body" style="padding: 0px 16px; padding-top: 10px;">
                <div class="deposito-filter">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <span class="filter-label">
                                <i class="bi bi-funnel"></i> Filtrar por Depósito:
                                <span class="badge-depositos" id="contadorSeleccionados">
                                    {{ count($depositoValues) }} seleccionados
                                </span>
                            </span>
                            <div id="depositosCheckboxes">
                                @foreach($deposito as $key => $nombre)
                                    @php
                                        $checked = in_array($key, $depositosSeleccionados ?? array_keys($deposito));
                                    @endphp
                                    <label class="deposito-check {{ $checked ? 'checked' : '' }}">
                                        <input type="checkbox"
                                               class="deposito-checkbox"
                                               value="{{ $key }}"
                                            {{ $checked ? 'checked' : '' }}>
                                        {{ Str::limit($nombre, 20) }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap mt-2 mt-md-0">
                            <button class="btn-seleccionar-todos verde" onclick="seleccionarTodos()">
                                <i class="bi bi-check-all"></i> Todos
                            </button>
                            <button class="btn-seleccionar-todos rojo" onclick="deseleccionarTodos()">
                                <i class="bi bi-x"></i> Ninguno
                            </button>
                            <button class="btn-aplicar" onclick="aplicarFiltro()">
                                <i class="bi bi-check2"></i> Aplicar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla -->
            <div class="card-body" style="margin: 0px; padding: 0px 16px 16px 16px;">
                <div class="scroll-existencias" style="max-height: 490px; overflow: auto;">
                    <table class="table-existencias table table-borderless table-centered align-middle table-nowrap mb-0" id="tablaExistencias">
                        <thead>
                        <tr>
                            <th width="4%" class="text-center">CÓD</th>
                            <th width="20%">PRODUCTO</th>
                            <th width="9%" class="text-center">REF</th>
                            @foreach($depositoValues as $indexDep => $descripdepo)
                                <th width="13%" class="text-center">
                                        <span class="deposito-tooltip" title="{{ $descripdepo }}">
                                            {{ Str::limit($descripdepo, 14) }}
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
                            $arraycantdep = array_fill(0, count($depositoValues), 0);
                        @endphp

                        @forelse($productos as $index => $producto)
                            @php
                                $tantos++;
                                $bgcolor = ($tantos % 2 == 0) ? '#ffffff' : '#f8f9fa';
                                $existdeps = 0;
                                $tieneStock = false;
                            @endphp

                            <tr bgcolor="{{ $bgcolor }}" style="color:#2c3e50;">
                                <td align="left" class="codigo-prod">{{ $index }}</td>
                                <td align="left" class="nombre-prod">{{ $producto['descrip'] ?? '' }}</td>
                                <td align="left" class="descrip2-prod">
                                    {{ $producto['descrip2'] ?? '-' }}
                                </td>

                                @foreach($depositoKeys as $depIndex => $depKey)
                                    @php
                                        $cantidad = $existenciasFiltradas[$index][$depKey] ?? 0;
                                        if($cantidad > 0) {
                                            $arraycantdep[$depIndex] += $cantidad;
                                            $existdeps += $cantidad;
                                            $existdepstt += $cantidad;
                                            $totalcost += $cantidad * ($producto['preciodpro'] ?? 0);
                                            $tieneStock = true;
                                        }
                                    @endphp
                                    <td align="center">
                                        @if($cantidad > 0)
                                            @php
                                                $claseBadge = 'positivo';
                                                if($cantidad >= 50) $claseBadge = 'muy-alto';
                                                elseif($cantidad >= 20) $claseBadge = 'alto';
                                            @endphp
                                            <span class="badge-cantidad {{ $claseBadge }}">
                                                    {{ number_format($cantidad, 0, ',', '.') }}
                                                </span>
                                        @else
                                            <span class="celda-vacia">-</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td align="center" class="total-unds">
                                    {{ number_format($existdeps, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($depositoValues) + 3 }}" align="center" style="padding: 40px 0;">
                                    <i class="bi bi-inbox" style="font-size: 48px; color: #ccc;"></i>
                                    <h5 style="color: #6c757d; margin-top: 10px;">No hay productos con existencias</h5>
                                    <p style="color: #999; font-size: 0.85rem;">No se encontraron productos en esta categoría</p>
                                </td>
                            </tr>
                        @endforelse

                        @if(count($productos) > 0)
                            <!-- FILA DE TOTALES -->
                            <tr class="fila-total">
                                <td colspan="3" align="right">
                                    <strong>TOTALES</strong>
                                </td>
                                @foreach($arraycantdep as $totalDep)
                                    <td align="center" class="total-unds-footer">
                                        @if($totalDep > 0)
                                            {{ number_format($totalDep, 0, ',', '.') }}
                                        @else
                                            <span style="color: rgba(255,255,255,0.4);">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td align="center" class="total-unds-footer">
                                    <strong>{{ $existdepstt+0 }}</strong>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-2 text-muted" style="font-size: 0.7rem;">
                    <span>
                        <i class="bi bi-info-circle me-1"></i>
                        {{ count($productos) }} productos ·
                        <span id="footerDepositos">{{ count($depositoValues) }}</span> depósitos
                    </span>
                    <span>
                        Última actualización: {{ now()->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // ==========================================
    // FILTRO DE DEPÓSITOS
    // ==========================================

    // Actualizar contador de seleccionados
    function actualizarContador() {
        var checkboxes = document.querySelectorAll('.deposito-checkbox:checked');
        var total = document.querySelectorAll('.deposito-checkbox').length;
        document.getElementById('contadorSeleccionados').textContent = checkboxes.length + ' seleccionados';

        // Actualizar badges
        document.getElementById('totalDepositos').textContent = checkboxes.length;
        document.getElementById('footerDepositos').textContent = checkboxes.length;

        // Marcar/desmarcar estilo de los labels
        document.querySelectorAll('.deposito-check').forEach(function(label) {
            var checkbox = label.querySelector('.deposito-checkbox');
            if(checkbox.checked) {
                label.classList.add('checked');
            } else {
                label.classList.remove('checked');
            }
        });
    }

    // Seleccionar todos
    function seleccionarTodos() {
        document.querySelectorAll('.deposito-checkbox').forEach(function(cb) {
            cb.checked = true;
        });
        actualizarContador();
    }

    // Deseleccionar todos
    function deseleccionarTodos() {
        document.querySelectorAll('.deposito-checkbox').forEach(function(cb) {
            cb.checked = false;
        });
        actualizarContador();
    }

    // Aplicar filtro
    function aplicarFiltro() {
        var checkboxes = document.querySelectorAll('.deposito-checkbox:checked');
        var depositos = [];
        checkboxes.forEach(function(cb) {
            depositos.push(cb.value);
        });

        if(depositos.length === 0) {
            alert('Debe seleccionar al menos un depósito para visualizar.');
            return;
        }

        // Obtener parámetros actuales de la URL
        var urlParams = new URLSearchParams(window.location.search);
        var codalte = urlParams.get('codalte');
        var busqueda = urlParams.get('busqueda') || '';

        // Construir nueva URL
        var url = window.location.pathname + '?codalte=' + codalte + '&busqueda=' + encodeURIComponent(busqueda) + '&depositos=' + JSON.stringify(depositos);

        // Redirigir con los depósitos seleccionados
        window.location.href = url;
    }

    // Evento para los checkboxes (actualizar contador en tiempo real)
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.deposito-checkbox').forEach(function(cb) {
            cb.addEventListener('change', actualizarContador);
        });

        // Inicializar contador
        actualizarContador();

        // Resaltar filas con stock
        var rows = document.querySelectorAll('.table-existencias tbody tr:not(.fila-total)');
        rows.forEach(function(row) {
            var celdas = row.querySelectorAll('td');
            var tieneStock = false;
            for(var i = 3; i < celdas.length - 1; i++) {
                var texto = celdas[i].textContent.trim();
                if(texto !== '-' && texto !== '') {
                    tieneStock = true;
                    break;
                }
            }
            if(tieneStock) {
                row.style.borderLeft = '3px solid #28a745';
            }
        });

        // Inicializar tooltips
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    });

    // ==========================================
    // EXPORTAR A EXCEL
    // ==========================================
    function exportarExcel() {
        var table = document.querySelector('.table-existencias');
        var html = table.outerHTML;

        var titulo = 'EXISTENCIAS POR DEPÓSITO';
        var fecha = new Date().toLocaleDateString('es-VE');

        var styles = `
            <style>
                th { background: #0072c5 !important; color: #fff !important; font-weight: bold; }
                .fila-total { background: #0072c5 !important; color: #fff !important; font-weight: bold; }
                .badge-cantidad.positivo { background: #d4edda !important; }
                .badge-cantidad.alto { background: #cce5ff !important; }
                .badge-cantidad.muy-alto { background: #fff3cd !important; }
                .codigo-prod { font-weight: bold; color: #0072c5; }
                .total-unds { font-weight: bold; color: #0072c5; }
                td { border: 1px solid #ddd; padding: 4px 6px; }
                .celda-vacia { color: #ccc; }
            </style>
        `;

        var fullHtml = `
            <html>
                <head>
                    <meta charset="UTF-8">
                    <title>${titulo}</title>
                    ${styles}
                </head>
                <body>
                    <h2>${titulo}</h2>
                    <p>Fecha: ${fecha}</p>
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

    // ==========================================
    // TOGGLE RESUMEN
    // ==========================================
    function toggleResumen() {
        var container = document.getElementById('resumenContainer');
        if (container.style.display === 'none') {
            container.style.display = 'block';
        } else {
            container.style.display = 'none';
        }
    }

    // ==========================================
    // TRUNCAR TEXTO (fallback)
    // ==========================================
    function truncateText(text, maxLength) {
        if (text.length <= maxLength) return text;
        return text.substring(0, maxLength) + '...';
    }

    // ==========================================
    // RECARGAR CON FILTRO DESDE URL (para botones de navegación)
    // ==========================================
    function recargarConFiltro(depositos) {
        var urlParams = new URLSearchParams(window.location.search);
        var codalte = urlParams.get('codalte');
        var busqueda = urlParams.get('busqueda') || '';
        var url = window.location.pathname + '?codalte=' + codalte + '&busqueda=' + encodeURIComponent(busqueda) + '&depositos=' + JSON.stringify(depositos);
        window.location.href = url;
    }
</script>
