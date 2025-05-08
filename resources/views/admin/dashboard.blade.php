@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    /* Variables de couleur */
    :root {
        --primary: #429182; 
        --secondary: #337b8d; 
        --light-beige: #F9F5EF;
        --dark-beige: #1b5858; 
        --text-dark: #000000; 
        --text-light: #FFFFFF; 
        --success: #5DBB63;
        --border: #E6D8C3; 
        --danger: #dc3545;
        --shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
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
        background-color:#429182;
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

</style>

<style>

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
        padding: 0.25rem;
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
 

    
</style>

<style>

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

/* ================================
   ACTIONS - Boutons
================================== */
.store-actions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.appointment-button,
.details-button {
    display: block;
    text-align: center;
    padding: 0.75rem;
    font-size: 0.85rem;
    font-weight: 600;
    border-radius: 6px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
}

/* Prendre rendez-vous */
.appointment-button {
    background: #429182
    color: var(--primary);
    border: 1px solid var(--primary);
}

.appointment-button:hover {
    background-color: rgba(166, 124, 82, 0.1);
}

/* Détails boutique */
.details-button {
    background-color: var(--primary);
    color: #fff;
    border: none;
}

.details-button:hover {
    background-color: var(--secondary);
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

/* Bouton désactivé */
.appointment-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    border-color: #ccc;
    color: #ccc;
}

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
        right: 0;
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

    
</style>

<style>

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
            background-color: #2A2118;
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
            background-color: #8f7659;
            border-color: #5C4033;
        }
        
        .store-location, .store-address, .store-phone {
            color: var(--light-beige);
        }
        
        .store-hours-toggle, .locate-button {
            color: var(--secondary);
        }
        
        .appointment-button {
            background-color: #3E2D1F;
            color: var(--secondary);
            border-color: var(--secondary);
        }
        
        .hours-dropdown {
            background-color: #ffffff;
            border-color: #472617;
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
            color: #5C4033 ;
        }
    }
</style>



<style>
    /* Variables de couleur */
    :root {
            --color-primary: #298675;;
            --color-primary-light: #337b8d;
            --color-secondary: #D2B48C;
            --color-background: #F9F5EF;
            --color-card: #FFFFFF;
            --color-text: #1b5858;
            --color-text-light: #1c3131;
            --color-border: #D7CCC8;
            --color-danger: #dc3545;
            --shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
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

    
</style>

<style>

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
            background-color: #f7f7f7;
            color: var(--text-light);
        }
        
        .dashboard-header {
            background-color: #294943;
            border-bottom-color: #476b65;
        }
        
        .title {
            color: var(--text-light);
        }
        
        .filter-button {
            background-color: #3b6361;
            border-color: #335b5c;
            color: var(--text-light);
        }
        
        .search-input {
            background-color: #22463f;
            border-color: #429182;
            color: var(--text-light);
        }
        
        .store-card {
            background-color: #22463f;
            border-color: #429182;
        }
        
        .store-address, .store-hours-toggle {
            color: #ffffff;
        }
        
        .appointment-button {
            background-color: #17302b;
            border-color: var(--dark-beige);
            color: var(--dark-beige);
        }

        .dropdown-options {
        position: absolute;
        top: 100%;
        left: 0;
        background:#22463f;
        border: 1px solid #429182;
        border-radius: 8px;
        padding: 10px;
        margin-top: 5px;
        min-width: 200px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        z-index: 10;
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

@endpush

@section('content')
<div class="dashboard-header">
    <h1 class="title">NOS BOUTIQUES</h1>
</div>

<div class="container">
    <div class="filters-container">

        {{-- This one is for the search bar and filters --}}
        <div class="filter-group">
            <button class="filter-button" onclick="toggleDropdown(this)">
                Service
                <i data-lucide="chevron-down" class="dropdown-icon"></i>
            </button>
            <div class="dropdown-options" style="display: none;">
                <div class="dropdown-option">
                    <input type="checkbox" id="specialite1" name="specialite" value="1">
                    <label for="specialite1">Dentiste</label>
                </div>     
                <div class="dropdown-option">
                    <input type="checkbox" id="specialite2" name="specialite" value="2">
                    <label for="specialite2">Opticien</label>
                </div>  
                <div class="dropdown-option">
                    <input type="checkbox" id="specialite3" name="specialite" value="3">
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
            <input type="text" id="storeSearch" placeholder="Code postal ou ville ou pays" class="search-input">
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
            // lucide.createIcons();
        });
    </script>
    

    {{-- <div id="toggleContainer"></div> --}}

    <div class="content">
        <div class="map-container" id="map-container">
            <div class="map-controls">
                {{-- <div class="map-type-buttons">
                    <button class="map-type-button active" data-type="roadmap">Plan</button>
                    <button class="map-type-button" data-type="satellite">Satellite</button>
                </div> --}}
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
 {{--                     
                    <span class="store-status {{ $store->is_open ? 'open' : 'closed' }}">
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

                    <button class="locate-button" onclick="centerMapOnStore({{ $store->latitude }}, {{ $store->longitude }})">
                        <i data-lucide="map-pin"></i>
                        Localiser
                    </button>
                </div>

                <div class="store-contact">
                    @if($store->phone)
                    <div class="store-phone">{{ $store->phone }}</div>
                    @endif
                   
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
                    
                    <a href="{{ route('admin.stores.show', $store->id) }}" class="details-button">
                        VOIR LA FICHE DU POINT DE VENTE
                    </a>
                </div>
            </div>
        </div>
            @endforeach
        </div>
    </div>


    


</div>
@endsection



@push('scripts')
{{-- tres important 3tiha betissa3 --}}
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
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap" async defer></script>
<script>
    // Le script JavaScript reste identique à celui que vous avez fourni
    // Il est déjà bien optimisé et fonctionnel
    // const stores = @json($stores);
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
            stylers: [{ color: "#A67C52" }, { visibility: "on" }],
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
@endpush
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_xQsTc41ShFh3sMnafHjUEht-8ZrDoM8"></script>

<script>
    // Variables globales
let map;
let markers = [];
let infoWindow;
let userMarker = null;
let userCircle = null;
let debounceTimer;

// Déclaration des variables globales manquantes
let lucide;
let google;
let stores;

// Initialisation au chargement du document
document.addEventListener("DOMContentLoaded", () => {
  // Initialiser les icônes Lucide
  if (typeof lucide !== "undefined") {
    lucide.createIcons()
  }

  // Éléments DOM
  const searchInput = document.getElementById("storeSearch")
  const searchButton = document.getElementById("searchButton")
  const locationButton = document.querySelector(".location-search")
  const storesContainer = document.getElementById("stores-container")

  // Ajouter les écouteurs d'événements pour les filtres
  setupFilterListeners()

  // Écouteurs d'événements pour la recherche
  if (searchButton) {
    searchButton.addEventListener("click", performSearch)
  }

  if (searchInput) {
    searchInput.addEventListener("keypress", (e) => {
      if (e.key === "Enter") {
        e.preventDefault()
        performSearch()
      }
    })

    // Recherche en temps réel avec debounce
    searchInput.addEventListener("input", () => {
      clearTimeout(debounceTimer)
      debounceTimer = setTimeout(performSearch, 500)
    })
  }

  // Écouteur pour la géolocalisation
  if (locationButton) {
    locationButton.addEventListener("click", handleGeolocation)
  }
})

// Configuration des écouteurs pour les filtres
function setupFilterListeners() {
  // Écouteurs pour les checkboxes de services
  document.querySelectorAll('input[name="specialite"]').forEach((checkbox) => {
    checkbox.addEventListener("change", performSearch)
  })

  // Écouteurs pour les boutons radio d'horaires
  document.querySelectorAll('input[name="horaire"]').forEach((radio) => {
    radio.addEventListener("change", performSearch)
  })
}

// Fonction principale de recherche
function performSearch() {
  // Récupérer les valeurs des filtres
  const selectedServices = Array.from(document.querySelectorAll('input[name="specialite"]:checked')).map(
    (el) => el.value,
  )

  const selectedHoraire = document.querySelector('input[name="horaire"]:checked')?.value
  const searchInput = document.getElementById("storeSearch")
  const searchTerm = searchInput ? searchInput.value.trim() : ""

  // Préparer les données pour l'envoi
  const formData = {
    specialite: selectedServices,
    horaire: selectedHoraire,
    search: searchTerm,
  }

  // Afficher un indicateur de chargement
  showLoading(true)

  // Envoyer la requête AJAX
  fetch("/admin/stores/filter", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
    body: JSON.stringify(formData),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Erreur réseau: " + response.status)
      }
      return response.json()
    })
    .then((stores) => {
      // Traiter et afficher les résultats
      updateStoresList(stores)
      updateMap(stores)
    })
    .catch((error) => {
      console.error("Erreur lors de la recherche:", error)
      showError("Une erreur est survenue lors de la recherche. Veuillez réessayer.")
    })
    .finally(() => {
      showLoading(false)
    })
}

// Gérer la géolocalisation
function handleGeolocation() {
  if (!navigator.geolocation) {
    showError("La géolocalisation n'est pas prise en charge par votre navigateur.")
    return
  }

  showLoading(true)

  navigator.geolocation.getCurrentPosition(
    // Succès
    (position) => {
      const latitude = position.coords.latitude
      const longitude = position.coords.longitude

      console.log("Position obtenue:", latitude, longitude)

      // Envoyer les coordonnées au serveur
      fetch("/admin/stores/nearby", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        },
        body: JSON.stringify({
          latitude: latitude,
          longitude: longitude,
        }),
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Erreur réseau: " + response.status)
          }
          return response.json()
        })
        .then((stores) => {
          // Traiter et afficher les résultats
          updateStoresList(stores)
          updateMap(stores, { lat: latitude, lng: longitude })
        })
        .catch((error) => {
          console.error("Erreur lors de la recherche par géolocalisation:", error)
          showError("Une erreur est survenue lors de la recherche par géolocalisation.")
        })
        .finally(() => {
          showLoading(false)
        })
    },
    // Erreur
    (error) => {
      showLoading(false)

      switch (error.code) {
        case error.PERMISSION_DENIED:
          showError("Vous avez refusé la demande de géolocalisation.")
          break
        case error.POSITION_UNAVAILABLE:
          showError("Les informations de localisation ne sont pas disponibles.")
          break
        case error.TIMEOUT:
          showError("La demande de géolocalisation a expiré.")
          break
        default:
          showError("Une erreur inconnue s'est produite lors de la géolocalisation.")
          break
      }
    },
    // Options
    {
      enableHighAccuracy: true,
      timeout: 10000,
      maximumAge: 0,
    },
  )
}

// Mettre à jour la liste des magasins
function updateStoresList(stores) {
  const storesContainer = document.getElementById("stores-container")
  if (!storesContainer) return

  // Vider le conteneur
  storesContainer.innerHTML = ""

  if (!stores || stores.length === 0) {
    storesContainer.innerHTML =
      '<div class="no-results" style="padding: 2rem; text-align: center; color: var(--text-dark);">Aucun résultat trouvé</div>'
    return
  }

  // Ajouter chaque magasin
  stores.forEach((store) => {
    const storeCard = createStoreCard(store)
    storesContainer.appendChild(storeCard)
  })

  // Réinitialiser les icônes Lucide
  if (typeof lucide !== "undefined" && lucide.createIcons) {
    lucide.createIcons()
  }
}

// Créer une carte de magasin
function createStoreCard(store) {
  const card = document.createElement("div")
  card.className = "store-card"
  card.dataset.lat = store.latitude
  card.dataset.lng = store.longitude

  // Déterminer le statut d'ouverture
  const isOpen = store.is_open === true || (store.today_status && store.today_status.toLowerCase().includes("ouvert"))
  const statusClass = isOpen ? "open" : "closed"

  // Formater les services
  let services = ""
  if (store.services) {
    if (Array.isArray(store.services)) {
      services = store.services.join(", ")
    } else if (typeof store.services === "object") {
      services = Object.values(store.services).join(", ")
    } else {
      services = store.services
    }
  }

  card.innerHTML = `
        <div class="store-header">
            <span class="store-badge">
                ${escapeHtml(services || "Service non défini")}
            </span>
            <span class="store-status ${statusClass}">
                ${escapeHtml(store.today_status || (isOpen ? "Ouvert" : "Fermé"))}
            </span>
        </div>
        
        ${
          store.ouvert_jusqua && !store.is_closed
            ? `
        <div class="store-hours">
            <i data-lucide="clock" class="hours-icon"></i>
            <span>Ouvert jusqu'à ${formatTime(store.ouvert_jusqua)}</span>
        </div>
        `
            : ""
        }
        
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
            ${store.phone ? `<div class="store-phone">${escapeHtml(store.phone)}</div>` : ""}
            
            <div class="store-hours-toggle" onclick="toggleHours(this)">
                HORAIRES <i data-lucide="chevron-down"></i>
                <div class="hours-dropdown">
                    ${store.formatted_weekly_hours || "Horaires non disponibles"}
                </div>
            </div>
        </div>
        
        <div class="store-actions">
            ${
              store.lien_rdv
                ? `
                <a href="${escapeHtml(store.lien_rdv)}" target="_blank" class="appointment-button">
                    PRENDRE RENDEZ-VOUS
                </a>
            `
                : `
                <button class="appointment-button" disabled>
                    PRENDRE RENDEZ-VOUS
                </button>
            `
            }
            
            <a href="/admin/stores/${store.id}" class="details-button">
                VOIR LA FICHE DU POINT DE VENTE
            </a>
        </div>
    `

  return card
}

// Initialiser la carte
function initMap() {
  // Coordonnées par défaut (Paris, France)
  const defaultLocation = { lat: 48.8566, lng: 2.3522 }

  // Initialiser la carte
  map = new google.maps.Map(document.getElementById("map"), {
    center: defaultLocation,
    zoom: 12,
    mapTypeControl: true,
    streetViewControl: false,
    fullscreenControl: true,
    styles: [
      {
        featureType: "poi",
        elementType: "labels",
        stylers: [{ visibility: "off" }],
      },
    ],
  })

  // Créer une fenêtre d'info
  infoWindow = new google.maps.InfoWindow();

  // Ajouter les marqueurs pour chaque boutique
  if (typeof stores !== "undefined") {
    addMarkersToMap(stores)
  }

  // Rendre la carte disponible globalement
  window.map = map
  window.markers = markers
  window.infoWindow = infoWindow
}

// Ajouter des marqueurs à la carte
function addMarkersToMap(stores) {
  // Effacer les marqueurs existants
  if (markers.length > 0) {
    markers.forEach((marker) => marker.setMap(null))
    markers = []
  }

  // Ajouter les nouveaux marqueurs
  const bounds = new google.maps.LatLngBounds()

  stores.forEach((store) => {
    if (store.latitude && store.longitude) {
      const position = {
        lat: Number.parseFloat(store.latitude),
        lng: Number.parseFloat(store.longitude),
      }

      // Déterminer le statut d'ouverture
      const isOpen =
        store.is_open === true || (store.today_status && store.today_status.toLowerCase().includes("ouvert"))

      // Icône personnalisée pour le marqueur
      const markerIcon = {
        path: google.maps.SymbolPath.CIRCLE,
        fillColor: isOpen ? "#82B183" : "#C17C74",
        fillOpacity: 0.9,
        strokeWeight: 2,
        strokeColor: "#FFFFFF",
        scale: 10,
      }

      const marker = new google.maps.Marker({
        position: position,
        map: map,
        title: store.nom,
        animation: google.maps.Animation.DROP,
        icon: markerIcon,
      })

      markers.push(marker)
      bounds.extend(position)

      // Ajouter un événement de clic sur le marqueur
      marker.addListener("click", () => {
        const content = `
                    <div style="padding: 15px; max-width: 250px; font-family: 'Inter', sans-serif;">
                        <h3 style="margin-bottom: 8px; color: #A67C52; font-weight: 600;">${escapeHtml(store.nom)}</h3>
                        <p style="margin-bottom: 10px; color: #333; font-size: 14px;">${escapeHtml(store.adresse)}</p>
                        <div style="display: flex; align-items: center; margin-bottom: 10px; font-size: 14px; color: ${isOpen ? "#82B183" : "#C17C74"};">
                            <span style="font-weight: 500;">${isOpen ? "Ouvert" : "Fermé"}</span>
                        </div>
                        <a href="/admin/stores/${store.id}" style="display: inline-block; background-color: #A67C52; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500;">
                            Voir détails
                        </a>
                    </div>
                `

        infoWindow.setContent(content)
        infoWindow.open(map, marker)

        // Animation du marqueur
        marker.setAnimation(google.maps.Animation.BOUNCE)
        setTimeout(() => {
          marker.setAnimation(null)
        }, 1500)

        // Faire défiler jusqu'à la carte de boutique correspondante
        const storeCard = document.querySelector(`[data-lat="${store.latitude}"][data-lng="${store.longitude}"]`)
        if (storeCard) {
          storeCard.scrollIntoView({ behavior: "smooth", block: "center" })
          storeCard.style.borderColor = "#A67C52"
          setTimeout(() => {
            storeCard.style.borderColor = ""
          }, 2000)
        }
      })
    }
  })

  // Ajuster la vue pour inclure tous les marqueurs
  if (markers.length > 0) {
    map.fitBounds(bounds)
  }
}

// Mettre à jour la carte
function updateMap(stores, userLocation = null) {
  if (!map) return

  // Ajouter les marqueurs
  addMarkersToMap(stores)

  // Supprimer l'ancien marqueur utilisateur et cercle
  if (userMarker) {
    userMarker.setMap(null)
    userMarker = null
  }

  if (userCircle) {
    userCircle.setMap(null)
    userCircle = null
  }

  // Ajouter le marqueur de l'utilisateur si disponible
  if (userLocation) {
    userMarker = new google.maps.Marker({
      position: userLocation,
      map: map,
      icon: {
        path: google.maps.SymbolPath.CIRCLE,
        scale: 12,
        fillColor: "#A67C52",
        fillOpacity: 1,
        strokeColor: "#FFFFFF",
        strokeWeight: 3,
      },
      title: "Votre position",
      zIndex: 999,
    })

    // Ajouter un cercle pour montrer le rayon de recherche
    userCircle = new google.maps.Circle({
      map: map,
      center: userLocation,
      radius: 10000, // 10km en mètres
      fillColor: "#A67C52",
      fillOpacity: 0.1,
      strokeColor: "#A67C52",
      strokeOpacity: 0.5,
      strokeWeight: 1,
    })

    // Ajuster la vue pour inclure le cercle
    const bounds = new google.maps.LatLngBounds()
    markers.forEach((marker) => bounds.extend(marker.getPosition()))
    bounds.extend(userLocation)
    map.fitBounds(bounds)
  }
}

// Centrer la carte sur un magasin
function centerMapOnStore(lat, lng) {
  if (!map) return

  const position = new google.maps.LatLng(lat, lng)
  map.setCenter(position)
  map.setZoom(16)

  // Trouver et ouvrir l'infoWindow du marqueur correspondant
  for (let i = 0; i < markers.length; i++) {
    if (markers[i].getPosition().equals(position)) {
      google.maps.event.trigger(markers[i], "click")
      break
    }
  }
}

// Fonction pour afficher/masquer les dropdowns
function toggleDropdown(button) {
  // Trouver le conteneur d'options correspondant
  const optionsContainer = button.nextElementSibling
  const icon = button.querySelector(".dropdown-icon")

  // Basculer l'affichage
  if (optionsContainer.style.display === "none") {
    optionsContainer.style.display = "block"
    icon.classList.add("rotated")
  } else {
    optionsContainer.style.display = "none"
    icon.classList.remove("rotated")
  }

  // Fermer les autres dropdowns ouverts
  document.querySelectorAll(".dropdown-options").forEach((dropdown) => {
    if (dropdown !== optionsContainer && dropdown.style.display === "block") {
      dropdown.style.display = "none"
      const otherIcon = dropdown.previousElementSibling.querySelector(".dropdown-icon")
      otherIcon.classList.remove("rotated")
    }
  })
}

// Fonction pour afficher/masquer les horaires
function toggleHours(element) {
  const dropdown = element.querySelector(".hours-dropdown")
  dropdown.style.display = dropdown.style.display === "block" ? "none" : "block"

  // Fermer les autres dropdowns
  document.querySelectorAll(".hours-dropdown").forEach((el) => {
    if (el !== dropdown) {
      el.style.display = "none"
    }
  })

  // Empêcher la propagation du clic
  event.stopPropagation()
}

// Fonctions utilitaires
function showLoading(show) {
  // Vous pouvez implémenter un indicateur de chargement ici
  console.log(show ? "Chargement en cours..." : "Chargement terminé")

  // Si vous avez un élément de chargement dans votre HTML, vous pouvez l'afficher/masquer ici
  const loadingElement = document.getElementById("loading-indicator")
  if (loadingElement) {
    loadingElement.style.display = show ? "block" : "none"
  }
}

function showError(message) {
  console.error(message)
  alert(message)

  // Si vous avez un élément d'erreur dans votre HTML, vous pouvez l'afficher ici
  const errorElement = document.getElementById("error-message")
  if (errorElement) {
    errorElement.textContent = message
    errorElement.style.display = "block"

    // Masquer après 5 secondes
    setTimeout(() => {
      errorElement.style.display = "none"
    }, 5000)
  }
}

function formatTime(timeString) {
  try {
    const date = new Date(`2000-01-01T${timeString}`)
    return date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })
  } catch (e) {
    return timeString
  }
}

function escapeHtml(unsafe) {
  if (!unsafe) return ""
  return String(unsafe)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;")
}

// Fermer les dropdowns quand on clique ailleurs
document.addEventListener("click", (event) => {
  // Fermer les dropdowns de filtres
  if (!event.target.closest(".filter-group")) {
    document.querySelectorAll(".dropdown-options").forEach((dropdown) => {
      dropdown.style.display = "none"
      const icon = dropdown.previousElementSibling.querySelector(".dropdown-icon")
      icon.classList.remove("rotated")
    })
  }

  // Fermer les dropdowns d'horaires
  if (!event.target.closest(".store-hours-toggle")) {
    document.querySelectorAll(".hours-dropdown").forEach((el) => {
      el.style.display = "none"
    })
  }
})

// Exposer les fonctions globalement
window.performSearch = performSearch
window.handleGeolocation = handleGeolocation
window.toggleDropdown = toggleDropdown
window.toggleHours = toggleHours
window.centerMapOnStore = centerMapOnStore
window.initMap = initMap

    document.addEventListener('DOMContentLoaded', function() {
    // Éléments DOM
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
        locationButton.addEventListener('click', handleGeolocation);
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
        // Récupérer les valeurs des filtres
        const selectedServices = Array.from(document.querySelectorAll('input[name="specialite"]:checked'))
            .map(el => el.value);
        
        const selectedHoraire = document.querySelector('input[name="horaire"]:checked')?.value;
        const searchTerm = searchInput ? searchInput.value.trim() : '';
        
        // Préparer les données pour l'envoi
        const formData = {
            specialite: selectedServices,
            horaire: selectedHoraire,
            search: searchTerm
        };
        
        // Afficher un indicateur de chargement
        showLoading(true);
        
        // Envoyer la requête AJAX
        fetch('/admin/stores/filter', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(stores => {
            // Traiter et afficher les résultats
            updateStoresList(stores);
            updateMap(stores);
        })
        .catch(error => {
            console.error('Erreur lors de la recherche:', error);
            showError("Une erreur est survenue lors de la recherche");
        })
        .finally(() => {
            showLoading(false);
        });
    }
    
    // Gérer la géolocalisation
    function handleGeolocation() {
        if (!navigator.geolocation) {
            showError("La géolocalisation n'est pas prise en charge par votre navigateur.");
            return;
        }
        
        showLoading(true);
        
        navigator.geolocation.getCurrentPosition(
            // Succès
            function(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                
                // Envoyer les coordonnées au serveur
                fetch('/admin/stores/nearby', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        latitude: latitude,
                        longitude: longitude
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur réseau');
                    }
                    return response.json();
                })
                .then(stores => {
                    // Traiter et afficher les résultats
                    updateStoresList(stores);
                    updateMap(stores, { lat: latitude, lng: longitude });
                })
                .catch(error => {
                    console.error('Erreur lors de la recherche par géolocalisation:', error);
                    showError("Une erreur est survenue lors de la recherche par géolocalisation");
                })
                .finally(() => {
                    showLoading(false);
                });
            },
            // Erreur
            function(error) {
                showLoading(false);
                
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        showError("Vous avez refusé la demande de géolocalisation.");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        showError("Les informations de localisation ne sont pas disponibles.");
                        break;
                    case error.TIMEOUT:
                        showError("La demande de géolocalisation a expiré.");
                        break;
                    default:
                        showError("Une erreur inconnue s'est produite lors de la géolocalisation.");
                        break;
                }
            },
            // Options
            {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            }
        );
    }
    
    // Mettre à jour la liste des magasins
    function updateStoresList(stores) {
        if (!storesContainer) return;
        
        // Vider le conteneur
        storesContainer.innerHTML = '';
        
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
            
            <a href="/admin/stores/${store.id}" class="details-button">
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
            map.fitBounds(bounds);
            
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

// Fonction globale pour centrer la carte sur un magasin
function centerMapOnStore(lat, lng) {
    if (typeof google === 'undefined' || !google.maps || !window.map) {
        console.warn('Google Maps n\'est pas disponible');
        return;
    }
    
    const position = new google.maps.LatLng(lat, lng);
    window.map.setCenter(position);
    window.map.setZoom(16);
    
    // Trouver et ouvrir l'infoWindow du marqueur correspondant
    if (window.markers && window.infoWindow) {
        for (let i = 0; i < window.markers.length; i++) {
            if (window.markers[i].getPosition().equals(position)) {
                google.maps.event.trigger(window.markers[i], 'click');
                break;
            }
        }
    }
}

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

