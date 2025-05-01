@extends('layouts.app')

@section('content')
<div class="schedule-form-container">
    <div class="schedule-form-header">
        <h1 class="schedule-form-title">Exception pour {{ $store->nom }}</h1>
        <a href="{{ route('admin.stores.schedules.select-type', $store) }}" class="back-button">
            <i data-lucide="arrow-left"></i>
            Retour au choix du type
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.stores.exceptions.store', $store) }}" method="POST" class="schedule-form">
        @csrf
        <input type="hidden" name="type" value="exception">

        <div class="form-group">
            <label for="exception_date">Date de l'exception</label>
            <input type="date" name="exception_date" id="exception_date" class="form-control" value="{{ old('exception_date') }}" min="{{ date('Y-m-d') }}" required>
            @error('exception_date')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="exception_raison">Raison de l'exception</label>
            <input type="text" name="exception_raison" id="exception_raison" class="form-control" value="{{ old('exception_raison') }}" required
                   placeholder="Ex: Réunion d'équipe, Inventaire...">
            @error('exception_raison')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="is_closed" class="checkbox-label">
                <input type="checkbox" name="is_closed" id="is_closed" {{ old('is_closed') ? 'checked' : '' }}>
                Fermé exceptionnellement
            </label>
        </div>

        {{-- 
        <div id="time_slots_container" class="time-slots-container" style="{{ old('is_closed') ? 'display: none;' : '' }}">
            <h3>Créneaux horaires exceptionnels</h3>
            <div id="time_slots">
                @if(old('time_slots'))
                    @foreach(old('time_slots') as $index => $slot)
                        <div class="time-slot-group">
                            <div class="time-inputs">
                                <input type="time" name="time_slots[{{ $index }}][start]" class="time-input" value="{{ $slot['start'] ?? '' }}" required>
                                <span class="time-separator">-</span>
                                <input type="time" name="time_slots[{{ $index }}][end]" class="time-input" value="{{ $slot['end'] ?? '' }}" required>
                            </div>
                            <button type="button" class="remove-slot" onclick="removeTimeSlot(this)">
                                <i data-lucide="x"></i>
                            </button>
                        </div>
                    @endforeach
                @else
                    <div class="time-slot-group">
                        <div class="time-inputs">
                            <input type="time" name="time_slots[0][start]" class="time-input" required>
                            <span class="time-separator">-</span>
                            <input type="time" name="time_slots[0][end]" class="time-input" required>
                        </div>
                        <button type="button" class="remove-slot" onclick="removeTimeSlot(this)">
                            <i data-lucide="x"></i>
                        </button>
                    </div>
                @endif
            </div>
       
            
            @error('time_slots')
                <div class="error-message">{{ $message }}</div>
            @enderror
            @error('time_slots.*.start')
                <div class="error-message">{{ $message }}</div>
            @enderror
            @error('time_slots.*.end')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div> --}}

        <div class="form-actions">
            <button type="submit" class="submit-btn">
                <i data-lucide="save"></i>
                Enregistrer l'exception
            </button>
        </div>
    </form>
</div>

<style>
    :root {
        --primary-color: #8B4513;
        --secondary-color: #F5F5DC;
        --text-color: #333;
        --border-color: #D2B48C;
        --error-color: #F44336;
    }

    .schedule-form-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .schedule-form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1rem;
        background-color: var(--secondary-color);
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .schedule-form-title {
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

    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
    }

    .alert-error {
        background-color: #FFEBEE;
        color: var(--error-color);
        border: 1px solid var(--error-color);
    }

    .schedule-form {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-color);
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        background: white;
        color: var(--text-color);
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .checkbox-label input[type="checkbox"] {
        width: 1.25rem;
        height: 1.25rem;
        accent-color: var(--primary-color);
    }

    .time-slots-container {
        margin-bottom: 1.5rem;
    }

    .time-slots-container h3 {
        margin-top: 0;
        margin-bottom: 1rem;
        font-size: 1.1rem;
        color: var(--primary-color);
    }

    .time-slot-group {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .time-inputs {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex: 1;
    }

    .time-input {
        padding: 0.5rem;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        background: white;
        color: var(--text-color);
        flex: 1;
    }

    .time-separator {
        color: var(--primary-color);
        font-weight: 600;
    }

    .remove-slot {
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        padding: 0.25rem;
    }

    .add-slot-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        width: 100%;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .add-slot-btn:hover {
        background-color: #6B2B00;
        transform: translateY(-1px);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
    }

    .submit-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 4px;
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
        .schedule-form-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .back-button {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        
        const isClosedCheckbox = document.getElementById('is_closed');
        const timeSlotsContainer = document.getElementById('time_slots_container');
        
        isClosedCheckbox.addEventListener('change', function() {
            timeSlotsContainer.style.display = this.checked ? 'none' : 'block';
            
            // Gérer les attributs required des champs de temps
            const timeInputs = timeSlotsContainer.querySelectorAll('input[type="time"]');
            timeInputs.forEach(input => {
                input.required = !this.checked;
            });
        });
    });

    function addTimeSlot() {
        const container = document.getElementById('time_slots');
        const timeSlotCount = container.children.length;
        
        const timeSlotGroup = document.createElement('div');
        timeSlotGroup.className = 'time-slot-group';
        
        timeSlotGroup.innerHTML = `
            <div class="time-inputs">
                <input type="time" name="time_slots[${timeSlotCount}][start]" class="time-input" required>
                <span class="time-separator">-</span>
                <input type="time" name="time_slots[${timeSlotCount}][end]" class="time-input" required>
            </div>
            <button type="button" class="remove-slot" onclick="removeTimeSlot(this)">
                <i data-lucide="x"></i>
            </button>
        `;
        
        container.appendChild(timeSlotGroup);
        lucide.createIcons();
    }

    function removeTimeSlot(button) {
        const container = document.getElementById('time_slots');
        if (container.children.length > 1) {
            button.closest('.time-slot-group').remove();
        }
    }
</script>
@endsection