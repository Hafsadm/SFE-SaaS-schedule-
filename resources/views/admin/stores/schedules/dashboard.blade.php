@extends('layouts.app')

@section('title', 'Gestion des horaires')

@section('content')
<div class="schedules-dashboard">
    <!-- En-tête -->
    <div class="dashboard-header">
        <div class="header-content">
            <h1 class="dashboard-title">Gestion des horaires</h1>
            <div class="header-actions">
                <a href="{{ route('admin.stores.index') }}" class="action-button secondary">
                    <i class="fas fa-store"></i>
                    Points de vente
                </a>
                <a href="{{ route('admin.schedules.calendar') }}" class="action-button secondary">
                    <i class="fas fa-calendar-alt"></i>
                    Vue calendrier
                </a>
                <a href="{{ route('admin.schedules.bulk') }}" class="action-button primary">
                    <i class="fas fa-clock"></i>
                    Gestion en masse
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-store"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total_stores'] }}</div>
                <div class="stat-label">Points de vente</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon open">
                <i class="fas fa-door-open"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['open_today'] }}</div>
                <div class="stat-label">Ouverts aujourd'hui</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon closed">
                <i class="fas fa-door-closed"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['closed_today'] }}</div>
                <div class="stat-label">Fermés aujourd'hui</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon exception">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total_exceptions'] }}</div>
                <div class="stat-label">Exceptions à venir</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon holiday">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['upcoming_holidays'] }}</div>
                <div class="stat-label">Jours fériés à venir</div>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="dashboard-content">
        <!-- Colonne de gauche: Liste des magasins -->
        <div class="stores-column">
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title">Points de vente</h2>
                    <div class="panel-actions">
                        <div class="search-container">
                            <input type="text" id="store-search" placeholder="Rechercher..." onkeyup="filterStores()">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="filter-container">
                            <select id="status-filter" onchange="filterStores()">
                                <option value="">Tous les statuts</option>
                                <option value="open">Ouvert</option>
                                <option value="closed">Fermé</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="stores-list" id="stores-list">
                        @foreach($stores as $store)
                        <div class="store-item" data-status="{{ $store->is_closed ? 'closed' : 'open' }}">
                            <div class="store-info">
                                <div class="store-name">{{ $store->nom }}</div>
                                <div class="store-location">{{ $store->ville }}</div>
                            </div>
                            <div class="store-status {{ $store->is_closed ? 'closed' : 'open' }}">
                                {{ $store->is_closed ? 'Fermé' : 'Ouvert' }}
                            </div>
                            <div class="store-actions">
                                <a href="{{ route('admin.schedules.store', $store) }}" class="store-action" title="Voir les horaires">
                                    <i class="fas fa-clock"></i>
                                </a>
                                <a href="{{ route('admin.stores.schedules.select-type', $store) }}" class="store-action" title="Ajouter un horaire">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne de droite: Événements à venir -->
        <div class="events-column">
            <!-- Exceptions à venir -->
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title">Exceptions à venir</h2>
                </div>
                <div class="panel-body">
                    @if($upcomingExceptions->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <p>Aucune exception à venir</p>
                        </div>
                    @else
                        <div class="events-list">
                            @foreach($upcomingExceptions as $exception)
                                <div class="event-item">
                                    <div class="event-date">
                                        <span class="day">{{ $exception->exception_date->format('d') }}</span>
                                        <span class="month">{{ $exception->exception_date->locale('fr')->format('M') }}</span>
                                    </div>
                                    <div class="event-details">
                                        <div class="event-title">{{ $exception->store->nom }}</div>
                                        <div class="event-description">{{ $exception->exception_raison }}</div>
                                        <div class="event-status {{ $exception->is_closed ? 'closed' : 'modified' }}">
                                            {{ $exception->is_closed ? 'Fermé' : 'Horaires modifiés' }}
                                        </div>
                                    </div>
                                    <div class="event-actions">
                                        <a href="{{ route('admin.stores.exceptions.edit', [$exception->store, $exception]) }}" class="event-action" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Jours fériés à venir -->
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title">Jours fériés à venir</h2>
                </div>
                <div class="panel-body">
                    @if($upcomingHolidays->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-calendar-day"></i>
                            <p>Aucun jour férié à venir</p>
                        </div>
                    @else
                        <div class="events-list">
                            @foreach($upcomingHolidays as $holiday)
                                <div class="event-item">
                                    <div class="event-date holiday">
                                        <span class="day">{{ $holiday->holiday_date->format('d') }}</span>
                                        <span class="month">{{ $holiday->holiday_date->locale('fr')->format('M') }}</span>
                                    </div>
                                    <div class="event-details">
                                        <div class="event-title">{{ $holiday->holiday_name }}</div>
                                        <div class="event-description">{{ $holiday->store->nom }}</div>
                                        <div class="event-status closed">Fermé</div>
                                    </div>
                                    <div class="event-actions">
                                        <a href="{{ route('admin.stores.holidays.edit', [$holiday->store, $holiday]) }}" class="event-action" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary: #0A2E2E; 
        --secondary: #2A6363; 
        --tertiary: #8E6E53;
        --light: #C69C72; 
        --text-dark: #000000; 
        --text-light: #FFFFFF; 
        --success: #5DBB63;
        --error: #dc3545;
        --warning: #f59e0b;
        --border: #E6D8C3; 
        --card-shadow: 0 4px 12px rgba(10, 46, 46, 0.1);
    }

    /* Base */
    .schedules-dashboard {
        max-width: 1600px;
        margin: 0 auto;
        padding: 1.5rem;
    }

    /* En-tête */
    .dashboard-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border-radius: 30px 0;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--card-shadow);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dashboard-title {
        color: var(--text-light);
        font-size: 1.8rem;
        margin: 0;
        position: relative;
        padding-left: 1rem;
    }

    .dashboard-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 70%;
        background-color: var(--light);
        border-radius: 2px;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .action-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 30px 0;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .action-button.primary {
        background-color: var(--light);
        color: var(--primary);
    }

    .action-button.primary:hover {
        background-color: #d8b08a;
        transform: translateY(-2px);
    }

    .action-button.secondary {
        background-color: rgba(255, 255, 255, 0.2);
        color: var(--text-light);
    }

    .action-button.secondary:hover {
        background-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    /* Statistiques */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background-color: var(--text-light);
        border-radius: 20px 0;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(10, 46, 46, 0.15);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 15px 0;
        background-color: var(--primary);
        color: var(--text-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon.open {
        background-color: var(--success);
    }

    .stat-icon.closed {
        background-color: var(--error);
    }

    .stat-icon.exception {
        background-color: var(--warning);
    }

    .stat-icon.holiday {
        background-color: var(--tertiary);
    }

    .stat-content {
        flex: 1;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary);
        line-height: 1.2;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #666;
    }

    /* Contenu principal */
    .dashboard-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    /* Panneaux */
    .panel {
        background-color: var(--text-light);
        border-radius: 30px 0;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .panel-header {
        padding: 1.25rem 1.5rem;
        background-color: var(--primary);
        color: var(--text-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .panel-title {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .panel-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .search-container {
        position: relative;
    }

    .search-container input {
        padding: 0.5rem 1rem 0.5rem 2.5rem;
        border: none;
        border-radius: 20px 0;
        background-color: rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        width: 200px;
    }

    .search-container input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .search-container i {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
    }

    .filter-container select {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 20px 0;
        background-color: rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        cursor: pointer;
    }

    .panel-body {
        padding: 1.5rem;
        max-height: 600px;
        overflow-y: auto;
    }

    /* Liste des magasins */
    .stores-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .store-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        background-color: #f9f9f9;
        border-radius: 15px 0;
        border: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .store-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(10, 46, 46, 0.1);
    }

    .store-info {
        flex: 1;
    }

    .store-name {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .store-location {
        font-size: 0.9rem;
        color: #666;
    }

    .store-status {
        padding: 0.35rem 0.75rem;
        border-radius: 20px 0;
        font-size: 0.8rem;
        font-weight: 600;
        margin: 0 1rem;
    }

    .store-status.open {
        background-color: rgba(93, 187, 99, 0.2);
        color: var(--success);
    }

    .store-status.closed {
        background-color: rgba(220, 53, 69, 0.2);
        color: var(--error);
    }

    .store-actions {
        display: flex;
        gap: 0.5rem;
    }

    .store-action {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px 0;
        background-color: var(--primary);
        color: var(--text-light);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .store-action:hover {
        background-color: var(--secondary);
        transform: translateY(-2px);
    }

    /* Liste des événements */
    .events-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .event-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background-color: #f9f9f9;
        border-radius: 15px 0;
        border: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .event-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(10, 46, 46, 0.1);
    }

    .event-date {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        background-color: var(--primary);
        color: var(--text-light);
        border-radius: 15px 0;
    }

    .event-date.holiday {
        background-color: var(--tertiary);
    }

    .event-date .day {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
    }

    .event-date .month {
        font-size: 0.8rem;
        text-transform: uppercase;
    }

    .event-details {
        flex: 1;
    }

    .event-title {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .event-description {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .event-status {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 10px 0;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .event-status.closed {
        background-color: rgba(220, 53, 69, 0.2);
        color: var(--error);
    }

    .event-status.modified {
        background-color: rgba(245, 158, 11, 0.2);
        color: var(--warning);
    }

    .event-actions {
        display: flex;
        gap: 0.5rem;
    }

    .event-action {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px 0;
        background-color: var(--primary);
        color: var(--text-light);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .event-action:hover {
        background-color: var(--secondary);
        transform: translateY(-2px);
    }

    /* État vide */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        text-align: center;
        color: #999;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state p {
        font-size: 1.1rem;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .dashboard-content {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .dashboard-title::before {
            display: none;
        }

        .header-actions {
            width: 100%;
            justify-content: center;
            flex-wrap: wrap;
        }

        .action-button {
            flex: 1;
            justify-content: center;
        }

        .stats-container {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }

        .panel-header {
            flex-direction: column;
            gap: 1rem;
        }

        .panel-actions {
            width: 100%;
            flex-direction: column;
        }

        .search-container, .filter-container, .search-container input, .filter-container select {
            width: 100%;
        }
    }

    /* Dark mode */
    @media (prefers-color-scheme: dark) {
        .store-item, .event-item {
            background-color: #0A2E2E;
            border-color: #2A6363;
        }
        
        .store-name, .event-title {
            color: var(--text-light);
        }
        
        .store-location, .event-description {
            color: #aaa;
        }
        
        .stat-card {
            background-color: #0A2E2E;
            border-color: #2A6363;
        }
        
        .stat-value {
            color: var(--text-light);
        }
        
        .stat-label {
            color: #aaa;
        }
    }
</style>

<script>
    // Filtrer les magasins
    function filterStores() {
        const searchText = document.getElementById('store-search').value.toLowerCase();
        const statusFilter = document.getElementById('status-filter').value;
        const storeItems = document.querySelectorAll('.store-item');
        
        storeItems.forEach(item => {
            const storeName = item.querySelector('.store-name').textContent.toLowerCase();
            const storeLocation = item.querySelector('.store-location').textContent.toLowerCase();
            const storeStatus = item.dataset.status;
            
            const matchesSearch = storeName.includes(searchText) || storeLocation.includes(searchText);
            const matchesStatus = statusFilter === '' || storeStatus === statusFilter;
            
            item.style.display = matchesSearch && matchesStatus ? 'flex' : 'none';
        });
    }
    
    // Initialiser les icônes Font Awesome
    document.addEventListener('DOMContentLoaded', function() {
        // Si vous utilisez Lucide au lieu de Font Awesome
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection
