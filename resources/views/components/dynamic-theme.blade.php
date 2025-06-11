{{-- Composant pour injecter les couleurs dynamiques --}}
<style>
{!! $cssVariables ?? '' !!}

/* Styles supplémentaires pour les thèmes personnalisés */
@if(isset($hasCustomColors) && $hasCustomColors)
/* Styles spécifiques pour les couleurs personnalisées */
.custom-theme-indicator {
    position: fixed;
    top: 10px;
    right: 10px;
    background: var(--primary);
    color: var(--text-light);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    z-index: 9999;
    opacity: 0.8;
}

/* Animation pour les éléments avec couleurs personnalisées */
.store-card:hover,
.btn:hover,
.filter-button:hover {
    transition: all 0.3s ease;
}
@endif
</style>

@if(isset($hasCustomColors) && $hasCustomColors)
<div class="custom-theme-indicator">
    🎨 Thème personnalisé
</div>
@endif
