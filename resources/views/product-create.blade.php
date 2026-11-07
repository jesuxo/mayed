@extends('layouts.master')
@section('title')
    Crear nuevo producto
@endsection
@section('css')
    <style>
        .card-datosprod {
            transition: all 0.3s ease;
        }
    </style>
@endsection
@section('content')
    <x-breadcrumb title="Crear nuevo producto" pagetitle="Productos" />

    <form id="createproduct-form" autocomplete="off" class="needs-validation" method="post" novalidate action="{{route('productos.store')}}">
        @method('POST')
        @csrf
        <div class="row">
            <div class="col-xl-9 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title rounded-circle bg-light text-primary fs-20">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1">Informaci&oacute;n</h5>
                                <p class="text-muted mb-0">Ingrese los datos del producto.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div>
                            <div class="d-flex align-items-start">
                                <div class="flex-grow-1">
                                    <label class="form-label">Instancia de inventario <span class="text-danger">*</span></label>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="/instancias" class="float-end text-decoration-underline">+1 Instancia</a>
                                </div>
                            </div>
                            <div>
                                <select onchange="$('.error-msg').hide(); $('.card-datosprod').fadeIn(); verUltimoProd(this.value)"
                                        class="form-select"
                                        data-choices
                                        required

                                        name="codinst">
                                    <option value=""> Seleccionar </option>
                                    @foreach($instancias as $instancia)
                                        <option style="margin-left: {{($instancia->nivel-1) * 14}}px !important;"
                                                value="{{$instancia->codinst}}">
                                            {!! $instancia->label !!}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="error-msg mt-1 text-danger" style="display: none;">
                                Por favor, seleccione una instancia del inventario para clasificar este producto.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-datosprod" style="display: none">
                    <div class="card-body" style="background-color: #f3f4f4">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" id="invalidcodprod" for="codprod">
                                        C&oacute;digo <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           id="codprod"
                                           maxlength="15"
                                           name="codprod"
                                           placeholder="Código único del producto"
                                           required
                                           value="{{ old('codprod', $lastCod ?? '') }}"
                                           onkeyup="limpiarCodigo(); validarCodigo();"
                                           onblur="convertirMayusculas(); validarCaracteresCodigo();"
                                           oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9\-]/g, '');"
                                           style="text-transform: uppercase;">
                                    <div class="invalid-feedback">Por favor, ingrese el código del producto</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="refere">Referencia</label>
                                    <input type="text" class="form-control" id="refere" name="refere"  maxlength="40"
                                           placeholder="Ej: C&oacute;digo de barra" value="{{ old('refere') }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="descrip">Nombre del producto <span class="text-danger">*</span></label>
                            <input type="hidden" class="form-control" id="formAction" name="formAction" value="add">
                            <input type="text" class="form-control" id="descrip"
                                   value="{{ old('descrip') }}" maxlength="40"
                                   placeholder="Descripci&oacute;n principal"
                                   name="descrip"
                                   required>
                            <div class="invalid-feedback">Por favor, ingrese el nombre/descripci&oacute;n del producto</div>
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control" id="descrip2" name="descrip2"
                                   value="{{ old('descrip2') }}"  maxlength="40"
                                   placeholder="Descripci&oacute;n 2">
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control" id="descrip3" name="descrip3"
                                   value="{{ old('descrip3') }}"  maxlength="40"
                                   placeholder="Descripci&oacute;n 3">
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="marca">Marca</label>
                                    <div class="position-relative">
                                        <input type="text"
                                               class="form-control"
                                               id="marca"
                                               name="marca"
                                               value=""
                                               placeholder="Escribe para buscar marcas..."
                                               autocomplete="off">
                                        <div id="marca-sugerencias" class="list-group position-absolute w-100"
                                             style="display: none; max-height: 200px; overflow-y: auto; z-index: 1000;
                        background: white; border: 1px solid #ddd; border-radius: 0 0 4px 4px;
                        box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-search"></i> Escribe y selecciona una marca existente
                                    </small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="unidad">Unidad de medida</label>
                                    <input type="text" class="form-control" id="unidad" name="unidad"  maxlength="3"
                                           placeholder="Ej: Kg, Unidad, Litro" value="{{ old('unidad') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="preciodpro">Costo</label>
                                    <input type="number" step="0.01" class="form-control" id="preciodpro" name="preciodpro"
                                           placeholder="Costo del producto" value="{{ old('preciodpro') }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="costod3">Precio de venta</label>
                                    <input type="number" step="0.01" class="form-control" id="costod3" name="costod3"
                                           placeholder="Precio de venta" value="{{ old('costod3') }}">
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="text-end mb-3">
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-success w-sm">Guardar Producto</button>
                </div>
            </div>
            <!-- end col -->

            <div class="col-xl-3 col-lg-4">
                <div class="card card-datosprod"  >
                    <div class="card-header">
                        <h5 class="card-title mb-0">Condici&oacute;n</h5>
                    </div>
                    <div class="card-body">
                        <div>
                            <select class="form-select" name="activo" id="choices-publish-visibility-input" data-choices>
                                <option value="1" selected>Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card card-datosprod" style="display: none">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Observaciones</h5>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" name="observaciones"
                                  placeholder="Ej: solo vender en condiciones especificas"
                                  rows="3">{{ old('observaciones') }}</textarea>
                    </div>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </form>
@endsection
@section('scripts')
    <script>
        // ============================================
        // BÚSQUEDA DE MARCAS
        // ============================================

        document.addEventListener('DOMContentLoaded', function() {
            const inputMarca = document.getElementById('marca');
            const sugerenciasContainer = document.getElementById('marca-sugerencias');

            let timeoutId = null;
            let marcasCache = [];
            let selectedIndex = -1;
            let isNavigating = false;

            function mostrarSugerencias(marcas, query) {
                sugerenciasContainer.innerHTML = '';
                selectedIndex = -1;

                if (!marcas || marcas.length === 0) {
                    if (query && query.length > 0) {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'list-group-item list-group-item-action text-success';
                        item.innerHTML = `<i class="bi bi-plus-circle"></i> Crear "${query}"`;
                        item.addEventListener('click', function() {
                            inputMarca.value = query;
                            ocultarSugerencias();
                        });
                        sugerenciasContainer.appendChild(item);
                        sugerenciasContainer.style.display = 'block';
                    } else {
                        sugerenciasContainer.style.display = 'none';
                    }
                    return;
                }

                marcas.forEach(function(marca, index) {
                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = 'list-group-item list-group-item-action';
                    item.textContent = marca;
                    item.dataset.index = index;

                    if (query && query.length > 0) {
                        const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                        item.innerHTML = marca.replace(regex, '<strong style="color: #007bff;">$1</strong>');
                    }

                    item.addEventListener('mouseenter', function() {
                        selectedIndex = parseInt(this.dataset.index);
                        actualizarSeleccion();
                    });

                    item.addEventListener('click', function() {
                        inputMarca.value = marca;
                        ocultarSugerencias();
                        inputMarca.focus();
                    });

                    sugerenciasContainer.appendChild(item);
                });

                sugerenciasContainer.style.display = 'block';
            }

            function ocultarSugerencias() {
                sugerenciasContainer.style.display = 'none';
                selectedIndex = -1;
                isNavigating = false;
            }

            function actualizarSeleccion() {
                const items = sugerenciasContainer.querySelectorAll('.list-group-item-action');
                items.forEach(function(item, index) {
                    if (index === selectedIndex) {
                        item.classList.add('active');
                        item.style.backgroundColor = '#e8f0fe';
                        item.style.borderColor = '#007bff';
                        item.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                    } else {
                        item.classList.remove('active');
                        item.style.backgroundColor = '';
                        item.style.borderColor = '';
                    }
                });
            }

            inputMarca.addEventListener('input', function() {
                const query = this.value.trim();

                clearTimeout(timeoutId);

                if (query.length === 0) {
                    ocultarSugerencias();
                    return;
                }

                if (query.length < 1) {
                    sugerenciasContainer.style.display = 'none';
                    return;
                }

                const coincidencias = marcasCache.filter(function(marca) {
                    return marca.toLowerCase().includes(query.toLowerCase());
                });

                if (coincidencias.length > 0 && coincidencias.length <= 10) {
                    mostrarSugerencias(coincidencias, query);
                    return;
                }

                timeoutId = setTimeout(function() {
                    fetch(`{{ route('saprod.marcas') }}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            marcasCache = data;
                            mostrarSugerencias(data, query);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }, 300);
            });

            inputMarca.addEventListener('keydown', function(e) {
                const items = sugerenciasContainer.querySelectorAll('.list-group-item-action');

                if (items.length === 0) return;

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    isNavigating = true;
                    selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                    actualizarSeleccion();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    isNavigating = true;
                    selectedIndex = Math.max(selectedIndex - 1, 0);
                    actualizarSeleccion();
                } else if (e.key === 'Enter') {
                    if (selectedIndex >= 0 && selectedIndex < items.length) {
                        e.preventDefault();
                        const item = items[selectedIndex];
                        const texto = item.textContent.trim();
                        const valor = texto.replace('✚ Crear', '').trim();
                        inputMarca.value = valor || texto;
                        ocultarSugerencias();
                        inputMarca.focus();
                    }
                } else if (e.key === 'Escape') {
                    ocultarSugerencias();
                    inputMarca.blur();
                }
            });

            inputMarca.addEventListener('blur', function() {
                setTimeout(function() {
                    ocultarSugerencias();
                }, 200);
            });

            inputMarca.addEventListener('focus', function() {
                const query = this.value.trim();
                if (query.length >= 2) {
                    this.dispatchEvent(new Event('input'));
                } else {
                    if (marcasCache.length === 0) {
                        fetch(`{{ route('saprod.marcas') }}?q=`)
                            .then(response => response.json())
                            .then(data => {
                                marcasCache = data;
                                if (data.length > 0) {
                                    mostrarSugerencias(data.slice(0, 10), '');
                                }
                            });
                    } else {
                        mostrarSugerencias(marcasCache.slice(0, 10), '');
                    }
                }
            });

            fetch(`{{ route('saprod.marcas') }}?q=`)
                .then(response => response.json())
                .then(data => {
                    marcasCache = data;
                })
                .catch(error => {
                    console.error('Error cargando marcas:', error);
                });
        });

        function verUltimoProd(codinst) {
            $('#invalidcodprod').html('C&oacute;digo ');

            if (!codinst) {
                $('#invalidcodprod').html('C&oacute;digo');
                return;
            }

            $.ajax({
                type: 'POST',
                url: '/sainsta/check/lastprod/' + codinst,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {},
                success: function(data) {
                    lastprod = data.last;
                    if (lastprod)
                        $('#invalidcodprod').html('C&oacute;digo [ ' + lastprod + ' &Uacute;ltimo producto creado ]');
                },
                error: function() {
                    console.log('Error al obtener último producto');
                }
            });
        }

        // Validar que el código no esté duplicado
        function validarCodigo() {
            var codigo = $('#codprod').val();
            var codinst = $('#choices-category-input').val();

            if (!codigo || !codinst) return;

            $.ajax({
                type: 'POST',
                url: '/productos/validar-codigo',
                data: {
                    codigo: codigo,
                    codinst: codinst,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.existe) {
                        $('#invalidcodprod').addClass('text-danger');
                        $('#invalidcodprod').html('C&oacute;digo ya existe en el inventario');
                        $('#codprod').addClass('is-invalid');
                    } else {
                        $('#invalidcodprod').removeClass('text-danger');
                        $('#codprod').removeClass('is-invalid');
                    }
                }
            });
        }

        function validarCaracteresCodigo() {
            var codigo = $('#codprod').val();
            // Permitir solo letras (mayúsculas y minúsculas), números y guiones
            var regex = /^[a-zA-Z0-9\-]*$/;

            if (!regex.test(codigo)) {
                $('#invalidcodprod').addClass('text-danger');
                $('#invalidcodprod').html('C&oacute;digo solo puede contener letras, números y guiones');
                $('#codprod').addClass('is-invalid');
                return false;
            } else {
                $('#invalidcodprod').removeClass('text-danger');
                $('#codprod').removeClass('is-invalid');
                return true;
            }
        }

        function limpiarCodigo() {
            var codigo = $('#codprod').val();
            // Reemplazar cualquier caracter que no sea letra, número o guión
            var codigoLimpio = codigo.replace(/[^a-zA-Z0-9\-]/g, '');

            if (codigo !== codigoLimpio) {
                $('#codprod').val(codigoLimpio);
                $('#invalidcodprod').addClass('text-danger');
                $('#invalidcodprod').html('C&oacute;digo solo puede contener letras, números y guiones (caracteres no permitidos eliminados)');
                $('#codprod').addClass('is-invalid');

                // Limpiar el mensaje después de 3 segundos
                setTimeout(function() {
                    if ($('#invalidcodprod').hasClass('text-danger')) {
                        $('#invalidcodprod').removeClass('text-danger');
                        $('#invalidcodprod').html('C&oacute;digo');
                        $('#codprod').removeClass('is-invalid');
                    }
                }, 3000);
            } else {
                validarCaracteresCodigo();
            }
        }

        // Convertir a mayúsculas automáticamente (opcional)
        function convertirMayusculas() {
            var codigo = $('#codprod').val();
            $('#codprod').val(codigo.toUpperCase());
        }

        // Validación completa antes de enviar el formulario
        function validarFormulario(event) {
            var codigo = $('#codprod').val();
            var regex = /^[a-zA-Z0-9\-]+$/;

            if (!regex.test(codigo)) {
                event.preventDefault();
                $('#invalidcodprod').addClass('text-danger');
                $('#invalidcodprod').html('C&oacute;digo inválido. Solo use letras, números y guiones');
                $('#codprod').addClass('is-invalid');
                return false;
            }
            return true;
        }
    </script>
    <script src="{{ URL::asset('build/js/backend/create-product.init.js') }}?version={{rand(0,500)}}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
