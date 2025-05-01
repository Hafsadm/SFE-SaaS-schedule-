@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    /* Variables de couleur */
    :root {
        --primary: #A67C52; /* Marron doré */
        --primary-light: #C8AD7F;
        --primary-dark: #8A6642;
        --secondary: #D2B48C; /* Beige doré */
        --light-beige: #F5F5DC;
        --dark-beige: #E0C9B4;
        --text-dark: #130404;
        --text-light: #F8F4E6;
        --success: #82B183; /* Vert doux */
        --error: #C17C74; /* Rouge doux */
        --border: #E0C9B4;
        --card-shadow: 0 4px 12px rgba(92, 64, 51, 0.1);
        --transition: all 0.3s ease;
    }

    /* Reset et base */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
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
    .manage-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        padding: 2rem 0;
        color: var(--text-light);
        margin-bottom: 2rem;
    }

    .manage-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .manage-subtitle {
        font-size: 1rem;
        opacity: 0.9;
    }

    /* Onglets */
    .tabs {
        display: flex;
        border-bottom: 1px solid var(--border);
        margin-bottom: 2rem;
    }

    .tab {
        padding: 1rem 1.5rem;
        cursor: pointer;
        font-weight: 500;
        color: var(--text-dark);
        border-bottom: 3px solid transparent;
        transition: var(--transition);
    }

    .tab.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }

    .tab:hover:not(.active) {
        color: var(--primary-light);
        border-bottom-color: var(--primary-light);
    }

    /* Contenu des onglets */
    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
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

    .card-actions {
        display: flex;
        gap: 1rem;
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
        font-family: 'Inter', sans-serif;
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

    .btn-danger {
        background-color: var(--error);
        color: white;
    }

    .btn-danger:hover {
        background-color: #A6655E;
    }

    .btn-sm {
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
    }

    /* Tableaux */
    .table-container {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border);
    }

    .table th {
        font-weight: 600;
        color: var(--primary);
        background-color: var(--light-beige);
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    .table tr:hover td {
        background-color: rgba(245, 245, 220, 0.5);
    }

    /* Badges */
    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .badge-success {
        background-color: rgba(130, 177, 131, 0.2);
        color: var(--success);
    }

    .badge-danger {
        background-color: rgba(193, 124, 116, 0.2);
        color: var(--error);
    }

    /* Images */
    .thumbnail {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
    }

    /* Alertes */
    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background-color: rgba(130, 177, 131, 0.2);
        color: var(--success);
        border: 1px solid var(--success);
    }

    .alert-danger {
        background-color: rgba(193, 124, 116, 0.2);
        color: var(--error);
        border: 1px solid var(--error);
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

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 0 1rem;
        }
        
        .tabs {
            flex-wrap: wrap;
        }
        
        .tab {
            flex: 1 0 auto;
            text-align: center;
            padding: 0.75rem;
        }
        
        .table th,
        .table td {
            padding: 0.75rem;
        }
    }

    /* Utilitaires */
    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .mt-4 {
        margin-top: 1rem;
    }

    .mb-4 {
        margin-bottom: 1rem;
    }

    .d-flex {
        display: flex;
    }

    .align-center {
        align-items: center;
    }

    .justify-between {
        justify-content: space-between;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-dark);
        opacity: 0.7;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--primary-light);
    }

    .empty-state-text {
        margin-bottom: 1.5rem;
        font-size: 1.1rem;
    }
</style>
@endpush

@section('content')
<!-- En-tête -->
<div class="manage-header">
    <div class="container">
        <h1 class="manage-title">Gestion du point de vente: {{ $store->nom }}</h1>
        <p class="manage-subtitle">{{ $store->adresse }}, {{ $store->ville }}</p>
    </div>
</div>

<div class="container">
    <!-- Alertes -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Onglets -->
    <div class="tabs">
        <div class="tab active" data-tab="products">Produits</div>
        <div class="tab" data-tab="staff">Personnel</div>
        <div class="tab" data-tab="info">Informations générales</div>
    </div>

    <!-- Contenu des onglets -->
    
    <!-- Onglet Produits -->
    <div class="tab-content active" id="products-tab">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Produits disponibles</h2>
                <div class="card-actions">
                    <a href="{{ route('admin.stores.products.create', $store->id) }}" class="btn btn-primary">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Ajouter un produit
                    </a>
                </div>
            </div>
            
            @if($store->products->count() > 0)
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Nom</th>
                                <th>Catégorie</th>
                                <th>Prix</th>
                                <th>Disponibilité</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($store->products as $product)
                                <tr>
                                    <td>
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="thumbnail">
                                        @else
                                            <div class="thumbnail d-flex align-center justify-between" style="background-color: var(--light-beige);">
                                                <i data-lucide="package" class="w-6 h-6 m-auto" style="color: var(--primary);"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category }}</td>
                                    <td>{{ number_format($product->price, 2) }} €</td>
                                    <td>
                                        @if($product->is_available)
                                            <span class="badge badge-success">Disponible</span>
                                        @else
                                            <span class="badge badge-danger">Indisponible</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.stores.products.edit', [$store->id, $product->id]) }}" class="btn btn-secondary btn-sm">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                                Modifier
                                            </a>
                                            <form action="{{ route('admin.stores.products.destroy', [$store->id, $product->id]) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i data-lucide="package" class="w-12 h-12"></i>
                    <p class="empty-state-text">Aucun produit n'a encore été ajouté à ce point de vente.</p>
                    <a href="{{ route('admin.stores.products.create', $store->id) }}" class="btn btn-primary">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Ajouter votre premier produit
                    </a>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Onglet Personnel -->
    <div class="tab-content" id="staff-tab">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Personnel</h2>
                <div class="card-actions">
                    <a href="{{ route('admin.stores.staff.create', $store->id) }}" class="btn btn-primary">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        Ajouter un membre
                    </a>
                </div>
            </div>
            
            @if($store->staff->count() > 0)
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Nom</th>
                                <th>Rôle</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($store->staff as $staff)
                                <tr>
                                    <td>
                                        @if($staff->image)
                                            <img src="{{ asset('storage/' . $staff->image) }}" alt="{{ $staff->name }}" class="thumbnail">
                                        @else
                                            <div class="thumbnail d-flex align-center justify-between" style="background-color: var(--light-beige);">
                                                <i data-lucide="user" class="w-6 h-6 m-auto" style="color: var(--primary);"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $staff->name }}</td>
                                    <td>{{ $staff->role }}</td>
                                    <td>{{ $staff->email ?? '-' }}</td>
                                    <td>{{ $staff->phone ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.stores.staff.edit', [$store->id, $staff->id]) }}" class="btn btn-secondary btn-sm">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                                Modifier
                                            </a>
                                            <form action="{{ route('admin.stores.staff.destroy', [$store->id, $staff->id]) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce membre du personnel?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i data-lucide="users" class="w-12 h-12"></i>
                    <p class="empty-state-text">Aucun membre du personnel n'a encore été ajouté à ce point de vente.</p>
                    <a href="{{ route('admin.stores.staff.create', $store->id) }}" class="btn btn-primary">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        Ajouter votre premier membre
                    </a>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Onglet Informations générales -->
    <div class="tab-content" id="info-tab">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Informations générales</h2>
                <div class="card-actions">
                    <a href="{{ route('admin.stores.edit', $store->id) }}" class="btn btn-primary">
                        <i data-lucide="edit" class="w-4 h-4"></i>
                        Modifier les informations
                    </a>
                </div>
            </div>
            
            <div class="table-container">
                <table class="table">
                    <tbody>
                        <tr>
                            <th style="width: 30%;">Nom</th>
                            <td>{{ $store->nom }}</td>
                        </tr>
                        <tr>
                            <th>Adresse</th>
                            <td>{{ $store->adresse }}</td>
                        </tr>
                        <tr>
                            <th>Ville</th>
                            <td>{{ $store->ville }}</td>
                        </tr>
                        {{-- <tr>
                            <th>Code postal</th>
                            <td>{{ $store->code_postal }}</td>
                        </tr> --}}
                        <tr>
                            <th>Téléphone</th>
                            <td>{{ $store->phone ?? '-' }}</td>
                        </tr>
                        {{-- <tr>
                            <th>Email</th>
                            <td>{{ $store->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Site web</th>
                            <td>{{ $store->website ?? '-' }}</td>
                        </tr> --}}
                        <tr>
                            <th>Services</th>
                            <td>
                                @if(is_array($store->services))
                                    @foreach($store->services as $service)
                                        @if($service == '1')
                                            <span class="badge badge-success">Dentiste</span>
                                        @elseif($service == '2')
                                            <span class="badge badge-success">Opticien</span>
                                        @elseif($service == '3')
                                            <span class="badge badge-success">Audition</span>
                                        @else
                                            <span class="badge badge-success">{{ $service }}</span>
                                        @endif
                                    @endforeach
                                @else
                                    {{ $store->services ?? 'Non défini' }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Lien de rendez-vous</th>
                            <td>
                                @if($store->lien_rdv)
                                    <a href="{{ $store->lien_rdv }}" target="_blank" class="btn btn-secondary btn-sm">
                                        <i data-lucide="external-link" class="w-4 h-4"></i>
                                        Voir le lien
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
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
        
        // Gestion des onglets
        const tabs = document.querySelectorAll('.tab');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const tabId = tab.getAttribute('data-tab');
                
                // Désactiver tous les onglets et contenus
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                // Activer l'onglet et le contenu sélectionnés
                tab.classList.add('active');
                document.getElementById(`${tabId}-tab`).classList.add('active');
            });
        });
    });
</script>
@endpush