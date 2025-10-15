@extends('tablar::page')

@section('title', 'Detalles de Sala - ' . $sala->nombre)

@section('content')
    <!-- Meta CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('salas.index') }}">Salas</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $sala->nombre }}</li>
                        </ol>
                    </nav>
                    <h2 class="page-title">
                        {{ $sala->nombre }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        @can('crear reuniones')
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-meeting">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <rect x="4" y="5" width="16" height="16" rx="2"/>
                                <line x1="16" y1="3" x2="16" y2="7"/>
                                <line x1="8" y1="3" x2="8" y2="7"/>
                                <line x1="4" y1="11" x2="20" y2="11"/>
                                <path d="M8 15h2v2h-2z"/>
                            </svg>
                            Crear Reunión
                        </button>
                        @endcan
                        @can('gestionar salas')
                        <a href="{{ route('salas.edit', $sala) }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                <path d="M16 5l3 3"/>
                            </svg>
                            Editar Sala
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <!-- Información de la Sala -->
                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Información de la Sala</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nombre</label>
                                        <div class="text-muted">{{ $sala->nombre }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Capacidad</label>
                                        <div class="text-muted">{{ $sala->capacidad }} personas</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Descripción</label>
                                        <div class="text-muted">{{ $sala->descripcion ?: 'Sin descripción' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Estado</label>
                                        <div>
                                            @if($sala->activo)
                                                <span class="badge bg-success">Activa</span>
                                            @else
                                                <span class="badge bg-danger">Inactiva</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Fecha de Creación</label>
                                        <div class="text-muted">{{ $sala->created_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reuniones Programadas -->
                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Reuniones Programadas</h3>
                        </div>
                        <div class="card-body">
                            @if($reuniones && $reuniones->count() > 0)
                                @foreach($reuniones as $reunion)
                                <div class="reunion-card card mb-3 border-warning">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0">{{ $reunion->titulo }}</h6>
                                            <span class="badge bg-info text-white">{{ $reunion->plataforma_nombre }}</span>
                                        </div>
                                        
                                        <div class="small text-muted mb-2">
                                            <div><strong>Fecha:</strong> {{ $reunion->fecha_hora_formatted }}</div>
                                            <div><strong>Duración:</strong> {{ $reunion->duracion }} min</div>
                                        </div>
                                        
                                        @if($reunion->descripcion)
                                        <p class="card-text small text-muted mb-2">{{ Str::limit($reunion->descripcion, 80) }}</p>
                                        @endif
                                        
                                        <!-- Botones de Administración para Maestros -->
                                        @can('crear reuniones')
                                            @if($reunion->user_id == Auth::id() || Auth::user()->hasRole('administrador'))
                                            <div class="btn-group w-100 mb-2" role="group">
                                                <button type="button" class="btn btn-outline-warning btn-sm btn-cancelar-reunion" 
                                                        data-reunion-id="{{ $reunion->id }}"
                                                        data-reunion-titulo="{{ $reunion->titulo }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clock-pause me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <circle cx="12" cy="12" r="9"/>
                                                        <path d="M10 10h4v4h-4z"/>
                                                    </svg>
                                                    Cancelar
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar-reunion" 
                                                        data-reunion-id="{{ $reunion->id }}"
                                                        data-reunion-titulo="{{ $reunion->titulo }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M4 7l16 0"/>
                                                        <path d="M10 11l0 6"/>
                                                        <path d="M14 11l0 6"/>
                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                                    </svg>
                                                    Eliminar
                                                </button>
                                            </div>
                                            @endif
                                        @endcan
                                        
                                        @auth('alumno')
                                            @if($reunion->puedeUnirse())
                                            <a href="{{ $reunion->enlace_reunion }}" target="_blank" class="btn btn-success btn-sm w-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-video me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M15 10l4.553 -2.276a1 1 0 0 1 1.447 .894v6.764a1 1 0 0 1 -1.447 .894l-4.553 -2.276v-4z"/>
                                                    <rect x="3" y="6" width="12" height="12" rx="2"/>
                                                </svg>
                                                Unirse a Reunión
                                            </a>
                                            @else
                                            <button class="btn btn-secondary btn-sm w-100" disabled>
                                                Reunión no disponible
                                            </button>
                                            @endif
                                        @else
                                            <div class="small text-info">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <circle cx="12" cy="12" r="9"/>
                                                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                                                    <polyline points="11,12 12,12 12,16 13,16"/>
                                                </svg>
                                                Inicia sesión como estudiante para unirte
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted py-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-off mb-2" width="48" height="48" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M9 5h9a2 2 0 0 1 2 2v9m-.184 3.839a2 2 0 0 1 -1.816 1.161h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h1"/>
                                        <line x1="16" y1="3" x2="16" y2="7"/>
                                        <line x1="8" y1="3" x2="8" y2="7"/>
                                        <path d="M4 11h7m4 0h5"/>
                                        <line x1="3" y1="3" x2="21" y2="21"/>
                                    </svg>
                                    <p>No hay reuniones programadas para esta sala</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horarios de la Sala -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Horarios de Clase</h3>
                        </div>
                        <div class="card-body">
                            @if($horarios && $horarios->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-vcenter">
                                        <thead>
                                            <tr>
                                                <th>Día</th>
                                                <th>Hora Inicio</th>
                                                <th>Hora Fin</th>
                                                <th>Materia</th>
                                                <th>Profesor</th>
                                                <th>Grupo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($horarios as $horario)
                                            <tr>
                                                <td>{{ ucfirst($horario->dia_semana) }}</td>
                                                <td>{{ $horario->hora_inicio }}</td>
                                                <td>{{ $horario->hora_fin }}</td>
                                                <td>{{ $horario->materia->nombre ?? 'N/A' }}</td>
                                                <td>{{ $horario->materia->profesor ?? 'N/A' }}</td>
                                                <td>
                                                    @if($horario->materia && $horario->materia->grupo)
                                                        <span class="badge bg-primary">
                                                            {{ $horario->materia->grupo->semestre }}{{ strtoupper($horario->materia->grupo->letra) }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clock-off mb-2" width="48" height="48" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M20.042 16.045a9 9 0 0 0 -12.087 -12.087m-2.318 1.677a9 9 0 1 0 12.725 12.73"/>
                                        <path d="M12 7v1m0 4h1"/>
                                        <line x1="3" y1="3" x2="21" y2="21"/>
                                    </svg>
                                    <p>No hay horarios programados para esta sala</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('crear reuniones')
    <!-- Modal para crear reunión -->
    <div class="modal modal-blur fade" id="modal-meeting" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nueva Reunión Virtual</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-meeting" action="{{ route('salas.crear-reunion') }}" method="POST">
                    @csrf
                    <input type="hidden" name="sala_id" value="{{ $sala->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Título de la Reunión <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="titulo" required 
                                   placeholder="Ej: Clase de Matemáticas - Álgebra Lineal">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Fecha <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="fecha" required min="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Hora <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" name="hora" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Duración (minutos) <span class="text-danger">*</span></label>
                                    <select class="form-select" name="duracion_minutos" required>
                                        <option value="">Seleccionar duración</option>
                                        <option value="30">30 minutos</option>
                                        <option value="60" selected>1 hora</option>
                                        <option value="90">1.5 horas</option>
                                        <option value="120">2 horas</option>
                                        <option value="180">3 horas</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Plataforma <span class="text-danger">*</span></label>
                                    <select class="form-select" name="plataforma" required>
                                        <option value="">Seleccionar plataforma</option>
                                        <option value="meet">Google Meet</option>
                                        <option value="zoom">Zoom</option>
                                        <option value="teams">Microsoft Teams</option>
                                        <option value="webex">Cisco Webex</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Enlace de la Reunión <span class="text-danger">*</span></label>
                            <input type="url" class="form-control" name="enlace" required
                                   placeholder="https://meet.google.com/abc-defg-hij o similar">
                            <small class="form-hint">Ingresa el enlace directo generado por la plataforma de videollamada</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="3" 
                                      placeholder="Descripción opcional de la reunión, temas a tratar, materiales necesarios, etc."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-success ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Crear Reunión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form submission handler
    const form = document.getElementById('form-meeting');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Creando...';
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Reunión creada!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al crear la reunión'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión. Por favor, intenta de nuevo.'
                });
            })
            .finally(() => {
                // Restore button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }

    // Event listeners para botones de cancelar reunión
    document.querySelectorAll('.btn-cancelar-reunion').forEach(button => {
        button.addEventListener('click', function() {
            const reunionId = this.dataset.reunionId;
            const reunionTitulo = this.dataset.reunionTitulo;
            cancelarReunion(reunionId, reunionTitulo);
        });
    });

    // Event listeners para botones de eliminar reunión
    document.querySelectorAll('.btn-eliminar-reunion').forEach(button => {
        button.addEventListener('click', function() {
            const reunionId = this.dataset.reunionId;
            const reunionTitulo = this.dataset.reunionTitulo;
            eliminarReunion(reunionId, reunionTitulo);
        });
    });

    // Event listeners para botones de unirse a reuniones
    document.querySelectorAll('.btn-unirse-sala').forEach(button => {
        button.addEventListener('click', function() {
            const reunionId = this.dataset.reunionId;
            unirseReunionSala(reunionId, this);
        });
    });
});

function unirseReunionSala(reunionId, button) {
    // Cambiar estado del botón
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Conectando...';
    
    fetch(`/reuniones/${reunionId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Conectando!',
                html: `
                    <p><strong>Reunión:</strong> ${data.reunion.titulo}</p>
                    <p><strong>Plataforma:</strong> ${data.reunion.plataforma}</p>
                    <p>Se abrirá en una nueva ventana...</p>
                `,
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                // Abrir enlace en nueva ventana
                window.open(data.reunion.enlace, '_blank');
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'No se puede acceder a la reunión en este momento'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión. Por favor, intenta de nuevo.'
        });
    })
    .finally(() => {
        // Restaurar botón
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function cancelarReunion(reunionId, reunionTitulo) {
    Swal.fire({
        title: '⚠️ ¿Cancelar Reunión?',
        html: `¿Estás seguro de que quieres <strong>cancelar</strong> la reunión:<br><em>"${reunionTitulo}"</em>?<br><br>La reunión seguirá existiendo pero se marcará como cancelada.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '✓ Sí, cancelar',
        cancelButtonText: '✗ No, mantener',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/reuniones/${reunionId}/cancelar`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '✅ Reunión Cancelada',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al cancelar la reunión'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Conexión',
                    text: 'No se pudo conectar con el servidor.'
                });
            });
        }
    });
}

function eliminarReunion(reunionId, reunionTitulo) {
    Swal.fire({
        title: '🗑️ ¿Eliminar Reunión?',
        html: `¿Estás seguro de que quieres <strong>eliminar permanentemente</strong> la reunión:<br><em>"${reunionTitulo}"</em>?<br><br><span class="text-danger"><strong>⚠️ Esta acción no se puede deshacer.</strong></span>`,
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '🗑️ Sí, eliminar',
        cancelButtonText: '✗ Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/reuniones/${reunionId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '✅ Reunión Eliminada',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al eliminar la reunión'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Conexión',
                    text: 'No se pudo conectar con el servidor.'
                });
            });
        }
    });
}
</script>

<style>
.reunion-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border-left: 4px solid transparent;
}

.reunion-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.reunion-card.border-warning {
    border-left-color: #ffc107 !important;
    background: linear-gradient(135deg, #fff9e6 0%, #ffffff 100%);
}

.btn-unirse-sala {
    transition: all 0.3s ease;
}

.btn-unirse-sala:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.btn-cancelar-reunion {
    transition: all 0.2s ease;
}

.btn-cancelar-reunion:hover {
    background-color: #f59e0b !important;
    border-color: #f59e0b !important;
    color: white !important;
    transform: translateY(-1px);
}

.btn-eliminar-reunion {
    transition: all 0.2s ease;
}

.btn-eliminar-reunion:hover {
    background-color: #dc2626 !important;
    border-color: #dc2626 !important;
    color: white !important;
    transform: translateY(-1px);
}
</style>
@endsection
