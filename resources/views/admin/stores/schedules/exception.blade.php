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
                <input type="checkbox" name="is_closed" id="is_closed">
                Fermé
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
        --primary: #2a6363;
        --primary-light: #3a7a7a;
        --secondary: #0a2e2e;
        --accent: #D2B48C;
        --accent-light: #e5d5b8;
        --text: #333333;
        --text-light: #f8f8f8;
        --border: #c4b7a0;
        --error: #e74c3c;
        --success: #2ecc71;
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
        background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
        border-radius: 30px 0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .schedule-form-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--accent);
        margin: 0;
        letter-spacing: -0.5px;
    }

    .back-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background-color: var(--primary);
        color: var(--text-light);
        border: none;
        border-radius: 30px 0;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .back-button:hover {
        background-color: var(--secondary);
        transform: translateY(-2px);
    }

    .alert {
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        border-radius: 8px;
        font-weight: 500;
    }

    .alert-error {
        background-color: rgba(231, 76, 60, 0.1);
        color: var(--error);
        border-left: 4px solid var(--error);
    }

    .schedule-form {
        background: var(--secondary);
        padding: 2rem;
        border-radius: 30px 0;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border);
    }

    .form-group {
        margin-bottom: 2rem;
    }

    .form-group label {
        display: block;
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
        font-weight: 600;
        color: var(--accent);
        letter-spacing: 0.2px;
    }

    .form-control {
        width: 97%;
        padding: 1rem;
        border: 2px solid var(--border);
        border-radius: 10px 0;
        background: white;
        color: var(--text);
        font-size: 1rem;
        transition: all 0.3s ease;
        margin-left: auto;
        margin-right: auto;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(42, 99, 99, 0.2);
        outline: none;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        color: var(--accent);
        font-weight: 500;
    }

    .checkbox-label input[type="checkbox"] {
        width: 1.25rem;
        height: 1.25rem;
        accent-color: var(--primary);
    }

    .time-slots-container {
        margin-bottom: 2rem;
    }

    .time-slots-container h3 {
        margin-top: 0;
        margin-bottom: 1.25rem;
        font-size: 1.2rem;
        color: var(--accent);
        font-weight: 600;
    }

    .time-slot-group {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .time-inputs {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
        margin-left: auto;
        margin-right: auto;
    }

    .time-input {
        padding: 0.75rem;
        border: 2px solid var(--border);
        border-radius: 20px 0;
        background: white;
        color: var(--text);
        font-size: 1rem;
        text-align: center;
        flex: 1;
    }

    .time-separator {
        color: var(--primary);
        font-weight: 700;
    }

    .remove-slot {
        background: none;
        border: none;
        color: var(--error);
        cursor: pointer;
        font-size: 1.25rem;
        transition: transform 0.2s;
    }

    .remove-slot:hover {
        transform: scale(1.1);
    }

    .add-slot-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.9rem 1.75rem;
        background-color: var(--primary);
        color: white;
        border: none;
        border-radius: 30px 0;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        margin-bottom: 1.5rem;
        width: 100%;
        justify-content: center;
    }

    .add-slot-btn:hover {
        background-color: var(--primary-light);
        transform: translateY(-2px);
    }

    .form-actions {
        display: flex;
        justify-content: flex-start;
        padding-left: 20px;
        margin-top: 2rem;
    }

    .submit-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2.25rem;
        background-color: var(--primary);
        color: var(--accent);
        border: none;
        border-radius: 30px 0;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 1.1rem;
        margin-left: auto;
        margin-right: -10px;
        margin-top: 20px;
        font-family: Georgia, 'Times New Roman', Times, serif;
    }

    .submit-btn:hover {
        background-color: var(--secondary);
        transform: translateY(-2px);
    }

    .error-message {
        color: var(--error);
        font-size: 0.9rem;
        margin-top: 0.5rem;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .schedule-form-header {
            flex-direction: column;
            gap: 1.25rem;
            text-align: center;
        }

        .back-button {
            width: 100%;
            justify-content: center;
        }

        .schedule-form {
            padding: 1.5rem;
        }

        .time-slot-group {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .time-inputs {
            width: 100%;
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