@extends('layouts.app')

@section('header')
    <div class="header-content">
      
        <div class="header-title">
            <h1>Modifier un point de vente</h1>
            <a href="{{ route('admin.stores.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Retour
            </a>
    
            <div class="action-buttons">
                <form action="{{ route('admin.stores.destroy', $store) }}" method="POST" class="delete-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce point de vente ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete">
                        <i class="fas fa-trash"></i>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="form-container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif


    <form action="{{ route('admin.stores.update', $store) }}" method="POST" class="store-form" enctype="multipart/form-data" onsubmit="return confirm('Êtes-vous sûr de vouloir modifier ce point de vente ?');">
        @csrf
        @method('PUT')
        
        <div class="form-section">
            <div class="section-header">
                <h2>Informations de base</h2>
                <div class="section-icon"><i class="fas fa-info-circle"></i></div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom du point de vente</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom', $store->nom) }}" required>
                    @error('nom')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <input type="text" id="adresse" name="adresse" value="{{ old('adresse', $store->adresse) }}" required>
                    @error('adresse')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="section-header">
                <h2>Localisation</h2>
                <div class="section-icon"><i class="fas fa-map-marker-alt"></i></div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="ville">Ville</label>
                    <input type="text" id="ville" name="ville" value="{{ old('ville', $store->ville) }}" required>
                    @error('ville')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="pays">Pays</label>
                    <input type="text" id="pays" name="pays" value="{{ old('pays', $store->pays) }}" required>
                    @error('pays')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="code_postal">Code postal</label>
                    <input type="text" id="code_postal" name="code_postal" value="{{ old('code_postal', $store->code_postal) }}">
                    @error('code_postal')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="region">Région</label>
                    <input type="text" id="region" name="region" value="{{ old('region', $store->region) }}">
                    @error('region')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="section-header">
                <h2>Carte de localisation</h2>
                <div class="section-icon"><i class="fas fa-map"></i></div>
            </div>
            <div class="map-container">
                <div id="map"></div>
                <div class="map-search-container">
                    <input type="text" id="map-search" placeholder="Rechercher une adresse...">
                    <button type="button" id="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="map-instructions">
                    <p><i class="fas fa-info-circle"></i> Cliquez sur la carte pour définir la position ou recherchez une adresse</p>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="section-header">
                <h2>Position géographique</h2>
                <div class="section-icon"><i class="fas fa-globe-americas"></i></div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="latitude">Latitude</label>
                    <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude', $store->latitude) }}" required>
                    @error('latitude')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="longitude">Longitude</label>
                    <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude', $store->longitude) }}" required>
                    @error('longitude')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="section-header">
                <h2>Contact et horaires</h2>
                <div class="section-icon"><i class="fas fa-phone-alt"></i></div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $store->phone) }}">
                    @error('phone')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $store->email) }}">
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="ouvert_jusqua">Ouvert jusqu'à</label>
                    <input type="time" id="ouvert_jusqua" name="ouvert_jusqua" value="{{ old('ouvert_jusqua', $store->ouvert_jusqua) }}" required>
                    @error('ouvert_jusqua')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="annee_ouverture">Année d'ouverture</label>
                    <input type="number" id="annee_ouverture" name="annee_ouverture" min="1900" max="{{ date('Y') }}" value="{{ old('annee_ouverture', $store->annee_ouverture) }}">
                    @error('annee_ouverture')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="section-header">
                <h2>Services et rendez-vous</h2>
                <div class="section-icon"><i class="fas fa-concierge-bell"></i></div>
            </div>
            <div class="form-group">
                <label for="services_text">Services</label>
                <textarea id="services_text" name="services_text" rows="4" class="services-textarea" placeholder="Entrez les services séparés par des virgules (ex: café, boulangerie, pâtisserie)">{{ old('services_text', is_array($store->services) ? implode(', ', $store->services) : $store->services) }}</textarea>
                <small>Entrez plusieurs services séparés par des virgules</small>
                @error('services_text')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="lien_rdv">Lien de rendez-vous</label>
                    <input type="url" id="lien_rdv" name="lien_rdv" value="{{ old('lien_rdv', $store->lien_rdv) }}">
                    @error('lien_rdv')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="site_web">Site web</label>
                    <input type="url" id="site_web" name="site_web" value="{{ old('site_web', $store->site_web) }}">
                    @error('site_web')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h2>Images de la boutique</h2>
            <div class="image-upload-row">
                <!-- Extérieur -->
                <div class="image-upload-column">
                    <div class="form-group">
                        <label>Extérieur</label>
                        <div class="image-upload-container">
                            <label for="exteriorImage" class="image-upload-label">
                                <span class="upload-text">+<br>Extérieur</span>
                                <img id="exteriorPreview" src="#" alt="Extérieur" style="display: none; width: 100%; height: 100%; margin-left: auto; margin-right: auto;">  
                            </label>
                            <input type="file" id="exteriorImage" name="exterior_image" accept="image/*" style="display: none; ">
                            @error('exterior_image')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Intérieur -->
                <div class="image-upload-column">
                    <div class="form-group">
                        <label>Intérieur</label>
                        <div class="image-upload-container">
                            <label for="interiorImage" class="image-upload-label">
                                <span class="upload-text">+<br>Intérieur</span>
                                <img id="interiorPreview" src="#" alt="Intérieur" style="display: none; max-width: 100%; height: 180px; margin-left: auto; margin-right: auto;">
                            </label>
                            <input type="file" id="interiorImage" name="interior_image" accept="image/*" style="display: none;">
                            @error('interior_image')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Équipement -->
                <div class="image-upload-column">
                    <div class="form-group">
                        <label>Équipement</label>
                        <div class="image-upload-container">
                            <label for="equipmentImage" class="image-upload-label">
                                <span class="upload-text">+<br>Équipement</span>
                                <img id="equipmentPreview" src="#" alt="Équipement" style="display: none; max-width: 100%; height: 180px; margin-left: auto; margin-right: auto; ">
                            </label>
                            <input type="file" id="equipmentImage" name="equipment_image" accept="image/*" style="display: none;">
                            @error('equipment_image')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.stores.schedules.index', $store) }}" class="btn-schedules">
                <i class="fas fa-calendar"></i>
                Gérer les horaires
            </a>
            
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i>
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

<style>
    :root {
        --primary: #235c5cc9; 
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

    /* Styles généraux */
    body {
        background-color: #f5f5f5;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }


/* Header moderne et chic */
.header-content {
    max-width: 1140px;
    margin: 0 auto;
    color: var(--text-light);
    border-radius: 30px ;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 2.5rem;
}

.header-title {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.header-title h1 {
    font-size: 1.75rem;
    margin: 0;
    font-weight: 600;
    letter-spacing: -0.5px;
    flex-grow: 1;
    color: var(--text-light);
}

/* Bouton Retour */
.btn-back {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-light);
    padding: 0.6rem 1.5rem;
    border-radius: 60px 30px ;
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(5px);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-1px);
}

.btn-back i {
    font-size: 0.9rem;
}

/* Bouton Supprimer */
.action-buttons {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.delete-form {
    margin: 0;
}

.btn-delete {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-light);   
    height: 50px;
    padding: 0.6rem 1.5rem;
    border-radius:  30px 60px ;
    border: none;
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    transition: var(--transition);
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 2px 15px rgba(220, 53, 69, 0.2);
}

.btn-delete:hover {
    background: rgba(200, 35, 51, 0.9);
    transform: translateY(-1px);
    box-shadow: 0 4px 20px rgba(220, 53, 69, 0.3);
}

.btn-delete i {
    font-size: 0.9rem;
}

/* Effets glassmorphism */
@media (prefers-color-scheme: dark) {
    .header-content {
        background: linear-gradient(135deg, #0a2e2e 0%, #2a6363 100%);
    }
    .btn-back {
        background: rgba(255, 255, 255, 0.05);
    }
}

    /* Container principal */
    .form-container {
        max-width: 1200px;
        margin: 0 auto 50px;
        padding: 0 20px;
    }

    /* Alertes */
    .alert {
        padding: 15px 20px;
        margin-bottom: 25px;
        border-radius: 30px 0;
        font-weight: 500;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    .alert::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
    }

    .alert-success {
        background-color: rgba(93, 187, 99, 0.1);
        color: var(--success);
    }

    .alert-success::before {
        background-color: var(--success);
    }

    .alert-error {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--danger);
    }

    .alert-error::before {
        background-color: var(--danger);
    }

  
    /* Formulaire principal */
    .store-form {
        background: #ffffff;
        border-radius: 30px 0;
        box-shadow: var(--shadow);
        padding: 40px;
        border: 1px solid var(--border);
        position: relative;
        overflow: hidden;
    }

    /* Sections du formulaire */
    .form-section {
        margin-bottom: 40px;
        padding-bottom: 30px;
        border-bottom: 1px dashed var(--border);
        position: relative;
    }

    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 20px;
    }

    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        position: relative;
    }

    .section-header h2 {
        color: var(--dark-beige);
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        padding-left: 15px;
        position: relative;
    }

    .section-header h2::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 5px;
        height: 70%;
        background-color: var(--primary);
        border-radius: 5px;
    }

    .section-icon {
        margin-left: auto;
        width: 40px;
        height: 40px;
        background-color: var(--primary);
        color: var(--text-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    /* Lignes et groupes de formulaire */
    .form-row {
        display: flex;
        gap: 25px;
        margin-bottom: 25px;
    }

    .form-group {
        flex: 1;
        position: relative;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: 500;
        color: var(--dark-beige);
        font-size: 0.95rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 90%;
        padding: 12px 15px;
        border: 1px solid var(--border);
        border-radius: 30px 0;
        font-size: 1rem;
        margin-left: auto;
        margin-right: auto;
        background-color: var(--text-light);
        transition: var(--transition);
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(66, 145, 130, 0.2);
    }

    .services-textarea {
       height: 30px;
        resize: vertical;
    }

    .form-group small {
        display: block;
        margin-top: 8px;
        color: #666;
        font-size: 0.85rem;
        font-style: italic;
    }

    .error-message {
        color: var(--danger);
        font-size: 0.85rem;
        margin-top: 8px;
        display: block;
        font-weight: 500;
    }

    /* Carte */
    .map-container {
        position: relative;
        height: 400px;
        margin-bottom: 20px;
        border-radius: 30px 0;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--border);
    }

    #map {
        height: 100%;
        width: 100%;
    }

    .map-search-container {
        position: absolute;
        top: 15px;
        left: 15px;
        right: 15px;
        z-index: 10;
        display: flex;
        max-width: 400px;
    }

    #map-search {
        flex: 1;
        padding: 12px 15px;
        border: none;
        border-radius: 30px 0 0 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        font-size: 0.95rem;
    }

    #search-button {
        padding: 0 20px;
        background: var(--primary);
        color: var(--text-light);
        border: none;
        border-radius: 0 0 30px 0;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .map-instructions {
        position: absolute;
        bottom: 15px;
        left: 15px;
        right: 15px;
        background: rgba(255, 255, 255, 0.9);
        padding: 12px 15px;
        border-radius: 30px 0;
        font-size: 0.9rem;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    /* Images */
    .image-upload-row {
        display: flex;
        flex-wrap: wrap;
        gap: 25px;
        margin-bottom: 20px;
    }
    
    .image-upload-column {
        flex: 1;
        min-width: 200px;
    }
    
    .image-upload-container {
        margin-bottom: 10px;
    }
    
    .image-upload-label {
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 15px;
        border: 2px dashed var(--border);
        border-radius: 30px 0;
        height: 220px;
        transition: var(--transition);
        background-color: rgba(255, 255, 255, 0.5);
        position: relative;
        overflow: hidden;
    }
    
    .image-upload-label:hover {
        border-color: var(--primary);
        background-color: rgba(66, 145, 130, 0.05);
        transform: translateY(-3px);
    }
    
    .image-upload-label img {
        max-width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .upload-text {
        display: block;
        font-size: 16px;
        color: var(--dark-beige);
        text-align: center;
        font-weight: 500;
    }

    /* Boutons d'action du formulaire */
    .form-actions {
        margin-top: 40px;
        display: flex;
        justify-content: flex-end;
        gap: 20px;
    }

    .btn-submit, .btn-schedules {
        padding: 14px 28px;
        border-radius: 30px 0;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        border: none;
        transition: var(--transition);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-submit {
        background-color: var(--primary);
        color: var(--text-light);
    }

    .btn-submit:hover {
        background-color: var(--dark-beige);
        transform: translateY(-3px);
    }

    .btn-schedules {
        background-color: var(--secondary);
        color: var(--text-light);
        text-decoration: none;
    }

    .btn-schedules:hover {
        background-color: #2a6574;
        transform: translateY(-3px);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .store-form {
            padding: 30px;
        }
        
        .form-section {
            margin-bottom: 30px;
            padding-bottom: 25px;
        }
    }

    @media (max-width: 768px) {
        .header-title h1 {
            font-size: 1.5rem;
        }
        
        .form-row {
            flex-direction: column;
            gap: 20px;
        }

        .form-container {
            padding: 0 15px;
        }

        .store-form {
            padding: 25px 20px;
        }
        
        .map-container {
            height: 300px;
        }
        
        .map-search-container {
            max-width: none;
        }
        
        .image-upload-column {
            flex: 100%;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-submit, .btn-schedules, .btn-delete {
            width: 100%;
            justify-content: center;
        }
        
        .section-header h2 {
            font-size: 1.3rem;
        }
    }

    @media (max-width: 480px) {
        .header-title {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .btn-back {
            align-self: flex-start;
        }
        
        .store-form {
            padding: 20px 15px;
        }
        
        .section-icon {
            display: none;
        }
    }

    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-section {
        animation: fadeIn 0.5s ease forwards;
    }

    .form-section:nth-child(1) { animation-delay: 0.1s; }
    .form-section:nth-child(2) { animation-delay: 0.2s; }
    .form-section:nth-child(3) { animation-delay: 0.3s; }
    .form-section:nth-child(4) { animation-delay: 0.4s; }
    .form-section:nth-child(5) { animation-delay: 0.5s; }
    .form-section:nth-child(6) { animation-delay: 0.6s; }
    .form-section:nth-child(7) { animation-delay: 0.7s; }
</style>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_xQsTc41ShFh3sMnafHjUEht-8ZrDoM8&libraries=places&callback=initMap" async defer></script>
<script>
    let map;
    let marker;
    let geocoder;
    let autocomplete;
    
    function initMap() {
        // Récupérer les coordonnées du store
        const lat = parseFloat(document.getElementById('latitude').value) || 48.8566;
        const lng = parseFloat(document.getElementById('longitude').value) || 2.3522;
        
        // Initialiser la carte
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: lat, lng: lng },
            zoom: 13,
            mapTypeControl: true,
            streetViewControl: false,
            fullscreenControl: true
        });
        
        // Initialiser le geocoder
        geocoder = new google.maps.Geocoder();
        
        // Créer un marqueur initial
        marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP
        });
        
        // Mettre à jour les champs lors du déplacement du marqueur
        marker.addListener('dragend', function() {
            updateLocationFields(marker.getPosition());
        });
        
        // Ajouter un marqueur lorsqu'on clique sur la carte
        map.addListener('click', function(event) {
            marker.setPosition(event.latLng);
            updateLocationFields(event.latLng);
        });
        
        // Initialiser l'autocomplétion pour la recherche
        const searchInput = document.getElementById('map-search');
        autocomplete = new google.maps.places.Autocomplete(searchInput);
        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            if (!place.geometry) {
                alert("Aucun résultat trouvé pour cette recherche.");
                return;
            }
            
            // Centrer la carte sur le lieu trouvé
            map.setCenter(place.geometry.location);
            marker.setPosition(place.geometry.location);
            
            // Mettre à jour les champs avec les informations du lieu
            updateFieldsFromPlace(place);
        });
        
        // Ajouter un événement au bouton de recherche
        document.getElementById('search-button').addEventListener('click', function() {
            const address = document.getElementById('map-search').value;
            geocodeAddress(address);
        });
        
        // Ajouter des écouteurs d'événements pour les champs d'adresse
        document.getElementById('adresse').addEventListener('blur', updateMarkerFromFields);
        document.getElementById('ville').addEventListener('blur', updateMarkerFromFields);
        document.getElementById('code_postal').addEventListener('blur', updateMarkerFromFields);
        document.getElementById('pays').addEventListener('blur', updateMarkerFromFields);
    }
    
    // Mettre à jour les champs de localisation à partir d'une position
    function updateLocationFields(position) {
        document.getElementById('latitude').value = position.lat();
        document.getElementById('longitude').value = position.lng();
        
        // Faire une géocodage inverse pour obtenir l'adresse
        geocoder.geocode({ 'location': position }, function(results, status) {
            if (status === 'OK' && results[0]) {
                updateFieldsFromPlace(results[0]);
            }
        });
    }
    
    // Mettre à jour les champs à partir d'un objet Place
    function updateFieldsFromPlace(place) {
        // Réinitialiser les champs
        let street_number = '';
        let route = '';
        let locality = '';
        let administrative_area_level_1 = '';
        let country = '';
        let postal_code = '';
        
        // Extraire les composants de l'adresse
        if (place.address_components) {
            for (const component of place.address_components) {
                const types = component.types;
                
                if (types.includes('street_number')) {
                    street_number = component.long_name;
                } else if (types.includes('route')) {
                    route = component.long_name;
                } else if (types.includes('locality')) {
                    locality = component.long_name;
                } else if (types.includes('administrative_area_level_1')) {
                    administrative_area_level_1 = component.long_name;
                } else if (types.includes('country')) {
                    country = component.long_name;
                } else if (types.includes('postal_code')) {
                    postal_code = component.long_name;
                }
            }
        }
        
        // Mettre à jour les champs du formulaire
        document.getElementById('adresse').value = street_number ? (street_number + ' ' + route) : route;
        document.getElementById('ville').value = locality;
        document.getElementById('pays').value = country;
        document.getElementById('code_postal').value = postal_code;
        document.getElementById('region').value = administrative_area_level_1;
        
        // Mettre à jour les coordonnées
        if (place.geometry && place.geometry.location) {
            document.getElementById('latitude').value = place.geometry.location.lat();
            document.getElementById('longitude').value = place.geometry.location.lng();
        }
    }
    
    // Géocoder une adresse et mettre à jour la carte
    function geocodeAddress(address) {
        geocoder.geocode({ 'address': address }, function(results, status) {
            if (status === 'OK' && results[0]) {
                map.setCenter(results[0].geometry.location);
                marker.setPosition(results[0].geometry.location);
                updateFieldsFromPlace(results[0]);
            } else {
                alert('Géocodage non réussi pour la raison suivante: ' + status);
            }
        });
    }
    
    // Mettre à jour le marqueur à partir des champs du formulaire
    function updateMarkerFromFields() {
        const adresse = document.getElementById('adresse').value;
        const ville = document.getElementById('ville').value;
        const codePostal = document.getElementById('code_postal').value;
        const pays = document.getElementById('pays').value;
        
        // Construire l'adresse complète
        const fullAddress = [adresse, ville, codePostal, pays].filter(Boolean).join(', ');
        
        if (fullAddress) {
            geocodeAddress(fullAddress);
        }
    }
    
    // Prévisualisation des images
    document.addEventListener('DOMContentLoaded', function() {
        // Configuration pour l'image extérieure
        document.getElementById('exteriorImage').addEventListener('change', function(e) {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('exteriorPreview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    document.querySelector('label[for="exteriorImage"] .upload-text').style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        // Configuration pour l'image intérieure
        document.getElementById('interiorImage').addEventListener('change', function(e) {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('interiorPreview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    document.querySelector('label[for="interiorImage"] .upload-text').style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        // Configuration pour l'image d'équipement
        document.getElementById('equipmentImage').addEventListener('change', function(e) {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('equipmentPreview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    document.querySelector('label[for="equipmentImage"] .upload-text').style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection