@extends('layouts.app')

@section('title', 'Points de vente')

<!-- Injection des couleurs dynamiques -->
@include('components.dynamic-theme')

<style>

 :root {
        --primary: {{ $themeColors['primary_color'] ?? '#0A2E2E' }}; 
        --secondary: {{ $themeColors['secondary_color'] ?? '#2A6363' }}; 
        /* --light: {{ $themeColors['accent_color'] ?? '#8E6E53' }};  */

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

    /* Base - Les variables CSS sont maintenant injectées par le composant dynamic-theme */
    body {
        font-family: 'Georgia', sans-serif;
        color: var(--text-dark);
        background-color: #FFFFFF;
        margin: 0;
        padding: 0;
    }

    /* Container principal */
    .admin-container {
        display: flex;
        height: 100vh;
        overflow: hidden;
        background-color: #FFFFFF;
    }

    /* Sidebar */
    .sidebar {
        width: 300px;
        flex-shrink: 0;
        background-color: var(--text-light);
        border-right: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
        border-radius: 30px 0;
    }

    .sidebar-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: var(--text-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sidebar-header h2 {
        margin: 0;
        color: #ffffff;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .add-store-button, .add-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        padding: 0.5rem 1rem;
        border-radius: 40px;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .add-button {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        padding: 0.8rem 1.5rem;
        box-shadow: var(--card-shadow);
    }

    .add-store-button:hover, .add-button:hover {
        background-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .add-button:hover {
        background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
        box-shadow: 0 6px 20px rgba(10, 46, 46, 0.3);
    }

    .sidebar-search {
        padding: 1rem;
        position: relative;
        border-bottom: 1px solid var(--border);
    }

    .search-icon {
        position: absolute;
        left: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary);
    }

    #sidebar-search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 1px solid var(--border);
        border-radius: 30px;
        font-size: 0.9rem;
        background-color: var(--text-light);
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    #sidebar-search-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(10, 46, 46, 0.2);
    }

    .stores-list {
        flex: 1;
        overflow-y: auto;
    }

    .stores-list::-webkit-scrollbar {
        width: 6px;
    }

    .stores-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 30px;
    }

    .stores-list::-webkit-scrollbar-thumb {
        background-color: var(--secondary);
        border-radius: 10px;
    }

    .store-item {
        border-bottom: 1px solid var(--border);
        transition: all 0.3s ease;
        display: flex;
    }

    .store-item:last-child {
        border-bottom: none;
    }

    .store-item a {
        display: block;
        padding: 1rem 1.5rem;
        text-decoration: none;
        color: var(--text-dark);
        flex: 1;
    }

    .store-item:hover {
        background-color: rgba(10, 46, 46, 0.05);
    }

    .store-item.active {
        background-color: rgba(10, 46, 46, 0.1);
        border-left: 4px solid var(--primary);
    }

    .store-item-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .store-item-address {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .store-item-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .store-status {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
    }

    .store-status.open {
        background-color: rgba(93, 187, 99, 0.2);
        color: var(--success);
    }

    .store-status.closed {
        background-color: rgba(220, 53, 69, 0.2);
        color: var(--error);
    }

    /* Contenu principal */
    .main-content {
        flex: 1;
        overflow-y: auto;
        padding: 2rem;
        display: flex;
        flex-direction: column;
    }

    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .content-header h1 {
        font-size: 1.8rem;
        color: var(--primary);
        margin: 0;
        position: relative;
        padding-left: 1rem;
    }

    .content-header h1::before {
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

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .content-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        flex: 1;
    }

    /* Carte */
    .map-container {
        height: calc(100vh - 150px);
        border-radius: 30px 0;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
        position: relative;
    }

    #map {
        height: 100%;
        width: 100%;
    }

    .map-controls {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 10;
    }

    .map-control-button {
        width: 40px;
        height: 40px;
        background-color: var(--text-light);
        border: 1px solid var(--border);
        border-radius: 30px 0;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        color: var(--primary);
    }

    .map-control-button:hover {
        background-color: rgba(10, 46, 46, 0.05);
    }

    /* Conteneur des détails des boutiques */
    .stores-details-container {
        background-color: #F9F5EF;
        border-radius: 30px 0;
        padding: 1rem;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
        height: calc(100vh - 150px);
        overflow: hidden;
    }

    /* Liste des détails des boutiques */
    .stores-details {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        max-height: calc(100vh - 180px);
        overflow-y: auto;
        padding-right: 0.5rem;
        overflow-x: hidden;
    }

    .store-card {
        max-width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
        background: var(--text-light);
        border-radius: 30px 0;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        border: 1px solid var(--border);
        position: relative;
        overflow-y: auto;
        max-height: calc(100vh - 200px);
        display: none;
    }

    .store-card.active {
        display: block;
    }

    .store-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(to bottom, var(--primary), var(--secondary));
    }

    /* Améliorer la réactivité des grilles dans les cartes */
    .store-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.2rem;
        margin-bottom: 1.2rem;
        padding: 1.2rem;
        background-color: rgba(10, 46, 46, 0.05);
        border-radius: 20px 0;
        width: 100%;
        box-sizing: border-box;
    }

    /* Scrollbar personnalisée */
    .stores-details::-webkit-scrollbar {
        width: 6px;
    }

    .stores-details::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .stores-details::-webkit-scrollbar-thumb {
        background-color: var(--secondary);
        border-radius: 3px;
    }

    .store-header {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1.2rem;
        padding-left: 0.5rem;
    }

    .store-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .store-title h2 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary);
    }

    .store-badge {
        background-color: var(--secondary);
        color: var(--text-light);
        padding: 0.25rem 0.75rem;
        border-radius: 30px 0;
        font-size: 0.8rem;
        font-weight: 600;
        align-self: flex-start;
    }

    .info-section {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .info-label {
        font-weight: 600;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .info-value {
        color: var(--text-dark);
        font-size: 0.95rem;
    }

    .store-link {
        color: var(--secondary);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .store-link:hover {
        color: var(--primary);
        text-decoration: underline;
    }

    .store-hours {
        margin-bottom: 1.2rem;
        border: 1px solid var(--border);
        border-radius: 20px 0;
        overflow: hidden;
    }

    .hours-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem;
        background-color: var(--primary);
        color: var(--text-light);
        font-weight: 600;
        cursor: pointer;
        position: relative;
    }

    .hours-toggle-icon {
        margin-left: auto;
        transition: transform 0.3s ease;
    }

    .hours-content {
        padding: 1rem;
        display: none;
    }

    .day-schedule {
        color: var(--primary);
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border);
    }

    .day-schedule:last-child {
        border-bottom: none;
    }

    .day-schedule.today {
        background-color: rgba(10, 46, 46, 0.1);
        margin: 0 -1rem;
        padding: 0.75rem 1rem;
        border-radius: 20px 0;
    }

    .day-name {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .time-slot {
        padding: 0.25rem 0;
        color: var(--text-dark);
    }

    .closed-text {
        color: var(--error);
        font-weight: 500;
    }

    .holiday-text {
        color: var(--tertiary);
        font-weight: 500;
    }

    .no-hours {
        color: var(--text-dark);
        opacity: 0.6;
        font-style: italic;
    }

    .store-locate {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--secondary);
        font-size: 0.9rem;
        margin-bottom: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        padding: 0.5rem 0;
    }

    .store-locate:hover {
        color: var(--primary);
        transform: translateX(5px);
    }

    .store-locate i {
        font-size: 1rem;
    }

    .store-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .btn-primary {
        padding: 0.75rem;
        background-color: var(--text-light);
        color: var(--primary);
        border: 1px solid var(--primary);
        border-radius: 30px 0;
        font-weight: 600;
        font-size: 0.85rem;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-primary:hover {
        background-color: rgba(10, 46, 46, 0.1);
        transform: translateY(-2px);
    }

    .btn-secondary {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: var(--text-light);
        border: none;
        border-radius: 30px 0;
        font-weight: 600;
        font-size: 0.85rem;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(10, 46, 46, 0.2);
        width: 92%;
        margin-bottom: 0.5rem;
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(10, 46, 46, 0.3);
    }

    /* Styles pour le bouton de mode sélection */
    .selection-mode-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background-color: rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        padding: 0.5rem 1rem;
        border-radius:20px;
        border: none;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .selection-mode-btn:hover {
        background-color: rgba(255, 255, 255, 0.3);
    }

    .selection-mode-btn.active {
        background-color: var(--text-light);
        color: var(--primary);
    }

    /* Styles pour les cases à cocher dans la sidebar */
    .store-checkbox {
        padding: 0 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .store-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--primary);
    }

    /* Styles pour le bouton d'action en masse */
    .bulk-action-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: var(--text-light);
        padding: 0.8rem 1.5rem;
        border-radius: 20px;
        border: none;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        box-shadow: var(--card-shadow);
        cursor: pointer;
        opacity: 0.7;
    }

    .bulk-action-button:enabled {
        opacity: 1;
    }

    .bulk-action-button:enabled:hover {
        background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(10, 46, 46, 0.3);
    }

    .bulk-action-button:disabled {
        cursor: not-allowed;
    }

    /* Styles pour la modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: var(--text-light);
        margin: 5% auto;
        padding: 0;
        border-radius: 30px 0;
        box-shadow: var(--card-shadow);
        width: 80%;
        max-width: 900px;
        max-height: 90vh;
        overflow-y: auto;
        animation: modalFadeIn 0.3s;
    }

    @keyframes modalFadeIn {
        from {opacity: 0; transform: translateY(-20px);}
        to {opacity: 1; transform: translateY(0);}
    }

    .modal-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: var(--text-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 30px 0 0 0;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 1.5rem;
    }

    .close-modal {
        color: var(--text-light);
        font-size: 1.8rem;
        font-weight: bold;
        cursor: pointer;
    }

    .close-modal:hover {
        color: #ddd;
    }

    .modal-body {
        padding: 1.5rem;
        color: var(--text-dark);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .content-container {
            grid-template-columns: 1fr;
        }
        
        .map-container {
            height: 500px;
        }
        
        .stores-details-container {
            height: auto;
        }
    }

    @media (max-width: 992px) {
        .admin-container {
            flex-direction: column;
            height: auto;
            overflow: visible;
        }
        
        .sidebar {
            width: 100%;
            height: auto;
            border-right: none;
            border-bottom: 1px solid var(--border);
        }
        
        .stores-list {
            max-height: 300px;
        }
        
        .main-content {
            padding: 1.5rem;
        }
        
        .content-container {
            gap: 1.5rem;
        }
        
        .store-info {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .content-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .header-actions {
            width: 100%;
        }
        
        .add-button {
            width: 100%;
            justify-content: center;
        }
        
        .store-actions {
            grid-template-columns: 1fr;
        }

        .modal-content {
            width: 95%;
            margin: 5% auto;
        }
        
        .form-row {
            flex-direction: column;
            gap: 1rem;
        }
        
        .time-slot {
            flex-direction: column;
            gap: 1rem;
        }
        
        .btn-remove-slot {
            align-self: flex-end;
        }
        
        .action-types {
            grid-template-columns: 1fr;
        }
    }

    /* Dark mode */
    @media (prefers-color-scheme: dark) {
        body {
            background-color: #f0f0f0;
        }
        
        .sidebar {
            background-color: #0A2E2E;
            border-color: #2A6363;
        }
        
        .sidebar-search {
            border-color: #2A6363;
        }
        
        #sidebar-search-input {
            background-color: #0A2E2E;
            color: var(--text-light);
            border-color: #2A6363;
        }
        
        .store-item {
            border-color: #2A6363;
        }
        
        .store-item a {
            color: var(--text-light);
        }
        
        .store-item-address {
            color: #aaa;
        }
        
        .stores-details-container {
            background-color: #0A2E2E;
            border-color: #2A6363;
        }
        
        .store-card {
            background-color: #0A2E2E;
            border-color: #2A6363;
        }
        
        .store-title h2 {
            color: var(--text-light);
        }
        
        .info-label {
            color: var(--text-light);
        }
        
        .info-value {
            color: var(--text-light);
            opacity: 0.9;
        }
        
        .store-info {
            background-color: rgba(42, 99, 99, 0.1);
        }
        
        .store-hours {
            border-color: #2A6363;
        }
        
        .day-schedule {
            border-color: #2A6363;
        }
        
        .time-slot {
            color: var(--text-light);
        }
        
        .btn-primary {
            background-color: #0A2E2E;
            color: var(--text-light);
            border-color: var(--secondary);
        }
        
        .map-control-button {
            background-color: #0A2E2E;
            color: var(--text-light);
            border-color: #2A6363;
        }

        .modal-content {
            background-color: #0A2E2E;
        }
        
        .modal-body {
            color: var(--text-light);
        }
        
        .form-control {
            background-color: #0A2E2E;
            color: var(--text-light);
            border-color: #2A6363;
        }
        
        .form-group label {
            color: var(--text-light);
        }
        
        .action-type label {
            background-color: rgba(42, 99, 99, 0.1);
            color: var(--text-light);
            border-color: #2A6363;
        }
        
        .time-slot {
            background-color: rgba(42, 99, 99, 0.1);
            border-color: #2A6363;
        }
        
        .btn-add-slot {
            background-color: rgba(42, 99, 99, 0.1);
            color: var(--text-light);
            border-color: #2A6363;
        }
        
        .selected-stores-list {
            background-color: rgba(42, 99, 99, 0.1);
            border-color: #2A6363;
        }
        
        .selected-store-tag {
            background-color: #0A2E2E;
            color: var(--text-light);
            border-color: #2A6363;
        }
        
        .holiday-info p {
            color: var(--text-light);
        }
    }
</style>

@section('content')
<div class="admin-container">
    <!-- Sidebar pour la navigation entre les points de vente -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>STORES</h2>
            <button id="selection-mode-btn" class="selection-mode-btn">
                <i class="fas fa-check-square"></i>
                Sélectionner
            </button>
        </div>
        
        <div class="sidebar-search">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="sidebar-search-input" placeholder="Rechercher..." onkeyup="filterSidebar()">
        </div>
        
        <div class="stores-list" id="stores-list">
            @foreach($stores as $store)
            <div class="store-item" data-id="{{ $store->id }}" data-lat="{{ $store->latitude }}" data-lng="{{ $store->longitude }}">
                <div class="store-checkbox" style="display: none;">
                    <input type="checkbox" id="select-store-{{ $store->id }}" class="store-selector" data-store-id="{{ $store->id }}">
                </div>
                <a href="#store-{{ $store->id }}" onclick="selectStore({{ $store->id }})">
                    <div class="store-item-name">{{ $store->nom }}</div>
                    <div class="store-item-address">{{ $store->ville }}</div>
                    <div class="store-item-badge">
                        <span class="store-status {{ $store->is_closed ? 'closed' : 'open' }}">
                            {{ $store->is_closed ? 'Fermé' : 'Ouvert' }}
                        </span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="main-content">
        <div class="content-header">
            <h1>Gestion des points de vente</h1>
            <div class="header-actions">
                <button id="bulk-action-btn" class="bulk-action-button" style="display: none;" disabled>
                    <i class="fas fa-clock"></i>
                    Gérer les horaires sélectionnés
                </button>
                <a href="{{ route('admin.stores.create') }}" class="add-button">
                    <i class="fas fa-plus-circle"></i>
                    Ajouter
                </a>
            </div>
        </div>

        <div class="content-container">
            <div class="map-container" id="map-container">
                <div class="map-controls"></div>
                <div id="map" style="width: 100%; height: 100%;"></div>
            </div>

            <div class="stores-details-container">
                <div class="stores-details" id="stores-details">
                    @foreach($stores as $store)
                    <div class="store-card" id="store-{{ $store->id }}" data-lat="{{ $store->latitude }}" data-lng="{{ $store->longitude }}">
                        <div class="store-header">
                            <div class="store-title">
                                <h2>{{ strtoupper($store->nom) }}</h2>
                                <span class="store-status {{ $store->is_closed ? 'closed' : 'open' }}">
                                    {{ $store->is_closed ? 'Fermé' : 'Ouvert' }}
                                </span>
                            </div>
                            <div class="store-badge">
                                {{ is_array($store->services) ? implode(', ', $store->services) : ($store->services ?? 'Service non défini') }}
                            </div>
                        </div>
                        
                        <div class="store-info">
                            <div class="info-section">
                                <div class="info-label"><i class="fas fa-map-marker-alt"></i> Adresse</div>
                                <div class="info-value">{{ $store->adresse }}, {{ $store->code_postal ?? '' }} {{ $store->ville }}</div>
                                <div class="info-value">{{ $store->region ?? '' }}, {{ $store->pays ?? '' }}</div>
                            </div>
                            
                            <div class="info-section">
                                <div class="info-label"><i class="fas fa-phone"></i> Contact</div>
                                <div class="info-value">{{ $store->phone ?? 'Aucun téléphone' }}</div>
                                <div class="info-value">{{ $store->email ?? 'Aucun email' }}</div>
                            </div>
                            
                            <div class="info-section">
                                <div class="info-label"><i class="fas fa-info-circle"></i> Détails</div>
                                <div class="info-value">Ouvert jusqu'à: {{ $store->ouvert_jusqua ?? 'Non spécifié' }}</div>
                                <div class="info-value">Année d'ouverture: {{ $store->annee_ouverture ?? 'Non spécifié' }}</div>
                            </div>
                            
                            @if($store->site_web || $store->lien_rdv)
                            <div class="info-section">
                                <div class="info-label"><i class="fas fa-link"></i> Liens</div>
                                @if($store->site_web)
                                <div class="info-value"><a href="{{ $store->site_web }}" target="_blank" class="store-link">Site web</a></div>
                                @endif
                                @if($store->lien_rdv)
                                <div class="info-value"><a href="{{ $store->lien_rdv }}" target="_blank" class="store-link">Prendre rendez-vous</a></div>
                                @endif
                            </div>
                            @endif
                        </div>
                        
                        <div class="store-hours">
                            <div class="hours-header" onclick="toggleHours(this)">
                                <i class="fas fa-clock"></i> HORAIRES D'OUVERTURE
                                <i class="fas fa-chevron-down hours-toggle-icon"></i>
                            </div>
                            <div class="hours-content">
                                {!! $store->formatted_weekly_hours !!}
                            </div>
                        </div>

                        <div class="store-locate" onclick="centerMapOnStore({{ $store->latitude }}, {{ $store->longitude }})">
                            <i class="fas fa-map-pin"></i>
                            Localiser sur la carte
                        </div>
                        
                        <div class="store-actions">
                            <a href="{{ route('admin.stores.schedules.select-type', $store) }}" class="btn-primary">
                                <i class="fas fa-clock"></i>
                                GÉRER LES HORAIRES
                            </a>
                            <a href="{{ route('admin.stores.edit', $store) }}" class="btn-primary">
                                <i class="fas fa-edit"></i>
                                MODIFIER
                            </a>
                        </div>
                        
                        <a href="{{ route('admin.stores.manage', $store) }}" class="btn-secondary">
                            <i class="fas fa-cog"></i>
                            GÉRER L'AFFICHE DES DETAILS
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Inclusion du composant de modal de gestion des horaires en masse --}}
@include('admin.stores.bulk-schedule-modal')

<script>
// Déclaration des variables globales
let activeStoreId = null
let map
const markers = []
let infoWindow
let bounds
let markerCluster
let isSelectStoreRunning = false
let debounceTimer = null

// Fonction optimisée pour sélectionner une seule boutique et afficher ses détails
function selectStore(storeId) {
  if (isSelectStoreRunning || activeStoreId === storeId) return

  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => {
    isSelectStoreRunning = true

    try {
      activeStoreId = storeId

      const selectedCard = document.getElementById(`store-${storeId}`)
      const selectedItem = document.querySelector(`.store-item[data-id="${storeId}"]`)

      document.querySelectorAll(".store-card.active").forEach((card) => {
        card.classList.remove("active")
        card.style.display = "none"
      })

      const activeItem = document.querySelector(".store-item.active")
      if (activeItem) activeItem.classList.remove("active")

      if (selectedCard) {
        selectedCard.classList.add("active")
        selectedCard.style.display = "block"
        selectedCard.scrollTop = 0
      }

      if (selectedItem) {
        selectedItem.classList.add("active")
        if (selectedItem.scrollIntoViewIfNeeded) {
          selectedItem.scrollIntoViewIfNeeded()
        } else {
          selectedItem.scrollIntoView({ behavior: "smooth", block: "nearest" })
        }
      }

      const marker = markers.find((m) => m.storeId === Number(storeId))
      if (marker) {
        markers.forEach((m) => {
          if (m.getAnimation()) m.setAnimation(null)
        })

        marker.setAnimation(google.maps.Animation.BOUNCE)
        setTimeout(() => {
          marker.setAnimation(null)
        }, 1500)

        map.setCenter(marker.getPosition())
        map.setZoom(15)

        const content = `
          <div style="padding: 10px; max-width: 200px;">
            <h3 style="margin-bottom: 5px; color: #000">${marker.title || ""}</h3>
            <p style="margin-bottom: 10px; color: #000">${marker.address || ""}</p>
            <a href="#store-${storeId}" style="color: #000; text-decoration: underline;">
              Voir détails
            </a>
          </div>
        `
        infoWindow.setContent(content)
        infoWindow.open(map, marker)
      }
    } finally {
      isSelectStoreRunning = false
    }
  }, 100)
}

// Fonction pour ajouter des marqueurs à la carte
function addMarkersToMap() {
  let stores
  try {
    stores = JSON.parse(document.getElementById("stores-data").textContent)
  } catch (error) {
    console.error("Erreur lors de la récupération des données des boutiques:", error)
    return
  }

  if (!Array.isArray(stores)) {
    console.error("Les données des boutiques ne sont pas un tableau:", stores)
    return
  }

  const validStores = stores.filter((store) => isValidCoordinate(store.latitude, store.longitude))

  const positions = validStores.map((store) => ({
    position: {
      lat: Number.parseFloat(store.latitude),
      lng: Number.parseFloat(store.longitude),
    },
    storeId: store.id,
    title: store.nom,
    address: store.adresse,
  }))

  const batchSize = 20
  for (let i = 0; i < positions.length; i += batchSize) {
    const batch = positions.slice(i, i + batchSize)

    setTimeout(
      () => {
        batch.forEach((data) => {
          const marker = new google.maps.Marker({
            position: data.position,
            map: map,
            title: data.title,
            animation: google.maps.Animation.DROP,
            storeId: data.storeId,
            address: data.address,
          })

          markers.push(marker)

          marker.addListener("click", () => {
            selectStore(data.storeId)
          })
        })

        if (i + batchSize >= positions.length) {
          if (markers.length > 0) {
            bounds = new google.maps.LatLngBounds()
            markers.forEach((marker) => bounds.extend(marker.getPosition()))
            map.fitBounds(bounds)
          }

          if (markers.length > 0) {
            if (typeof MarkerClusterer !== "undefined") {
              createMarkerCluster()
            } else {
              loadMarkerClusterer()
            }
          }
        }
      },
      i === 0 ? 0 : 100,
    )
  }
}

// Initialisation de la carte avec Google Maps
function initMap() {
  const defaultLocation = { lat: 31.7917, lng: -7.0926 }

  map = new google.maps.Map(document.getElementById("map"), {
    center: defaultLocation,
    zoom: 5,
    mapTypeControl: true,
    streetViewControl: false,
    fullscreenControl: true,
    styles: [
      {
        featureType: "administrative.country",
        elementType: "geometry.stroke",
        stylers: [{ visibility: "off" }],
      },
      {
        featureType: "administrative",
        elementType: "labels.text.fill",
        stylers: [{ color: "#444444" }],
      },
      {
        featureType: "landscape",
        elementType: "all",
        stylers: [{ color: "#f2f2f2" }],
      },
      {
        featureType: "poi",
        elementType: "all",
        stylers: [{ visibility: "off" }],
      },
      {
        featureType: "road",
        elementType: "all",
        stylers: [{ saturation: -100 }, { lightness: 45 }],
      },
      {
        featureType: "road.highway",
        elementType: "all",
        stylers: [{ visibility: "simplified" }],
      },
      {
        featureType: "road.arterial",
        elementType: "labels.icon",
        stylers: [{ visibility: "off" }],
      },
      {
        featureType: "transit",
        elementType: "all",
        stylers: [{ visibility: "off" }],
      },
      {
        featureType: "water",
        elementType: "all",
        stylers: [{ color: "#0A2E2E" }, { visibility: "on" }],
      },
    ],
  })

  infoWindow = new google.maps.InfoWindow()

  setTimeout(() => {
    loadCountryBorders()
  }, 500)

  addMarkersToMap()
}

function loadCountryBorders() {
  map.data.loadGeoJson("https://raw.githubusercontent.com/johan/world.geo.json/master/countries.geo.json")

  map.data.setStyle((feature) => {
    const name = feature.getProperty("name")
    return {
      fillOpacity: 0,
      strokeColor: "#444",
      strokeWeight: 1,
      strokeOpacity: name === "Western Sahara" ? 0 : 1,
    }
  })
}

function createMarkerCluster() {
  if (markerCluster) {
    markerCluster.clearMarkers()
  }

  markerCluster = new MarkerClusterer(map, markers, {
    imagePath: "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m",
    gridSize: 50,
    minimumClusterSize: 3,
  })
}

function loadMarkerClusterer() {
  const script = document.createElement("script")
  script.src = "https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"
  script.onload = () => {
    if (typeof markerClusterer !== "undefined") {
      markerCluster = new markerClusterer.MarkerClusterer({
        map,
        markers,
        algorithm: new markerClusterer.GridAlgorithm({
          gridSize: 50,
          minimumClusterSize: 3,
        }),
      })
    } else {
      console.warn("La bibliothèque MarkerClusterer n'a pas pu être chargée correctement")
    }
  }
  document.head.appendChild(script)
}

function isValidCoordinate(lat, lng) {
  if (!lat || !lng) return false

  const latNum = Number.parseFloat(lat)
  const lngNum = Number.parseFloat(lng)

  return !isNaN(latNum) && !isNaN(lngNum) && latNum >= -90 && latNum <= 90 && lngNum >= -180 && lngNum <= 180
}

function centerMapOnStore(lat, lng) {
  if (!map) return

  const position = new google.maps.LatLng(lat, lng)
  map.setCenter(position)
  map.setZoom(16)

  const marker = markers.find((m) => {
    const pos = m.getPosition()
    return pos && Math.abs(pos.lat() - position.lat()) < 0.0001 && Math.abs(pos.lng() - position.lng()) < 0.0001
  })

  if (marker) {
    const content = `
      <div style="padding: 10px; max-width: 200px;">
        <h3 style="margin-bottom: 5px; color: #000">${marker.title || ""}</h3>
        <p style="margin-bottom: 10px; color: #000">${marker.address || ""}</p>
        <a href="#store-${marker.storeId}" style="color: #000; text-decoration: underline;">
          Voir détails
        </a>
      </div>
    `
    infoWindow.setContent(content)
    infoWindow.open(map, marker)

    selectStore(marker.storeId)
  }
}

function toggleHours(element) {
  const content = element.parentNode.querySelector(".hours-content")
  const icon = element.querySelector(".hours-toggle-icon")

  if (!content || !icon) return

  const isVisible = content.style.display === "block"
  content.style.display = isVisible ? "none" : "block"
  icon.style.transform = isVisible ? "rotate(0deg)" : "rotate(180deg)"
}

let filterTimer = null
function filterSidebar() {
  clearTimeout(filterTimer)
  filterTimer = setTimeout(() => {
    const searchText = document.getElementById("sidebar-search-input").value.toLowerCase()
    const storeItems = document.querySelectorAll(".store-item")

    storeItems.forEach((item) => {
      const storeName = item.querySelector(".store-item-name").textContent.toLowerCase()
      const storeAddress = item.querySelector(".store-item-address").textContent.toLowerCase()

      item.style.display = storeName.includes(searchText) || storeAddress.includes(searchText) ? "flex" : "none"
    })
  }, 200)
}

function handleMultipleSelection() {
  const selectionModeBtn = document.getElementById("selection-mode-btn")
  const storeCheckboxes = document.querySelectorAll(".store-checkbox")
  const bulkActionBtn = document.getElementById("bulk-action-btn")
  const modal = document.getElementById("bulk-schedule-modal")
  const closeModal = document.querySelector(".close-modal")
  const selectedStoresList = document.getElementById("selected-stores-list")
  const selectedStoresInputs = document.getElementById("selected-stores-inputs")
  const selectedCount = document.getElementById("selected-count")

  if (!selectionModeBtn) {
    console.error("Le bouton de mode sélection n'a pas été trouvé")
    return
  }

  function updateBulkActionButton() {
    const checkedStores = document.querySelectorAll(".store-selector:checked")

    if (bulkActionBtn) {
      bulkActionBtn.disabled = checkedStores.length === 0
    }

    if (selectedCount) {
      selectedCount.textContent = checkedStores.length
    }

    if (selectedStoresList) {
      selectedStoresList.innerHTML = ""
    }

    if (selectedStoresInputs) {
      selectedStoresInputs.innerHTML = ""
    }

    const listFragment = document.createDocumentFragment()
    const inputsFragment = document.createDocumentFragment()

    checkedStores.forEach((checkbox) => {
      const storeId = checkbox.dataset.storeId
      const storeItem = checkbox.closest(".store-item")
      if (!storeItem) return

      const storeNameElement = storeItem.querySelector(".store-item-name")
      if (!storeNameElement) return

      const storeName = storeNameElement.textContent

      if (selectedStoresList) {
        const storeTag = document.createElement("div")
        storeTag.className = "selected-store-tag"
        storeTag.innerHTML = `
          <span>${storeName}</span>
          <span class="remove-tag" data-store-id="${storeId}">&times;</span>
        `

        const removeTag = storeTag.querySelector(".remove-tag")
        if (removeTag) {
          removeTag.addEventListener("click", function () {
            const tagStoreId = this.dataset.storeId
            const checkbox = document.querySelector(`.store-selector[data-store-id="${tagStoreId}"]`)
            if (checkbox) {
              checkbox.checked = false
            }
            updateBulkActionButton()
          })
        }

        listFragment.appendChild(storeTag)
      }

      if (selectedStoresInputs) {
        const storeInput = document.createElement("input")
        storeInput.type = "hidden"
        storeInput.name = "store_ids[]"
        storeInput.value = storeId
        inputsFragment.appendChild(storeInput)
      }
    })

    if (selectedStoresList) {
      selectedStoresList.appendChild(listFragment)
    }

    if (selectedStoresInputs) {
      selectedStoresInputs.appendChild(inputsFragment)
    }
  }

  selectionModeBtn.addEventListener("click", function () {
    const isActive = this.classList.toggle("active")

    storeCheckboxes.forEach((checkbox) => {
      checkbox.style.display = isActive ? "flex" : "none"
      const input = checkbox.querySelector("input")
      if (input) {
        input.checked = false
      }
    })

    this.innerHTML = isActive
      ? '<i class="fas fa-times"></i> Annuler'
      : '<i class="fas fa-check-square"></i> Sélectionner'

    if (bulkActionBtn) {
      bulkActionBtn.style.display = isActive ? "flex" : "none"
      bulkActionBtn.disabled = true
    }

    updateBulkActionButton()
  })

  document.querySelectorAll(".store-selector").forEach((checkbox) => {
    checkbox.addEventListener("change", updateBulkActionButton)
  })

  if (bulkActionBtn && modal) {
    bulkActionBtn.addEventListener("click", () => {
      modal.style.display = "block"
      updateBulkActionButton()
    })
  }

  if (closeModal && modal) {
    closeModal.addEventListener("click", () => {
      modal.style.display = "none"
    })

    window.addEventListener("click", (event) => {
      if (event.target === modal) {
        modal.style.display = "none"
      }
    })
  }
}

document.addEventListener("DOMContentLoaded", () => {
  console.log("DOM chargé, initialisation des fonctionnalités")

  handleMultipleSelection()

  if (typeof google !== "undefined" && google.maps) {
    requestAnimationFrame(() => {
      initMap()
    })
  } else {
    console.log("Google Maps n'est pas encore chargé")
  }

  const actionTypes = document.querySelectorAll('input[name="action_type"]')
  const regularScheduleSection = document.getElementById("regular-schedule-section")
  const exceptionSection = document.getElementById("exception-section")
  const temporaryClosureSection = document.getElementById("temporary-closure-section")
  const holidaySection = document.getElementById("holiday-section")

  if (actionTypes.length > 0) {
    actionTypes.forEach((radio) => {
      radio.addEventListener("change", function () {
        if (regularScheduleSection) regularScheduleSection.style.display = "none"
        if (exceptionSection) exceptionSection.style.display = "none"
        if (temporaryClosureSection) temporaryClosureSection.style.display = "none"
        if (holidaySection) holidaySection.style.display = "none"

        switch (this.value) {
          case "regular_schedule":
            if (regularScheduleSection) regularScheduleSection.style.display = "block"
            break
          case "exception":
            if (exceptionSection) exceptionSection.style.display = "block"
            break
          case "temporary_closure":
            if (temporaryClosureSection) temporaryClosureSection.style.display = "block"
            break
          case "holiday":
            if (holidaySection) holidaySection.style.display = "block"
            break
        }
      })
    })
  }

  const afterClosure = document.getElementById("after_closure")
  const afterClosureCustom = document.getElementById("after-closure-custom")

  if (afterClosure && afterClosureCustom) {
    afterClosure.addEventListener("change", function () {
      afterClosureCustom.style.display = this.value === "custom" ? "block" : "none"
    })
  }
})
</script>

<script id="stores-data" type="application/json" style="display: none;">
    {!! json_encode($stores) !!}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDVXn3v4gNvgDImCifWbY5iZJLCUaRdVFI&callback=initMap" async defer></script>
@endsection
