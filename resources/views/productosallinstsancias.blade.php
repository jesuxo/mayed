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

    /* ========================================== */
    /* FILTRO DE DEPÓSITOS - DENTRO DEL MODAL */
    /* ========================================== */
    .deposito-filter-modal {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 10px 14px;
        margin: 0 0 12px 0;
        border: 1px solid #e9ecef;
    }

    .deposito-filter-modal .filter-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 6px;
        display: block;
    }

    .deposito-filter-modal .deposito-check {
        display: inline-flex;
        align-items: center;
        margin-right: 8px;
        margin-bottom: 4px;
        cursor: pointer;
        padding: 3px 10px;
        border-radius: 20px;
        background: #fff;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease;
        font-size: 0.7rem;
    }

    .deposito-filter-modal .deposito-check:hover {
        background: #e9ecef;
    }

    .deposito-filter-modal .deposito-check input[type="checkbox"] {
        margin-right: 5px;
        accent-color: #0072c5;
        width: 13px;
        height: 13px;
        cursor: pointer;
    }

    .deposito-filter-modal .deposito-check.checked {
        background: #cce5ff;
        border-color: #0072c5;
    }

    .deposito-filter-modal .btn-aplicar-modal {
        background: #0072c5;
        color: #fff;
        border: none;
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .deposito-filter-modal .btn-aplicar-modal:hover {
        background: #0056a7;
    }

    .deposito-filter-modal .btn-seleccionar-todos-modal {
        color: #fff;
        border: none;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.65rem;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-right: 4px;
    }

    .deposito-filter-modal .btn-seleccionar-todos-modal.verde {
        background: #28a745;
    }

    .deposito-filter-modal .btn-seleccionar-todos-modal.verde:hover {
        background: #218838;
    }

    .deposito-filter-modal .btn-seleccionar-todos-modal.rojo {
        background: #dc3545;
    }

    .deposito-filter-modal .btn-seleccionar-todos-modal.rojo:hover {
        background: #c82333;
    }

    .deposito-filter-modal .badge-depositos-modal {
        background: #0072c5;
        color: #fff;
        padding: 1px 8px;
        border-radius: 10px;
        font-size: 0.65rem;
        margin-left: 5px;
    }

    .deposito-filter-modal .filter-actions {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
        margin-top: 4px;
    }

    /* Responsive para el filtro dentro del modal */
    @media (max-width: 768px) {
        .deposito-filter-modal .deposito-check {
            font-size: 0.6rem;
            padding: 2px 6px;
            margin-right: 4px;
        }

        .deposito-filter-modal .btn-aplicar-modal,
        .deposito-filter-modal .btn-seleccionar-todos-modal {
            font-size: 0.6rem;
            padding: 3px 10px;
        }
    }
</style>

<div class="card card-existencias">
    <div class="card-body" style="padding: 0px;">
        <!-- ========================================== -->
        <!-- FILTRO DE DEPÓSITOS DENTRO DEL MODAL -->
        <!-- ========================================== -->
        @if(count($deposito) > 0)
            <div class="deposito-filter-modal">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                    <span class="filter-label">
                        <i class="bi bi-funnel"></i> Filtrar por Depósito:
                        <span class="badge-depositos-modal" id="contadorSeleccionadosModal">
                            {{ count($depositoValues) }} seleccionados
                        </span>
                    </span>
                        <div id="depositosCheckboxesModal">
                            @foreach($deposito as $key => $nombre)
                                @php
                                    $checked = in_array($key, $depositosSeleccionados ?? array_keys($deposito));
                                @endphp
                                <label class="deposito-check {{ $checked ? 'checked' : '' }}">
                                    <input type="checkbox"
                                           class="deposito-checkbox-modal"
                                           value="{{ $key }}"
                                        {{ $checked ? 'checked' : '' }}>
                                    {{ Str::limit($nombre, 16) }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="filter-actions mt-1 mt-md-0">
                        <button class="btn-seleccionar-todos-modal verde" onclick="seleccionarTodosModal()">
                            <i class="bi bi-check-all"></i> Todos
                        </button>
                        <button class="btn-seleccionar-todos-modal rojo" onclick="deseleccionarTodosModal()">
                            <i class="bi bi-x"></i> Ninguno
                        </button>
                        <button class="btn-aplicar-modal" onclick="aplicarFiltroModal()">
                            <i class="bi bi-check2"></i> Aplicar
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tabla -->
        <div class="scroll-existencias" style="max-height: 490px; overflow: auto;">
            <table class="table-existencias table table-borderless table-centered align-middle table-nowrap mb-0" id="tablaExistenciasModal">
                <thead>
                <tr>
                    <th width="4%" class="text-center">CÓD</th>
                    <th width="20%">PRODUCTO</th>
                    <th width="9%" class="text-center">REF</th>
                    @foreach($depositoValues as $indexDep => $descripdepo)
                        <th width="13%" class="text-center">
                                <span class="deposito-tooltip" title="{{ $descripdepo }}">
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

        <div class="d-flex justify-content-between align-items-center mt-2 text-muted" style="font-size: 0.7rem; padding: 0 4px;">
            <span>
                <i class="bi bi-info-circle me-1"></i>
                {{ count($productos) }} productos ·
                <span id="footerDepositosModal">{{ count($depositoValues) }}</span> depósitos
            </span>
            <span>
                <i class="bi bi-clock me-1"></i>
                {{ now()->format('H:i') }}
            </span>
        </div>
    </div>
</div>

<script>
    // ==========================================
    // FILTRO DE DEPÓSITOS - DENTRO DEL MODAL
    // ==========================================

    // Variables para almacenar los parámetros actuales
    var modalCodalte = '{{ $codalte ?? '' }}';
    var modalBusqueda = '{{ $busqueda ?? '' }}';

    // Actualizar contador de seleccionados
    function actualizarContadorModal() {
        var checkboxes = document.querySelectorAll('.deposito-checkbox-modal:checked');
        var total = document.querySelectorAll('.deposito-checkbox-modal').length;
        var contador = document.getElementById('contadorSeleccionadosModal');
        if (contador) {
            contador.textContent = checkboxes.length + ' seleccionados';
        }

        // Actualizar badges
        var totalDep = document.getElementById('totalDepositosModal');
        var footerDep = document.getElementById('footerDepositosModal');
        if (totalDep) totalDep.textContent = checkboxes.length;
        if (footerDep) footerDep.textContent = checkboxes.length;

        // Marcar/desmarcar estilo de los labels
        document.querySelectorAll('.deposito-check').forEach(function(label) {
            var checkbox = label.querySelector('.deposito-checkbox-modal');
            if (checkbox && checkbox.checked) {
                label.classList.add('checked');
            } else {
                label.classList.remove('checked');
            }
        });
    }

    // Seleccionar todos
    function seleccionarTodosModal() {
        document.querySelectorAll('.deposito-checkbox-modal').forEach(function(cb) {
            cb.checked = true;
        });
        actualizarContadorModal();
    }

    // Deseleccionar todos
    function deseleccionarTodosModal() {
        document.querySelectorAll('.deposito-checkbox-modal').forEach(function(cb) {
            cb.checked = false;
        });
        actualizarContadorModal();
    }

    // Aplicar filtro - recarga el contenido del modal
    function aplicarFiltroModal() {
        var checkboxes = document.querySelectorAll('.deposito-checkbox-modal:checked');
        var depositos = [];
        checkboxes.forEach(function(cb) {
            depositos.push(cb.value);
        });

        if (depositos.length === 0) {
            alert('Debe seleccionar al menos un depósito para visualizar.');
            return;
        }

        // Mostrar loading
        var content = document.getElementById('contentviewprodcodalte');
        if (content) {
            content.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-3 text-muted">Actualizando datos...</p>
                </div>
            `;
        }

        // Llamar al método del controlador con los depósitos seleccionados
        $.ajax({
            type: 'post',
            data: {
                codalte: modalCodalte,
                busqueda: modalBusqueda,
                depositos: JSON.stringify(depositos)
            },
            url: '/saprod/viewprodinstsanciascodalte',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (content) {
                    content.innerHTML = response;
                    // Re-inicializar eventos del filtro
                    inicializarFiltroModal();
                }
            },
            error: function() {
                if (content) {
                    content.innerHTML = `
                        <div class="alert alert-danger text-center">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Error al actualizar los datos. Por favor, intente nuevamente.
                        </div>
                    `;
                }
            }
        });
    }

    // Inicializar eventos del filtro (se llama después de recargar el contenido)
    function inicializarFiltroModal() {
        // Evento para los checkboxes
        document.querySelectorAll('.deposito-checkbox-modal').forEach(function(cb) {
            cb.addEventListener('change', actualizarContadorModal);
        });

        // Inicializar contador
        actualizarContadorModal();

        // Inicializar tooltips
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        // Resaltar filas con stock
        var rows = document.querySelectorAll('.table-existencias tbody tr:not(.fila-total)');
        rows.forEach(function(row) {
            var celdas = row.querySelectorAll('td');
            var tieneStock = false;
            for (var i = 3; i < celdas.length - 1; i++) {
                var texto = celdas[i].textContent.trim();
                if (texto !== '-' && texto !== '') {
                    tieneStock = true;
                    break;
                }
            }
            if (tieneStock) {
                row.style.borderLeft = '3px solid #28a745';
            }
        });
    }

    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        inicializarFiltroModal();
    });

    // También inicializar cuando el modal se abre completamente
    $(document).ready(function() {
        // Cuando el modal se muestra, reinicializar el filtro
        $('#viewprodcodaltemodal').on('shown.bs.modal', function() {
            setTimeout(function() {
                inicializarFiltroModal();
            }, 300);
        });
    });
</script>
