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
    <link href="https://fonts.googleapis.com/css2?family=Georgia:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

<style>
    /* 🎨 Variables de couleur modernisées */
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
        border: 1px solid rgba(42, 99, 99, 0.2);
        animation: fadeIn 0.3s ease-out;
    }

    #loader::after {
        content: "";
        display: inline-block;
        width: 1rem;
        height: 1rem;
        margin-left: 0.75rem;
        border: 2px solid rgba(42, 99, 99, 0.3);
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
        color: var(--primary);
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
        background-color: var(--secondary);
        border-radius: 60px 0;
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
        border-radius: 30px 0px;
        cursor: pointer;
        font-size: 0.9rem;
        text-align: center;
        transition: all 0.3s ease;
        background-color: white;
        color: var(--primary);
    }

    .filter-button:hover {
        background-color: var(--secondary);
        color: var(--text-light);
    }

    /* 🔧 FIX 1: Amélioration du dropdown des filtres horaires */
    .dropdown-options {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid var(--border);
        border-radius: 0 30px 0 30px;
        padding: 0.75rem;
        margin-top: 5px;
        min-width: 200px;
        max-height: 250px; /* 🔧 Hauteur maximale pour éviter l'agrandissement */
        overflow-y: auto; /* 🔧 Scroll si nécessaire */
        box-shadow: 0 8px 25px rgba(0,0,0,0.15); /* 🔧 Ombre plus prononcée */
        z-index: 1000; /* 🔧 Z-index élevé pour passer au-dessus */
        color: var(--text-dark);
        backdrop-filter: blur(8px); /* 🔧 Effet de flou d'arrière-plan */
        border-top: 3px solid var(--primary); /* 🔧 Bordure supérieure colorée */
    }

    /* 🔧 Scrollbar personnalisée pour le dropdown */
    .dropdown-options::-webkit-scrollbar {
        width: 4px;
    }

    .dropdown-options::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 2px;
    }

    .dropdown-options::-webkit-scrollbar-thumb {
        background-color: var(--secondary);
        border-radius: 2px;
    }

    .dropdown-option {
        padding: 0.6rem 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--text-dark);
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.2s ease;
        margin-bottom: 0.25rem;
    }

    /* 🔧 Hover effect pour les options */
    .dropdown-option:hover {
        background-color: rgba(42, 99, 99, 0.1);
        transform: translateX(2px);
    }

    .dropdown-option input[type="radio"] {
        margin: 0;
        accent-color: var(--primary);
    }

    .dropdown-option label {
        cursor: pointer;
        font-weight: 500;
        flex: 1;
    }

    /* Recherche par localisation */
    .location-search {
        display: flex;
        align-items: center;
        text-align: center;
        background-color: var(--primary);
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 30px 0;
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
        border-radius: 30px 0 0 0;
        width: 100%;
        height: 46px;
        transition: all 0.3s ease;
        color: var(--text-dark);
        background-color: white;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary);
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
        color: var(--primary);
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

    /* Liste des boutiques */
    .stores-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        max-height: 600px;
        overflow-y: auto;
        padding-right: 0.5rem;
        color: var(--text-dark);
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
        background: #f1f1f1;
        border-radius: 30px;
    }

    .stores-container::-webkit-scrollbar-thumb {
        background-color: var(--secondary);
        border-radius: 10px;
    }

    .store-card {
        border: 1px solid var(--border);
        border-radius: 30px 0 0 30px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        background-color: white;
    }

    .store-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(10, 46, 46, 0.15);
    }

    .store-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .store-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .store-badge {
        background-color: var(--primary);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 30px 60px 30px 60px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .store-status {
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
        color: var(--primary);
        font-weight: 500;
        margin-top: 1.3rem;
        font-size: 1rem;
        font-family: Georgia, 'Times New Roman', Times, serif;
        gap: 0.5rem;
        font-weight: 700;
    }

    .store-hours i {
        margin-right: 0.5rem;
    }

    /* Nouveau style pour les raisons de fermeture */
    .closure-reason {
        display: flex;
        align-items: center;
        margin-top: 0.75rem;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 500;
        gap: 0.5rem;
    }

    .closure-reason.exception {
        background-color: rgba(245, 158, 11, 0.1);
        color: var(--warning);
        border-left: 3px solid var(--warning);
    }

    .closure-reason.holiday {
        background-color: rgba(142, 110, 83, 0.1);
        color: var(--tertiary);
        border-left: 3px solid var(--tertiary);
    }

    .closure-reason.regular-closed {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--error);
        border-left: 3px solid var(--error);
    }

    .closure-reason i {
        font-size: 1rem;
        flex-shrink: 0;
    }

    .store-info {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .store-location {
        margin-top: 15px;
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--primary);
    }

    .store-address {
        font-size: 0.9rem;
        color: var(--text-dark);
        opacity: 0.8;
        font-weight: 700;
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
        background-color: rgba(42, 99, 99, 0.1);
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
        color: #dc2626;
        font-size: 0.9rem;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 0.5rem 0.75rem;
        margin: 1rem 0;
        border-radius: 6px;
        background-color: rgba(220, 38, 38, 0.05);
        border: 1px solid rgba(220, 38, 38, 0.1);
        position: relative; /* 🔧 Position relative pour le dropdown */
    }

    .store-hours-toggle:hover {
        color: var(--secondary);
        background-color: rgba(42, 99, 99, 0.05);
        border-color: rgba(42, 99, 99, 0.2);
    }

    /* 🔧 FIX 2: Amélioration du dropdown des horaires avec scroll */
    .hours-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 1rem;
        margin-top: 0.5rem;
        z-index: 100;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        color: var(--text-dark);
        max-height: 200px; /* 🔧 Hauteur maximale */
        overflow-y: auto; /* 🔧 Scroll vertical */
        backdrop-filter: blur(8px);
        border-top: 3px solid var(--primary);
    }

    /* 🔧 Scrollbar personnalisée pour les horaires */
    .hours-dropdown::-webkit-scrollbar {
        width: 6px;
    }

    .hours-dropdown::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .hours-dropdown::-webkit-scrollbar-thumb {
        background-color: var(--secondary);
        border-radius: 3px;
    }

    .hours-dropdown::-webkit-scrollbar-thumb:hover {
        background-color: var(--primary);
    }

    /* 🔧 Style amélioré pour le contenu des horaires */
    .hours-dropdown .time-slot {
        padding: 0.4rem 0;
        color: var(--text-dark);
        border-bottom: 1px solid rgba(230, 216, 195, 0.3);
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .hours-dropdown .time-slot:last-child {
        border-bottom: none;
    }

    .hours-dropdown .time-slot strong {
        color: var(--primary);
        font-weight: 600;
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

    .time-slot {
        padding: 0.25rem 0;
        color: var(--text-dark);
    }

    .store-locate {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--secondary);
        font-size: 0.9rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .store-locate:hover {
        color: var(--tertiary);
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
        background-color: rgba(10, 46, 46, 0.1);
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
        background-color: var(--secondary);
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

        /* 🔧 Responsive pour les dropdowns */
        .dropdown-options,
        .hours-dropdown {
            left: -10px;
            right: -10px;
            max-height: 180px;
        }
    }

    .store-actions {
        display: grid;
        color: var(--text-dark);
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
        background-color: var(--secondary);
        color: white;
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
            background-color: #ffffff;
            color: var(--text-dark);
        }
        
        .dashboard-header {
            background-color: #ffffff;
            border-bottom-color: var(--border);
        }
        
        .title {
            color: var(--primary);
        }
        
        .filter-button {
            background-color: white;
            color: var(--primary);
            border-color: var(--border);
        }
        
        .search-input {
            background-color: #ffffff;
            color: var(--text-dark);
            border-color: var(--border);
        }
        
        .store-card {
            background-color: #ffffff;
            backdrop-filter: blur(8px); 
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 1rem;
        }
        
        .store-address, .store-hours-toggle {
            color: var(--text-dark);
        }
        
        .appointment-button {
            background-color: white;
            border-color: var(--secondary);
            color: var(--secondary);
        }

        .dropdown-options {
            background: white;
            border: 1px solid var(--border);
            color: var(--text-dark);
        }
        
        .hours-dropdown {
            background-color: #ffffff;
            border-color: var(--border);
            color: var(--text-dark);
        }
    
        .dropdown-option {
            color: var(--text-dark);
        }
        
        .dropdown-icon {
            transition: transform 0.2s;
        }
        
        .dropdown-icon.rotated {
            transform: rotate(180deg);
        }

        .reset-button {
            background-color: #f8f8f8;
            color: var(--primary);
            border-color: var(--border);
        }

        .reset-button:hover {
            background-color: #e8e8e8;
        }

        .no-results {
            background-color: #f8f9fa;
            border-color: var(--border);
            color: #6c757d;
        }

        .no-results h3 {
            color: var(--text-dark);
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
        background-color: rgba(10, 46, 46, 0.1);
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
            background-color: var(--secondary);
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

    .service-filter-group {
        position: relative;
        min-width: 200px;
    }

    .service-search-container {
        display: flex;
        align-items: center;
        position: relative;
        width: 100%;
    }

    .service-search-input {
        width: 95%;
        padding: 0.75rem 1.5rem 0.75rem 2.5rem;
        border: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1.5rem;
        border-radius: 30px 0px;
        cursor: pointer;
        height: 50px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background-color: white;
        color: var(--text-dark);
    }

    .service-search-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(10, 46, 46, 0.1);
    }

    .service-search-icon {
        position: absolute;
        left: 0.55rem;
        right: 0.55rem;
        color: var(--primary);
        font-size: 1.2rem;
        width: 1rem;
        height: 1rem;
    }

    /* --- TYPO Responsive et moderne --- */
    .title {
        font-size: clamp(1.8rem, 4vw, 2.5rem);
    }

    /* --- Responsive amélioré pour la grille principale --- */
    .content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    @media (max-width: 992px) {
        .content {
            grid-template-columns: 1fr;
        }

        .search-bar {
            max-width: 100%;
        }

        .filters-container {
            flex-direction: column;
            align-items: stretch;
        }
    }

    /* --- Pour les très petits écrans --- */
    @media (max-width: 480px) {
        .filter-group,
        .search-bar,
        .reset-button,
        .location-search {
            width: 100% !important;
        }

        .ok-button {
            width: 100%;
            border-radius: 0 0 10px 10px;
        }

        .search-input {
            border-radius: 10px 10px 0 0;
            border-right: 1px solid var(--border);
        }

        .filters-container {
            padding: 1rem 0;
            gap: 0.75rem;
        }

        .dashboard-header {
            padding: 1rem 0;
        }
    }

    /* --- Loader moderne et accessible --- */
    #loader {
        backdrop-filter: blur(4px);
        border-radius: 1rem;
        font-size: 1rem;
    }

    /* --- Hover améliorés --- */
    .filter-button:hover,
    .ok-button:hover,
    .location-search:hover,
    .reset-button:hover {
        transform: scale(1.02);
    }

    /* --- Focus accessibles --- */
    .search-input:focus,
    .filter-button:focus,
    .ok-button:focus,
    .reset-button:focus {
        outline: 2px solid var(--primary);
        outline-offset: 2px;
    }

    /* --- Scroll personnalisé pour la liste des boutiques --- */
    .stores-container::-webkit-scrollbar {
        width: 6px;
    }

    .stores-container::-webkit-scrollbar-thumb {
        background-color: var(--primary);
        border-radius: 4px;
    }

    .stores-container {
        scrollbar-width: thin;
        scrollbar-color: var(--primary) transparent;
    }

</style>
</head>
<body>
    <!-- Le reste du HTML reste identique -->
    <div class="dashboard-header">
        <h1 class="title">NOS BOUTIQUES</h1>
    </div>

    <div class="container">
        <div class="filters-container">
            <div class="filter-group service-filter-group">
                <div class="service-search-container">
                    <input type="text" id="serviceSearch" placeholder="Rechercher un service..." class="service-search-input">
                    <i data-lucide="search" class="service-search-icon"></i>
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
                        <label for="horaire_morning">Matin</label>
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

        <!-- 🔧 JavaScript amélioré pour les dropdowns -->
        <script>
            function toggleDropdown(button) {
                const optionsContainer = button.nextElementSibling;
                const icon = button.querySelector('.dropdown-icon');
                
                // Basculer l'affichage
                if (optionsContainer.style.display === 'none') {
                    optionsContainer.style.display = 'block';
                    icon.classList.add('rotated');
                    
                    // 🔧 Animation d'apparition
                    optionsContainer.style.opacity = '0';
                    optionsContainer.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        optionsContainer.style.transition = 'all 0.3s ease';
                        optionsContainer.style.opacity = '1';
                        optionsContainer.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    // 🔧 Animation de disparition
                    optionsContainer.style.transition = 'all 0.2s ease';
                    optionsContainer.style.opacity = '0';
                    optionsContainer.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        optionsContainer.style.display = 'none';
                        icon.classList.remove('rotated');
                    }, 200);
                }
                
                // Fermer les autres dropdowns ouverts
                document.querySelectorAll('.dropdown-options').forEach(dropdown => {
                    if (dropdown !== optionsContainer && dropdown.style.display === 'block') {
                        dropdown.style.transition = 'all 0.2s ease';
                        dropdown.style.opacity = '0';
                        dropdown.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            dropdown.style.display = 'none';
                            const otherIcon = dropdown.previousElementSibling.querySelector('.dropdown-icon');
                            otherIcon.classList.remove('rotated');
                        }, 200);
                    }
                });
            }
            
            // Fermer les dropdowns quand on clique ailleurs
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.filter-group')) {
                    document.querySelectorAll('.dropdown-options').forEach(dropdown => {
                        if (dropdown.style.display === 'block') {
                            dropdown.style.transition = 'all 0.2s ease';
                            dropdown.style.opacity = '0';
                            dropdown.style.transform = 'translateY(-10px)';
                            setTimeout(() => {
                                dropdown.style.display = 'none';
                                const icon = dropdown.previousElementSibling.querySelector('.dropdown-icon');
                                icon.classList.remove('rotated');
                            }, 200);
                        }
                    });
                }
            });
            
            // Initialiser les icônes Lucide
            document.addEventListener('DOMContentLoaded', function() {
                lucide.createIcons();
            });
        </script>

        <div class="content">
            <div class="map-container" id="map-container">
                <div class="map-controls"></div>
                <div id="map" style="width: 100%; height: 100%;"></div>
            </div>

            <div class="stores-container" id="stores-container">
                @foreach($stores as $store)
                <div class="store-card">
                    <div class="store-header">
                        <span class="store-badge">
                            {{ is_array($store->services) ? implode(' - ', $store->services) : ($store->services ?? 'Service non défini') }}
                        </span>                                             
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

                    {{-- Affichage des raisons de fermeture exceptionnelle et jours fériés --}}
                    @if($store->is_closed && $store->closed_reason)
                        @php
                            $closureType = 'regular-closed';
                            $icon = 'x-circle';
                            
                            if(str_contains(strtolower($store->closed_reason), 'exception')) {
                                $closureType = 'exception';
                                $icon = 'alert-triangle';
                            } elseif(str_contains(strtolower($store->closed_reason), 'férié') || str_contains(strtolower($store->closed_reason), 'holiday')) {
                                $closureType = 'holiday';
                                $icon = 'calendar-x';
                            }
                        @endphp
                        
                        <div class="closure-reason {{ $closureType }}">
                            <i data-lucide="{{ $icon }}"></i>
                            <span>{{ $store->closed_reason }}</span>
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
                        <div class="store-phone">
                            <a href="tel:{{ preg_replace('/\s+/', '', $store->phone) }}" class="phone-link">
                                {{ $store->phone }}
                            </a>
                        </div>
                        @endif

                        <div class="store-locate" onclick="centerMapOnStore({{ $store->latitude }}, {{ $store->longitude }})">
                            <i data-lucide="map-pin"></i>
                            Localiser sur la carte
                        </div>
                       
                        <!-- 🔧 Amélioration du toggle des horaires -->
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
    </div>

    <!-- 🔧 JavaScript amélioré pour les horaires -->
    <script>
        // Fonction pour afficher/masquer les horaires avec animation
        function toggleHours(element) {
            const dropdown = element.querySelector('.hours-dropdown');
            
            if (dropdown.style.display === 'block') {
                // 🔧 Animation de fermeture
                dropdown.style.transition = 'all 0.3s ease';
                dropdown.style.opacity = '0';
                dropdown.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    dropdown.style.display = 'none';
                }, 300);
            } else {
                // 🔧 Animation d'ouverture
                dropdown.style.display = 'block';
                dropdown.style.opacity = '0';
                dropdown.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    dropdown.style.transition = 'all 0.3s ease';
                    dropdown.style.opacity = '1';
                    dropdown.style.transform = 'translateY(0)';
                }, 10);
            }
            
            // Fermer les autres dropdowns
            document.querySelectorAll('.hours-dropdown').forEach(el => {
                if (el !== dropdown && el.style.display === 'block') {
                    el.style.transition = 'all 0.2s ease';
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        el.style.display = 'none';
                    }, 200);
                }
            });
            
            // Empêcher la propagation du clic
            event.stopPropagation();
        }

        // Fermer les dropdowns des horaires quand on clique ailleurs
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.store-hours-toggle')) {
                document.querySelectorAll('.hours-dropdown').forEach(el => {
                    if (el.style.display === 'block') {
                        el.style.transition = 'all 0.2s ease';
                        el.style.opacity = '0';
                        el.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            el.style.display = 'none';
                        }, 200);
                    }
                });
            }
        });

        // Ajouter un écouteur d'événement pour le bouton de réinitialisation
        document.getElementById('resetButton').addEventListener('click', function() {
            resetFilters();
        });
    </script>

    <!-- Le reste du JavaScript reste identique -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDLcjtNpP0apwxa7aQp1oW01YXQrtE2cgE&callback=initMap" async defer></script>
    
    <!-- Votre JavaScript existant pour la recherche et la carte reste identique -->
   
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('storeSearch');
            const serviceSearchInput = document.getElementById('serviceSearch');
            const searchButton = document.getElementById('searchButton');
            const locationButton = document.querySelector('.location-search');
            const storesContainer = document.getElementById('stores-container');
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
            
            if (serviceSearchInput) {
                serviceSearchInput.addEventListener('input', debounce(performSearch, 500));
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
            
            // Ajouter des écouteurs pour les filtres d'horaire
            horaireRadios.forEach(radio => {
                radio.addEventListener('change', performSearch);
            });
            
            // Fonction principale de recherche
            function performSearch() {
                // 1. Récupérer les valeurs des filtres
                const serviceSearchTerm = document.getElementById('serviceSearch')?.value.trim() || '';
                const selectedHoraire = document.querySelector('input[name="horaire"]:checked')?.value;
                const searchTerm = document.getElementById('storeSearch')?.value.trim() || '';
                const aroundMeChecked = document.querySelector('.location-search').classList.contains('active');
    
                // 2. Préparer les paramètres pour l'URL
                const params = new URLSearchParams();
    
                // Ajouter le terme de recherche de service
                if (serviceSearchTerm) params.append('service_search', serviceSearchTerm);
    
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
                // Réinitialiser les radios
                document.querySelectorAll('input[name="horaire"]:checked').forEach(radio => {
                    radio.checked = false;
                });
                
                // Réinitialiser les champs de recherche
                document.getElementById('storeSearch').value = '';
                document.getElementById('serviceSearch').value = '';
                
                // Désactiver le bouton "Autour de moi"
                document.querySelector('.location-search').classList.remove('active');
                
                // Utiliser les données initiales des magasins au lieu de faire une nouvelle requête
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
    
                // Envoyer la requête au backend
                fetch(`filter?${params.toString()}`, {
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

                // Déterminer le type de fermeture et l'icône
                let closureHtml = '';
                if (store.is_closed && store.closed_reason) {
                    let closureType = 'regular-closed';
                    let icon = 'x-circle';
                    
                    if (store.closed_reason.toLowerCase().includes('exception')) {
                        closureType = 'exception';
                        icon = 'alert-triangle';
                    } else if (store.closed_reason.toLowerCase().includes('férié') || 
                               store.closed_reason.toLowerCase().includes('holiday')) {
                        closureType = 'holiday';
                        icon = 'calendar-x';
                    }
                    
                    closureHtml = `
                        <div class="closure-reason ${closureType}">
                            <i data-lucide="${icon}"></i>
                            <span>${escapeHtml(store.closed_reason)}</span>
                        </div>
                    `;
                }
                
                card.innerHTML = `
                    <div class="store-header">
                        <span class="store-badge">
                            ${escapeHtml(services || 'Service non défini')}
                        </span>
                        <span class="store-status ${statusClass}">
                            ${escapeHtml(store.today_status || (isOpen ? 'Ouvert' : 'Fermé'))}
                        </span>
                    </div>
                    
                    ${store.ouvert_jusqua && !store.is_closed ? `
                    <div class="store-hours">
                        <i data-lucide="clock" class="hours-icon"></i>
                        <span>Ouvert jusqu'à ${formatTime(store.ouvert_jusqua)}</span>
                    </div>
                    ` : ''}
                    
                    ${closureHtml}
                    
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
            
            // Fonction de debounce pour limiter les appels lors de la frappe
            function debounce(func, wait) {
                let timeout;
                return function() {
                    const context = this, args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        func.apply(context, args);
                    }, wait);
                };
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
        const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary').trim();

        // Stocker les données initiales pour la réinitialisation
        window.stores = stores;
        
     function initMap() {
        // Coordonnées par défaut (centre du Maroc)
        const defaultLocation = { lat: 31.7917, lng: -7.0926 };
        
        // Initialiser la carte avec les styles pour masquer les frontières du Sahara Occidental
        map = new google.maps.Map(document.getElementById('map'), {
            center: defaultLocation,
            zoom: 4,
            mapTypeControl: true,
            streetViewControl: false,
            fullscreenControl: true,
            styles: [
                {
                    // Masquer toutes les frontières des pays
                    featureType: "administrative.country",
                    elementType: "geometry.stroke",
                    stylers: [
                        { visibility: "off" }
                    ]
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
                    stylers: [{ color: primaryColor }, { visibility: "on" }],
                },
            ]
        });
        
        // Charger les frontières des pays (sauf Sahara Occidental)
        loadCountryBorders();
        
        // Créer une fenêtre d'info
        infoWindow = new google.maps.InfoWindow();
        
        // Ajouter les marqueurs pour chaque boutique
        addMarkersToMap(stores);
        
        // Ajuster la vue pour inclure tous les marqueurs
        if (markers.length > 0) {
            bounds = new google.maps.LatLngBounds();
            markers.forEach(marker => bounds.extend(marker.getPosition()));
            map.fitBounds(bounds);
        }
    }
    
    // Fonction pour charger les frontières des pays
    function loadCountryBorders() {
        // Charger le GeoJSON des frontières mondiales
        map.data.loadGeoJson(
            "https://raw.githubusercontent.com/johan/world.geo.json/master/countries.geo.json"
        );
        
        // Styler chaque pays: masquer le Sahara Occidental, dessiner les autres
        map.data.setStyle(function(feature) {
            const name = feature.getProperty("name");
            return {
                // pas de remplissage
                fillOpacity: 0,
                // style de bordure pour les "autres" pays
                strokeColor: "#444",
                strokeWeight: 1,
                strokeOpacity: name === "Sahara Occidental" ? 0 : 1
            };
        });
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