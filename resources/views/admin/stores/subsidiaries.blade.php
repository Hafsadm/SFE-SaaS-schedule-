@extends('layouts.app')

@section('title', 'Gestion des filiales')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>Gestion des filiales</h1>
        <p class="page-subtitle">Magasin principal: {{ $store->nom }}</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Filiales de {{ $store->nom }}</h2>
            <a href="{{ route('admin.stores.create') }}?parent_store_id={{ $store->id }}" class="btn-primary">
                <i class="fas fa-plus-circle"></i>
                Ajouter une filiale
            </a>
        </div>

        <div class="card-body">
            @if($subsidiaries->count() > 0)
                <div class="subsidiaries-list">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Adresse</th>
                                <th>Ville</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subsidiaries as $subsidiary)
                                <tr>
                                    <td>{{ $subsidiary->nom }}</td>
                                    <td>{{ $subsidiary->adresse }}</td>
                                    <td>{{ $subsidiary->ville }}</td>
                                    <td>
                                        <span class="status-badge {{ $subsidiary->is_closed ? 'closed' : 'open' }}">
                                            {{ $subsidiary->is_closed ? 'Fermé' : 'Ouvert' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.stores.edit', $subsidiary) }}" class="btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                                Modifier
                                            </a>
                                            <a href="{{ route('admin.stores.schedules.select-type', $subsidiary) }}" class="btn-sm btn-primary">
                                                <i class="fas fa-clock"></i>
                                                Horaires
                                            </a>
                                            <form action="{{ route('admin.stores.destroy', $subsidiary) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette filiale?')">
                                                    <i class="fas fa-trash"></i>
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
                    <i class="fas fa-store-alt"></i>
                    <p>Ce magasin principal n'a pas encore de filiales.</p>
                    <a href="{{ route('admin.stores.create') }}?parent_store_id={{ $store->id }}" class="btn-primary">
                        <i class="fas fa-plus-circle"></i>
                        Ajouter une première filiale
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h2 class="card-title">Actions sur les filiales</h2>
        </div>
        <div class="card-body">
            <div class="action-cards">
                <div class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <div class="action-content">
                        <h3>Synchroniser les horaires</h3>
                        <p>Appliquer les horaires du magasin principal à toutes les filiales.</p>
                        <form action="{{ route('admin.stores.apply-schedules', $store) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-secondary" onclick="return confirm('Êtes-vous sûr de vouloir appliquer les horaires de ce magasin principal à toutes ses filiales? Cette action écrasera les horaires existants des filiales.')">
                                Synchroniser maintenant
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div class="action-content">
                        <h3>Voir sur la carte</h3>
                        <p>Afficher le magasin principal et toutes ses filiales sur la carte.</p>
                        <a href="{{ route('admin.stores.index') }}#store-{{ $store->id }}" class="btn-secondary">
                            Voir sur la carte
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="back-link">
        <a href="{{ route('admin.stores.index') }}">
            <i class="fas fa-arrow-left"></i>
            Retour à la liste des points de vente
        </a>
    </div>
</div>

<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .page-header {
        margin-bottom: 2rem;
    }
    
    .page-header h1 {
        font-size: 2rem;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }
    
    .page-subtitle {
        color: var(--secondary);
        font-size: 1.1rem;
    }
    
    .card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .card-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .card-title {
        margin: 0;
        font-size: 1.5rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table th, .table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .table th {
        font-weight: 600;
        background-color: #f8fafc;
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .status-badge.open {
        background-color: rgba(93, 187, 99, 0.2);
        color: #5DBB63;
    }
    
    .status-badge.closed {
        background-color: rgba(220, 53, 69, 0.2);
        color: #dc3545;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .btn-primary {
        background-color: var(--primary);
        color: white;
        border: none;
        border-radius: 5px;
        padding: 0.75rem 1.5rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background-color: var(--secondary);
        transform: translateY(-2px);
    }
    
    .btn-secondary {
        background-color: var(--secondary);
        color: white;
        border: none;
        border-radius: 5px;
        padding: 0.75rem 1.5rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        background-color: var(--primary);
        transform: translateY(-2px);
    }
    
    .btn-danger {
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 0.375rem 0.75rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-danger:hover {
        background-color: #c82333;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: var(--primary);
        margin-bottom: 1rem;
    }
    
    .empty-state p {
        margin-bottom: 1.5rem;
        color: #64748b;
    }
    
    .action-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .action-card {
        display: flex;
        background-color: #f8fafc;
        border-radius: 10px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    .action-icon {
        font-size: 2rem;
        color: var(--primary);
        margin-right: 1.5rem;
        display: flex;
        align-items: center;
    }
    
    .action-content h3 {
        margin: 0 0 0.5rem 0;
        color: var(--primary);
    }
    
    .action-content p {
        margin-bottom: 1rem;
        color: #64748b;
    }
    
    .back-link {
        margin-top: 2rem;
    }
    
    .back-link a {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary);
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .back-link a:hover {
        color: var(--secondary);
    }
    
    .mt-4 {
        margin-top: 1.5rem;
    }
    
    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .action-buttons {
            flex-direction: column;
            width: 100%;
        }
        
        .btn-sm {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection
