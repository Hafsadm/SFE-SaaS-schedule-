@extends('layouts.app')

@section('title', 'Points de vente')
   
<style>
    
    /* Styles pour les horaires hebdomadaires */
    .hours-dropdown {
        display: none;
        position: absolute;
        background: white;
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
        background-color: rgba(163, 163, 163, 0.1);
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
    }
    
    /* Dark mode */
    @media (prefers-color-scheme: dark) {
        .hours-dropdown {
            background-color: #3E2D1F;
            border-color: #5C4033;
        }
        
        .day-schedule.today {
            background-color: rgba(145, 102, 45, 0.1);
        }
        
        .day-schedule {
            border-bottom-color: #a3866e;
        }
        
        .exceptions-section {
            border-top-color: #5C4033;
        }
        
        .exception-item {
            border-bottom-color: #5C4033;
        }
        
        .time-slot, .exception-date {
            color: var(--light-beige);
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
        
        <div class="stores-list" id="store-list">
            @foreach($stores as $store)
            <div class="store-card" data-lat="{{ $store->latitude }}" data-lng="{{ $store->longitude }}">
                <div class="store-header">
                    <span class="store-badge">Opticien</span>
                    <span class="store-status {{ $store->getTodaySchedule() && !$store->getTodaySchedule()->is_closed ? 'open' : 'closed' }}">
                        {{ $store->getTodaySchedule() ? ($store->getTodaySchedule()->is_closed ? 'Fermé' : 'Ouvert') : 'Horaires non définis' }}
                    </span>
                </div>
                
                <div class="store-info">
                    <div class="store-location"> {{ strtoupper($store->nom) }}</div>
                    <div class="store-address">{{ $store->adresse }}</div>
                </div>
                
                <div class="store-contact">
                    <div class="store-phone">{{ $store->telephone ?? 'Aucun téléphone' }}</div>
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
                    <button class="btn-primary">PRENDRE RENDEZ-VOUS</button>
                </div>
                
                <a href="{{ route('admin.stores.show', $store) }}" class="btn-secondary">
                    Voir LA FICHE Des Details
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    /* Variables */
    :root {
        --primary: #A67C52; /* Marron doré */
        --secondary: #D2B48C; /* Beige doré */
        --light-beige: #F5F5DC;
        --dark-beige: #E0C9B4;
        --text-dark: #5C4033; /* Marron foncé */
        --text-light: #F8F4E6;
        --success: #82B183; /* Vert doux */
        --error: #C17C74; /* Rouge doux */
        --border: #E0C9B4;
        --card-shadow: 0 4px 12px rgba(92, 64, 51, 0.1);
    }

    /* Base */
    body {
        font-family: 'Inter', sans-serif;
        color: var(--text-dark);
        background-color: var(--light-beige);
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
        color: var(--text-dark);
        font-weight: 700;
        position: relative;
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
        background: linear-gradient(135deg, var(--primary) 0%, #8C5E3B 100%);
        color: white;
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
        box-shadow: 0 6px 20px rgba(166, 124, 82, 0.3);
        background: linear-gradient(135deg, #B58E6A 0%, #6D4B2C 100%);
    }

    /* Conteneur principal */
    .content-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    /* Carte */
    .map-container {
        height: 600px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
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
        background: var(--dark-beige);
        border-radius: 3px;
    }

    /* Carte de boutique */
    .store-card {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        border: 1px solid var(--border);
    }

    .store-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(92, 64, 51, 0.15);
    }

    .store-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .store-badge {
        background-color: var(--primary);
        color: white;
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
        color: var(--text-dark);
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
        color: var(--text-dark);
    }

    .hours-dropdown {
        display: none;
        position: absolute;
        background: white;
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
        color: var(--text-dark);
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
        background-color: white;
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
        background-color: rgba(166, 124, 82, 0.1);
    }

    .btn-secondary {
        display: block;
        width: 100%;
        padding: 0.75rem;
        background-color: var(--primary);
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #8C5E3B;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .content-container {
            grid-template-columns: 1fr;
        }
        
        .map-container {
            height: 400px;
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
        body {
            background-color: #2A2118;
        }
        
        .stores-header {
            border-bottom-color: #5C4033;
        }
        
        .store-card {
            background-color: #6b5542;
            border-color: #5C4033;
        }
        
        .store-location, .store-address, .store-phone {
            color: var(--light-beige);
        }
        
        .store-hours-toggle, .store-locate {
            color: var(--secondary);
        }
        
        .btn-primary {
            background-color: #3E2D1F;
            color: var(--secondary);
            border-color: var(--secondary);
        }
        
        .hours-dropdown {
            background-color: #3E2D1F;
            border-color: #5C4033;
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
    // Le script JavaScript reste identique à celui que vous avez fourni
    // Il est déjà bien optimisé et fonctionnel
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