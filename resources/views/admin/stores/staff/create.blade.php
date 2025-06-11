@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    /* Variables de couleur */
     :root {
    --primary: {{ $themeColors['primary_color'] ?? '#0A2E2E' }}; 
        --secondary: {{ $themeColors['secondary_color'] ?? '#2A6363' }}; 
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



    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'georgia', serif;
        color: var(--text-dark);
        background-color: #fff;
        line-height: 1.6;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* En-tête */
    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        padding: 2rem 0;
        color: var(--text-light);
        margin-bottom: 2rem;
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
        background-color: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        padding: 1.5rem;
        margin-bottom: 2rem;
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
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary);
    }

    /* Formulaires */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(166, 124, 82, 0.2);
    }

    .form-text {
        font-size: 0.8rem;
        color: var(--text-dark);
        opacity: 0.7;
        margin-top: 0.25rem;
    }

    /* Boutons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        transition: var(--transition);
        cursor: pointer;
        border: none;
        font-family: 'georgia', serif;
        font-size: 0.9rem;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
    }

    .btn-secondary {
        background-color: white;
        color: var(--primary);
        border: 1px solid var(--primary);
    }

    .btn-secondary:hover {
        background-color: var(--light-beige);
    }

    /* Prévisualisation d'image */
    .image-preview {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background-color: var(--light-beige);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 0.5rem;
        overflow: hidden;
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

    /* Alertes */
    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .alert-danger {
        background-color: rgba(193, 124, 116, 0.2);
        color: var(--error);
        border: 1px solid var(--error);
    }

    /* Utilitaires */
    .d-flex {
        display: flex;
    }

    .justify-between {
        justify-content: space-between;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .mt-4 {
        margin-top: 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 0 1rem;
        }
    }
</style>
@endpush

@section('content')
<!-- En-tête -->
<div class="page-header">
    <div class="container">
        <h1 class="page-title">Ajouter un membre du personnel</h1>
        <p class="page-subtitle">Point de vente: {{ $store->nom }}</p>
    </div>
</div>

<div class="container">
    <!-- Alertes d'erreur -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 1rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Informations du membre</h2>
        </div>
        
        <form action="{{ route('admin.stores.staff.store', $store->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="name" class="form-label">Nom complet *</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            
            <div class="form-group">
                <label for="role" class="form-label">Rôle / Fonction *</label>
                <textarea id="role" name="role" class="form-control" rows="3" placeholder="Entrez un ou plusieurs rôles séparés par des virgules">{{ old('role') }}</textarea>
                <small class="form-text">Vous pouvez entrer plusieurs rôles séparés par des virgules (ex: Opticien, Responsable)</small>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>
            
            <div class="form-group">
                <label for="phone" class="form-label">Téléphone</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>
            
            <div class="form-group">
                <label for="bio" class="form-label">Biographie</label>
                <textarea id="bio" name="bio" class="form-control" rows="4">{{ old('bio') }}</textarea>
                <small class="form-text">Une courte description de l'expérience et des compétences du membre</small>
            </div>
            
            <div class="form-group">
                <label for="image" class="form-label">Photo</label>
                <input type="file" id="image" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                <small class="form-text">Format recommandé: JPG ou PNG, max 2MB</small>
                
                <div class="image-preview" id="imagePreview">
                    <i data-lucide="user" class="w-12 h-12"></i>
                </div>
            </div>
            
            <div class="d-flex justify-between mt-4">
                <a href="{{ route('admin.stores.manage', $store->id) }}" class="btn btn-secondary">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Retour
                </a>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save" class="w-4 h-4"></i>
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
        // Initialiser les icônes Lucide
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
    
    // Prévisualisation de l'image
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Prévisualisation">`;
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.innerHTML = `<i data-lucide="user" class="w-12 h-12"></i>`;
            lucide.createIcons();
        }
    }
</script>
@endpush
