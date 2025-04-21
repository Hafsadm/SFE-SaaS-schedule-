@extends('layouts.app')

@section('header')
<div class="header">
    <div class="header-content">
        <div class="header-title">
            <h1>Ajouter un point de vente</h1>
            <a href="{{ route('admin.stores.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Retour
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="form-container">
    <form action="{{ route('admin.stores.store') }}" method="POST" class="store-form">
        @csrf
        
        <div class="form-section">
            <h2>Informations de base</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom du point de vente</label>
                    <input type="text" id="nom" name="nom" required>
                </div>

                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <input type="text" id="adresse" name="adresse" required>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>Localisation</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="ville">Ville</label>
                    <input type="text" id="ville" name="ville" required>
                </div>

                <div class="form-group">
                    <label for="pays">Pays</label>
                    <input type="text" id="pays" name="pays" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="code_postal">Code postal</label>
                    <input type="text" id="code_postal" name="code_postal">
                </div>
                
                <div class="form-group">
                    <label for="region">Région</label>
                    <input type="text" id="region" name="region">
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
                    <input type="number" step="any" id="latitude" name="latitude" required>
                </div>

                <div class="form-group">
                    <label for="longitude">Longitude</label>
                    <input type="number" step="any" id="longitude" name="longitude" required>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>Contact et horaires</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone">
                </div>

                <div class="form-group">
                    <label for="ouvert_jusqua">Ouvert jusqu'à</label>
                    <input type="time" id="ouvert_jusqua" name="ouvert_jusqua" required>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>Services et rendez-vous</h2>
            <div class="form-group">
                <label for="services">Services</label>
                <select id="services" name="services[]" multiple>
                    <option value="opticien">Opticien</option>
                    <option value="audition">Audition</option>
                    <option value="dentiste">Dentiste</option>
                </select>
                <small>Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs services</small>
            </div>

            <div class="form-group">
                <label for="lien_rdv">Lien de rendez-vous</label>
                <input type="url" id="lien_rdv" name="lien_rdv">
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
    .form-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .store-form {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 30px;
    }

    .form-section {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .form-section h2 {
        color: #333;
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
        color: #444;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }

    .form-group select[multiple] {
        height: 120px;
    }

    .form-group small {
        display: block;
        margin-top: 5px;
        color: #666;
        font-size: 0.85rem;
    }

    .form-actions {
        margin-top: 30px;
        text-align: right;
    }

    .btn-submit {
        background: linear-gradient(135deg, #b1bbc2 0%, #77838b 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 4px;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #a3b7c5 0%, #3498db 100%);
    }

    .header-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-back {
        background: #6c757d;
        color: white;
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
        transition: background 0.3s ease;
    }

    .btn-back:hover {
        background: #5a6268;
    }

    /* Styles pour la carte */
    .map-container {
        position: relative;
        height: 400px;
        margin-bottom: 20px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
        border: 1px solid #ddd;
        border-right: none;
        border-radius: 4px 0 0 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    #search-button {
        padding: 0 15px;
        background: white;
        border: 1px solid #ddd;
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
    }
</style>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_xQsTc41ShFh3sMnafHjUEht-8ZrDoM8&libraries=places&callback=initMap" async defer></script>
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
</script>
@endsection
