@extends('tablar::page')

@section('title')
    Gestión de Roles
@endsection

@section('content')
    <!-- Encabezado de la página -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Sistema de Gestión
                    </div>
                    <h2 class="page-title">
                        {{ __('Administración de Roles y Permisos') }}
                    </h2>
                </div>
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignRoleModal">
                            <i class="ti ti-user-plus me-2"></i>
                            <span class="d-none d-sm-inline-block">Asignar Rol</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cuerpo de la página -->
    <div class="page-body">
        <div class="container-xl">
            <!-- Modal para Asignar Roles -->
            <div class="modal modal-blur fade" id="assignRoleModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Asignar Rol a Usuarios</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('roles.assign') }}" method="POST" id="assignRoleForm">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Seleccionar Rol</label>
                                            <select name="role_id" class="form-control" required id="roleSelect">
                                                <option value="">Selecciona un rol</option>
                                                @foreach($roles as $role)
                                                    {{-- Mostrar roles administrables incluyendo maestros --}}
                                                    @if(in_array($role->name, ['admin', 'control_escolar', 'servicio_financiero', 'tester', 'maestros', 'maestro']))
                                                        <option value="{{ $role->id }}" data-role-name="{{ $role->name }}">
                                                            @switch($role->name)
                                                                @case('admin')
                                                                    🔑 Administrador
                                                                    @break
                                                                @case('control_escolar')
                                                                    📚 Control Escolar
                                                                    @break
                                                                @case('servicio_financiero')
                                                                    💰 Servicio Financiero
                                                                    @break
                                                                @case('tester')
                                                                    🧪 Tester (Acceso Completo)
                                                                    @break
                                                                    @case('maestros')
                                                                    👩‍🏫 Maestros
                                                                    @break
                                                                    @case('maestro')
                                                                    👩‍🏫 Maestros
                                                                    @break
                                                            @endswitch
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Buscar Usuario</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="userSearch" placeholder="Buscar por nombre...">
                                                <span class="input-group-text">
                                                    <i class="ti ti-search"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Alerta de restricciones -->
                                <div class="alert alert-info" id="restrictionAlert" style="display: none;">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <i class="ti ti-info-circle"></i>
                                        </div>
                                        <div>
                                            <h6 class="alert-title">Restricciones de Asignación</h6>
                                            <div class="text-muted" id="restrictionMessage"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Alerta para rol tester -->
                                <div class="alert alert-warning" id="testerAlert" style="display: none;">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <i class="ti ti-alert-triangle"></i>
                                        </div>
                                        <div>
                                            <h6 class="alert-title">Rol Tester - Acceso Completo</h6>
                                            <div class="text-muted">
                                                Este rol tiene acceso a todas las funcionalidades del sistema. 
                                                Asigna con precaución y solo a usuarios de confianza.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Seleccionar Usuarios</label>
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>Usuarios Disponibles</span>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                                    <label class="form-check-label small" for="selectAll">
                                                        Seleccionar todos los visibles
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div style="max-height: 300px; overflow-y: auto;">
                                                <div class="list-group list-group-flush">
                                                    @foreach($users as $user)
                                                        <div class="list-group-item user-checkbox" data-user-name="{{ strtolower($user->name) }}">
                                                            <div class="form-check mb-0">
                                                                <input class="form-check-input user-checkbox-input" type="checkbox" 
                                                                       name="user_ids[]" value="{{ $user->id }}" 
                                                                       id="modal-user-checkbox-{{ $user->id }}">
                                                                <label class="form-check-label d-flex justify-content-between align-items-center w-100" 
                                                                       for="modal-user-checkbox-{{ $user->id }}">
                                                                    <div class="d-flex align-items-center">
                                                                        <span class="avatar avatar-sm me-3 bg-blue-lt">
                                                                            {{ substr($user->name, 0, 1) }}
                                                                        </span>
                                                                        <div>
                                                                            <div class="font-weight-medium">{{ $user->name }}</div>
                                                                            <div class="text-muted small">{{ $user->email }}</div>
                                                                        </div>
                                                                    </div>
                                                                    @if($user->roles->count() > 0)
                                                                        <div class="d-flex flex-wrap gap-1 justify-content-end" style="min-width: 120px;">
                                                                            @foreach($user->roles as $role)
                                                                                <span class="badge 
                                                                                    @if($role->name == 'admin') bg-danger
                                                                                    @elseif($role->name == 'tester') bg-warning
                                                                                    @elseif($role->name == 'control_escolar') bg-info
                                                                                    @elseif($role->name == 'servicio_financiero') bg-success
                                                                                    @elseif($role->name == 'maestros') bg-primary
                                                                                    @else bg-secondary @endif 
                                                                                    small text-white">
                                                                                    {{ $role->name }}
                                                                                </span>
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <span class="badge bg-secondary-lt small text-white">Sin rol</span>
                                                                    @endif
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <small class="text-white">
                                                <span id="selectedCount">0</span> usuarios seleccionados de 
                                                <span id="totalCount">{{ $users->count() }}</span> disponibles
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="ti ti-x me-2"></i>Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary" id="submitButton">
                                    <i class="ti ti-check me-2"></i>
                                    Asignar Rol
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Lista de Roles y Usuarios Asignados -->
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ti ti-users me-2"></i>
                                Distribución de Roles en el Sistema
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="row g-3 p-3">
                                @foreach($roles as $role)
                                    @if(in_array($role->name, ['admin', 'control_escolar', 'servicio_financiero', 'tester', 'maestros', 'maestro']))
                                        <div class="col-12 col-xl-6">
                                            <div class="card card-sm shadow-sm border-0">
                                                <div class="card-header 
                                                    @if($role->name == 'admin') bg-danger-lt
                                                    @elseif($role->name == 'tester') bg-warning-lt
                                                    @elseif($role->name == 'control_escolar') bg-info-lt
                                                    @elseif($role->name == 'servicio_financiero') bg-success-lt
                                                    @elseif($role->name == 'maestros' || $role->name == 'maestro') bg-primary-lt
                                                    @endif py-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            @if($role->name == 'admin')
                                                                <span class="avatar avatar-md bg-danger text-white">🔑</span>
                                                            @elseif($role->name == 'tester')
                                                                <span class="avatar avatar-md bg-warning text-white">🧪</span>
                                                            @elseif($role->name == 'control_escolar')
                                                                <span class="avatar avatar-md bg-info text-white">📚</span>
                                                            @elseif($role->name == 'servicio_financiero')
                                                                <span class="avatar avatar-md bg-success text-white">💰</span>
                                                            @elseif($role->name == 'maestros' || $role->name == 'maestro')
                                                                <span class="avatar avatar-md bg-primary text-white">👩‍🏫</span>
                                                            @endif
                                                        </div>
                                                        <div class="flex-fill">
                                                            <h4 class="card-title mb-1">
                                                                @switch($role->name)
                                                                    @case('admin') Administrador @break
                                                                    @case('tester') Tester @break
                                                                    @case('control_escolar') Control Escolar @break
                                                                    @case('servicio_financiero') Servicio Financiero @break
                                                                    @case('maestros') Maestros @break
                                                                    @case('maestro') Maestros @break
                                                                @endswitch
                                                            </h4>
                                                            <div class="text-muted small">
                                                                @switch($role->name)
                                                                    @case('admin') Acceso completo al sistema @break
                                                                    @case('tester') Acceso completo para pruebas @break
                                                                    @case('control_escolar') Gestión académica @break
                                                                    @case('servicio_financiero') Gestión financiera @break
                                                                    @case('maestros') Gestión de docentes @break
                                                                    @case('maestro') Gestión de docentes @break
                                                                @endswitch
                                                            </div>
                                                        </div>
                                                        <div class="ms-auto">
                                                            <span class="badge 
                                                                @if($role->name == 'admin') bg-danger
                                                                @elseif($role->name == 'tester') bg-warning
                                                                @elseif($role->name == 'control_escolar') bg-info
                                                                @elseif($role->name == 'servicio_financiero') bg-success
                                                                @elseif($role->name == 'maestros' || $role->name == 'maestro') bg-primary
                                                                @endif text-white">
                                                                {{ $role->users->count() }} usuarios
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    @if($role->users->count() > 0)
                                                        <div class="list-group list-group-flush">
                                                            @foreach($role->users as $user)
                                                                <div class="list-group-item px-0 py-3">
                                                                    <div class="row align-items-center">
                                                                        <div class="col-auto">
                                                                            <span class="avatar avatar-sm bg-blue-lt">
                                                                                {{ substr($user->name, 0, 1) }}
                                                                            </span>
                                                                        </div>
                                                                        <div class="col">
                                                                            <div class="font-weight-medium">{{ $user->name }}</div>
                                                                            <div class="text-muted small">{{ $user->email }}</div>
                                                                        </div>
                                                                        <div class="col-auto">
                                                                            <div class="dropdown">
                                                                                <button class="btn btn-ghost-secondary btn-sm" 
                                                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                    <i class="ti ti-dots-vertical"></i>
                                                                                </button>
                                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                                    <li>
                                                                                        <a class="dropdown-item" href="{{ route('users.show', $user->id) }}">
                                                                                            <i class="ti ti-eye me-2"></i>Ver perfil
                                                                                        </a>
                                                                                    </li>
                                                                                    @if(auth()->user()->hasRole('admin'))
                                                                                        <li>
                                                                                            <form action="{{ route('roles.remove') }}" method="POST" id="remove-role-{{ $user->id }}-{{ $role->id }}">
                                                                                                @csrf
                                                                                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                                                                <input type="hidden" name="role_id" value="{{ $role->id }}">
                                                                                                <button type="button" class="dropdown-item text-danger" onclick="confirmRemoveRole({{ $user->id }}, '{{ addslashes($user->name) }}', {{ $role->id }}, '{{ $role->name }}')">
                                                                                                    <i class="ti ti-user-off me-2"></i>Quitar rol {{ ucfirst($role->name) }}
                                                                                                </button>
                                                                                            </form>
                                                                                        </li>
                                                                                    @endif
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="text-center py-4">
                                                            <div class="empty">
                                                                <div class="empty-icon">
                                                                    <i class="ti ti-users-off" style="font-size: 3rem;"></i>
                                                                </div>
                                                                <p class="empty-title">No hay usuarios asignados</p>
                                                                <p class="empty-subtitle text-muted">
                                                                    No hay usuarios con este rol en el sistema.
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paginación para Usuarios -->
            @if ($users->hasPages())
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-footer d-flex align-items-center">
                                <p class="m-0 text-white">
                                    Mostrando <span>{{ $users->firstItem() }}</span> a <span>{{ $users->lastItem() }}</span> 
                                    de <span>{{ $users->total() }}</span> usuarios
                                </p>
                                <div class="ms-auto">
                                    {!! $users->links('tablar::pagination') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos del DOM
            const roleSelect = document.getElementById('roleSelect');
            const userSearch = document.getElementById('userSearch');
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            const userCheckboxInputs = document.querySelectorAll('.user-checkbox-input');
            const selectAllCheckbox = document.getElementById('selectAll');
            const selectedCount = document.getElementById('selectedCount');
            const totalCount = document.getElementById('totalCount');
            const restrictionAlert = document.getElementById('restrictionAlert');
            const restrictionMessage = document.getElementById('restrictionMessage');
            const testerAlert = document.getElementById('testerAlert');

            // Mensajes de restricción por rol
            const restrictionMessages = {
                'admin': 'Solo se recomienda asignar el rol de Administrador a usuarios de máxima confianza.',
                'tester': 'El rol Tester tiene acceso completo. Limita su asignación a personal autorizado.',
                'control_escolar': 'Este rol tiene acceso a información académica sensible.',
                'servicio_financiero': 'Este rol maneja información financiera. Asigna con precaución.'
            };

            // Actualizar alertas según el rol seleccionado
            if (roleSelect) {
                roleSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const roleName = selectedOption.getAttribute('data-role-name');
                    
                    // Mostrar/ocultar alerta de tester
                    if (roleName === 'tester') {
                        testerAlert.style.display = 'flex';
                    } else {
                        testerAlert.style.display = 'none';
                    }

                    // Mostrar/ocultar alerta de restricciones
                    if (roleName && restrictionMessages[roleName]) {
                        restrictionMessage.textContent = restrictionMessages[roleName];
                        restrictionAlert.style.display = 'flex';
                    } else {
                        restrictionAlert.style.display = 'none';
                    }
                });
            }

            // Funcionalidad de búsqueda de usuarios
            if (userSearch) {
                userSearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    let visibleCount = 0;
                    
                    userCheckboxes.forEach(function(checkbox) {
                        const userName = checkbox.getAttribute('data-user-name');
                        const shouldShow = userName.includes(searchTerm);
                        checkbox.style.display = shouldShow ? 'block' : 'none';
                        
                        if (shouldShow) {
                            visibleCount++;
                        }
                    });

                    totalCount.textContent = visibleCount;
                    updateSelectedCount();
                });
            }

            // Funcionalidad de seleccionar todos
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const isChecked = this.checked;
                    
                    userCheckboxInputs.forEach(function(checkbox) {
                        if (checkbox.closest('.user-checkbox').style.display !== 'none') {
                            checkbox.checked = isChecked;
                        }
                    });
                    updateSelectedCount();
                });
            }

            // Actualizar contador de seleccionados
            function updateSelectedCount() {
                let count = 0;
                userCheckboxInputs.forEach(function(checkbox) {
                    if (checkbox.checked && checkbox.closest('.user-checkbox').style.display !== 'none') {
                        count++;
                    }
                });
                selectedCount.textContent = count;
            }

            // Actualizar estado de checkboxes individuales
            userCheckboxInputs.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    updateSelectedCount();
                    
                    // Actualizar estado de "Seleccionar todos"
                    const visibleCheckboxes = Array.from(userCheckboxInputs).filter(cb => 
                        cb.closest('.user-checkbox').style.display !== 'none'
                    );
                    const checkedVisibleCheckboxes = visibleCheckboxes.filter(cb => cb.checked);
                    
                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = visibleCheckboxes.length > 0 && 
                            checkedVisibleCheckboxes.length === visibleCheckboxes.length;
                        selectAllCheckbox.indeterminate = checkedVisibleCheckboxes.length > 0 && 
                            checkedVisibleCheckboxes.length < visibleCheckboxes.length;
                    }
                });
            });

            // Validación del formulario antes de enviar
            const assignRoleForm = document.getElementById('assignRoleForm');
            if (assignRoleForm) {
                assignRoleForm.addEventListener('submit', function(e) {
                    const selectedUsers = Array.from(userCheckboxInputs).filter(cb => cb.checked);
                    
                    if (selectedUsers.length === 0) {
                        e.preventDefault();
                        
                        const swalWithBootstrapButtons = Swal.mixin({
                            customClass: {
                                confirmButton: "btn btn-danger",
                                cancelButton: "btn btn-secondary"
                            },
                            buttonsStyling: false
                        });
                        
                        swalWithBootstrapButtons.fire({
                            title: "Error",
                            text: "Debes seleccionar al menos un usuario.",
                            icon: "error",
                            confirmButtonText: "Entendido"
                        });
                        return;
                    }
                });
            }

            // Limpiar formulario cuando se cierra el modal
            const assignRoleModal = document.getElementById('assignRoleModal');
            if (assignRoleModal) {
                assignRoleModal.addEventListener('hidden.bs.modal', function() {
                    const form = this.querySelector('form');
                    if (form) {
                        form.reset();
                        if (userSearch) userSearch.value = '';
                        userCheckboxes.forEach(function(checkbox) {
                            checkbox.style.display = 'block';
                        });
                        if (selectAllCheckbox) {
                            selectAllCheckbox.checked = false;
                            selectAllCheckbox.indeterminate = false;
                        }
                        restrictionAlert.style.display = 'none';
                        testerAlert.style.display = 'none';
                        updateSelectedCount();
                        totalCount.textContent = {{ $users->count() }};
                    }
                });
            }

            // Actualizar contador inicial
            updateSelectedCount();
            totalCount.textContent = {{ $users->count() }};
        });

        // Función para confirmar remoción de rol tester
        function confirmTesterRemoval(userId, userName) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });
            
            swalWithBootstrapButtons.fire({
                title: "¿Estás seguro?",
                text: "¡No podrás revertir esta acción!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "No, cancelar",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Aquí iría la llamada AJAX o redirección para remover el rol
                    swalWithBootstrapButtons.fire({
                        title: "¡Eliminado!",
                        text: "El rol Tester ha sido removido.",
                        icon: "success"
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelado",
                        text: "El rol Tester está a salvo :)",
                        icon: "error"
                    });
                }
            });
        }

        // Función para habilitar/deshabilitar tester
        function toggleTesterStatus(userId, userName) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });
            
            swalWithBootstrapButtons.fire({
                title: "¿Estás seguro?",
                text: "¡No podrás revertir esta acción!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, deshabilitar",
                cancelButtonText: "No, cancelar",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Aquí iría la llamada AJAX o redirección para cambiar el estado
                    swalWithBootstrapButtons.fire({
                        title: "¡Deshabilitado!",
                        text: "El acceso Tester ha sido deshabilitado.",
                        icon: "success"
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelado",
                        text: "El acceso Tester está a salvo :)",
                        icon: "error"
                    });
                }
            });
        }

        // Función genérica para confirmar y enviar el formulario de remoción de rol
        function confirmRemoveRole(userId, userName, roleId, roleName) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-secondary"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: `Quitar rol ${roleName}`,
                text: `¿Deseas quitar el rol ${roleName} al usuario ${userName}? Esta acción se puede revertir asignando el rol nuevamente.`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, quitar",
                cancelButtonText: "No, cancelar",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const formId = `remove-role-${userId}-${roleId}`;
                    const form = document.getElementById(formId);
                    if (form) {
                        form.submit();
                    }
                }
            });
        }
    </script>
@endsection