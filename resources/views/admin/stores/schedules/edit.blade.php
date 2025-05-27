@extends('layouts.app')

@section('content')
<div class="schedule-form-container">
    <div class="schedule-form-header">
        <h1 class="schedule-form-title">Modifier l'horaire pour {{ $store->nom }}</h1>
        <a href="{{ route('admin.stores.schedules.index', $store) }}" class="back-button">
            <i data-lucide="arrow-left"></i>
            Retour aux horaires
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.stores.schedules.update', [$store, $schedule]) }}" method="POST" class="schedule-form" id="scheduleForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="apply_to_all_days" id="applyToAllDays" value="0">

        <div class="form-group">
            <label for="day_of_week">Jour de la semaine</label>
            <select name="day_of_week" id="day_of_week" class="form-control" required>
                @foreach($days as $value => $label)
                    <option value="{{ $value }}" {{ $schedule->day_of_week == $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('day_of_week')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div id="time_slots_container" class="time-slots-container" style="{{ $schedule->is_closed ? 'display: none;' : '' }}">
            <h3>Créneaux horaires</h3>
            <div id="time_slots">
                @if(count($schedule->time_slots) > 0)
                    @foreach($schedule->time_slots as $index => $slot)
                        <div class="time-slot-group">
                            <div class="time-inputs">
                                <input type="time" name="time_slots[{{ $index }}][start]" class="time-input" value="{{ $slot['start'] }}" required>
                                <span class="time-separator">-</span>
                                <input type="time" name="time_slots[{{ $index }}][end]" class="time-input" value="{{ $slot['end'] }}" required>
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

            <button type="button" class="add-slot-btn" onclick="addTimeSlot()">
                <i data-lucide="plus"></i>
                Ajouter un créneau
            </button>
            
            @error('time_slots')
                <div class="error-message">{{ $message }}</div>
            @enderror
            @error('time_slots.*.start')
                <div class="error-message">{{ $message }}</div>
            @enderror
            @error('time_slots.*.end')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="is_closed" class="checkbox-label">
                <input type="checkbox" name="is_closed" id="is_closed" {{ $schedule->is_closed ? 'checked' : '' }}>
                Fermé
            </label>
        </div>

        @if($isSunday)
            <div class="form-group">
                <label for="sunday_override" class="checkbox-label">
                    <input type="checkbox" name="sunday_override" id="sunday_override" {{ !$schedule->is_closed ? 'checked' : '' }}>
                    Autoriser l'ouverture le dimanche
                </label>
            </div>
        @endif

        <div class="form-actions">
            <button type="button" class="apply-all-btn" id="applyToAllButton">
                <i data-lucide="copy"></i>
                Appliquer à tous les jours
            </button>
            <button type="submit" class="submit-btn">
                <i data-lucide="save"></i>
                Enregistrer l'horaire
            </button>
        </div>
    </form>
</div>

<!-- Modal de confirmation -->
<div id="confirmModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Confirmation</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <p>Voulez-vous appliquer ces horaires à tous les jours de la semaine?</p>
            <p class="modal-info">Cette action va créer ou remplacer les horaires pour tous les jours de la semaine avec les créneaux horaires que vous avez définis.</p>
        </div>
        <div class="modal-footer">
            <button id="cancelButton" class="cancel-btn">Annuler</button>
            <button id="confirmButton" class="confirm-btn">Confirmer</button>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color:  #2a6363;
        --secondary-color: #0a2e2e;
        --text-color: #333;
        --border-color: #D2B48C;
        --error-color: #F44336;
    }

    .schedule-form-container {
        max-width: 700px;
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
        border-radius:  30px  0 ;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .schedule-form-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #D2B48C;
        margin: 0;
    }

    .back-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background-color:  #2a6363;
        color: white;
        border: none;
        border-radius: 30px 0;
        text-decoration: none;
        transition: all 0.2s;
    }

    .back-button:hover {
        background-color:  #0a2e2e;
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
        background: #0a2e2e;
        padding: 2rem;
        border-radius: 30px 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-size: 1rem;
        margin-bottom: 0.5rem;
        font-size: 1.2rem;
        font-weight: 600;
        cursor: pointer;
        margin-bottom: 0.5rem;
        color: #D2B48C;
    }

    .form-control {
        width: 97%;
        padding: 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 10px 0;
        background: white;
        color: var(--text-color);
        margin-left: auto;
        margin-right: auto;
        
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
        color:  #D2B48C;
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
        flex: 10px;
        margin-left: auto;
        margin-right: auto;
    }

    .time-input {
        padding: 0.5rem;
        border: 1px solid var(--border-color);
        border-radius: 20px 0;
       
        background: white;
        align-items: center;
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
        background-color: #0a2e2e;
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
        background-color:  #2a6363;
        transform: translateY(-1px);
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .submit-btn, .apply-all-btn {
        font-family: Georgia, 'Times New Roman', Times, serif;
        display: flex;
        align-items: center;
        border: none;
        font-weight: bold;
        cursor: pointer;     
        font-size: 1rem;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background-color:  #2a6363;
        color: #D2B48C;
        border: none;
        border-radius: 30px 0;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 1.4rem;
    }

    .apply-all-btn {
        background-color: #D2B48C;
        color: #0a2e2e;
    }

    .submit-btn:hover, .apply-all-btn:hover {
        background-color: #0a2e2e;
        color: #D2B48C;
        transform: translateY(-1px);
    }

    .apply-all-btn:hover {
        background-color: #b89a76;
        color: #0a2e2e;
    }

    .error-message {
        color: var(--error-color);
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #0a2e2e;
        margin: 15% auto;
        padding: 0;
        border: 1px solid var(--border-color);
        border-radius: 30px 0;
        width: 80%;
        max-width: 500px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        animation: modalFadeIn 0.3s;
    }

    @keyframes modalFadeIn {
        from {opacity: 0; transform: translateY(-20px);}
        to {opacity: 1; transform: translateY(0);}
    }

    .modal-header {
        padding: 1rem;
        background-color: var(--secondary-color);
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 30px 0 0 0;
    }

    .modal-header h2 {
        margin: 0;
        color: #D2B48C;
        font-size: 1.5rem;
    }

    .close {
        color: #D2B48C;
        font-size: 1.5rem;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover {
        color: white;
    }

    .modal-body {
        padding: 1.5rem;
        color: #D2B48C;
    }

    .modal-info {
        font-size: 0.9rem;
        color: #aaa;
        margin-top: 1rem;
    }

    .modal-footer {
        padding: 1rem;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .cancel-btn, .confirm-btn {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 30px 0;
        cursor: pointer;
        font-weight: bold;
    }

    .cancel-btn {
        background-color: #555;
        color: white;
    }

    .confirm-btn {
        background-color: #2a6363;
        color: #D2B48C;
    }

    .cancel-btn:hover {
        background-color: #444;
    }

    .confirm-btn:hover {
        background-color: #1b5858;
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

        .form-actions {
            flex-direction: column;
            gap: 1rem;
        }

        .apply-all-btn, .submit-btn {
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

        // Gestion du dimanche
        const sundayOverrideCheckbox = document.getElementById('sunday_override');
        if (sundayOverrideCheckbox) {
            sundayOverrideCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    isClosedCheckbox.disabled = false;
                } else {
                    isClosedCheckbox.checked = true;
                    isClosedCheckbox.disabled = true;
                    timeSlotsContainer.style.display = 'none';
                }
            });
            
            // Initialisation
            if (!sundayOverrideCheckbox.checked) {
                isClosedCheckbox.disabled = true;
            }
        }

        // Modal de confirmation
        const modal = document.getElementById('confirmModal');
        const applyToAllButton = document.getElementById('applyToAllButton');
        const confirmButton = document.getElementById('confirmButton');
        const cancelButton = document.getElementById('cancelButton');
        const closeButton = document.querySelector('.close');
        const applyToAllDaysInput = document.getElementById('applyToAllDays');
        const scheduleForm = document.getElementById('scheduleForm');

        // Ouvrir le modal
        applyToAllButton.addEventListener('click', function() {
            // Vérifier si des créneaux horaires sont définis (si le magasin n'est pas fermé)
            if (!isClosedCheckbox.checked) {
                const timeSlots = document.querySelectorAll('.time-slot-group');
                if (timeSlots.length === 0) {
                    alert('Veuillez ajouter au moins un créneau horaire avant d\'appliquer à tous les jours.');
                    return;
                }

                // Vérifier que tous les créneaux horaires sont remplis
                let allFilled = true;
                document.querySelectorAll('.time-input').forEach(input => {
                    if (!input.value) {
                        allFilled = false;
                    }
                });

                if (!allFilled) {
                    alert('Veuillez remplir tous les créneaux horaires avant d\'appliquer à tous les jours.');
                    return;
                }
            }

            modal.style.display = 'block';
        });

        // Fermer le modal
        closeButton.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        cancelButton.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // Cliquer en dehors du modal pour le fermer
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Confirmer l'application à tous les jours
        confirmButton.addEventListener('click', function() {
            applyToAllDaysInput.value = '1';
            scheduleForm.submit();
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
