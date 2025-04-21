@extends('layouts.app')

@section('content')
<div class="schedule-form-container">
    {{-- <div class="schedule-header">
        <h1 class="schedule-title">
            @if($type === 'regular')
                Ajouter un horaire régulier pour {{ $store->nom }}
            @elseif($type === 'exception')
                Ajouter une exception pour {{ $store->nom }}
            @else
                Ajouter un jour férié pour {{ $store->nom }}
            @endif
        </h1>
        <a href="{{ route('admin.stores.schedules.select-type', $store) }}" class="back-button">
            <i data-lucide="arrow-left"></i>
            Retour au choix
        </a>
    </div> --}}

    <div class="schedule-form">
        <form action="{{ route('admin.stores.schedules.store', $store) }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            @if($type === 'regular')
                <div class="form-group">
                    <label for="day_of_week" class="form-label">Jour de la semaine</label>
                    <select name="day_of_week" id="day_of_week" class="form-select" required>
                        <option value="">Sélectionner un jour</option>
                        @foreach(['monday' => 'Lundi', 'tuesday' => 'Mardi', 'wednesday' => 'Mercredi', 
                                'thursday' => 'Jeudi', 'friday' => 'Vendredi', 'saturday' => 'Samedi', 
                                'sunday' => 'Dimanche'] as $key => $day)
                            <option value="{{ $key }}" {{ old('day_of_week') == $key ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                        @endforeach
                    </select>
                    @error('day_of_week')
                        <div class="error-message">{{ $errors->first('day_of_week') }}</div>
                    @enderror
                </div>
            @else
                <div class="form-group">
                    <label for="exception_date" class="form-label">Date</label>
                    <input type="date" name="exception_date" id="exception_date" class="form-input" 
                           value="{{ old('exception_date') }}" required>
                    @error('exception_date')
                        <div class="error-message">{{ $errors->first('exception_date') }}</div>
                    @enderror
                </div>

                @if($type === 'exception')
                    <div class="form-group">
                        <label for="exception_reason" class="form-label">Raison de l'exception</label>
                        <input type="text" name="exception_reason" id="exception_reason" class="form-input" 
                               value="{{ old('exception_reason') }}" placeholder="Ex: Congés annuels, Formation...">
                        @error('exception_reason')
                            <div class="error-message">{{ $errors->first('exception_reason') }}</div>
                        @enderror
                    </div>
                @endif
            @endif
{{-- 
            <div class="form-checkbox">
                <input type="checkbox" name="is_closed" id="is_closed" {{ old('is_closed') ? 'checked' : '' }}>
                <label for="is_closed">Fermé ce jour</label>
            </div> --}}

            @if($type === 'holiday')
                <div class="form-checkbox">
                    <input type="checkbox" name="is_holiday" id="is_holiday" checked disabled>
                    <label for="is_holiday">Jour férié</label>
                </div>
            @endif

            <div id="time-fields" style="display: {{ old('is_closed') ? 'none' : 'block' }}">
                <div class="time-inputs">
                    <div class="form-group">
                        <label for="opening_time" class="form-label">Heure d'ouverture</label>
                        <input type="time" name="opening_time" id="opening_time" class="form-input" 
                               value="{{ old('opening_time') }}" required>
                        @error('opening_time')
                            <div class="error-message">{{ $errors->first('opening_time') }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="closing_time" class="form-label">Heure de fermeture</label>
                        <input type="time" name="closing_time" id="closing_time" class="form-input" 
                               value="{{ old('closing_time') }}" required>
                        @error('closing_time')
                            <div class="error-message">{{ $errors->first('closing_time') }}</div>
                        @enderror
                    </div>
                </div>

             
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.stores.schedules.select-type', $store) }}" class="cancel-btn">
                    Annuler
                </a>
                <button type="submit" class="submit-btn">
                    <i data-lucide="save"></i>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    :root {
        --primary-color: #8B4513; /* Marron */
        --secondary-color: #F5F5DC; /* Beige */
        --text-color: #333;
        --border-color: #D2B48C;
        --error-color: #F44336;
    }

    .schedule-form-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .schedule-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1rem;
        background-color: var(--secondary-color);
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .schedule-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary-color);
        margin: 0;
    }

    .back-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .back-button:hover {
        background-color: #6B2B00;
        transform: translateY(-1px);
    }

    .schedule-form {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 2rem;
        border: 1px solid var(--border-color);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--text-color);
    }

    .form-select, .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 1rem;
        color: var(--text-color);
        transition: all 0.2s;
        background: white;
    }

    .form-select:focus, .form-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
    }

    .form-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .form-checkbox input[type="checkbox"] {
        accent-color: var(--primary-color);
        width: 1.25rem;
        height: 1.25rem;
    }

    .form-checkbox label {
        color: var(--text-color);
    }

    .time-inputs {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
    }

    .cancel-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        background: white;
        color: var(--text-color);
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }

    .cancel-btn:hover {
        background: var(--secondary-color);
        border-color: var(--primary-color);
    }

    .submit-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        background-color: var(--primary-color);
        color: white;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .submit-btn:hover {
        background-color: #6B2B00;
        transform: translateY(-1px);
    }

    .error-message {
        color: var(--error-color);
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    @media (max-width: 768px) {
        .schedule-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .back-button {
            width: 100%;
            justify-content: center;
        }

        .time-inputs {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .cancel-btn, .submit-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    document.getElementById('is_closed').addEventListener('change', function() {
        const timeFields = document.getElementById('time-fields');
        const openingTime = document.getElementById('opening_time');
        const closingTime = document.getElementById('closing_time');
        
        if (this.checked) {
            timeFields.style.display = 'none';
            openingTime.removeAttribute('required');
            closingTime.removeAttribute('required');
        } else {
            timeFields.style.display = 'block';
            openingTime.setAttribute('required', 'required');
            closingTime.setAttribute('required', 'required');
        }
    });

    // Initialiser les icônes Lucide
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection 