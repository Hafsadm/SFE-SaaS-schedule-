@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    /* Variables de couleur */
    :root {
           --primary: #0A2E2E; 
        --secondary: #143333; 
        --primary-light: #5aad9e;
        --primary-dark: #337b8d;
        --secondary-light: #ffffff;
        --secondary-dark: #0a2e2e;
        --tertiary: #8E6E53;
        --light: #C69C72; 
        --text-dark: #000000; 
        --text-light: #FFFFFF; 
        --success: #5DBB63;
        --error: #dc3545;
        --border: #E6D8C3; 
        --card-shadow: 0 4px 12px rgba(10, 46, 46, 0.1);
    }


    /* Reset et base */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'georgia', serif;
        font-size: 1rem;
        color: var(--text-dark);
        background-color: #ffffff;
        line-height: 1.6;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* En-tête */
    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        padding: 2rem 0;
        color: var(--text-light);
        margin-bottom: 2rem;
        border-radius: 0 0 30px 30px;
    }

    .page-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        font-size: 1rem;
        opacity: 0.9;
    }

    /* Cartes */
    .card {
        background-color: var(--text-light);
        border-radius: 30px 0;
        box-shadow: var(--card-shadow);
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border);
        position: relative;
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(to bottom, var(--primary), var(--secondary));
        border-radius: 30px 0 0 0;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border);
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary);
        position: relative;
        padding-left: 1rem;
    }

    .card-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 70%;
        background-color: var(--primary);
        border-radius: 2px;
    }

    /* Formulaires */
    .form-group {
        margin-bottom: 2rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.75rem;
        font-weight: 500;
        color: var(--primary);
    }

    .form-control {
        width: 100%;
        padding: 1rem;
        border: 1px solid var(--border);
        border-radius: 20px 0;
        font-family: 'georgia', serif;
        font-size: 1rem;
        background-color: var(--light-beige);
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(66, 145, 130, 0.2);
    }

    .form-text {
        font-size: 0.9rem;
        color: var(--text-dark);
        opacity: 0.7;
        margin-top: 0.5rem;
        display: block;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        accent-color: var(--primary);
    }

    .form-check-label {
        font-size: 1rem;
        color: var(--text-dark);
    }

    /* Boutons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem 1.75rem;
        border-radius: 30px 0;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        font-family: 'Inter', sans-serif;
        font-size: 1rem;
        box-shadow: var(--card-shadow);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: var(--text-light);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #4BA793 0%, #2A6A7D 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(66, 145, 130, 0.3);
    }

    .btn-secondary {
        background-color: var(--text-light);
        color: var(--primary);
        border: 1px solid var(--primary);
    }

    .btn-secondary:hover {
        background-color: rgba(66, 145, 130, 0.1);
        transform: translateY(-2px);
    }

    /* Prévisualisation d'image */
    .image-preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1rem;
    }

    .image-preview {
        width: 150px;
        height: 150px;
        border-radius: 20px 0;
        background-color: var(--light-beige);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 1px dashed var(--border);
        position: relative;
    }

    .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-preview i {
        color: var(--primary);
        font-size: 3rem;
    }

    .image-upload-container {
        position: relative;
    }

    .image-upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 150px;
        height: 150px;
        border-radius: 20px 0;
        background-color: var(--light-beige);
        border: 2px dashed var(--border);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .image-upload-label:hover {
        background-color: rgba(66, 145, 130, 0.1);
    }

    .image-upload-icon {
        font-size: 2rem;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .image-upload-text {
        font-size: 0.9rem;
        color: var(--primary);
        text-align: center;
    }

    /* Alertes */
    .alert {
        padding: 1.5rem;
        border-radius: 20px 0;
        margin-bottom: 2rem;
        border-left: 4px solid;
    }

    .alert-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--error);
        border-color: var(--error);
    }

    .alert ul {
        margin: 0;
        padding-left: 1.5rem;
    }

    /* Utilitaires */
    .d-flex {
        display: flex;
    }

    .justify-between {
        justify-content: space-between;
    }

    .gap-4 {
        gap: 1.5rem;
    }

    .mt-6 {
        margin-top: 3rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 0 1.5rem;
        }
        
        .card {
            padding: 1.5rem;
        }
        
        .btn {
            width: 100%;
            padding: 1rem;
        }
        
        .d-flex {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>
@endpush

@section('content')
<!-- En-tête -->
<div class="page-header">
    <div class="container">
        <h1 class="page-title">Ajouter un produit</h1>
        <p class="page-subtitle">Point de vente: {{ $store->nom }}</p>
    </div>
</div>

<div class="container">
    <!-- Alertes d'erreur -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Informations du produit</h2>
        </div>
        
        <form action="{{ route('admin.stores.products.store', $store->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="name" class="form-label">Nom du produit *</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            
            <div class="form-group">
                <label for="category" class="form-label">Catégorie(s) *</label>
                <textarea id="category" name="category" class="form-control" rows="3" placeholder="Entrez une ou plusieurs catégories séparées par des virgules">{{ old('category') }}</textarea>
                <small class="form-text">Vous pouvez entrer plusieurs catégories séparées par des virgules (ex: Montures, Solaires)</small>
            </div>
            
            <div class="form-group">
                <label for="price" class="form-label">Prix (€) *</label>
                <input type="number" id="price" name="price" class="form-control" value="{{ old('price') }}" step="0.01" min="0" required>
                <small class="form-text">Utilisez un point pour les décimales (ex: 19.99)</small>
            </div>
            
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control" rows="5">{{ old('description') }}</textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Images du produit</label>
                <div class="image-preview-container" id="imagePreviewContainer">
                    <div class="image-upload-container">
                        <label for="images" class="image-upload-label">
                            <i data-lucide="plus" class="image-upload-icon"></i>
                            <span class="image-upload-text">Ajouter des images</span>
                        </label>
                        <input type="file" id="images" name="images[]" accept="image/*" style="display: none;" multiple onchange="previewImages(this)">
                    </div>
                </div>
                <small class="form-text">Vous pouvez sélectionner plusieurs images. Format recommandé: JPG ou PNG, max 5MB par image</small>
            </div>
            
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" id="is_available" name="is_available" class="form-check-input" {{ old('is_available') ? 'checked' : 'checked' }}>
                    <label for="is_available" class="form-check-label">Produit disponible</label>
                </div>
                <small class="form-text">Décochez si le produit est temporairement indisponible</small>
            </div>
            
            <div class="d-flex justify-between gap-4 mt-6">
                <a href="{{ route('admin.stores.manage', $store->id) }}" class="btn btn-secondary">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    Retour
                </a>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
    
    function previewImages(input) {
        const container = document.getElementById('imagePreviewContainer');
        const uploadLabel = container.querySelector('.image-upload-container');
        
        // Supprimer les prévisualisations existantes
        const existingPreviews = container.querySelectorAll('.image-preview');
        existingPreviews.forEach(preview => {
            if (!preview.classList.contains('image-upload-container')) {
                preview.remove();
            }
        });
        
        if (input.files && input.files.length > 0) {
            for (let i = 0; i < input.files.length; i++) {
                const file = input.files[i];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const preview = document.createElement('div');
                    preview.className = 'image-preview';
                    preview.innerHTML = `<img src="${e.target.result}" alt="Prévisualisation">`;
                    
                    // Insérer avant le label d'upload
                    container.insertBefore(preview, uploadLabel);
                };
                
                reader.readAsDataURL(file);
            }
        }
    }
</script>
@endpush
