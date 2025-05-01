{{-- @extends('layouts.app') --}}


<style>
    :root {
        --primary: #A67C52; /* Marron foncé */
        --secondary: #ccbbb3; /* Marron clair */
        --light-beige: #F5F5DC;
        --dark-beige: #D2B48C;
        --text-dark: #333333;
        --text-light: #F8F4E6;
        --success: #82b183;
        --border: #E0C9B4;
        --shadow: 0 4px 20px rgba(166, 124, 82, 0.15);
        --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    /* Base stylée */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        line-height: 1.6;
        color: var(--text-dark);
        background-color: var(--light-beige);
        padding: 2rem;
    }

    /* Container principal */
    .store-edit-container {
        max-width: 1200px;
        margin: 0 auto;
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow);
        overflow: hidden;
        transition: var(--transition);
    }

    /* Header */
    .store-edit-header {
        padding: 2rem;
        background: linear-gradient(135deg, var(--primary), var(--dark-beige));
        color: var(--text-light);
        position: relative;
    }

    .store-edit-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-light);
        text-decoration: none;
        font-weight: 500;
        opacity: 0.9;
        transition: var(--transition);
    }

    .back-button:hover {
        opacity: 1;
        transform: translateX(-3px);
    }

    /* Alertes */
    .alert {
        padding: 1rem 1.5rem;
        margin: 0 2rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
    }

    .alert-success {
        background-color: rgba(130, 177, 131, 0.2);
        color: var(--success);
        border-left: 4px solid var(--success);
    }

    .alert-error {
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border-left: 4px solid #ef4444;
    }

    /* Formulaire */
    .store-form {
        padding: 2rem;
        display: grid;
        gap: 2rem;
    }

    .form-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    }

    .form-section h2 {
        font-size: 1.25rem;
        margin-bottom: 1.5rem;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-section h2::before {
        content: '';
        display: block;
        width: 8px;
        height: 24px;
        background: var(--primary);
        border-radius: 4px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-group {
        position: relative;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--text-dark);
    }

    .form-group input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 1rem;
        background: white;
        transition: var(--transition);
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(166, 124, 82, 0.2);
    }

    .error-message {
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Carte interactive */
    .map-container {
        margin-top: 1rem;
    }

    #map {
        width: 100%;
        height: 400px;
        border-radius: 12px;
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .map-search-container {
        display: flex;
        margin-bottom: 1rem;
    }

    .map-search-container input {
        flex: 1;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border);
        border-radius: 8px 0 0 8px;
        font-size: 1rem;
    }

    .map-search-container button {
        padding: 0 1.25rem;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 0 8px 8px 0;
        cursor: pointer;
        transition: var(--transition);
    }

    .map-search-container button:hover {
        background: #8c6840;
    }

    .map-instructions {
        font-size: 0.9rem;
        color: var(--text-dark);
        opacity: 0.7;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Actions */
    .form-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1rem;
    }

    .submit-btn, .schedules-btn, .delete-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
        transition: var(--transition);
    }

    .submit-btn {
        background: var(--primary);
        color: white;
    }

    .submit-btn:hover {
        background: #8c6840;
        transform: translateY(-2px);
    }

    .schedules-btn {
        background: var(--secondary);
        color: var(--text-dark);
        text-decoration: none;
    }

    .schedules-btn:hover {
        background: #b8a79f;
        transform: translateY(-2px);
    }

    .delete-btn {
        background: #ef4444;
        color: white;
    }

    .delete-btn:hover {
        background: #dc2626;
        transform: translateY(-2px);
    }

    /* Dark mode */
    @media (prefers-color-scheme: dark) {
        body {
            background: #1a1a1a;
            color: var(--text-light);
        }

        .store-edit-container, .form-section {
            background: #2d2d2d;
        }

        .form-group input {
            background: #3d3d3d;
            color: var(--text-light);
            border-color: #555;
        }

        .form-group label {
            color: var(--text-light);
        }

        .form-section h2 {
            color: var(--dark-beige);
        }

        .map-instructions {
            color: #bbb;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        body {
            padding: 1rem;
        }

        .store-edit-header {
            padding: 1.5rem;
        }

        .store-form {
            padding: 1.5rem;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .submit-btn, .schedules-btn, .delete-btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .store-edit-container {
        animation: fadeIn 0.4s ease-out;
    }
</style>
<div class="store-edit-container">
    <div class="store-edit-header">
        <h1 class="store-edit-title">Modifier le point de vente</h1>
        <a href="{{ route('admin.stores.index') }}" class="back-button">
            <i data-lucide="arrow-left"></i>
            Retour aux points de vente
        </a>
    </div>

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

    <form action="{{ route('admin.stores.update', $store) }}" method="POST" class="store-form">
        @csrf
        @method('PUT')
        
        <div class="form-section">
            <h2>Informations de base</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom du point de vente</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom', $store->nom) }}" required>
                    @error('nom')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <input type="text" id="adresse" name="adresse" value="{{ old('adresse', $store->adresse) }}" required>
                    @error('adresse')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>Localisation</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="ville">Ville</label>
                    <input type="text" id="ville" name="ville" value="{{ old('ville', $store->ville) }}" required>
                    @error('ville')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="pays">Pays</label>
                    <input type="text" id="pays" name="pays" value="{{ old('pays', $store->pays) }}" required>
                    @error('pays')
                        <div class="error-message">{{ $message }}</div>
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
                    <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude', $store->latitude) }}" required>
                    @error('latitude')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="longitude">Longitude</label>
                    <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude', $store->longitude) }}" required>
                    @error('longitude')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="submit-btn">
                <i data-lucide="save"></i>
                Enregistrer les modifications
            </button>
            
            <a href="{{ route('admin.stores.schedules.index', $store) }}" class="schedules-btn">
                <i data-lucide="calendar"></i>
                Gérer les horaires
            </a>
            
            <form action="{{ route('admin.stores.destroy', $store) }}" method="POST" class="delete-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce point de vente ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-btn">
                    <i data-lucide="trash-2"></i>
                    Supprimer
                </button>
            </form>
        </div>
    </form>
</div>

<script>
    function initMap() {
        const lat = parseFloat(document.getElementById('latitude').value) || 48.8566;
        const lng = parseFloat(document.getElementById('longitude').value) || 2.3522;
        const map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: lat, lng: lng },
            zoom: 12,
        });
        let marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            draggable: true
        });
        const geocoder = new google.maps.Geocoder();
        const addressInput = document.getElementById('adresse');
        addressInput.addEventListener('change', function() {
            geocoder.geocode({ address: this.value }, function(results, status) {
                if (status === 'OK' && results[0]) {
                    const location = results[0].geometry.location;
                    map.setCenter(location);
                    marker.setMap(null);
                    marker = new google.maps.Marker({
                        map: map,
                        position: location,
                        draggable: true
                    });
                    document.getElementById('latitude').value = location.lat();
                    document.getElementById('longitude').value = location.lng();
                    marker.addListener('dragend', function() {
                        const position = marker.getPosition();
                        document.getElementById('latitude').value = position.lat();
                        document.getElementById('longitude').value = position.lng();
                    });
                }
            });
        });
        map.addListener('click', function(e) {
            marker.setMap(null);
            marker = new google.maps.Marker({
                position: e.latLng,
                map: map,
                draggable: true
            });
            document.getElementById('latitude').value = e.latLng.lat();
            document.getElementById('longitude').value = e.latLng.lng();
            marker.addListener('dragend', function() {
                const position = marker.getPosition();
                document.getElementById('latitude').value = position.lat();
                document.getElementById('longitude').value = position.lng();
            });
        });
    }
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_xQsTc41ShFh3sMnafHjUEht-8ZrDoM8&callback=initMap"></script>