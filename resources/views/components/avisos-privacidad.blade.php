<div class="mb-3">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="acepto_terminos" name="acepto_terminos" required>
        <label class="form-check-label" for="acepto_terminos">
            He leído y acepto los 
            <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#terminosModal">términos y condiciones</a>
            y la 
            <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#privacidadModal">política de privacidad</a>
        </label>
    </div>
    @error('acepto_terminos')
        <div class="text-danger small mt-1">
            <i class="fas fa-exclamation-circle"></i> {{ $message }}
        </div>
    @enderror
</div>

<!-- Modal Términos y Condiciones -->
<div class="modal fade" id="terminosModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Términos y Condiciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                @include('components.terminos-contenido')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Política de Privacidad -->
<div class="modal fade" id="privacidadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Política de Privacidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                @include('components.privacidad-contenido')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>