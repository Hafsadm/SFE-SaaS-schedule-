@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
<style>
    /* Variables de couleur */
    :root {
        --primary: #A67C52; /* Marron foncé */
        --secondary: #ccbbb3; /* Marron clair */
        --light-beige: #F5F5DC;
        --dark-beige: #D2B48C;
        --text-dark: #333333;
        --text-light: #F8F4E6;
        --success: #82b183;
        --border: #E0C9B4;
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

    /* En-tête */
    .dashboard-header {
        text-align: center;
        padding: 2rem 0;
        background-color: var(--light-beige);
        border-bottom: 1px solid var(--border);
        margin-bottom: 2rem;
    }

    .title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
        letter-spacing: 1px;
        position: relative;
        display: inline-block;
    }

    .title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background-color: var(--secondary);
    }

    /* Conteneur principal */
    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* Filtres */
    .filters-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 2rem 0;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border);
        flex-wrap: wrap;
        gap: 1rem;
    }

    .filter-group {
        position: relative;
        min-width: 200px;
    }

    .filter-button {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        padding: 0.75rem 1rem;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.9rem;
        text-align: left;
        transition: all 0.3s ease;
    }

    .filter-button:hover {
        border-color: var(--secondary);
    }

    /* Recherche par localisation */
    .location-search {
        display: flex;
        align-items: center;
        background-color: var(--primary);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .location-search:hover {
        background-color: var(--secondary);
    }

    .location-search-icon {
        margin-right: 0.5rem;
    }

    .location-search-text {
        font-weight: 500;
        font-size: 0.9rem;
    }

    /* Barre de recherche */
    .search-bar {
        display: flex;
        flex-grow: 1;
        max-width: 400px;
    }

    .search-input {
        padding: 0.75rem 1rem;
        border: 1px solid var(--border);
        border-right: none;
        border-radius: 6px 0 0 6px;
        width: 100%;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--secondary);
    }

    .ok-button {
        padding: 0 1.5rem;
        background: var(--primary);
        border: none;
        border-radius: 0 6px 6px 0;
        cursor: pointer;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .ok-button:hover {
        background-color: var(--secondary);
    }

    /* Contenu principal */
    .content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 3rem;
    }

    /* Carte */
    .map-container {
        height: 600px;
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 4px 12px rgba(92, 64, 51, 0.1);
    }

    .map-controls {
        position: absolute;
        top: 1rem;
        left: 1rem;
        z-index: 10;
        background: white;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .map-type-buttons {
        display: flex;
    }

    .map-type-button {
        padding: 0.5rem 1rem;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .map-type-button.active {
        background-color: var(--primary);
        color: white;
    }

    .map-type-button:not(.active):hover {
        background-color: var(--light-beige);
    }

    /* Liste des boutiques */
    .stores-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        max-height: 600px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    /* Scrollbar personnalisée */
    .stores-container::-webkit-scrollbar {
        width: 6px;
    }

    .stores-container::-webkit-scrollbar-track {
        background: var(--light-beige);
        border-radius: 10px;
    }

    .stores-container::-webkit-scrollbar-thumb {
        background-color: var(--dark-beige);
        border-radius: 10px;
    }

    .store-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .store-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(92, 64, 51, 0.15);
    }

    .store-badge {
        display: inline-block;
        background-color: var(--primary);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.8rem;
        margin-bottom: 1rem;
        font-weight: 500;
    }

    .store-hours {
        display: flex;
        align-items: center;
        color: var(--success);
        font-weight: 500;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }

    .store-hours i {
        margin-right: 0.5rem;
    }

    .store-info {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .store-location {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--primary);
    }

    .store-address {
        font-size: 0.9rem;
        color: var(--text-dark);
        opacity: 0.8;
    }

    .locate-button {
        display: flex;
        align-items: center;
        background: none;
        border: none;
        cursor: pointer;
        color: var(--secondary);
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }

    .locate-button:hover {
        background-color: rgba(166, 124, 82, 0.1);
    }

    .locate-button i {
        margin-right: 0.5rem;
    }

    .store-contact {
        padding: 1rem 0;
        border-top: 1px solid var(--border);
        margin-bottom: 1rem;
    }

    .store-phone {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--primary);
    }

    .store-hours-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: var(--secondary);
        font-size: 0.9rem;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 0.25rem 0;
    }

    .store-hours-toggle:hover {
        color: var(--primary);
    }

    .store-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .appointment-button, .details-button {
        padding: 0.75rem;
        text-align: center;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.85rem;
        cursor: pointer;
        text-decoration: none;
        display: block;
        transition: all 0.3s ease;
    }

    .appointment-button {
        background-color: white;
        border: 1px solid var(--secondary);
        color: var(--secondary);
    }

    .appointment-button:hover {
        background-color: rgba(166, 124, 82, 0.1);
    }

    .details-button {
        background-color: var(--primary);
        border: none;
        color: white;
    }

    .details-button:hover {
        background-color: var(--secondary);
    }

    /* Bouton désactivé */
    .appointment-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        border-color: #ccc;
        color: #ccc;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .content {
            grid-template-columns: 1.5fr 1fr;
        }
    }

    @media (max-width: 992px) {
        .content {
            grid-template-columns: 1fr;
        }
        
        .map-container {
            height: 500px;
        }
        
        .stores-container {
            max-height: none;
            overflow-y: visible;
        }
        
        .filters-container {
            flex-direction: row;
            flex-wrap: wrap;
        }
        
        .search-bar {
            order: 1;
            max-width: 100%;
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .container {
            padding: 0 1rem;
        }
        
        .title {
            font-size: 1.5rem;
        }
        
        .filter-group {
            min-width: calc(50% - 0.5rem);
        }
        
        .store-actions {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .filter-group {
            min-width: 100%;
        }
        
        .location-search {
            width: 100%;
            justify-content: center;
        }
    }

    /* Dark mode */
    @media (prefers-color-scheme: dark) {
        body {
            background-color: #6b5035;
            color: var(--text-light);
        }
        
        .dashboard-header {
            background-color: #634d3b;
            border-bottom-color: #5C4033;
        }
        
        .title {
            color: var(--text-light);
        }
        
        .filter-button {
            background-color: #634d3b;
            border-color: #5C4033;
            color: var(--text-light);
        }
        
        .search-input {
            background-color: #3E2D1F;
            border-color: #5C4033;
            color: var(--text-light);
        }
        
        .store-card {
            background-color: #3E2D1F;
            border-color: #5C4033;
        }
        
        .store-address, .store-hours-toggle {
            color: var(--dark-beige);
        }
        
        .appointment-button {
            background-color: #3E2D1F;
            border-color: var(--dark-beige);
            color: var(--dark-beige);
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-header">
    <h1 class="title">NOS BOUTIQUES</h1>
</div>

<div class="container">
    <div class="filters-container">
        <div class="filter-group">
            <button class="filter-button">
                Spécialités
                <i data-lucide="chevron-down"></i>
            </button>
        </div>
        
        <div class="filter-group">
            <button class="filter-button">
                Marques
                <i data-lucide="chevron-down"></i>
            </button>
        </div>
        
        <div class="filter-group">
            <button class="filter-button">
                Horaires
                <i data-lucide="chevron-down"></i>
            </button>
        </div>
        
        <div class="location-search">
            <i data-lucide="map-pin" class="location-search-icon"></i>
            <span class="location-search-text">Autour de moi</span>
        </div>
        
        <div class="search-bar">
            <input type="text" id="storeSearch" placeholder="Code postal ou ville ou pays" class="search-input">
            <button class="ok-button" id="searchButton">OK</button>
        </div>
    </div>

    <div id="toggleContainer"></div>

    <div class="content">
        <div class="map-container" id="map-container">
            <div class="map-controls">
                <div class="map-type-buttons">
                    <button class="map-type-button active" data-type="roadmap">Plan</button>
                    <button class="map-type-button" data-type="satellite">Satellite</button>
                </div>
            </div>
            <div id="map" style="width: 100%; height: 100%;"></div>
        </div>

        <div class="stores-container" id="stores-container">
            @foreach($stores as $store)
            <div class="store-card" data-lat="{{ $store->latitude }}" data-lng="{{ $store->longitude }}">
                <div class="store-badge">
                    @if(is_array($store->services) && count($store->services) > 0)
                        {{ $store->services[0] }}
                    @else
                        Non specifié
                    @endif
                </div>
                
                @if($store->ouvert_jusqua)
                <div class="store-hours">
                    <i data-lucide="clock" class="hours-icon"></i>
                    <span>Ouvert jusqu'à {{ \Carbon\Carbon::parse($store->ouvert_jusqua)->format('H:i') }}</span>
                </div>
                @endif

                <div class="store-info">
                    <div class="store-details">
                        <div class="store-location">{{ strtoupper($store->nom) }}-{{ $store->ville }}</div>
                        <div class="store-address">{{ $store->adresse }}</div>
                    </div>

       

                    <button class="locate-button" onclick="centerMapOnStore({{ $store->latitude }}, {{ $store->longitude }})">
                        <i data-lucide="map-pin"></i>
                        Localiser
                    </button>
                </div>

                <div class="store-contact">
                    @if($store->phone)
                    <div class="store-phone">{{ $store->phone }}</div>
                    @endif
                    <div class="store-hours-toggle">
                        HORAIRES <i data-lucide="chevron-down"></i>
                    </div>
                </div>

                <div class="store-actions">
                    @if($store->lien_rdv)
                    <a href="{{ $store->lien_rdv }}" target="_blank" class="appointment-button">
                        PRENDRE RENDEZ-VOUS
                    </a>
                    @else
                    <button class="appointment-button" disabled>
                        PRENDRE RENDEZ-VOUS
                    </button>
                    @endif
                    
                    <a href="{{ route('admin.stores.show', $store->id) }}" class="details-button">
                        VOIR LA FICHE
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_xQsTc41ShFh3sMnafHjUEht-8ZrDoM8&callback=initMap" async defer></script>
<script>
    let map;
    let markers = [];
    let infoWindow;

    function initMap() {
        // Centrer la carte sur la France par défaut
        const defaultCenter = { lat: 46.603354, lng: 1.888334 };
        
        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 6,
            center: defaultCenter,
            mapTypeId: "roadmap",
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false,
            zoomControl: true,
            zoomControlOptions: {
                position: google.maps.ControlPosition.LEFT_BOTTOM
            }
        });
        
        infoWindow = new google.maps.InfoWindow();
        
        // Ajouter les marqueurs pour chaque boutique
        const storeCards = document.querySelectorAll('.store-card');
        storeCards.forEach(card => {
            const lat = parseFloat(card.dataset.lat);
            const lng = parseFloat(card.dataset.lng);
            
            if (lat && lng) {
                addMarker({ lat, lng }, card);
            }
        });
        
        // Si nous avons des marqueurs, ajuster la vue pour les inclure tous
        if (markers.length > 0) {
            const bounds = new google.maps.LatLngBounds();
            markers.forEach(marker => bounds.extend(marker.getPosition()));
            map.fitBounds(bounds);
        }
        
        // Gérer les boutons de type de carte
        const mapTypeButtons = document.querySelectorAll('.map-type-button');
        mapTypeButtons.forEach(button => {
            button.addEventListener('click', function() {
                mapTypeButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const mapType = this.dataset.type;
                map.setMapTypeId(mapType);
            });
        });
    }
    
    function addMarker(position, storeCard) {
        const marker = new google.maps.Marker({
            position: position,
            map: map,
            animation: google.maps.Animation.DROP
        });
        
        markers.push(marker);
        
        // Créer le contenu de l'infoWindow à partir des données de la carte
        const storeName = storeCard.querySelector('.store-location').textContent;
        const storeAddress = storeCard.querySelector('.store-address').textContent;
        
        const infoContent = `
            <div style="padding: 10px; max-width: 200px;">
                <h3 style="margin-bottom: 5px;">${storeName}</h3>
                <p style="margin-bottom: 10px;">${storeAddress}</p>
                <a href="#" onclick="scrollToStore('${storeCard.id}'); return false;" 
                   style="color: #333; text-decoration: underline;">
                   Voir détails
                </a>
            </div>
        `;
        
        marker.addListener('click', () => {
            infoWindow.setContent(infoContent);
            infoWindow.open(map, marker);
            
            // Faire défiler jusqu'à la carte de boutique correspondante
            storeCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
        
        // Ajouter un ID à la carte de boutique pour pouvoir y accéder facilement
        if (!storeCard.id) {
            storeCard.id = 'store-' + markers.length;
        }
    }
    
    function scrollToStore(storeId) {
        const storeCard = document.getElementById(storeId);
        if (storeCard) {
            storeCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
    
    function centerMapOnStore(lat, lng) {
        if (map) {
            map.setCenter({ lat, lng });
            map.setZoom(15);
            
            // Trouver et ouvrir l'infoWindow du marqueur correspondant
            const position = new google.maps.LatLng(lat, lng);
            for (let i = 0; i < markers.length; i++) {
                if (markers[i].getPosition().equals(position)) {
                    google.maps.event.trigger(markers[i], 'click');
                    break;
                }
            }
        }
    }

    // Géolocalisation "Autour de moi"
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les icônes Lucide
        lucide.createIcons();
        
        // Gestion de l'affichage responsive
        setupResponsiveLayout();
        
        // Initialiser la recherche
        initSearch();
        
        // Géolocalisation
        const locationButton = document.querySelector('.location-search');
        if (locationButton) {
            locationButton.addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const pos = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };
                            
                            if (map) {
                                map.setCenter(pos);
                                map.setZoom(13);
                                
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
                                });
                                
                                // Rechercher les boutiques à proximité
                                searchNearbyStores(pos.lat, pos.lng);
                            }
                        },
                        () => {
                            alert("Impossible d'obtenir votre position. Veuillez vérifier vos paramètres de localisation.");
                        }
                    );
                } else {
                    alert("La géolocalisation n'est pas prise en charge par votre navigateur.");
                }
            });
        }
    });

    function searchNearbyStores(lat, lng) {
        fetch(`/admin/stores/nearby?lat=${lat}&lng=${lng}`)
            .then(response => response.json())
            .then(stores => {
                const container = document.getElementById('stores-container');
                container.innerHTML = ''; // Vider le conteneur
                
                // Supprimer les marqueurs existants
                markers.forEach(marker => marker.setMap(null));
                markers = [];
                
                stores.forEach(store => {
                    // Créer une nouvelle carte de store
                    const storeCard = createStoreCard(store);
                    container.appendChild(storeCard);
                    
                    // Ajouter un marqueur pour cette boutique
                    if (store.latitude && store.longitude) {
                        addMarker(
                            { lat: parseFloat(store.latitude), lng: parseFloat(store.longitude) }, 
                            storeCard
                        );
                    }
                });
                
                // Réinitialiser les icônes Lucide
                lucide.createIcons();
            })
            .catch(error => {
                console.error('Erreur lors de la recherche de boutiques à proximité:', error);
            });
    }

    function setupResponsiveLayout() {
        const mapContainer = document.getElementById('map-container');
        const storesContainer = document.getElementById('stores-container');
        const toggleContainer = document.getElementById('toggleContainer');
        
        function createToggleButton() {
            if (!document.querySelector('.toggle-view-button')) {
                const toggleButton = document.createElement('button');
                toggleButton.className = 'toggle-view-button';
                toggleButton.innerHTML = 'Voir la carte';
                toggleButton.addEventListener('click', function() {
                    mapContainer.classList.toggle('show-mobile');
                    this.innerHTML = mapContainer.classList.contains('show-mobile') ? 'Voir la liste' : 'Voir la carte';
                    updateDisplay();
                });
                toggleContainer.appendChild(toggleButton);
            }
        }
        
        function updateDisplay() {
            if (window.innerWidth < 1024) {
                const showMap = mapContainer.classList.contains('show-mobile');
                mapContainer.style.display = showMap ? 'block' : 'none';
                storesContainer.style.display = showMap ? 'none' : 'block';
            } else {
                mapContainer.style.display = 'block';
                storesContainer.style.display = 'block';
            }
        }
        
        function handleResize() {
            if (window.innerWidth < 1024) {
                createToggleButton();
            } else {
                const toggleButton = document.querySelector('.toggle-view-button');
                if (toggleButton) {
                    toggleButton.remove();
                }
            }
            updateDisplay();
        }
        
        window.addEventListener('resize', handleResize);
        handleResize();
    }

    function initSearch() {
        const searchInput = document.getElementById('storeSearch');
        const searchButton = document.getElementById('searchButton');
        
        searchButton.addEventListener('click', function() {
            performSearch(searchInput.value);
        });
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch(searchInput.value);
            }
        });
    }
    
    function performSearch(query) {
        fetch(`/admin/stores/search?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(stores => {
                const container = document.getElementById('stores-container');
                container.innerHTML = ''; // Vider le conteneur
                
                // Supprimer les marqueurs existants
                markers.forEach(marker => marker.setMap(null));
                markers = [];
                
                stores.forEach(store => {
                    // Créer une nouvelle carte de store
                    const storeCard = createStoreCard(store);
                    container.appendChild(storeCard);
                    
                    // Ajouter un marqueur pour cette boutique
                    if (store.latitude && store.longitude) {
                        addMarker(
                            { lat: parseFloat(store.latitude), lng: parseFloat(store.longitude) }, 
                            storeCard
                        );
                    }
                });
                
                // Ajuster la carte pour montrer tous les marqueurs
                if (markers.length > 0) {
                    const bounds = new google.maps.LatLngBounds();
                    markers.forEach(marker => bounds.extend(marker.getPosition()));
                    map.fitBounds(bounds);
                }
                
                // Réinitialiser les icônes Lucide
                lucide.createIcons();
            })
            .catch(error => {
                console.error('Erreur lors de la recherche:', error);
            });
    }
    
    function createStoreCard(store) {
        const card = document.createElement('div');
        card.className = 'store-card';
        card.id = 'store-' + store.id;
        card.dataset.lat = store.latitude;
        card.dataset.lng = store.longitude;
        
        card.innerHTML = `
            <div class="store-badge">
                ${store.services && store.services.length > 0 ? store.services[0] : 'Opticien'}
            </div>
            
            ${store.ouvert_jusqua ? `
            <div class="store-hours">
                <i data-lucide="clock" class="hours-icon"></i>
                <span>Ouvert jusqu'à ${new Date(store.ouvert_jusqua).toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})}</span>
            </div>
            ` : ''}

            <div class="store-info">
                <div class="store-details">
                    <div class="store-location">${store.distance ? (store.distance.toFixed(1) + ' km · ') : ''}${store.ville}</div>
                    <div class="store-address">${store.adresse}</div>
                </div>
                <button class="locate-button" onclick="centerMapOnStore(${store.latitude}, ${store.longitude})">
                    <i data-lucide="map-pin"></i>
                    Localiser
                </button>
            </div>

            <div class="store-contact">
                ${store.phone ? `<div class="store-phone">${store.phone}</div>` : ''}
                <div class="store-hours-toggle">
                    HORAIRES <i data-lucide="chevron-down"></i>
                </div>
            </div>

            <div class="store-actions">
                ${store.lien_rdv ? `
                <a href="${store.lien_rdv}" target="_blank" class="appointment-button">
                    PRENDRE RENDEZ-VOUS
                </a>
                ` : `
                <button class="appointment-button" disabled>
                    PRENDRE RENDEZ-VOUS
                </button>
                `}
                
                <a href="/admin/stores/${store.id}" class="details-button">
                    VOIR LA FICHE DE L'OPTICIEN
                </a>
            </div>
        `;
        
        return card;
    }
</script>
@endpush