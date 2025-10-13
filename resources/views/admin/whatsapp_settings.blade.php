@extends('tablar::page')

@section('title', 'Configuración de WhatsApp')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-fluid">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-auto">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Configuración
                    </div>
                    <h2 class="page-title">
                        {{ __('Configuración de WhatsApp') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Actualizar Botón de WhatsApp</h3>
                        </div>
                        <div class="card-body">
                            <form id="whatsappForm" action="{{ route('update.whatsapp.settings') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="phoneNumber" class="form-label">Número de WhatsApp:</label>
                                            <input type="text" 
                                                   id="phoneNumber" 
                                                   name="phoneNumber" 
                                                   class="form-control" 
                                                   value="{{ $settings->phone_number ?? '' }}"
                                                   placeholder="Ej: +51999999999">
                                            <small class="form-text text-muted">
                                                Incluye el código de país (Ej: +51 para Perú)
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="message" class="form-label">Mensaje (opcional):</label>
                                            <textarea id="message" 
                                                     name="message" 
                                                     class="form-control" 
                                                     rows="3"
                                                     placeholder="Mensaje que se enviará automáticamente...">{{ $settings->message ?? '' }}</textarea>
                                            <small class="form-text text-muted">
                                                Puedes dejar este campo vacío si no deseas enviar un mensaje automático.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-footer d-flex flex-column flex-sm-row gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill flex-sm-grow-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M5 12l5 5l10 -10"/>
                                        </svg>
                                        Actualizar Configuración
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary flex-fill flex-sm-grow-0" onclick="testWhatsApp()">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9"/>
                                            <path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1"/>
                                        </svg>
                                        Probar WhatsApp
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Card informativa -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-info me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="12" cy="12" r="9"/>
                                    <line x1="12" y1="8" x2="12" y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                                <h5 class="mb-0">Información Importante</h5>
                            </div>
                            <div class="text-muted">
                                <ul class="mb-0 ps-3">
                                    <li class="mb-1">El número debe incluir el código de país completo</li>
                                    <li class="mb-1">El botón aparecerá en la esquina inferior derecha de todas las páginas</li>
                                    <li>Los usuarios podrán contactarte directamente por WhatsApp</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
function testWhatsApp() {
    const phoneNumber = document.getElementById('phoneNumber').value;
    const message = document.getElementById('message').value;
    
    if (!phoneNumber) {
        alert('Por favor, ingresa un número de WhatsApp');
        return;
    }
    
    // Limpiar el número de espacios y caracteres especiales excepto el +
    const cleanPhone = phoneNumber.replace(/[^+\d]/g, '');
    
    // Crear la URL de WhatsApp
    let whatsappUrl = `https://wa.me/${cleanPhone}`;
    
    if (message.trim()) {
        whatsappUrl += `?text=${encodeURIComponent(message)}`;
    }
    
    // Abrir WhatsApp en una nueva ventana
    window.open(whatsappUrl, '_blank');
}

// Validación en tiempo real del número de teléfono
document.getElementById('phoneNumber').addEventListener('input', function(e) {
    let value = e.target.value;
    
    // Solo permitir números, espacios, guiones y el símbolo +
    value = value.replace(/[^+\d\s\-]/g, '');
    
    // Asegurar que el + solo esté al principio
    if (value.includes('+') && !value.startsWith('+')) {
        value = '+' + value.replace(/\+/g, '');
    }
    
    e.target.value = value;
});

// Añadir validación visual
document.getElementById('whatsappForm').addEventListener('submit', function(e) {
    const phoneNumber = document.getElementById('phoneNumber').value;
    const phoneInput = document.getElementById('phoneNumber');
    
    if (!phoneNumber || phoneNumber.length < 8) {
        e.preventDefault();
        phoneInput.classList.add('is-invalid');
        
        if (!phoneInput.nextElementSibling || !phoneInput.nextElementSibling.classList.contains('invalid-feedback')) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = 'Por favor, ingresa un número de WhatsApp válido';
            phoneInput.parentNode.appendChild(errorDiv);
        }
        
        return false;
    } else {
        phoneInput.classList.remove('is-invalid');
        const errorDiv = phoneInput.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
});
</script>
@endpush

