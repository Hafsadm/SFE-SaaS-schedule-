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

/* Loader */
#loader {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000;
    padding: 1.5rem 2rem;
    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 0.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    font-size: 1rem;
    font-weight: 500;
    color: var(--primary);
    border: 1px solid rgba(66, 145, 130, 0.2);
    animation: fadeIn 0.3s ease-out;
}

#loader::after {
    content: "";
    display: inline-block;
    width: 1rem;
    height: 1rem;
    margin-left: 0.75rem;
    border: 2px solid rgba(66, 145, 130, 0.3);
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

/* Animation du loader */
@keyframes spin {
    to { transform: rotate(360deg); }
}

@keyframes fadeIn {
    from { opacity: 0; transform: translate(-50%, -45%); }
    to { opacity: 1; transform: translate(-50%, -50%); }
}

/* Message d'erreur */
#error-message {
    position: fixed;
    top: 1rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    padding: 0.75rem 1.5rem;
    background-color: #fff1f1;
    border: 1px solid #fee2e2;
    border-radius: 0.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    font-size: 0.9rem;
    color: #dc2626;
    text-align: center;
    max-width: 90%;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateX(-50%) translateY(-20px); }
    to { opacity: 1; transform: translateX(-50%) translateY(0); }
}

/* Version mobile */
@media (max-width: 768px) {
    #loader {
        width: 90%;
        text-align: center;
        padding: 1rem;
    }
    
    #error-message {
        width: 90%;
        padding: 0.75rem;
    }
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

    /* Bouton de réinitialisation */
    .reset-button {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f8f8;
        color: var(--text-dark);
        border: 1px solid var(--border);
        border-radius: 30px 0;
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        height: 46px;
    }

    .reset-button:hover {
        background-color: #e8e8e8;
    }

    .reset-button i {
        margin-right: 0.5rem;
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

    /* Message "Aucun résultat trouvé" */
    .no-results {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        text-align: center;
        background-color: #f8f9fa;
        border: 1px dashed var(--border);
        border-radius: 8px;
        color: #6c757d;
    }

    .no-results i {
        font-size: 3rem;
        color: var(--primary);
        margin-bottom: 1rem;
        opacity: 0.7;
    }

    .no-results h3 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
    }

    .no-results p {
        font-size: 1rem;
        max-width: 80%;
        margin: 0 auto;
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
        margin-left: 70px;
        margin-right: auto;
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
        margin: 1rem 0;
    }

    .store-hours-toggle:hover {
        color:#000000;
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

    /* Animation des marqueurs */
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }

    .bounce {
        animation: bounce 0.8s ease infinite;
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

    .reset-button {
        background-color: #2a2a2a;
        color: #ffffff;
        border-color: #505050;
    }

    .reset-button:hover {
        background-color: #3a3a3a;
    }

    .no-results {
        background-color: #2a2a2a;
        border-color: #505050;
        color: #aaaaaa;
    }

    .no-results h3 {
        color: #ffffff;
    }
    }



    .phone-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
}

.phone-link:hover {
    background-color: rgba(188, 207, 204, 0.1);
    text-decoration: underline;
}

.phone-link:active {
    transform: scale(0.98);
}

.phone-icon {
    width: 1rem;
    height: 1rem;
    color: var(--primary);
}

/* Style pour les appareils mobiles */
@media (max-width: 768px) {
    .phone-link {
        padding: 0.75rem 1rem;
        background-color: var(--primary);
        color: white;
        justify-content: center;
    }
    
    .phone-link:hover {
        background-color: #000000;
    }
    
    .phone-icon {
        color: white;
    }
}

/* Accessibilité - Focus visible */
.phone-link:focus-visible {
    outline: 2px solid var(--primary);
    outline-offset: 2px;
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
                <input type="text" id="storeSearch" placeholder="Nom,Ville ou Pays" class="search-input">
                <button class="ok-button" id="searchButton">OK</button>
            </div>
    
            <!-- Bouton de réinitialisation -->
            <button class="reset-button" id="resetButton">
                <i data-lucide="refresh-cw"></i>
                Réinitialiser
            </button>

            <div id="loader" style="display: none;">Chargement en cours...</div>
            <div id="error-message" style="display: none; color: red; padding: 10px;"></div>

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
                document.querySelectorAll('input[name="specialite"]:checked').forEach((checkbox) => {
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

            // Fonction de réinitialisation
            function resetFilters() {
                // Réinitialiser les checkboxes et radios
                document.querySelectorAll('input[type="checkbox"], input[type="radio"]').forEach(input => {
                    input.checked = false;
                });
                
                // Réinitialiser le champ de recherche
                document.getElementById('storeSearch').value = '';
                
                // Désactiver le bouton "Autour de moi"
                document.querySelector('.location-search').classList.remove('active');
                
                // Réinitialiser la carte et les marqueurs
                if (window.map) {
                    // Recharger tous les magasins
                    loadAllStores();
                }
            }

            // Fonction pour charger tous les magasins
            function loadAllStores() {
                showLoading(true);
                
                fetch('/stores', {
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
                    updateStoresList(stores);
                    updateMap(stores);
                })
                .catch(error => {
                    console.error("Fetch error:", error);
                    showError("Erreur lors du chargement des résultats");
                })
                .finally(() => showLoading(false));
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
                        {{-- @if($store->phone)
                        <div class="store-phone">{{ $store->phone }}</div>
                        @endif --}}

                       @if($store->phone)
                        <div class="store-phone">
                            <a href="tel:{{ preg_replace('/\s+/', '', $store->phone) }}" class="phone-link">
                                {{-- <i data-lucide="phone" class="phone-icon"></i> --}}
                                {{ $store->phone }}
                            </a>
                        </div>
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

        // Ajouter un écouteur d'événement pour le bouton de réinitialisation
        document.getElementById('resetButton').addEventListener('click', function() {
            resetFilters();
        });
    </script>



    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_xQsTc41ShFh3sMnafHjUEht-8ZrDoM8&callback=initMap" async defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('storeSearch');
            const searchButton = document.getElementById('searchButton');
            const locationButton = document.querySelector('.location-search');
            const storesContainer = document.getElementById('stores-container');
            const serviceCheckboxes = document.querySelectorAll('input[name="specialite"]');
            const horaireRadios = document.querySelectorAll('input[name="horaire"]');
            const resetButton = document.getElementById('resetButton');
            
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
                locationButton.addEventListener('click', function() {
                    this.classList.toggle('active');
                    performSearch();
                });
            }
            
            if (resetButton) {
                resetButton.addEventListener('click', resetFilters);
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
                const searchTerm = document.getElementById('storeSearch')?.value.trim() || '';
                const aroundMeChecked = document.querySelector('.location-search').classList.contains('active');
    
                // 2. Préparer les paramètres pour l'URL
                const params = new URLSearchParams();
    
                // Ajouter les services
                selectedServices.forEach(service => {
                    params.append('specialite[]', service);
                });
    
                // Ajouter les autres filtres
                if (selectedHoraire) params.append('horaire', selectedHoraire);
                if (searchTerm) params.append('search', searchTerm);
    
                // 3. Gestion de la géolocalisation
                const handleFetch = (location = null) => {
                    // Si "Autour de moi" est coché et qu'on a une position
                    if (aroundMeChecked && location) {
                        params.append('latitude', location.latitude);
                        params.append('longitude', location.longitude);
                        console.log("Recherche géolocalisée:", location);
                    }
    
                    // Envoyer la requête
                    sendFetchRequest(params);
                };
    
                // Logique de géolocalisation
                if (aroundMeChecked) {
                    if (navigator.geolocation) {
                        showLoading(true);
                        
                        navigator.geolocation.getCurrentPosition(
                            position => {
                                handleFetch({
                                    latitude: position.coords.latitude,
                                    longitude: position.coords.longitude
                                });
                                showLoading(false);
                            },
                            error => {
                                console.error("Erreur de géolocalisation:", error);
                                showError("Géolocalisation impossible - Affichage de tous les résultats");
                                handleFetch(); // Continuer sans géolocalisation
                                showLoading(false);
                            },
                            { 
                                enableHighAccuracy: true, 
                                timeout: 10000,
                                maximumAge: 60000
                            }
                        );
                    } else {
                        showError("Votre navigateur ne supporte pas la géolocalisation");
                        handleFetch();
                    }
                } else {
                    console.log("Recherche standard sans géolocalisation");
                    handleFetch();
                }
            }
    
            // Fonction de réinitialisation
            function resetFilters() {
                // Réinitialiser les checkboxes et radios
                document.querySelectorAll('input[name="specialite"]:checked').forEach(checkbox => {
                    checkbox.checked = false;
                });
                
                document.querySelectorAll('input[name="horaire"]:checked').forEach(radio => {
                    radio.checked = false;
                });
                
                // Réinitialiser le champ de recherche
                document.getElementById('storeSearch').value = '';
                
                // Désactiver le bouton "Autour de moi"
                document.querySelector('.location-search').classList.remove('active');
                
                // Utiliser les données initiales des magasins au lieu de faire une nouvelle requête
                // Cela évite l'erreur "Erreur lors du chargement des résultats"
                const initialStores = window.stores || [];
                updateStoresList(initialStores);
                updateMap(initialStores);
                
                // Afficher un message de confirmation
                showMessage("Filtres réinitialisés avec succès");
            }
    
            // Fonction pour afficher un message de succès
            function showMessage(message) {
                const messageElement = document.createElement('div');
                messageElement.style.position = 'fixed';
                messageElement.style.top = '1rem';
                messageElement.style.left = '50%';
                messageElement.style.transform = 'translateX(-50%)';
                messageElement.style.zIndex = '1000';
                messageElement.style.padding = '0.75rem 1.5rem';
                messageElement.style.backgroundColor = '#f0fff4';
                messageElement.style.border = '1px solid #c6f6d5';
                messageElement.style.borderRadius = '0.5rem';
                messageElement.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
                messageElement.style.fontSize = '0.9rem';
                messageElement.style.color = '#38a169';
                messageElement.style.textAlign = 'center';
                messageElement.style.maxWidth = '90%';
                messageElement.style.animation = 'slideDown 0.3s ease-out';
                
                messageElement.textContent = message;
                document.body.appendChild(messageElement);
                
                setTimeout(() => {
                    messageElement.style.opacity = '0';
                    messageElement.style.transition = 'opacity 0.3s ease-out';
                    setTimeout(() => {
                        document.body.removeChild(messageElement);
                    }, 300);
                }, 3000);
            }
    
            // Fonctions helpers
            function showLoading(show) {
                const loader = document.getElementById('loader');
                if (loader) loader.style.display = show ? 'block' : 'none';
            }
    
            function showError(message) {
                const errorElement = document.getElementById('error-message');
                if (errorElement) {
                    errorElement.textContent = message;
                    errorElement.style.display = 'block';
                    setTimeout(() => errorElement.style.display = 'none', 5000);
                }
            }
    
            function sendFetchRequest(params) {
                showLoading(true);
    
                // Pour le développement et les tests, utilisez cette approche
                // qui simule une réponse si la requête échoue
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
                    showError("Erreur lors du chargement des résultats");
                    
                    // En cas d'erreur, utiliser les données initiales
                    const initialStores = window.stores || [];
                    updateStoresList(initialStores);
                    updateMap(initialStores);
                })
                .finally(() => showLoading(false));
            }
            
            // Mettre à jour la liste des magasins
            function updateStoresList(stores) {
                if (!storesContainer) return;
                
                // Vider le conteneur
                storesContainer.innerHTML = '';
                
                if (!stores || stores.length === 0) {
                    // Afficher un message amélioré quand aucun résultat n'est trouvé
                    storesContainer.innerHTML = `
                        <div class="no-results">
                            <i data-lucide="search-x"></i>
                            <h3>Aucun résultat trouvé</h3>
                            <p>Essayez de modifier vos critères de recherche ou utilisez le bouton "Réinitialiser" pour afficher toutes les boutiques.</p>
                        </div>`;
                    
                    // Réinitialiser les icônes Lucide
                    if (typeof lucide !== 'undefined' && lucide.createIcons) {
                        lucide.createIcons();
                    }
                    
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
                card.setAttribute('data-lat', store.latitude);
                card.setAttribute('data-lng', store.longitude);
                card.setAttribute('id', `store-${store.id}`);
                
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
                            <div class="store-location">${escapeHtml(store.nom.toUpperCase())}-${escapeHtml(store.ville || '')}</div>
                            <div class="store-address">${escapeHtml(store.adresse || '')}</div>
                        </div>
                        
                    
                    </div>
                    
                    <div class="store-contact">
                        ${store.phone ? `
                            <div class="store-phone">
                                <a href="tel:${store.phone.replace(/\s+/g, '')}" class="phone-link">
                                    ${escapeHtml(store.phone)}
                                </a>
                            </div>
                        ` : ''}                        
                        <div class="store-locate" onclick="centerMapOnStore(${store.latitude}, ${store.longitude})">
                            <i data-lucide="map-pin"></i>
                            Localiser sur la carte
                        </div>
                        
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
    </script>
    
    {{-- THIS IS THE MAP PART --}}
    <script>
        // Variables globales pour la carte
        const stores = @json($stores);
        let map;
        let markers = [];
        let infoWindow;
        let markerCluster;
        
        // Stocker les données initiales pour la réinitialisation
        window.stores = stores;
        
        function initMap() {
            // Coordonnées par défaut (Paris, France)
            const defaultLocation = { lat: 48.8566, lng: 2.3522 };
            
            // Initialiser la carte
            map = new google.maps.Map(document.getElementById('map'), {
                center: defaultLocation,
                zoom: 4,
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
            
            // Rendre les variables accessibles globalement
            window.map = map;
            window.infoWindow = infoWindow;
            
            // Ajouter les marqueurs pour chaque boutique
            addMarkersToMap(stores);
            
            // Ajuster la vue pour inclure tous les marqueurs
            if (markers.length > 0) {
                const bounds = new google.maps.LatLngBounds();
                markers.forEach(marker => bounds.extend(marker.getPosition()));
                map.fitBounds(bounds);
            }
        }
        
        // Fonction pour ajouter des marqueurs à la carte avec clustering
        function addMarkersToMap(stores) {
            // Effacer les marqueurs existants
            clearMarkers();
            
            // Vérifier si stores est un tableau
            if (!Array.isArray(stores)) {
                console.error('Les données des boutiques ne sont pas un tableau:', stores);
                return;
            }
            
            // Ajouter les nouveaux marqueurs
            stores.forEach(store => {
                if (store && store.latitude && store.longitude) {
                    const position = {
                        lat: parseFloat(store.latitude),
                        lng: parseFloat(store.longitude)
                    };
                    
                    const marker = new google.maps.Marker({
                        position: position,
                        map: map,
                        title: store.nom,
                        animation: google.maps.Animation.DROP,
                        storeId: store.id
                    });
                    
                    markers.push(marker);
                    
                    // Ajouter un événement de clic sur le marqueur
                    marker.addListener('click', () => {
                        // Arrêter l'animation de tous les marqueurs
                        markers.forEach(m => {
                            m.setAnimation(null);
                        });
                        
                        // Animer le marqueur cliqué
                        marker.setAnimation(google.maps.Animation.BOUNCE);
                        setTimeout(() => {
                            marker.setAnimation(null);
                        }, 1500);
                        
                        const content = `
                            <div style="padding: 10px; max-width: 200px;">
                                <h3 style="margin-bottom: 5px; color: #000">${store.nom || ''}</h3>
                                <p style="margin-bottom: 10px; color: #000">${store.adresse || ''}</p>
                                <a href="#store-${store.id}" style="color: #000; text-decoration: underline;">
                                    Voir détails
                                </a>
                            </div>
                        `;
                        
                        infoWindow.setContent(content);
                        infoWindow.open(map, marker);
                        
                        // Faire défiler jusqu'à la carte de boutique correspondante
                        const storeElement = document.getElementById(`store-${store.id}`);
                        if (storeElement) {
                            storeElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    });
                }
            });
            
            // Charger la bibliothèque MarkerClusterer si elle n'est pas déjà chargée
            if (markers.length > 0) {
                if (typeof MarkerClusterer !== 'undefined') {
                    createMarkerCluster();
                } else {
                    loadMarkerClusterer();
                }
            }
        }
        
        // Fonction pour créer le cluster de marqueurs
        function createMarkerCluster() {
            if (markerCluster) {
                markerCluster.clearMarkers();
            }
            
            markerCluster = new MarkerClusterer(map, markers, {
                imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m',
                gridSize: 50,
                minimumClusterSize: 3
            });
        }
        
        // Fonction pour charger la bibliothèque MarkerClusterer
        function loadMarkerClusterer() {
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js';
            script.onload = function() {
                if (typeof markerClusterer !== 'undefined') {
                    markerCluster = new markerClusterer.MarkerClusterer({
                        map,
                        markers,
                        algorithm: new markerClusterer.GridAlgorithm({
                            gridSize: 50,
                            minimumClusterSize: 3
                        })
                    });
                } else {
                    console.warn('La bibliothèque MarkerClusterer n\'a pas pu être chargée correctement');
                }
            };
            document.head.appendChild(script);
        }
        
        // Fonction pour effacer tous les marqueurs
        function clearMarkers() {
            // Supprimer le cluster s'il existe
            if (markerCluster) {
                if (typeof markerCluster.clearMarkers === 'function') {
                    markerCluster.clearMarkers();
                } else if (typeof markerCluster.setMap === 'function') {
                    markerCluster.setMap(null);
                }
            }
            
            // Supprimer les marqueurs individuels
            markers.forEach(marker => marker.setMap(null));
            markers = [];
        }
        
        // Fonction pour mettre à jour la carte
        function updateMap(stores, userLocation = null) {
            if (typeof google === 'undefined' || !google.maps) {
                console.warn('Google Maps n\'est pas chargé');
                return;
            }
            
            // Récupérer l'instance de carte
            if (!map) {
                console.warn('L\'instance de carte n\'est pas disponible');
                return;
            }
            
            // Effacer les marqueurs existants
            clearMarkers();
            
            // Si aucun résultat, afficher un message mais ne pas recharger tous les magasins
            if (!stores || stores.length === 0) {
                console.log('Aucun magasin trouvé');
                return;
            }
            
            // Ajouter les nouveaux marqueurs
            const bounds = new google.maps.LatLngBounds();
            
            stores.forEach(store => {
                if (store && store.latitude && store.longitude) {
                    const position = {
                        lat: parseFloat(store.latitude),
                        lng: parseFloat(store.longitude)
                    };
                    
                    const marker = new google.maps.Marker({
                        position: position,
                        map: map,
                        title: store.nom,
                        animation: google.maps.Animation.DROP,
                        storeId: store.id
                    });
                    
                    markers.push(marker);
                    bounds.extend(position);
                    
                    // Ajouter un événement de clic sur le marqueur
                    marker.addListener('click', () => {
                        // Arrêter l'animation de tous les marqueurs
                        markers.forEach(m => {
                            m.setAnimation(null);
                        });
                        
                        // Animer le marqueur cliqué
                        marker.setAnimation(google.maps.Animation.BOUNCE);
                        setTimeout(() => {
                            marker.setAnimation(null);
                        }, 1500);
                        
                        const content = `
                            <div style="padding: 10px; max-width: 200px;">
                                <h3 style="margin-bottom: 5px; color: #000">${store.nom || ''}</h3>
                                <p style="margin-bottom: 10px; color: #000">${store.adresse || ''}</p>
                                <a href="#store-${store.id}" style="color: #000; text-decoration: underline;">
                                    Voir détails
                                </a>
                            </div>
                        `;
                        
                        infoWindow.setContent(content);
                        infoWindow.open(map, marker);
                        
                        // Faire défiler jusqu'à la carte de boutique correspondante
                        const storeElement = document.getElementById(`store-${store.id}`);
                        if (storeElement) {
                            storeElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
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
            
            // Créer un MarkerClusterer si la bibliothèque est disponible
            if (markers.length > 0) {
                if (typeof MarkerClusterer !== 'undefined') {
                    createMarkerCluster();
                } else {
                    loadMarkerClusterer();
                }
            }
            
            // Ajuster la vue pour inclure tous les marqueurs
            if (markers.length > 0 || userLocation) {
                map.fitBounds(bounds);
                
                // Zoom out un peu si un seul point
                if ((markers.length === 1 && !userLocation) || 
                    (markers.length === 0 && userLocation)) {
                    google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
                        map.setZoom(Math.min(14, map.getZoom()));
                    });
                }
            }
        }
        
        // Centrer la carte sur une boutique avec animation améliorée
        function centerMapOnStore(lat, lng) {
            if (!map) {
                console.warn('La carte n\'est pas initialisée');
                return;
            }
            
            if (!lat || !lng) {
                console.warn('Coordonnées invalides:', lat, lng);
                return;
            }
            
            const position = new google.maps.LatLng(lat, lng);
            map.setCenter(position);
            map.setZoom(16);
            
            // Trouver et animer le marqueur correspondant
            let foundMarker = null;
            for (let i = 0; i < markers.length; i++) {
                const markerPos = markers[i].getPosition();
                if (markerPos && markerPos.lat() === position.lat() && markerPos.lng() === position.lng()) {
                    foundMarker = markers[i];
                    break;
                }
            }
            
            if (foundMarker) {
                // Arrêter l'animation de tous les marqueurs
                markers.forEach(marker => {
                    marker.setAnimation(null);
                });
                
                // Animer le marqueur sélectionné
                foundMarker.setAnimation(google.maps.Animation.BOUNCE);
                setTimeout(() => {
                    foundMarker.setAnimation(null);
                }, 1500);
                
                // Ouvrir l'infoWindow
                google.maps.event.trigger(foundMarker, 'click');
            } else {
                console.warn('Aucun marqueur trouvé pour ces coordonnées:', lat, lng);
                
                // Créer un marqueur temporaire si aucun n'est trouvé
                const tempMarker = new google.maps.Marker({
                    position: position,
                    map: map,
                    animation: google.maps.Animation.BOUNCE
                });
                
                setTimeout(() => {
                    tempMarker.setAnimation(null);
                    setTimeout(() => {
                        tempMarker.setMap(null);
                    }, 500);
                }, 1500);
            }
        }
        
        // Initialiser la carte au chargement de la page
        window.addEventListener('load', initMap);
        
        // Fonction pour afficher/masquer les horaires
        function toggleHours(element) {
            const dropdown = element.querySelector('.hours-dropdown');
            if (!dropdown) return;
            
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