@extends('tablar::page')

@section('title')
    Usuarios
@endsection

@section('content')
    <!-- Encabezado de la página -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Gestión del Sistema
                    </div>
                    <h2 class="page-title">
                        {{ __('Administración de Usuarios') }}
                    </h2>
                </div>
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-2"></i>
                            <span class="d-none d-sm-inline-block">Crear Usuario</span>
                            <span class="d-sm-none">Crear</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cuerpo de la página -->
    <div class="page-body">
        <div class="container-xl">
            <!-- SOLUCIÓN: Eliminado el include de alertas de Tablar para evitar duplicados -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <div class="d-flex">
                        <div>
                            <i class="ti ti-circle-check me-2"></i>
                        </div>
                        <div>
                            {{ session('success') }}
                        </div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <div class="d-flex">
                        <div>
                            <i class="ti ti-alert-circle me-2"></i>
                        </div>
                        <div>
                            {{ session('error') }}
                        </div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            @endif

            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ti ti-users me-2"></i>
                                Lista de Usuarios Registrados
                            </h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter datatable">
                                <thead>
                                <tr>
                                    <th>Información del Usuario</th>
                                    <th class="d-none d-lg-table-cell">Contacto</th>
                                    <th class="d-none d-xl-table-cell">Roles Asignados</th>
                                    <th class="w-1">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <div class="font-weight-medium">{{ $user->name }}</div>
                                                    <div class="text-muted d-lg-none small mt-1">
                                                        <i class="ti ti-mail me-1"></i>{{ $user->email }}
                                                    </div>
                                                    <div class="text-muted d-xl-none small mt-1">
                                                        <i class="ti ti-shield me-1"></i>
                                                        @if($user->roles->count() > 0)
                                                            @foreach($user->roles as $role)
                                                                <span class="badge 
                                                                    @if($role->name == 'admin') bg-danger
                                                                    @elseif($role->name == 'tester') bg-warning
                                                                    @elseif($role->name == 'control_escolar') bg-info
                                                                    @elseif($role->name == 'servicio_financiero') bg-success
                                                                    @else bg-secondary @endif 
                                                                    small text-white me-1">
                                                                    {{ $role->name }}
                                                                </span>
                                                            @endforeach
                                                        @else
                                                            <span class="badge bg-secondary-lt small">Sin rol</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            <div class="text-muted">
                                                <i class="ti ti-mail me-1"></i>
                                                {{ $user->email }}
                                            </div>
                                        </td>
                                        <td class="d-none d-xl-table-cell">
                                            @if($user->roles->count() > 0)
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($user->roles as $role)
                                                        <span class="badge 
                                                            @if($role->name == 'admin') bg-danger
                                                            @elseif($role->name == 'tester') bg-warning
                                                            @elseif($role->name == 'control_escolar') bg-info
                                                            @elseif($role->name == 'servicio_financiero') bg-success
                                                            @else bg-secondary @endif 
                                                            small text-white">
                                                            {{ $role->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="badge bg-secondary-lt small">Sin rol asignado</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-list justify-content-end">
                                                <div class="dropdown">
                                                    <button class="btn btn-ghost-secondary btn-sm" 
                                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('users.show', $user->id) }}">
                                                                <i class="ti ti-eye me-2"></i>
                                                                Ver Detalles
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                                                                <i class="ti ti-edit me-2"></i>
                                                                Editar Usuario
                                                            </a>
                                                        </li>
                                                        @if(auth()->user()->hasRole('admin'))
                                                            <li class="dropdown-divider"></li>
                                                            <li>
                                                                <form class="delete-form" action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button" class="dropdown-item text-danger delete-button">
                                                                        <i class="ti ti-trash me-2"></i>
                                                                        Eliminar Usuario
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="empty">
                                                <div class="empty-icon">
                                                    <i class="ti ti-users-off" style="font-size: 3rem;"></i>
                                                </div>
                                                <p class="empty-title">No se encontraron usuarios</p>
                                                <p class="empty-subtitle text-muted">
                                                    No hay usuarios registrados en el sistema.
                                                </p>
                                                <div class="empty-action">
                                                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                                                        <i class="ti ti-plus me-2"></i>
                                                        Crear primer usuario
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-muted">
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
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-button');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const form = button.closest('form');
                    const userName = form.closest('tr').querySelector('.font-weight-medium').textContent;

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
                            form.submit();
                            swalWithBootstrapButtons.fire({
                                title: "¡Eliminado!",
                                text: "El usuario ha sido eliminado.",
                                icon: "success"
                            });
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            swalWithBootstrapButtons.fire({
                                title: "Cancelado",
                                text: "El usuario está a salvo :)",
                                icon: "error"
                            });
                        }
                    });
                });
            });
        });
    </script>

    <style>
        .table-responsive {
            min-height: 400px;
        }
        
        .table td {
            padding: 0.75rem 0.5rem;
            vertical-align: middle;
        }
        
        .table th {
            padding: 0.75rem 0.5rem;
            font-weight: 600;
            white-space: nowrap;
        }
        
        /* Mejorar espaciado en móviles */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .table td, .table th {
                padding: 0.5rem 0.25rem;
            }
            
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }
        
        /* Para tablets */
        @media (max-width: 992px) {
            .table-responsive {
                font-size: 0.9rem;
            }
        }
        
        /* Para pantallas muy pequeñas */
        @media (max-width: 576px) {
            .card-body {
                padding: 0.75rem;
            }
            
            .table-responsive {
                margin: 0 -0.75rem;
            }
        }
    </style>
@endsection
