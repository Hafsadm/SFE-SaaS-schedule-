@extends('layouts.app')

@section('title', 'Points de vente')
   

<style>
    :root {
        --primary: #429182;
        --secondary: #337b8d;
        --light-beige: #F9F5EF;
        --dark-beige: #1b5858;
        --text-dark: #000000;
        --text-light: #FFFFFF;
        --success: #5DBB63;
        --border: #E6D8C3;
        --error: #E74C3C;
        --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .stores-list-container {
        width: 100%;
        height: auto;
        border-radius: 4px  30px ;
        overflow-y: auto;
       
        padding: 10px;
        background-color: var(--light-beige);
    }
    
    .store-card {
        width: 100%;
        margin-bottom: 20px;
        background: var(--light-beige);
        border: 1px solid var(--border);
        border-radius: 8px;
        box-shadow: var(--card-shadow);
        padding: 15px;
        box-sizing: border-box;
    }
    
    .store-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    
    .store-badge {
        background: var(--secondary);
        color: var(--text-light);
        padding: 3px 8px;
        border-radius: 4px  30px ;
        font-size: 12px;
    }
    
    .store-status {
        font-size: 12px;
        font-weight: bold;
    }
    
    .store-status.open {
        color: var(--success);
    }
    
    .store-status.closed {
        color: var(--error);
    }
    
    .store-info {
        margin-bottom: 10px;
    }
    
    .store-location {
        font-weight: bold;
        margin-bottom: 5px;
        color: var(--dark-beige);
        
    }
    
    .store-address {
        color: var(--text-dark);
    }
    
    .store-contact {
        margin-bottom: 10px;
    }
    
    .store-phone {
        color: var(--text-dark);
    }
    
    .store-hours-toggle {
        cursor: pointer;
        color: var(--primary);
        margin-top: 5px;
        font-weight: 500;
    }
    
    /* Styles pour les horaires hebdomadaires */
    .hours-dropdown {
        display: none;
        position: absolute;
        background: var(--light-beige);
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 1rem;
        margin-top: 0.5rem;
        z-index: 10;
        box-shadow: var(--card-shadow);
        width: calc(100% - 3rem);
        max-height: 400px;
        overflow-y: auto;
    }
    
    .day-schedule {
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border);
    }
    
    .day-schedule:last-child {
        border-bottom: none;
    }
    
    .day-schedule.today {
        background-color: rgba(66, 145, 130, 0.1);
        margin: 0 -1rem;
        padding: 0.75rem 1rem;
        border-radius: 4px;
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
        color: var(--primary);
        font-weight: 500;
    }
    
    .no-hours {
        color: var(--text-dark);
        opacity: 0.6;
        font-style: italic;
    }
    
    .exception-reason {
        font-size: 0.85rem;
        color: var(--text-dark);
        opacity: 0.8;
        margin-top: 0.25rem;
        font-style: italic;
    }
    
    .exceptions-section {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px dashed var(--border);
    }
    
    .exceptions-title {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.75rem;
    }
    
    .exception-item {
        padding: 0.5rem 0;
        border-bottom: 1px dotted var(--border);
    }
    
    .exception-item:last-child {
        border-bottom: none;
    }
    
    .exception-date {
        font-weight: 500;
        margin-bottom: 0.25rem;
        color: var(--dark-beige);
    }
    
    .store-locate {
        cursor: pointer;
        color: var(--primary);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 5px;
        font-weight: 500;
    }
    
    .store-actions {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }
    
    .btn-primary, .btn-secondary {
        padding: 8px 12px;
        border-radius: 4px;
        text-align: center;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-primary {
        background: var(--primary);
        color: var(--text-light);
        border: 1px solid var(--primary);
    }
    
    .btn-primary:hover {
        background: var(--dark-beige);
        border-color: var(--dark-beige);
    }
    
    .btn-secondary {
        background: var(--light-beige);
        color: var(--dark-beige);
        border: 1px solid var(--dark-beige);
        display: block;
    }
    
    .btn-secondary:hover {
        background: var(--dark-beige);
        color: var(--text-light);
    }
    
    /* Dark mode */
    @media (prefers-color-scheme: dark) {
        .stores-list-container {
            background-color: var(--dark-beige);
        }
        
        .store-card {
            background-color: #1a3a3a;
            border-color: #2a4a4a;
        }
        
        .store-address, .store-phone, .time-slot, .exception-reason {
            color: var(--light-beige);
        }
        
        .hours-dropdown {
            background-color: #1a3a3a;
            border-color: #2a4a4a;
        }
        
        .day-schedule.today {
            background-color: rgba(66, 145, 130, 0.2);
        }
        
        .day-schedule {
            border-bottom-color: #2a4a4a;
        }
        
        .exceptions-section {
            border-top-color: #2a4a4a;
        }
        
        .exception-item {
            border-bottom-color: #2a4a4a;
        }
        
        .btn-secondary {
            background-color: #1a3a3a;
            color: var(--light-beige);
            border-color: var(--light-beige);
        }
    }
</style>


@section('content')
<div class="stores-container">
    <div class="stores-header">
        <h1>Points de vente</h1>
        <a href="{{ route('admin.stores.create') }}" class="add-store-button">
            <i class="fas fa-plus-circle"></i>
            Ajouter un point de vente
        </a>
    </div>

    <div class="content-container">
        <div class="map-container">
            <div id="map"></div>
        </div>
        

        <div class="stores-list-container" id="store-list">
            @foreach($stores as $store)
            <div class="store-card">
                <div class="store-header">
                    <span class="store-badge">
                        {{ is_array($store->services) ? implode(', ', $store->services) : ($store->services ?? 'Service non défini') }}
                    </span>
                    <span class="store-status {{ $store->is_closed ? 'closed' : 'open' }}">
                        {{ $store->is_closed ? 'Fermé' : 'Ouvert' }}
                    </span>
                </div>
                
                <div class="store-info">
                    <div class="store-location">{{ strtoupper($store->nom) }}</div>
                    <div class="store-address">{{ $store->adresse }}</div>
                </div>
                
                
                <div class="store-contact">
                    <div class="store-phone">{{ $store->phone ?? 'Aucun téléphone' }}</div>
                    <div class="store-hours-toggle" onclick="toggleHours(this)">
                        HORAIRES <i class="fas fa-chevron-down"></i>
                        <div class="hours-dropdown">
                            {!! $store->formatted_weekly_hours !!}
                        </div>
                    </div>
                </div>
        
                <div class="store-locate" onclick="centerMapOnStore({{ $store->latitude }}, {{ $store->longitude }})">
                    <i class="fas fa-map-pin"></i>
                    Localiser sur la carte
                </div>
                
                <div class="store-actions">
                    <a href="{{ route('admin.stores.schedules.select-type', $store) }}" class="btn-primary">
                        GÉRER LES HORAIRES
                    </a>
                    <a href="{{ route('admin.stores.edit', $store) }}" class="btn-primary">
                        MODIFIER
                    </a>
                </div>
                
                <a href="{{ route('admin.stores.manage', $store) }}" class="btn-secondary">
                    GÉRER L'AFFICHE DES DETAILS
                </a>
            </div>
            @endforeach
        </div>
        
        <style>
            :root {
                --primary: #429182;
                --secondary: #337b8d;
                --light-beige: #F9F5EF;
                --dark-beige: #1b5858;
                --text-dark: #000000;
                --text-light: #FFFFFF;
                --success: #5DBB63;
                --error: #E74C3C;
                --border: #E6D8C3;
                --card-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }
        
            .stores-list-container {
                width: 100%;
                overflow-y: auto;
                max-height: 80vh;
                padding: 10px;
                background-color: var(--light-beige);
            }
            
            .store-card {
                width: 100%;
                margin-bottom: 20px;
                background: var(--light-beige);
                border-radius: 8px;
                box-shadow: var(--card-shadow);
                border: 1px solid var(--border);
                padding: 15px;
                box-sizing: border-box;
            }
            
            .store-header {
                display: flex;
                justify-content: space-between;
                margin-bottom: 10px;
            }
            
            .store-badge {
                background: var(--secondary);
                color: var(--text-light);
                padding: 3px 8px;
                border-radius: 4px;
                font-size: 12px;
                font-weight: 500;
            }
            
            .store-status {
                font-size: 12px;
                font-weight: bold;
            }
            
            .store-status.open {
                color: var(--success);
            }
            
            .store-status.closed {
                color: var(--error);
            }
            
            .store-info {
                margin-bottom: 10px;
            }
            
            .store-location {
                font-weight: bold;
                margin-bottom: 5px;
                color: var(--dark-beige);
                font-size: 1.1rem;
            }
            
            .store-address {
                color: var(--text-dark);
                font-size: 0.9rem;
            }
            
            .store-contact {
                margin-bottom: 10px;
            }
            
            .store-phone {
                color: var(--text-dark);
                font-size: 0.9rem;
            }
            
            .store-hours-toggle {
                cursor: pointer;
                color: var(--primary);
                margin-top: 5px;
                font-weight: 500;
                font-size: 0.9rem;
                display: flex;
                align-items: center;
                gap: 5px;
            }
            
            .hours-dropdown {
                display: none;
                margin-top: 10px;
                padding: 12px;
                background: var(--light-beige);
                border-radius: 6px;
                border: 1px solid var(--border);
                box-shadow: var(--card-shadow);
            }
            
            .store-locate {
                cursor: pointer;
                color: var(--primary);
                margin-bottom: 15px;
                display: flex;
                align-items: center;
                gap: 5px;
                font-weight: 500;
                font-size: 0.9rem;
            }
            
            .store-actions {
                display: flex;
                gap: 10px;
                margin-bottom: 15px;
            }
            
            .btn-primary, .btn-secondary {
                padding: 8px 16px;
                border-radius: 6px;
                text-align: center;
                font-size: 0.9rem;
                text-decoration: none;
                font-weight: 500;
                transition: all 0.2s ease;
                flex: 1;
            }
            
            .btn-primary {
                background: var(--primary);
                color: var(--text-light);
                border: 1px solid var(--primary);
            }
            
            .btn-primary:hover {
                background: var(--dark-beige);
                border-color: var(--dark-beige);
                transform: translateY(-1px);
            }
            
            .btn-secondary {
                background: transparent;
                color: var(--primary);
                border: 1px solid var(--primary);
                display: block;
            }
            
            .btn-secondary:hover {
                background: rgba(66, 145, 130, 0.1);
                transform: translateY(-1px);
            }
        
            /* Dark mode */
            @media (prefers-color-scheme: dark) {
                .stores-list-container {
                    background-color: var(--dark-beige);
                }
                
                .store-card {
                    background-color: #1a3a3a;
                    border-color: #2a4a4a;
                }
                
                .store-address, .store-phone {
                    color: var(--light-beige);
                }
                
                .hours-dropdown {
                    background-color: #1a3a3a;
                    border-color: #2a4a4a;
                }
                
                .btn-secondary {
                    color: var(--light-beige);
                    border-color: var(--light-beige);
                }
                
                .btn-secondary:hover {
                    background: rgba(249, 245, 239, 0.1);
                }
            }
        </style>
        
        <script>
            function toggleHours(element) {
                const dropdown = element.querySelector('.hours-dropdown');
                const icon = element.querySelector('i');
                
                if (dropdown.style.display === 'block') {
                    dropdown.style.display = 'none';
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                } else {
                    dropdown.style.display = 'block';
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            }
            
            function centerMapOnStore(latitude, longitude) {
                // Votre code existant pour centrer la carte
                console.log('Centering map on:', latitude, longitude);
            }
        </script>
  
    </div>
</div>

<style>
    /* Variables */
    :root {
        --primary: #429182;
        --secondary: #337b8d;
        --light-beige: #F9F5EF;
        --dark-beige: #1b5858;
        --text-dark: #000000;
        --text-light: #FFFFFF;
        --success: #5DBB63;
        --error: #E74C3C;
        --border: #E6D8C3;
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Base */
    body {
        font-family: 'Inter', sans-serif;
        color: var(--text-dark);
        background-color: var(--light-beige);
        margin: 0;
        padding: 0;
    }

    /* Container principal */
    .stores-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* En-tête */
    .stores-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border);
    }

    .stores-header h1 {
        font-size: 1.8rem;
        color: var(--dark-beige);
        font-weight: 700;
        position: relative;
        margin: 0;
    }

    .stores-header h1::after {
        content: '';
        position: absolute;
        bottom: -0.5rem;
        left: 0;
        width: 3rem;
        height: 3px;
        background-color: var(--primary);
    }

    /* Bouton d'ajout */
    .add-store-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: var(--text-light);
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1rem;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .add-store-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(66, 145, 130, 0.3);
        background: linear-gradient(135deg, #4BA793 0%, #2A5F6F 100%);
    }

    /* Conteneur principal */
    .content-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    /* Carte */
    .map-container {
        height: auto;
        border-radius: 4px  30px ;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
        background-color: var(--light-beige);
    }

    #map {
        height: 100%;
        width: 100%;
    }

    /* Liste des boutiques */
    .stores-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        max-height: 600px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    /* Scrollbar personnalisée */
    .stores-list::-webkit-scrollbar {
        width: 6px;
    }

    .stores-list::-webkit-scrollbar-track {
        background: var(--light-beige);
        border-radius: 3px;
    }

    .stores-list::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 3px;
    }

    /* Carte de boutique */
    .store-card {
        background: var(--light-beige);
        height: auto;
        border-radius: 4px  30px ;
        padding: 1.25rem;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        border: 1px solid var(--border);
    }

    .store-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(66, 145, 130, 0.15);
    }

    .store-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .store-badge {
        background-color: var(--secondary);
        color: var(--text-light);
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .store-status {
        font-weight: 600;
        font-size: 0.9rem;
    }

    .store-status.open {
        color: var(--success);
    }

    .store-status.closed {
        color: var(--error);
    }

    .store-info {
        margin-bottom: 1rem;
    }

    .store-location {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark-beige);
        margin-bottom: 0.5rem;
    }

    .store-address {
        color: var(--text-dark);
        opacity: 0.8;
        font-size: 0.95rem;
    }

    .store-contact {
        padding: 1rem 0;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
        margin-bottom: 1rem;
    }

    .store-phone {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.75rem;
    }

    .store-hours-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: var(--primary);
        font-size: 0.9rem;
        cursor: pointer;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .store-hours-toggle:hover {
        color: var(--dark-beige);
    }

    .hours-dropdown {
        display: none;
        position: absolute;
        background: var(--light-beige);
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 1rem;
        margin-top: 0.5rem;
        z-index: 10;
        box-shadow: var(--card-shadow);
        width: calc(100% - 3rem);
    }

    .closed-text {
        color: var(--error);
        font-weight: 500;
    }

    .holiday-text {
        color: var(--primary);
        font-weight: 500;
    }

    .no-hours {
        color: var(--text-dark);
        opacity: 0.6;
        font-style: italic;
    }

    .time-slot {
        padding: 0.25rem 0;
        color: var(--text-dark);
    }

    .store-locate {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary);
        font-size: 0.9rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .store-locate:hover {
        color: var(--dark-beige);
    }

    .store-locate i {
        font-size: 1rem;
    }

    .store-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .btn-primary {
        padding: 0.75rem;
        background-color: var(--light-beige);
        color: var(--primary);
        border: 1px solid var(--primary);
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: rgba(66, 145, 130, 0.1);
    }

    .btn-secondary {
        display: block;
        padding: 0.75rem;
        background-color: var(--primary);
        color: var(--text-light);
        border: none;
        border-radius: 6px 30px;
        font-weight: 600;
        font-size: 0.85rem;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: 93%;
    }

    .btn-secondary:hover {
        background-color: var(--dark-beige);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .content-container {
            grid-template-columns: 1fr;
        }
        
        .map-container {
            height: 300px;
        }
        
        .stores-list {
            max-height: none;
        }
    }

    @media (max-width: 768px) {
        .stores-container {
            padding: 1.5rem;
        }
        
        .stores-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .store-actions {
            grid-template-columns: 1fr;
        }
    }

    /* Dark mode */
    @media (prefers-color-scheme: dark) {
        :root {
            --text-dark: #F9F5EF;
            --light-beige: #1a3a3a;
            --border: #2a4a4a;
        }

        body {
            background-color: #ffffff;
        }
        
        .stores-header {
            border-bottom-color: #122725;
        }
        
        .store-card {
            background-color: #1a3a3a;
            border-color: var(--border);
        }
        
        .store-location {
            color: var(--text-light);
        }
        
        .store-address, .store-phone {
            color: #F9F5EF;
            opacity: 0.9;
        }
        
        .store-hours-toggle, .store-locate {
            color: var(--secondary);
        }
        
        .btn-primary {
            background-color: #1a3a3a;
            color: var(--secondary);
            border-color: var(--secondary);
        }
        
        .hours-dropdown {
            background-color: #1a3a3a;
            border-color: var(--border);
        }

        .map-container {
            background-color: #1a3a3a;
            border-color: var(--border);
        }

        .stores-list::-webkit-scrollbar-track {
            background: #1a3a3a;
        }

        .stores-list::-webkit-scrollbar-thumb {
            background: var(--primary);
        }
    }
</style>


<script>
    // Fonction pour afficher/masquer les horaires
    function toggleHours(element) {
        const dropdown = element.querySelector('.hours-dropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        
        // Fermer les autres dropdowns
        document.querySelectorAll('.hours-dropdown').forEach(el => {
            if (el !== dropdown) {
                el.style.display = 'none';
            }
        });
        
        // Empêcher la propagation du clic
        event.stopPropagation();
    }
    
    // Fermer les dropdowns lors d'un clic ailleurs sur la page
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.store-hours-toggle')) {
            document.querySelectorAll('.hours-dropdown').forEach(el => {
                el.style.display = 'none';
            });
        }
    });
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_xQsTc41ShFh3sMnafHjUEht-8ZrDoM8"></script>
<script>

    const stores = @json($stores);
    let map;
    let markers = [];
    let infoWindow;
    
    function initMap() {
        // Coordonnées par défaut (Paris, France)
        const defaultLocation = { lat: 48.8566, lng: 2.3522 };
        
        // Initialiser la carte
        map = new google.maps.Map(document.getElementById('map'), {
            center: defaultLocation,
            zoom: 12,
            mapTypeControl: true,
            streetViewControl: false,
            fullscreenControl: true,
            styles: [
                {
                    featureType: "poi",
                    elementType: "labels",
                    stylers: [{ visibility: "off" }]
                }
            ]
        });
        
        // Créer une fenêtre d'info
        infoWindow = new google.maps.InfoWindow();
        
        // Ajouter les marqueurs pour chaque boutique
        stores.forEach(store => {
            if (store.latitude && store.longitude) {
                const position = {
                    lat: parseFloat(store.latitude),
                    lng: parseFloat(store.longitude)
                };
                
                const marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    title: store.nom,
                    animation: google.maps.Animation.DROP
                });
                
                markers.push(marker);
                
                // Ajouter un événement de clic sur le marqueur
                marker.addListener('click', () => {
                    const content = `
                        <div style="padding: 10px; max-width: 200px;">
                            <h3 style="margin-bottom: 5px;">${store.nom}</h3>
                            <p style="margin-bottom: 10px;">${store.adresse}</p>
                            <a href="#store-${store.id}" style="color: #4a90e2; text-decoration: underline;">
                                Voir détails
                            </a>
                        </div>
                    `;
                    
                    infoWindow.setContent(content);
                    infoWindow.open(map, marker);
                    
                    // Faire défiler jusqu'à la carte de boutique correspondante
                    document.querySelector(`[data-lat="${store.latitude}"][data-lng="${store.longitude}"]`)
                        ?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            }
        });
        
        // Ajuster la vue pour inclure tous les marqueurs
        if (markers.length > 0) {
            const bounds = new google.maps.LatLngBounds();
            markers.forEach(marker => bounds.extend(marker.getPosition()));
            map.fitBounds(bounds);
        }
    }
    
    // Initialiser la carte au chargement de la page
    window.addEventListener('load', initMap);
    
    // Géolocalisation
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                
                map.setCenter(pos);
                map.setZoom(14);
                
                // Ajouter un marqueur pour la position actuelle
                new google.maps.Marker({
                    position: pos,
                    map: map,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 10,
                        fillColor: "#4285F4",
                        fillOpacity: 1,
                        strokeColor: "#FFFFFF",
                        strokeWeight: 2,
                    },
                    zIndex: 999
                });
            }, function() {
                alert("Impossible d'obtenir votre position. Veuillez vérifier vos paramètres de localisation.");
            });
        } else {
            alert("La géolocalisation n'est pas prise en charge par votre navigateur.");
        }
    }
    
    // Centrer la carte sur une boutique
    function centerMapOnStore(lat, lng) {
        if (map) {
            const position = new google.maps.LatLng(lat, lng);
            map.setCenter(position);
            map.setZoom(16);
            
            // Trouver et ouvrir l'infoWindow du marqueur correspondant
            for (let i = 0; i < markers.length; i++) {
                if (markers[i].getPosition().equals(position)) {
                    google.maps.event.trigger(markers[i], 'click');
                    break;
                }
            }
        }
    }
    
    // Afficher/masquer les horaires
    function toggleHours(element) {
        const dropdown = element.querySelector('.hours-dropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        
        // Fermer les autres dropdowns
        document.querySelectorAll('.hours-dropdown').forEach(el => {
            if (el !== dropdown) {
                el.style.display = 'none';
            }
        });
        
        // Empêcher la propagation du clic
        event.stopPropagation();
    }
    
    // Fermer les dropdowns lors d'un clic ailleurs sur la page
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.store-hours-toggle')) {
            document.querySelectorAll('.hours-dropdown').forEach(el => {
                el.style.display = 'none';
            });
        }
    });
</script>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOM8d7x1z5l5e5c5e5e5e5e5e5e5e5e5e5e5e5" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.1.0/lucide.min.js"></script>