<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
        <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>


<style>
  /* 🎨 Variables de couleur modernisées */
:root {
    --primary: #429182; 
    --secondary: #337b8d; 
    --light-beige: #F9F5EF;
    --dark-beige: #1b5858; 
    --text-dark: #000000; 
    --text-light: #FFFFFF; 
    --success: #5DBB63;
    --border: #E6D8C3; 
}


* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Georgia', sans-serif;
    line-height: 1.6;
    padding: 1rem;
}

/* 🏷️ En-tête */
.dashboard-header {
    font-family: 'Georgia', sans-serif;
    text-align: center;
    padding: 2rem 0;
    border-bottom: 2px solid var(--border);
    margin-bottom: 2.5rem;
}

.title {
    font-size: 2.5rem;
    font-weight: 800;
    color: #000000;
    letter-spacing: 1.2px;
    position: relative;
    display: inline-block;
}

.title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 90px;
    height: 3px;
    background-color:#337b8d;
    border-radius: 60px 0 ;
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
        padding: 0.75rem 1.5rem;
        border: 1px solid var(--border);
        border-radius: 30px 0px ;
        cursor: pointer;
        font-size: 0.9rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .filter-button:hover {
        background-color: var(--secondary)
    }

    /* Recherche par localisation */
    .location-search {
        display: flex;
        align-items: center;
        text-align: center;
        background-color: var(--primary);
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 30px 0 ;
        cursor: pointer;
        transition: all 0.3s ease;
        height: 50px;
        width: 190px;
    
        
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
        border-radius: 30px 0 0 0 ;
        width: 100%;
        height: 46px;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #000000;
    }

    .ok-button {
        padding: 0 1.5rem;
        background: var(--primary);
        border: none;
        border-radius: 0 0 30px 0;
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
    /* .map-container {
        height: 600px;
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 4px 12px rgba(92, 64, 51, 0.1);
    } */

    /* .map-controls {
        position: absolute;
        top: 1rem;
        left: 1rem;
        z-index: 10;
        background: white;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    } */

    /* .map-type-buttons {
        display: flex;
    } */
/* 
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
    } */

    /* Liste des boutiques */
    .stores-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        max-height: 600px;
        overflow-y: auto;
        padding-right: 0.5rem;
        color: #000000;

    }

    /* Scrollbar personnalisée */
    .stores-container::-webkit-scrollbar {
        width: 6px;
    }

    .stores-container::-webkit-scrollbar-track {
        background: var(--light-beige);
        border-radius: 30px  ;
    }

    .stores-container::-webkit-scrollbar-thumb {
        background-color: var(--dark-beige);
        border-radius: 10px;
    }

    .store-card {
        border: 1px solid var(--border);
        border-radius: 30px 0 0 30px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .store-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(92, 64, 51, 0.15);
    }



    .store-badge {
        background-color: var(--primary);
        color: white;
        margin-bottom:1rem;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    
    .store-status {
        display: inline-block;
        margin-left: 205px;
        font-weight: 600;
        font-size: 1.2rem;
    }

    .store-status.open {
        color: var(--success);
    }

    .store-status.closed {
        color: var(--error);
    }



    .store-hours {

        display: flex;
        align-items: center;
        color:#418a71;
        font-weight: 500;
        margin-top: 1.3rem;
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
        margin-top :15px;
        font-size: 1.4rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color:#000000 ;
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
        color: #000000  ;
        font-size: 0.9rem;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 0.25rem 0;
    }

    .store-hours-toggle:hover {
        color: var(--primary);
    }


    .hours-dropdown {
        display: none;
        position: absolute;
        background: rgb(190, 207, 211);
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



    .store-actions {
        display: grid;
        color: #000;
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
        background-color: var(--secondary);    }

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
            background-color: #ffffff;
            color: var(--text-light);
        }
        
        .dashboard-header {
            background-color: #ffffff;
            border-bottom-color: #FFFFFF;
        }
        
        .title {
            color:#1b5858;
        }
        
        .filter-button {
            background-color: #1b5858;
            border-color:#1b5858;
            color: var(--text-light);
        }
        
        .search-input {
            background-color: #1b5858;
            border-color: #000000;
            color: var(--text-light);
        }
        
        .store-card {
    background-color: #1b5858;
    backdrop-filter: blur(8px); 
    border: 1px solid #000000;
    border-radius: 16px;
    padding: 1rem;
}
        
        .store-address, .store-hours-toggle {
            color: #ffffff;
        }
        
        .appointment-button {
            background-color: var(--primary);       
            border-color: var(--dark-beige);
            color:#FFF;
        }

        .dropdown-options {
        position: absolute;
        top: 100%;
        left: 0;
        background:#1b5858;
        border: 1px solid #505050;
        border-radius:0  30px 0 30px ;
        padding: 10px;
        margin-top: 5px;
        min-width: 200px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        z-index: 10;
    }
    .hours-dropdown {
            background-color: #ffffff;
            border-color: #080807;
        }
    
    .dropdown-option {
        padding: 8px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .dropdown-icon {
        transition: transform 0.2s;
    }
    
    .dropdown-icon.rotated {
        transform: rotate(180deg);
    }
    }

</style>
</head>
<body>

  <div class="dashboard-header">
        <h1 class="title">NOS BOUTIQUES</h1>
    </div>
    
    <div class="container">
        <div class="filters-container">
    
            <div class="filter-group">
                <button class="filter-button" onclick="toggleDropdown(this)">
                    Service
                    <i data-lucide="chevron-down" class="dropdown-icon"></i>
                </button>
                <div class="dropdown-options" style="display: none;">
                    <div class="dropdown-option">
                        <input type="checkbox" id="specialite1" name="specialite" value="dentiste">
                        <label for="specialite1">Dentiste</label>
                    </div>     
                    <div class="dropdown-option">
                        <input type="checkbox" id="specialite2" name="specialite" value="opticien">
                        <label for="specialite2">Opticien</label>
                    </div>  
                    <div class="dropdown-option">
                        <input type="checkbox" id="specialite3" name="specialite" value="audition">
                        <label for="specialite3">Audition</label>      
                    </div>
                </div>
            </div> 
    
         
        
            <!-- Filtre Horaires -->
            <div class="filter-group">
                <button class="filter-button" onclick="toggleDropdown(this)">
                    Horaires
                    <i data-lucide="chevron-down" class="dropdown-icon"></i>
                </button>
                <div class="dropdown-options" style="display: none;">
            
                    
                    <!-- Options principales -->
                    <div class="dropdown-option">
                        <input type="radio" id="horaire_open" name="horaire" value="open_now">
                        <label for="horaire_open">Ouvert maintenant</label>
                    </div>
                    
                  
                    <div class="dropdown-option">
                        <input type="radio" id="horaire_morning" name="horaire" value="morning">
                        <label for="horaire_morning">Matin </label>
                    </div>
                    
                    <div class="dropdown-option">
                        <input type="radio" id="horaire_afternoon" name="horaire" value="afternoon">
                        <label for="horaire_afternoon">Après-midi</label>
                    </div>
                    
                    <div class="dropdown-option">
                        <input type="radio" id="horaire_evening" name="horaire" value="evening">
                        <label for="horaire_evening">Soir</label>
                    </div>
                    
                    
                    <div class="dropdown-option">
                        <input type="radio" id="horaire_weekend" name="horaire" value="weekend">
                        <label for="horaire_weekend">Week-end</label>
                    </div>
                </div>
            </div>
    
            
            <div class="location-search">
                <i data-lucide="map-pin" class="location-search-icon"></i>
                <span class="location-search-text">Autour de moi</span>
            </div>

            
            <div class="search-bar">
                <input type="text" id="storeSearch" placeholder="ville ou pays" class="search-input">
                <button class="ok-button" id="searchButton">OK</button>
            </div>
    
        </div>

        <script>
            function toggleDropdown(button) {
                // Trouver le conteneur d'options correspondant
                const optionsContainer = button.nextElementSibling;
                const icon = button.querySelector('.dropdown-icon');
                
                // Basculer l'affichage
                if (optionsContainer.style.display === 'none') {
                    optionsContainer.style.display = 'block';
                    icon.classList.add('rotated');
                } else {
                    optionsContainer.style.display = 'none';
                    icon.classList.remove('rotated');
                }
                
                // Fermer les autres dropdowns ouverts
                document.querySelectorAll('.dropdown-options').forEach(dropdown => {
                    if (dropdown !== optionsContainer && dropdown.style.display === 'block') {
                        dropdown.style.display = 'none';
                        const otherIcon = dropdown.previousElementSibling.querySelector('.dropdown-icon');
                        otherIcon.classList.remove('rotated');
                    }
                });
            }
            
            // Fermer les dropdowns quand on clique ailleurs
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.filter-group')) {
                    document.querySelectorAll('.dropdown-options').forEach(dropdown => {
                        dropdown.style.display = 'none';
                        const icon = dropdown.previousElementSibling.querySelector('.dropdown-icon');
                        icon.classList.remove('rotated');
                    });
                }
            });
            
            // Initialiser les icônes Lucide
            document.addEventListener('DOMContentLoaded', function() {
                lucide.createIcons();
            });

            function filterStores() {
    // Récupérer les services sélectionnés
    const selectedServices = [];
    document.querySelectorAll('input[name="specialite[]"]:checked').forEach((checkbox) => {
        selectedServices.push(checkbox.value);
    });

    // Envoyer la requête via Fetch
    // fetch('/filter-stores', {
    //     method: 'GET',  // ou POST si tu préfères
    //     headers: {
    //         'Content-Type': 'application/json',
    //     },
    //     body: JSON.stringify({ specialite: selectedServices }),
    // })
    // .then(response => response.json() )
    // .then(data => {
    //     // Mettre à jour la liste des magasins
    //     const storesList = document.getElementById('stores-list');
    //     storesList.innerHTML = '';  // Vider la liste avant de la remplir

    //     data.stores.forEach(store => {
    //         const storeElement = document.createElement('div');
    //         storeElement.innerHTML = `
    //             <h3>${store.nom}</h3>
    //             <p>${store.adresse}, ${store.ville}</p>
    //             <p>Services: ${store.services.join(', ')}</p>
    //         `;
    //         storesList.appendChild(storeElement);
    //     });
    // })
    // .catch(error => {
    //     console.error('Erreur lors du filtrage des magasins', error);
    // });
}


        </script>
        
    
        {{-- <div id="toggleContainer"></div> --}}
    
        <div class="content">
            <div class="map-container" id="map-container">
                <div class="map-controls">
                </div>
                <div id="map" style="width: 100%; height: 100%;"></div>
            </div>
    
            <div class="stores-container" id="stores-container">
                @foreach($stores as $store)
                <div class="store-card" >
                    <div class="store-header">
                        <span class="store-badge">
                            {{ is_array($store->services) ? implode(', ', $store->services) : ($store->services ?? 'Service non défini') }}
                        </span>              
                         
                        {{-- <span class="store-status {{ $store->is_open ? 'open' : 'closed' }}">
                            {{ $store->is_open ? 'Ouvert' : 'Fermé' }}
                        </span> --}}
                        
                                  
                        <span class="store-status {{ $store->is_open ? 'open' : 'closed' }}">
                            {{ $store->today_status }}
                        </span>
                 
    
    
                    </div>
                    
                    @if($store->ouvert_jusqua && !$store->is_closed)
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
    

                        <input type="hidden" id="latitude" name="latitude">
                        <input type="hidden" id="longitude" name="longitude">

                    </div>
    
                    <div class="store-contact">
                        @if($store->phone)
                        <div class="store-phone">{{ $store->phone }}</div>
                        @endif

                        <div class="store-locate" onclick="centerMapOnStore({{ $store->latitude }}, {{ $store->longitude }})">
                            <i data-lucide="map-pin"></i>
                            Localiser sur la carte
                        </div>
                       
                        <div class="store-hours-toggle" onclick="toggleHours(this)">
                            HORAIRES <i data-lucide="chevron-down"></i>
                            <div class="hours-dropdown">
                                {!! $store->formatted_weekly_hours !!}
                            </div>
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
                        
                        <a href="{{ route('stores.show', $store->id) }}" class="details-button">
                            VOIR LA FICHE DU POINT DE VENTE
                        </a>
                    </div>
                </div>
            </div>
                @endforeach
            </div>
    </div>
    
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
             // Faire défiler jusqu'à la carte de boutique correspondante

           

        document.addEventListener('click', function(event) {
        if (!event.target.closest('.store-hours-toggle')) {
            document.querySelectorAll('.hours-dropdown').forEach(el => {
                el.style.display = 'none';
            });
        }
    });


    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_xQsTc41ShFh3sMnafHjUEht-8ZrDoM8&callback=initMap" async defer></script>
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
                stylers: [{ color: "#429182" }, { visibility: "on" }],
              },
            ],
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
                                <h3 style="margin-bottom: 5px; color: #000">${store.nom}</h3>
                                <p style="margin-bottom: 10px; color: #000">${store.adresse}</p>
                                <a href="#store-${store.id}" style="color: #000; text-decoration: underline;">
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
                // map.fitBounds(bounds);
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
    
    

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('storeSearch');
        const searchButton = document.getElementById('searchButton');
        const locationButton = document.querySelector('.location-search');
        const storesContainer = document.getElementById('stores-container');
        const serviceCheckboxes = document.querySelectorAll('input[name="specialite"]');
        const horaireRadios = document.querySelectorAll('input[name="horaire"]');
        
        // Écouteurs d'événements
        if (searchButton) {
            searchButton.addEventListener('click', performSearch);
        }
        
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    performSearch();
                }
            });
        }
        
        if (locationButton) {
            locationButton.addEventListener('click',  performSearch);
        }
        
        // Ajouter des écouteurs pour les filtres de service et d'horaire
        serviceCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', performSearch);
        });
        
        horaireRadios.forEach(radio => {
            radio.addEventListener('change', performSearch);
        });
        
        // Fonction principale de recherche
        function performSearch() {
    // 1. Récupérer les valeurs des filtres
    const selectedServices = Array.from(document.querySelectorAll('input[name="specialite"]:checked')).map(el => el.value);
    const selectedHoraire = document.querySelector('input[name="horaire"]:checked')?.value;
    const searchTerm = searchInput ? searchInput.value.trim() : '';
    const aroundMeChecked = document.querySelector('input[name="around_me"]:checked') !== null;

    // 2. Préparer les paramètres pour l'URL
    const params = new URLSearchParams();

    if (selectedServices.length > 0) {
        selectedServices.forEach(service => {
            params.append('specialite[]', service); // Notez les crochets []
        });
    }

    // Ajouter les autres filtres
    if (selectedHoraire) params.append('horaire', selectedHoraire);
    if (searchTerm) params.append('search', searchTerm);

    // 3. Gestion de la géolocalisation
    const handleFetch = (locationParams = {}) => {
        // Si "Autour de moi" est coché, ajouter les coordonnées
        if (aroundMeChecked && locationParams.latitude) {
            params.append('latitude', locationParams.latitude);
            params.append('longitude', locationParams.longitude);
        }

        // Envoyer la requête
        sendFetchRequest(params);
    };

    if (aroundMeChecked) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                position => handleFetch({
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                }),
                error => {
                    console.error("Erreur de géolocalisation:", error);
                    showError("Géolocalisation impossible - Affichage de tous les résultats");
                    handleFetch(); // Continuer sans géolocalisation
                },
                { enableHighAccuracy: true, timeout: 5000 }
            );
        } else {
            showError("Votre navigateur ne supporte pas la géolocalisation");
            handleFetch();
        }
    } else {
        handleFetch();
    }
}

function sendFetchRequest(params) {
    showLoading(true);

    fetch(`/filter?${params.toString()}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return response.json();
    })
    .then(stores => {
        if (!Array.isArray(stores)) {
            console.error("Réponse inattendue:", stores);
            throw new Error("Format de données invalide");
        }
        updateStoresList(stores);
        updateMap(stores);
    })
    .catch(error => {
        console.error("Fetch error:", error);
        // showError("Erreur lors du chargement des résultats");
    })
    .finally(() => showLoading(false));
}


        // function handleGeolocation() {
        //     if (!navigator.geolocation) {
        //         showError("La géolocalisation n'est pas prise en charge par votre navigateur.");
        //         return;
        //     }
            
        //     showLoading(true);
            
        //     navigator.geolocation.getCurrentPosition(
        //         // Succès
        //         function(position) {
        //             const latitude = position.coords.latitude;
        //             const longitude = position.coords.longitude;

                    
        //             console.log("Position actuelle:", latitude, longitude);


                    
        //             // Envoyer les coordonnées au serveur
        //         //   fetch('/nearby', {
        //             fetch(`/filter?latitude=${latitude}&longitude=${longitude}`, {
        //                 method: 'GET',
        //                 headers: {
        //                     'Content-Type': 'application/json',
        //                    // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        //                 }
        //                 // body: JSON.stringify({
        //                 //     latitude: latitude,
        //                 //     longitude: longitude
        //                 // })
        //             })
        //             // .then(response => {
        //             //     if (!response.ok) {
        //             //         throw new Error('Erreur réseau');
        //             //     }
        //             //     return response.json();
        //             // })
        //             .then(stores => {
        //                 // Traiter et afficher les résultats
        //                 updateStoresList(stores);
        //                 updateMap(stores, { lat: latitude, lng: longitude });
        //             })
        //             .catch(error => {
        //                 console.error('Erreur lors de la recherche par géolocalisation:', error);
        //                 // showError("Une erreur est survenue lors de la recherche par géolocalisation");
        //             })
        //             .finally(() => {
        //                 showLoading(false);
        //             });
        //         },
        //         // Erreur
        //         function(error) {
        //             showLoading(false);
                    
        //             switch(error.code) {
        //                 case error.PERMISSION_DENIED:
        //                     showError("Vous avez refusé la demande de géolocalisation.");
        //                     break;
        //                 case error.POSITION_UNAVAILABLE:
        //                     showError("Les informations de localisation ne sont pas disponibles.");
        //                     break;
        //                 case error.TIMEOUT:
        //                     showError("La demande de géolocalisation a expiré.");
        //                     break;
        //                 default:
        //                     showError("Une erreur inconnue s'est produite lors de la géolocalisation.");
        //                     break;
        //             }
        //         },
        //         // Options
        //         {
        //             enableHighAccuracy: true,
        //             timeout: 5000,
        //             maximumAge: 0
        //         }
        //     );
        // }
        
        // // Mettre à jour la liste des magasins
        function updateStoresList(stores) {
            if (!storesContainer) return;
            
            // Vider le conteneur
            // storesContainer.innerHTML = '';
            
            if (!stores || stores.length === 0) {
                storesContainer.innerHTML = '<div class="no-results">Aucun résultat trouvé</div>';
                return;
            }
            
            // Ajouter chaque magasin
            stores.forEach(store => {
                const storeCard = createStoreCard(store);
                storesContainer.appendChild(storeCard);
            });
            
            // Réinitialiser les icônes Lucide
            if (typeof lucide !== 'undefined' && lucide.createIcons) {
                lucide.createIcons();
            }
        }
        
        // Créer une carte de magasin
        function createStoreCard(store) {
        const card = document.createElement('div');
        card.className = 'store-card';
        
        // Déterminer le statut d'ouverture
        // Vérifier explicitement si is_open est true
        const isOpen = store.is_open === true || 
                      (store.today_status && store.today_status.toLowerCase().includes('ouvert'));
        const statusClass = isOpen ? 'open' : 'closed';
        
        // Formater les services
        let services = '';
        if (store.services) {
            if (Array.isArray(store.services)) {
                services = store.services.join(', ');
            } else if (typeof store.services === 'object') {
                services = Object.values(store.services).join(', ');
            } else {
                services = store.services;
            }
        }
        
        // Afficher le statut d'ouverture pour le débogage
        console.log(`Store ${store.nom}: is_open=${store.is_open}, today_status=${store.today_status}`);
        
        card.innerHTML = `
            <div class="store-header">
                <span class="store-badge">
                    ${escapeHtml(services || 'Service non défini')}
                </span>
                <span class="store-status ${statusClass}">
                    ${escapeHtml(isOpen ? 'Ouvert' : 'Fermé')}
                </span>
            </div>
            
            ${store.ouvert_jusqua && !store.is_closed ? `
            <div class="store-hours">
                <i data-lucide="clock" class="hours-icon"></i>
                <span>Ouvert jusqu'à ${formatTime(store.ouvert_jusqua)}</span>
            </div>
            ` : ''}
            
            <div class="store-info">
                <div class="store-details">
                    <div class="store-location">${escapeHtml(store.nom.toUpperCase())}-${escapeHtml(store.ville)}</div>
                    <div class="store-address">${escapeHtml(store.adresse)}</div>
                </div>
                
                <button class="locate-button" onclick="centerMapOnStore(${store.latitude}, ${store.longitude})">
                    <i data-lucide="map-pin"></i>
                    Localiser
                </button>
            </div>
            
            <div class="store-contact">
                ${store.phone ? `<div class="store-phone">${escapeHtml(store.phone)}</div>` : ''}
                
                <div class="store-hours-toggle" onclick="toggleHours(this)">
                    HORAIRES <i data-lucide="chevron-down"></i>
                    <div class="hours-dropdown">
                        ${store.formatted_weekly_hours || 'Horaires non disponibles'}
                    </div>
                </div>
            </div>
            
            <div class="store-actions">
                ${store.lien_rdv ? `
                    <a href="${escapeHtml(store.lien_rdv)}" target="_blank" class="appointment-button">
                        PRENDRE RENDEZ-VOUS
                    </a>
                ` : `
                    <button class="appointment-button" disabled>
                        PRENDRE RENDEZ-VOUS
                    </button>
                `}
                
                <a href="/stores/${store.id}" class="details-button">
                    VOIR LA FICHE DU POINT DE VENTE
                </a>
            </div>
        `;
        
        return card;
    }
        
        // Mettre à jour la carte
        function updateMap(stores, userLocation = null) {
            if (typeof google === 'undefined' || !google.maps) {
                console.warn('Google Maps n\'est pas chargé');
                return;
            }
            
            // Récupérer l'instance de carte
            const map = window.map;
            if (!map) {
                console.warn('L\'instance de carte n\'est pas disponible');
                return;
            }
            
            // Effacer les marqueurs existants
            if (window.markers && window.markers.length) {
                window.markers.forEach(marker => marker.setMap(null));
            }
            window.markers = [];
            
            // Ajouter les nouveaux marqueurs
            const bounds = new google.maps.LatLngBounds();
            
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
                    
                    window.markers.push(marker);
                    bounds.extend(position);
                    
                    // Ajouter un événement de clic sur le marqueur
                    marker.addListener('click', () => {
                        if (window.infoWindow) {
                            const content = `
                                <div style="padding: 10px; max-width: 200px;">
                                    <h3 style="margin-bottom: 5px; color: #000">${escapeHtml(store.nom)}</h3>
                                    <p style="margin-bottom: 10px; color: #000">${escapeHtml(store.adresse)}</p>
                                    <a href="/admin/stores/${store.id}" style="color: #000; text-decoration: underline;">
                                        Voir détails
                                    </a>
                                </div>
                            `;
                            
                            window.infoWindow.setContent(content);
                            window.infoWindow.open(map, marker);
                        }
                    });
                }
            });
            
            // Ajouter le marqueur de l'utilisateur si disponible
            if (userLocation) {
                const userMarker = new google.maps.Marker({
                    position: userLocation,
                    map: map,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 10,
                        fillColor: "#4285F4",
                        fillOpacity: 1,
                        strokeColor: "#FFFFFF",
                        strokeWeight: 2,
                    },
                    title: "Votre position",
                    zIndex: 999
                });
                
                bounds.extend(userLocation);
            }
            
            // Ajuster la vue pour inclure tous les marqueurs
            if (window.markers.length > 0 || userLocation) {
                // map.fitBounds(bounds);
                
                // Zoom out un peu si un seul point
                if ((window.markers.length === 1 && !userLocation) || 
                    (window.markers.length === 0 && userLocation)) {
                    google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
                        map.setZoom(Math.min(14, map.getZoom()));
                    });
                }
            }
        }
        
        // Fonctions utilitaires
        function showLoading(show) {
            // Vous pouvez implémenter un indicateur de chargement ici
            const loadingElement = document.getElementById('loading-indicator');
            if (loadingElement) {
                loadingElement.style.display = show ? 'block' : 'none';
            }
        }
        
        function showError(message) {
            alert(message);
        }
        
        function formatTime(timeString) {
            try {
                const date = new Date(`2000-01-01T${timeString}`);
                return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            } catch (e) {
                return timeString;
            }
        }
        
        function escapeHtml(unsafe) {
            if (!unsafe) return '';
            return String(unsafe)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
    });
    

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
   

    // Fonction globale pour centrer la carte sur un magasin
    // function centerMapOnStore(lat, lng) {
    //     if (typeof google === 'undefined' || !google.maps || !window.map) {
    //         console.warn('Google Maps n\'est pas disponible');
    //         return;
    //     }
        
    //     const position = new google.maps.LatLng(lat, lng);
    //     window.map.setCenter(position);
    //     window.map.setZoom(16);
        
    //     // Trouver et ouvrir l'infoWindow du marqueur correspondant
    //     if (window.markers && window.infoWindow) {
    //         for (let i = 0; i < window.markers.length; i++) {
    //             if (window.markers[i].getPosition().equals(position)) {
    //                 google.maps.event.trigger(window.markers[i], 'click');
    //                 break;
    //             }
    //         }
    //     }
    // }
    
    // Fonction globale pour afficher/masquer les horaires
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
    
    

</body>
</html>

