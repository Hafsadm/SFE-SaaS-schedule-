@extends('layouts.app')

@section('title', 'Gestion des horaires en masse')

@section('content')
<div class="bulk-schedules-container">
    <!-- En-tête -->
    <div class="bulk-header">
        <div class="header-content">
            <h1 class="bulk-title">Gestion des horaires en masse</h1>
            <div class="header-actions">
                <a href="{{ route('admin.stores.index') }}" class="action-button secondary">
                    <i class="fas fa-arrow-left"></i>
                    Retour aux points de vente
                </a>
            </div>
        </div>
    </div>

    <!-- Alertes -->
    @if(session('success'))
        <div class="alert success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Contenu principal -->
    <div class="bulk-content">
        <div class="bulk-panel">
            <div class="panel-header">
                <h2 class="panel-title">Sélection des points de vente</h2>
            </div>
            <div class="panel-body">
                <div class="search-filter">
                    <div class="search-container">
                        <input type="text" id="store-search" placeholder="Rechercher un point de vente..." onkeyup="filterStores()">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="filter-actions">
                        <button type="button" id="select-all-btn" class="filter-button" onclick="selectAllStores()">
                            <i class="fas fa-check-square"></i>
                            Tout sélectionner
                        </button>
                        <button type="button" id="deselect-all-btn" class="filter-button" onclick="deselectAllStores()">
                            <i class="fas fa-square"></i>
                            Tout désélectionner
                        </button>
                    </div>
                </div>

                <form action="{{ route('admin.schedules.apply-bulk') }}" method="POST" id="bulk-form">
                    @csrf
                    <div class="stores-selection">
                        <div class="stores-list" id="stores-list">
                            @foreach($stores ?? [] as $store)
                                <div class="store-item">
                                    <label class="store-checkbox">
                                        <input type="checkbox" name="store_ids[]" value="{{ $store->id }}" class="store-selector">
                                        <span class="checkbox-custom"></span>
                                    </label>
                                    <div class="store-info">
                                        <div class="store-name">{{ $store->nom }}</div>
                                        <div class="store-location">{{ $store->ville }}</div>
                                    </div>
                                    <div class="store-status {{ $store->is_closed ? 'closed' : 'open' }}">
                                        {{ $store->is_closed ? 'Fermé' : 'Ouvert' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="action-selection">
                        <h3 class="section-subtitle">Type d'action</h3>
                        <div class="action-types">
                            <label class="action-type">
                                <input type="radio" name="action_type" value="regular_schedule" checked>
                                <div class="action-content">
                                    <div class="action-icon">
                                        <i class="fas fa-calendar-week"></i>
                                    </div>
                                    <div class="action-info">
                                        <div class="action-title">Horaire régulier</div>
                                        <div class="action-description">Appliquer un horaire régulier à tous les points de vente sélectionnés</div>
                                    </div>
                                </div>
                            </label>
                            <label class="action-type">
                                <input type="radio" name="action_type" value="exception">
                                <div class="action-content">
                                    <div class="action-icon">
                                        <i class="fas fa-calendar-times"></i>
                                    </div>
                                    <div class="action-info">
                                        <div class="action-title">Exception</div>
                                        <div class="action-description">Créer une exception pour tous les points de vente sélectionnés</div>
                                    </div>
                                </div>
                            </label>
                            <label class="action-type">
                                <input type="radio" name="action_type" value="holiday">
                                <div class="action-content">
                                    <div class="action-icon">
                                        <i class="fas fa-calendar-day"></i>
                                    </div>
                                    <div class="action-info">
                                        <div class="action-title">Jour férié</div>
                                        <div class="action-description">Ajouter un jour férié à tous les points de vente sélectionnés</div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Horaire régulier -->
                        <div id="regular-schedule-section" class="action-details">
                            <h3 class="section-subtitle">Détails de l'horaire régulier</h3>
                            <div class="form-group">
                                <label for="day_of_week">Jour de la semaine</label>
                                <select name="day_of_week" id="day_of_week" class="form-control">
                                    <option value="monday">Lundi</option>
                                    <option value="tuesday">Mardi</option>
                                    <option value="wednesday">Mercredi</option>
                                    <option value="thursday">Jeudi</option>
                                    <option value="friday">Vendredi</option>
                                    <option value="saturday">Samedi</option>
                                    <option value="sunday">Dimanche</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="is_closed" id="is_closed">
                                    <span class="checkbox-custom"></span>
                                    Fermé ce jour
                                </label>
                            </div>
                            <div id="time-slots-container">
                                <div class="time-slots">
                                    <div class="time-slot">
                                        <div class="time-inputs">
                                            <div class="form-group">
                                                <label for="opening_time">Heure d'ouverture</label>
                                                <input type="time" name="time_slots[0][start]" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="closing_time">Heure de fermeture</label>
                                                <input type="time" name="time_slots[0][end]" class="form-control">
                                            </div>
                                        </div>
                                        <button type="button" class="remove-slot" onclick="removeTimeSlot(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="add-slot-btn" onclick="addTimeSlot()">
                                    <i class="fas fa-plus"></i>
                                    Ajouter un créneau horaire
                                </button>
                            </div>
                        </div>

                        <!-- Exception -->
                        <div id="exception-section" class="action-details" style="display: none;">
                            <h3 class="section-subtitle">Détails de l'exception</h3>
                            <div class="form-group">
                                <label for="exception_date">Date de l'exception</label>
                                <input type="date" name="exception_date" id="exception_date" class="form-control" min="{{ date('Y-m-d') }}">
                            </div>
                            <div class="form-group">
                                <label for="exception_reason">Raison de l'exception</label>
                                <input type="text" name="exception_reason" id="exception_reason" class="form-control" placeholder="Ex: Inventaire, Réunion d'équipe...">
                            </div>
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="exception_is_closed" id="exception_is_closed">
                                    <span class="checkbox-custom"></span>
                                    Fermé ce jour
                                </label>
                            </div>
                            <div id="exception-time-slots-container">
                                <div class="time-slots">
                                    <div class="time-slot">
                                        <div class="time-inputs">
                                            <div class="form-group">
                                                <label for="exception_opening_time">Heure d'ouverture</label>
                                                <input type="time" name="exception_time_slots[0][start]" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="exception_closing_time">Heure de fermeture</label>
                                                <input type="time" name="exception_time_slots[0][end]" class="form-control">
                                            </div>
                                        </div>
                                        <button type="button" class="remove-slot" onclick="removeExceptionTimeSlot(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="add-slot-btn" onclick="addExceptionTimeSlot()">
                                    <i class="fas fa-plus"></i>
                                    Ajouter un créneau horaire
                                </button>
                            </div>
                        </div>

                        <!-- Jour férié -->
                        <div id="holiday-section" class="action-details" style="display: none;">
                            <h3 class="section-subtitle">Détails du jour férié</h3>
                            <div class="form-group">
                                <label for="holiday_date">Date du jour férié</label>
                                <input type="date" name="holiday_date" id="holiday_date" class="form-control" min="{{ date('Y-m-d') }}">
                            </div>
                            <div class="form-group">
                                <label for="holiday_name">Nom du jour férié</label>
                                <input type="text" name="holiday_name" id="holiday_name" class="form-control" placeholder="Ex: Noël, Jour de l'An...">
                            </div>
                            <div class="holiday-info">
                                <p><i class="fas fa-info-circle"></i> Les points de vente seront automatiquement fermés pour ce jour férié.</p>
                            </div>
                        </div>
                    </div>

                    <div class="selected-stores-summary">
                        <h3 class="section-subtitle">Points de vente sélectionnés (<span id="selected-count">0</span>)</h3>
                        <div id="selected-stores-list" class="selected-stores-list"></div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" id="submit-btn" class="submit-btn" disabled>
                            <i class="fas fa-save"></i>
                            Appliquer aux points de vente sélectionnés
                        </button>
                    </div>
                </form>
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
    .bulk-schedules-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1.5rem;
    }

    /* En-tête */
    .bulk-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border-radius: 30px 0;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--card-shadow);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .bulk-title {
        color: var(--text-light);
        font-size: 1.8rem;
        margin: 0;
        position: relative;
        padding-left: 1rem;
    }

    .bulk-title::before {
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

    .action-button.secondary {
        background-color: rgba(255, 255, 255, 0.2);
        color: var(--text-light);
    }

    .action-button.secondary:hover {
        background-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    /* Alertes */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 20px 0;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert.success {
        background-color: rgba(93, 187, 99, 0.1);
        color: var(--success);
        border-left: 4px solid var(--success);
    }

    .alert.error {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--error);
        border-left: 4px solid var(--error);
    }

    /* Panneau principal */
    .bulk-panel {
        background-color: var(--text-light);
        border-radius: 30px 0;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
        margin-bottom: 2rem;
    }

    .panel-header {
        padding: 1.25rem 1.5rem;
        background-color: var(--primary);
        color: var(--text-light);
    }

    .panel-title {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .panel-body {
        padding: 1.5rem;
    }

    /* Recherche et filtres */
    .search-filter {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .search-container {
        position: relative;
        flex: 1;
        max-width: 400px;
    }

    .search-container input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 1px solid var(--border);
        border-radius: 20px 0;
        background-color: #f9f9f9;
    }

    .search-container i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
    }

    .filter-actions {
        display: flex;
        gap: 0.75rem;
    }

    .filter-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background-color: #f9f9f9;
        border: 1px solid var(--border);
        border-radius: 20px 0;
        color: var(--primary);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .filter-button:hover {
        background-color: var(--primary);
        color: var(--text-light);
    }

    /* Liste des magasins */
    .stores-selection {
        margin-bottom: 2rem;
        border: 1px solid var(--border);
        border-radius: 20px 0;
        overflow: hidden;
    }

    .stores-list {
        max-height: 300px;
        overflow-y: auto;
    }

    .store-item {
        display: flex;
        align-items: center;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border);
    }

    .store-item:last-child {
        border-bottom: none;
    }

    .store-checkbox {
        display: flex;
        align-items: center;
        margin-right: 1rem;
        cursor: pointer;
    }

    .store-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkbox-custom {
        position: relative;
        display: inline-block;
        width: 20px;
        height: 20px;
        background-color: #f9f9f9;
        border: 1px solid var(--border);
        border-radius: 4px;
    }

    .store-checkbox input:checked ~ .checkbox-custom::after {
        content: '\f00c';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: var(--primary);
        font-size: 0.75rem;
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
        margin-left: 1rem;
    }

    .store-status.open {
        background-color: rgba(93, 187, 99, 0.2);
        color: var(--success);
    }

    .store-status.closed {
        background-color: rgba(220, 53, 69, 0.2);
        color: var(--error);
    }

    /* Sélection d'action */
    .action-selection {
        margin-bottom: 2rem;
    }

    .section-subtitle {
        font-size: 1.1rem;
        color: var(--primary);
        margin-bottom: 1rem;
        position: relative;
        padding-left: 0.75rem;
    }

    .section-subtitle::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 70%;
        background-color: var(--primary);
        border-radius: 2px;
    }

    .action-types {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .action-type {
        position: relative;
        cursor: pointer;
    }

    .action-type input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .action-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        background-color: #f9f9f9;
        border: 1px solid var(--border);
        border-radius: 20px 0;
        transition: all 0.2s ease;
    }

    .action-type input:checked ~ .action-content {
        background-color: rgba(10, 46, 46, 0.05);
        border-color: var(--primary);
    }

    .action-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--primary);
        color: var(--text-light);
        border-radius: 10px 0;
        font-size: 1.25rem;
    }

    .action-info {
        flex: 1;
    }

    .action-title {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .action-description {
        font-size: 0.85rem;
        color: #666;
    }

    /* Détails de l'action */
    .action-details {
        background-color: #f9f9f9;
        padding: 1.5rem;
        border-radius: 20px 0;
        border: 1px solid var(--border);
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--primary);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border);
        border-radius: 15px 0;
        background-color: white;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
    }

    .time-slots {
        margin-bottom: 1rem;
    }

    .time-slot {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        padding: 1rem;
        background-color: white;
        border: 1px solid var(--border);
        border-radius: 15px 0;
    }

    .time-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        flex: 1;
    }

    .remove-slot {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--error);
        border: none;
        border-radius: 8px 0;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .remove-slot:hover {
        background-color: var(--error);
        color: var(--text-light);
    }

    .add-slot-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.75rem;
        background-color: rgba(10, 46, 46, 0.05);
        color: var(--primary);
        border: 1px dashed var(--border);
        border-radius: 15px 0;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .add-slot-btn:hover {
        background-color: rgba(10, 46, 46, 0.1);
    }

    .holiday-info {
        padding: 1rem;
        background-color: rgba(142, 110, 83, 0.1);
        border-radius: 15px 0;
        border-left: 4px solid var(--tertiary);
    }

    .holiday-info p {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--tertiary);
    }

    /* Résumé des magasins sélectionnés */
    .selected-stores-summary {
        margin-bottom: 2rem;
    }

    .selected-stores-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        padding: 1rem;
        background-color: #f9f9f9;
        border: 1px solid var(--border);
        border-radius: 15px 0;
        min-height: 60px;
    }

    .selected-store-tag {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        background-color: white;
        border: 1px solid var(--border);
        border-radius: 15px 0;
        font-size: 0.9rem;
    }

    .remove-tag {
        cursor: pointer;
        color: #999;
        transition: all 0.2s ease;
    }

    .remove-tag:hover {
        color: var(--error);
    }

    /* Actions du formulaire */
    .form-actions {
        display: flex;
        justify-content: flex-end;
    }

    .submit-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: var(--text-light);
        border: none;
        border-radius: 30px 0;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(10, 46, 46, 0.2);
    }

    .submit-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .bulk-title::before {
            display: none;
        }

        .header-actions {
            width: 100%;
        }

        .action-button {
            width: 100%;
            justify-content: center;
        }

        .search-filter {
            flex-direction: column;
            gap: 1rem;
        }

        .search-container {
            width: 100%;
            max-width: none;
        }

        .filter-actions {
            width: 100%;
        }

        .filter-button {
            flex: 1;
            justify-content: center;
        }

        .action-types {
            grid-template-columns: 1fr;
        }

        .time-inputs {
            grid-template-columns: 1fr;
        }
    }

    /* Dark mode */
    @media (prefers-color-scheme: dark) {
        .bulk-panel, .action-details {
            background-color: #0A2E2E;
            border-color: #2A6363;
        }
        
        .search-container input, .filter-button, .action-content, .time-slot, .selected-stores-list, .selected-store-tag, .form-control {
            background-color: #1a3e3e;
            border-color: #2A6363;
            color: var(--text-light);
        }
        
        .store-item {
            border-color: #2A6363;
        }
        
        .store-name, .action-title, .section-subtitle, .form-group label {
            color: var(--text-light);
        }
        
        .store-location, .action-description {
            color: #aaa;
        }
        
        .checkbox-custom {
            background-color: #1a3e3e;
            border-color: #2A6363;
        }
        
        .holiday-info {
            background-color: rgba(142, 110, 83, 0.2);
        }
        
        .holiday-info p {
            color: #e0c0a8;
        }
    }
</style>

<script>
    // Variables globales
    let timeSlotCounter = 1;
    let exceptionTimeSlotCounter = 1;
    
    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les icônes Font Awesome
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        
        // Gérer l'affichage des sections en fonction du type d'action
        const actionTypes = document.querySelectorAll('input[name="action_type"]');
        actionTypes.forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('regular-schedule-section').style.display = 'none';
                document.getElementById('exception-section').style.display = 'none';
                document.getElementById('holiday-section').style.display = 'none';
                
                switch(this.value) {
                    case 'regular_schedule':
                        document.getElementById('regular-schedule-section').style.display = 'block';
                        break;
                    case 'exception':
                        document.getElementById('exception-section').style.display = 'block';
                        break;
                    case 'holiday':
                        document.getElementById('holiday-section').style.display = 'block';
                        break;
                }
            });
        });
        
        // Gérer l'affichage des créneaux horaires en fonction de l'état "fermé"
        const isClosedCheckbox = document.getElementById('is_closed');
        const timeSlotsContainer = document.getElementById('time-slots-container');
        
        isClosedCheckbox.addEventListener('change', function() {
            timeSlotsContainer.style.display = this.checked ? 'none' : 'block';
        });
        
        const exceptionIsClosedCheckbox = document.getElementById('exception_is_closed');
        const exceptionTimeSlotsContainer = document.getElementById('exception-time-slots-container');
        
        exceptionIsClosedCheckbox.addEventListener('change', function() {
            exceptionTimeSlotsContainer.style.display = this.checked ? 'none' : 'block';
        });
        
        // Gérer la sélection des magasins
        const storeSelectors = document.querySelectorAll('.store-selector');
        storeSelectors.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedStores);
        });
        
        // Faire disparaître les alertes après 5 secondes
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 500);
            });
        }, 5000);
    });
    
    // Filtrer les magasins
    function filterStores() {
        const searchText = document.getElementById('store-search').value.toLowerCase();
        const storeItems = document.querySelectorAll('.store-item');
        
        storeItems.forEach(item => {
            const storeName = item.querySelector('.store-name').textContent.toLowerCase();
            const storeLocation = item.querySelector('.store-location').textContent.toLowerCase();
            
            if (storeName.includes(searchText) || storeLocation.includes(searchText)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }
    
    // Sélectionner tous les magasins
    function selectAllStores() {
        const storeSelectors = document.querySelectorAll('.store-selector');
        storeSelectors.forEach(checkbox => {
            checkbox.checked = true;
        });
        updateSelectedStores();
    }
    
    // Désélectionner tous les magasins
    function deselectAllStores() {
        const storeSelectors = document.querySelectorAll('.store-selector');
        storeSelectors.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedStores();
    }
    
    // Mettre à jour la liste des magasins sélectionnés
    function updateSelectedStores() {
        const selectedStores = document.querySelectorAll('.store-selector:checked');
        const selectedCount = document.getElementById('selected-count');
        const selectedStoresList = document.getElementById('selected-stores-list');
        const submitBtn = document.getElementById('submit-btn');
        
        // Mettre à jour le compteur
        selectedCount.textContent = selectedStores.length;
        
        // Activer/désactiver le bouton de soumission
        submitBtn.disabled = selectedStores.length === 0;
        
        // Mettre à jour la liste des magasins sélectionnés
        selectedStoresList.innerHTML = '';
        
        selectedStores.forEach(checkbox => {
            const storeItem = checkbox.closest('.store-item');
            const storeName = storeItem.querySelector('.store-name').textContent;
            
            const storeTag = document.createElement('div');
            storeTag.className = 'selected-store-tag';
            storeTag.innerHTML = `
                <span>${storeName}</span>
                <span class="remove-tag" onclick="deselectStore('${checkbox.value}')">&times;</span>
            `;
            
            selectedStoresList.appendChild(storeTag);
        });
    }
    
    // Désélectionner un magasin spécifique
    function deselectStore(storeId) {
        const checkbox = document.querySelector(`.store-selector[value="${storeId}"]`);
        if (checkbox) {
            checkbox.checked = false;
            updateSelectedStores();
        }
    }
    
    // Ajouter un créneau horaire pour l'horaire régulier
    function addTimeSlot() {
        const timeSlots = document.querySelector('#time-slots-container .time-slots');
        
        const timeSlot = document.createElement('div');
        timeSlot.className = 'time-slot';
        timeSlot.innerHTML = `
            <div class="time-inputs">
                <div class="form-group">
                    <label for="opening_time_${timeSlotCounter}">Heure d'ouverture</label>
                    <input type="time" name="time_slots[${timeSlotCounter}][start]" id="opening_time_${timeSlotCounter}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="closing_time_${timeSlotCounter}">Heure de fermeture</label>
                    <input type="time" name="time_slots[${timeSlotCounter}][end]" id="closing_time_${timeSlotCounter}" class="form-control">
                </div>
            </div>
            <button type="button" class="remove-slot" onclick="removeTimeSlot(this)">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        timeSlots.appendChild(timeSlot);
        timeSlotCounter++;
    }
    
    // Supprimer un créneau horaire pour l'horaire régulier
    function removeTimeSlot(button) {
        const timeSlots = document.querySelector('#time-slots-container .time-slots');
        
        if (timeSlots.children.length > 1) {
            button.closest('.time-slot').remove();
        }
    }
    
    // Ajouter un créneau horaire pour l'exception
    function addExceptionTimeSlot() {
        const timeSlots = document.querySelector('#exception-time-slots-container .time-slots');
        
        const timeSlot = document.createElement('div');
        timeSlot.className = 'time-slot';
        timeSlot.innerHTML = `
            <div class="time-inputs">
                <div class="form-group">
                    <label for="exception_opening_time_${exceptionTimeSlotCounter}">Heure d'ouverture</label>
                    <input type="time" name="exception_time_slots[${exceptionTimeSlotCounter}][start]" id="exception_opening_time_${exceptionTimeSlotCounter}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="exception_closing_time_${exceptionTimeSlotCounter}">Heure de fermeture</label>
                    <input type="time" name="exception_time_slots[${exceptionTimeSlotCounter}][end]" id="exception_closing_time_${exceptionTimeSlotCounter}" class="form-control">
                </div>
            </div>
            <button type="button" class="remove-slot" onclick="removeExceptionTimeSlot(this)">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        timeSlots.appendChild(timeSlot);
        exceptionTimeSlotCounter++;
    }
    
    // Supprimer un créneau horaire pour l'exception
    function removeExceptionTimeSlot(button) {
        const timeSlots = document.querySelector('#exception-time-slots-container .time-slots');
        
        if (timeSlots.children.length > 1) {
            button.closest('.time-slot').remove();
        }
    }
</script>
@endsection
