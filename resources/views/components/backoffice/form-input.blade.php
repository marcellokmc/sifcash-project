<!-- Component Input Réutilisable -->
<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}{{ isset($required) && $required ? ' *' : '' }}</label>
    <input type="{{ $type ?? 'text' }}" 
           class="form-control @error($name) is-invalid @enderror" 
           id="{{ $name }}" 
           name="{{ $name }}" 
           value="{{ old($name, $value ?? '') }}"
           {{ isset($required) && $required ? 'required' : '' }}
           {{ isset($placeholder) ? "placeholder=$placeholder" : '' }}>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @if(isset($help))
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
</div>