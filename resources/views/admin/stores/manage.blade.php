@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    :root {
        --primary: #2a6363;
        --primary-light: #3a7a7a;
        --secondary: #0a2e2e;
        --accent: #D2B48C;
        --accent-light: #e5d5b8;
        --text: #333333;
        --text-light: #f8f8f8;
        --border: #c4b7a0;
        --error: #e74c3c;
        --success: #2ecc71;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    /* Reset et base */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Georgia', sans-serif;
        color: var(--text);
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
        background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
        padding: 2rem 0;
        color: var(--accent);
        margin-bottom: 2rem;
        border-radius: 30px 0 0 0;
    }

    .manage-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }

    .manage-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        color: var(--accent-light);
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
        font-weight: 600;
        color: var(--text);
        border-bottom: 3px solid transparent;
        transition: var(--transition);
    }

    .tab.active {
        color: var(--accent);
        border-bottom-color: var(--accent);
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
        background-color: var(--secondary);
        border-radius: 30px 0;
        box-shadow: var(--card-shadow);
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border);
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
        color: var(--accent);
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
        padding: 0.75rem 1.5rem;
        border-radius: 30px 0;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        cursor: pointer;
        border: none;
        font-family: 'Georgia', sans-serif;
        font-size: 0.9rem;
    }

    .btn-primary {
        background-color: var(--primary);
        color: var(--accent);
    }

    .btn-primary:hover {
        background-color: var(--secondary);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background-color: transparent;
        color: var(--accent);
        border: 2px solid var(--accent);
    }

    .btn-secondary:hover {
        background-color: rgba(210, 180, 140, 0.1);
        transform: translateY(-2px);
    }

    .btn-danger {
        background-color: var(--error);
        color: white;
    }

    .btn-danger:hover {
        background-color: #c0392b;
        transform: translateY(-2px);
    }

    .btn-sm {
        padding: 0.5rem 1rem;
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
        color: var(--accent-light);
    }

    .table th {
        font-weight: 600;
        background-color: rgba(42, 99, 99, 0.2);
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    .table tr:hover td {
        background-color: rgba(42, 99, 99, 0.1);
    }

    /* Badges */
    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-success {
        background-color: rgba(46, 204, 113, 0.2);
        color: var(--success);
    }

    .badge-danger {
        background-color: rgba(231, 76, 60, 0.2);
        color: var(--error);
    }

    /* Images */
    .thumbnail {
        width: 60px;
        height: 60px;
        border-radius: 10px 0;
        object-fit: cover;
        border: 2px solid var(--border);
    }

    /* Galerie d'images */
    .image-gallery {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .image-gallery-item {
        width: 40px;
        height: 40px;
        border-radius: 5px;
        object-fit: cover;
        border: 1px solid var(--border);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .image-gallery-item:hover {
        transform: scale(1.1);
    }

    /* Alertes */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 10px 0;
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    .alert-success {
        background-color: rgba(46, 204, 113, 0.1);
        color: var(--success);
        border-left: 4px solid var(--success);
    }

    .alert-danger {
        background-color: rgba(231, 76, 60, 0.1);
        color: var(--error);
        border-left: 4px solid var(--error);
    }

    /* Formulaires */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.75rem;
        font-weight: 600;
        color: var(--accent);
    }

    .form-control {
        width: 100%;
        padding: 1rem;
        border: 2px solid var(--border);
        border-radius: 10px 0;
        background: white;
        color: var(--text);
        font-size: 1rem;
        transition: var(--transition);
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(42, 99, 99, 0.2);
        outline: none;
    }

    .form-text {
        font-size: 0.8rem;
        color: var(--accent-light);
        margin-top: 0.5rem;
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
            font-size: 0.9rem;
        }
        
        .table th,
        .table td {
            padding: 0.75rem;
            font-size: 0.9rem;
        }

        .btn {
            padding: 0.6rem 1rem;
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
        color: var(--accent-light);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--accent);
    }

    .empty-state-text {
        margin-bottom: 1.5rem;
        font-size: 1.1rem;
    }

    /* Modal pour les images */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.8);
    }

    .modal-content {
        margin: auto;
        display: block;
        max-width: 80%;
        max-height: 80%;
    }

    .modal-close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        cursor: pointer;
    }

    .modal-close:hover {
        color: #bbb;
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
        <div class="tab" data-tab="staff">Personne</div>
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
                                        @php
                                            $images = json_decode($product->image, true);
                                        @endphp
                                        
                                        @if($images && count($images) > 0)
                                            {{-- <img src="{{ asset('Produit/' . $images[0]) }}" alt="{{ $product->name  > 0 }}" class="thumbnail"> --}}
                                            <img src="{{ asset('Produit/' . $images[0]) }}" alt="{{ $product->name }}" class="thumbnail">
                                            
                                            @if(count($images) > 1)
                                                <div class="image-gallery">
                                                    @foreach(array_slice($images, 1, 3) as $index => $image)
                                                        <img src="{{ asset('Produit/' . $image) }}" alt="{{ $product->name }}" class="image-gallery-item" onclick="openImageModal('{{ asset('Produit/' . $image) }}')">
                                                    @endforeach
                                                    
                                                    @if(count($images) > 4)
                                                        <div class="image-gallery-item" style="display: flex; align-items: center; justify-content: center; background-color: rgba(42, 99, 99, 0.2);">
                                                            <span style="color: var(--accent);">+{{ count($images) - 4 }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
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
                                            <img src="{{ asset('Stuff/' . $staff->image) }}" alt="{{ $staff->name }}" class="thumbnail">
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
                        <tr>
                            <th>Téléphone</th>
                            <td>{{ $store->phone ?? '-' }}</td>
                        </tr>
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

<!-- Modal pour afficher les images en grand -->
<div id="imageModal" class="modal">
    <span class="modal-close" onclick="closeImageModal()">&times;</span>
    <img class="modal-content" id="modalImage">
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
    
    // Fonctions pour la modal d'image
    function openImageModal(src) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        modal.style.display = "block";
        modalImg.src = src;
    }
    
    function closeImageModal() {
        document.getElementById('imageModal').style.display = "none";
    }
</script>
@endpush
