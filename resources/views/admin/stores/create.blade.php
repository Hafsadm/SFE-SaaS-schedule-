@extends('layouts.app')

@section('header')
<div class="header" style="background-color: var(--primary); color: var(--text-light);">
    <div class="header-content">
        <div class="header-title">
            <h1>Ajouter un point de vente</h1>
            <a href="{{ route('admin.stores.index') }}" class="btn-back" style="background-color: var(--secondary); color: var(--text-light);">
                <i class="fas fa-arrow-left"></i>
                Retour
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="form-container">
    <form action="{{ route('admin.stores.store') }}" method="POST" class="store-form" enctype="multipart/form-data">
        @csrf
        
        <div class="form-section">
            <h2>Informations de base</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom du point de vente</label>
                    <input type="text" id="nom" name="nom" required value="{{ old('nom') }}">
                    @error('nom')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <input type="text" id="adresse" name="adresse" required value="{{ old('adresse') }}">
                    @error('adresse')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>Localisation</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="ville">Ville</label>
                    <input type="text" id="ville" name="ville" required value="{{ old('ville') }}">
                    @error('ville')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="pays">Pays</label>
                    <input type="text" id="pays" name="pays" required value="{{ old('pays') }}">
                    @error('pays')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="code_postal">Code postal</label>
                    <input type="text" id="code_postal" name="code_postal" value="{{ old('code_postal') }}">
                    @error('code_postal')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="region">Région</label>
                    <input type="text" id="region" name="region" value="{{ old('region') }}">
                    @error('region')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>Carte de localisation</h2>
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
            <h2>Position géographique</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="latitude">Latitude</label>
                    <input type="number" step="any" id="latitude" name="latitude" required value="{{ old('latitude') }}">
                    @error('latitude')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="longitude">Longitude</label>
                    <input type="number" step="any" id="longitude" name="longitude" required value="{{ old('longitude') }}">
                    @error('longitude')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>Contact et horaires</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}">
                    @error('phone')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}">
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="ouvert_jusqua">Ouvert jusqu'à</label>
                    <input type="time" id="ouvert_jusqua" name="ouvert_jusqua" required value="{{ old('ouvert_jusqua') }}">
                    @error('ouvert_jusqua')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="annee_ouverture">Année d'ouverture</label>
                    <input type="number" id="annee_ouverture" name="annee_ouverture" min="1900" max="{{ date('Y') }}" value="{{ old('annee_ouverture') }}">
                    @error('annee_ouverture')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>Services et rendez-vous</h2>
            <div class="form-group">
                <label for="services_text">Services</label>
                <textarea id="services_text" name="services_text" rows="4" class="services-textarea" placeholder="Entrez les services séparés par des virgules (ex: café, boulangerie, pâtisserie)">{{ old('services_text') }}</textarea>
                <small>Entrez plusieurs services séparés par des virgules</small>
                @error('services_text')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="lien_rdv">Lien de rendez-vous</label>
                    <input type="url" id="lien_rdv" name="lien_rdv" value="{{ old('lien_rdv') }}">
                    @error('lien_rdv')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="site_web">Site web</label>
                    <input type="url" id="site_web" name="site_web" value="{{ old('site_web') }}">
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
                                <img id="exteriorPreview" src="#" alt="Extérieur" style="display: none; max-width: 100%; height: 180px; margin-left: auto; margin-right: auto;">  
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
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i>
                Enregistrer
            </button>
        </div>
    </form>
</div>

<style>
     :root {
           --primary: #0A2E2E; 
        --secondary: #143333; 
        --primary-light: #5aad9e;
        --primary-dark: #337b8d;
        --secondary-light: #ffffff;
        --secondary-dark: #0a2e2e;
        --tertiary: #8E6E53;
        --light: #C69C72; 
        --text-dark: #000000; 
        --text-light: #FFFFFF; 
        --success: #5DBB63;
        --error: #dc3545;
        --border: #E6D8C3; 
        --card-shadow: 0 4px 12px rgba(10, 46, 46, 0.1);
    }


    .form-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .store-form {
        background: var(--light-beige);
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 30px;
        border: 1px solid var(--border);
    }

    .form-section {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border);
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .form-section h2 {
        color: var(--dark-beige);
        font-size: 1.5rem;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        flex: 1;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--text-dark);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid var(--border);
        border-radius: 4px;
        font-size: 1rem;
        background-color: var(--text-light);
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(66, 145, 130, 0.2);
    }

    .services-textarea {
        min-height: 100px;
        resize: vertical;
    }

    .form-group small {
        display: block;
        margin-top: 5px;
        color: #666;
        font-size: 0.85rem;
    }

    .error-message {
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 5px;
        display: block;
    }

    .form-actions {
        margin-top: 30px;
        text-align: right;
    }

    .btn-submit {
        background-color: var(--primary);
        color: var(--text-light);
        border: none;
        padding: 12px 24px;
        border-radius: 4px;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .btn-submit:hover {
        background-color: var(--dark-beige);
    }

    .header-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
    }

    .btn-back {
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
        transition: background 0.3s ease;
    }

    .btn-back:hover {
        opacity: 0.9;
    }

    /* Styles pour la carte */
    .map-container {
        position: relative;
        height: 400px;
        margin-bottom: 20px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--border);
    }

    #map {
        height: 100%;
        width: 100%;
    }

    .map-search-container {
        position: absolute;
        top: 10px;
        left: 10px;
        right: 10px;
        z-index: 10;
        display: flex;
        max-width: 400px;
    }

    #map-search {
        flex: 1;
        padding: 10px;
        border: 1px solid var(--border);
        border-right: none;
        border-radius: 4px 0 0 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    #search-button {
        padding: 0 15px;
        background: var(--text-light);
        border: 1px solid var(--border);
        border-radius: 0 4px 4px 0;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .map-instructions {
        position: absolute;
        bottom: 10px;
        left: 10px;
        right: 10px;
        background: rgba(255, 255, 255, 0.8);
        padding: 10px;
        border-radius: 4px;
        font-size: 0.9rem;
        text-align: center;
    }

    .image-upload-row {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .image-upload-column {
        flex: 1;
        min-width: 120px;
        height: 100%;
      
    }
    
    .image-upload-container {
        margin-bottom: 5px;
    }
    
    .image-upload-label {
        cursor: pointer;
        display: block;
        padding: 15px 10px;
        border: 2px dashed var(--border);
        text-align: center;
        height:100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        background-color: var(--light-beige);
    }
    
    .image-upload-label:hover {
        border-color: var(--primary);
        background-color: #e9f5f2;
    }
    
    .upload-text {
        display: block;
        font-size: 14px;
        color: var(--dark-beige);
    }

    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 15px;
        }

        .form-container {
            padding: 10px;
        }

        .store-form {
            padding: 20px;
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
    }
</style>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDVXn3v4gNvgDImCifWbY5iZJLCUaRdVFI&libraries=places&callback=initMap" async defer></script>
<script>
    let map;
    let marker;
    let geocoder;
    let autocomplete;
    
    function initMap() {
        // Coordonnées par défaut (Paris, France)
        const defaultLocation = { lat: 48.8566, lng: 2.3522 };
        
        // Initialiser la carte
        map = new google.maps.Map(document.getElementById('map'), {
            center: defaultLocation,
            zoom: 13,
            mapTypeControl: true,
            streetViewControl: false,
            fullscreenControl: true
        });
        
        // Initialiser le geocoder
        geocoder = new google.maps.Geocoder();
        
        // Créer un marqueur initial
        marker = new google.maps.Marker({
            position: defaultLocation,
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
                    preview.style.display = 'flex';
                    document.querySelector('#exteriorImage + label .upload-text').style.display = 'none';
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
                    document.querySelector('#interiorImage + label .upload-text').style.display = 'none';
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
                    document.querySelector('#equipmentImage + label .upload-text').style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection
