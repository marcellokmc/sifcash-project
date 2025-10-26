@props(['id', 'title', 'message', 'confirmText' => 'Confirmer', 'cancelText' => 'Annuler', 'confirmClass' => 'btn-primary'])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="mdi mdi-help-circle fs-1 text-warning mb-3"></i>
                    <p class="mb-0">{{ $message }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $cancelText }}</button>
                <button type="button" class="btn {{ $confirmClass }}" id="{{ $id }}-confirm">{{ $confirmText }}</button>
            </div>
        </div>
    </div>
</div>
