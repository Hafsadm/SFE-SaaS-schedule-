<x-app-layout>
    <style>
        body, input, button, select, textarea {
            font-family: system-ui, sans-serif;
        }
        .aff-header {
            text-align: center;
            font-size: 2rem;
            font-weight: 600;
            margin: 2rem 0 1rem 0;
            letter-spacing: 0.1em;
        }
        .aff-form-container {
            max-width: 600px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            padding: 2rem 2.5rem;
        }
        .aff-form-row {
            margin-bottom: 1.5rem;
        }
        .aff-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #222;
        }
        .aff-input, .aff-input[readonly] {
            width: 100%;
            padding: 0.7rem 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
            background: #fafafa;
            color: #222;
        }
        .aff-input:focus {
            border-color: #222;
            outline: none;
        }
        .aff-error {
            color: #d32f2f;
            font-size: 0.95rem;
            margin-top: 0.2rem;
        }
        .aff-map-container {
            margin-bottom: 1.5rem;
        }
        #map {
            width: 100%;
            height: 320px;
            border-radius: 6px;
            border: 1px solid #eee;
        }
        .aff-form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }
        .aff-btn {
            padding: 0.7rem 2rem;
            border-radius: 2px;
            border: none;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            background: #222;
            color: #fff;
            transition: background 0.2s;
        }
        .aff-btn:hover {
            background: #d32f2f;
        }
        .aff-btn-secondary {
            background: #e5e7eb;
            color: #222;
        }
        .aff-btn-secondary:hover {
            background: #bdbdbd;
        }
        @media (max-width: 700px) {
            .aff-form-container {
                padding: 1rem 0.5rem;
            }
        }
        @media (prefers-color-scheme: dark) {
            body, .aff-form-container {
                background: #18181b;
                color: #f3f4f6;
            }
            .aff-form-container {
                border-color: #333;
            }
            .aff-label {
                color: #f3f4f6;
            }
            .aff-input, .aff-input[readonly] {
                background: #23232b;
                color: #f3f4f6;
                border-color: #444;
            }
            .aff-btn-secondary {
                background: #444;
                color: #f3f4f6;
            }
            .aff-btn-secondary:hover {
                background: #666;
            }
        }
    </style>
    <div class="aff-header">MODIFIER LE POINT DE VENTE</div>
    <div class="aff-form-container">
        <form action="{{ route('admin.stores.update', $store) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="aff-form-row">
                <label for="nom" class="aff-label">Nom du point de vente</label>
                <input id="nom" name="nom" type="text" class="aff-input" value="{{ old('nom', $store->nom) }}" required autofocus>
                @if($errors->has('nom'))
                    <div class="aff-error">{{ $errors->first('nom') }}</div>
                @endif
            </div>
            <div class="aff-form-row">
                <label for="adresse" class="aff-label">Adresse</label>
                <input id="adresse" name="adresse" type="text" class="aff-input" value="{{ old('adresse', $store->adresse) }}" required>
                @if($errors->has('adresse'))
                    <div class="aff-error">{{ $errors->first('adresse') }}</div>
                @endif
            </div>
            <div class="aff-form-row aff-map-container">
                <label class="aff-label">Localisation</label>
                <div id="map"></div>
            </div>
            <div class="aff-form-row" style="display: flex; gap: 1rem;">
                <div style="flex:1;">
                    <label for="latitude" class="aff-label">Latitude</label>
                    <input id="latitude" name="latitude" type="text" class="aff-input" value="{{ old('latitude', $store->latitude) }}" required readonly>
                    @if($errors->has('latitude'))
                        <div class="aff-error">{{ $errors->first('latitude') }}</div>
                    @endif
                </div>
                <div style="flex:1;">
                    <label for="longitude" class="aff-label">Longitude</label>
                    <input id="longitude" name="longitude" type="text" class="aff-input" value="{{ old('longitude', $store->longitude) }}" required readonly>
                    @if($errors->has('longitude'))
                        <div class="aff-error">{{ $errors->first('longitude') }}</div>
                    @endif
                </div>
            </div>
            <div class="aff-form-actions">
                <a href="{{ route('admin.stores.index') }}" class="aff-btn aff-btn-secondary">Annuler</a>
                <button type="submit" class="aff-btn">Enregistrer</button>
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
</x-app-layout> 