@extends('layouts.app')

@section('content')
<div class="schedule-form-container">
    <div class="schedule-form-header">
        <h1 class="schedule-form-title">
            <?php
            if ($type === 'regular') {
                echo "Modifier l'horaire régulier pour " . htmlspecialchars($store->nom);
            } elseif ($type === 'exception') {
                echo "Modifier l'exception pour " . htmlspecialchars($store->nom);
            } else {
                echo "Modifier le jour férié pour " . htmlspecialchars($store->nom);
            }
            ?>
        </h1>
        <a href="<?= route('admin.stores.schedules.index', $store) ?>" class="back-button">
            <i data-lucide="arrow-left"></i>
            Retour aux horaires
        </a>
    </div>

    <?php if (session('error')) : ?>
        <div class="alert alert-error">
            <?= htmlspecialchars(session('error')) ?>
        </div>
    <?php endif; ?>

    <?php
    // Déterminer l'objet principal et la route de mise à jour
    $mainObject = $type === 'regular' ? $schedule : 
                 ($type === 'exception' ? $exception : $holiday);
    
    $updateRoute = $type === 'regular' ? route('admin.stores.schedules.update', [$store, $schedule]) :
                  ($type === 'exception' ? route('admin.stores.schedules.update', [$store, $exception]) :
                  route('admin.stores.schedules.update', [$store, $holiday]));
    ?>

    <form action="<?= $updateRoute ?>" method="POST" class="schedule-form">
        <?= csrf_field() ?>
        <?= method_field('PUT') ?>

        <!-- Champ Jour/Date -->
        <div class="form-group">
            <?php if ($type === 'regular') : ?>
                <label for="day_of_week">Jour de la semaine</label>
                <select name="day_of_week" id="day_of_week" class="form-control" required>
                    <option value="">Sélectionner un jour</option>
                    <?php foreach ($days as $value => $label) : ?>
                        <option value="<?= htmlspecialchars($value) ?>" <?= $schedule->day_of_week == $value ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($errors->has('day_of_week')) : ?>
                    <div class="error-message"><?= htmlspecialchars($errors->first('day_of_week')) ?></div>
                <?php endif; ?>
                
                <?php if (isset($isSunday) && $isSunday) : ?>
                    <div class="form-group mt-3">
                        <label for="sunday_override" class="checkbox-label">
                            <input type="checkbox" name="sunday_override" id="sunday_override" 
                                   <?= !$schedule->is_closed ? 'checked' : '' ?>>
                            Permettre l'ouverture le dimanche (par défaut fermé)
                        </label>
                    </div>
                <?php endif; ?>
                
            <?php else : ?>
                <label for="<?= $type === 'exception' ? 'exception_date' : 'holiday_date' ?>">
                    Date
                </label>
                <input type="date" 
                       name="<?= $type === 'exception' ? 'exception_date' : 'holiday_date' ?>" 
                       id="<?= $type === 'exception' ? 'exception_date' : 'holiday_date' ?>" 
                       class="form-control" 
                       value="<?= $type === 'exception' ? htmlspecialchars($exception->exception_date->format('Y-m-d')) : htmlspecialchars($holiday->holiday_date->format('Y-m-d')) ?>" 
                       required>
                <?php if ($errors->has($type === 'exception' ? 'exception_date' : 'holiday_date')) : ?>
                    <div class="error-message"><?= htmlspecialchars($errors->first($type === 'exception' ? 'exception_date' : 'holiday_date')) ?></div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Champ Raison/Nom -->
        <?php if ($type !== 'regular') : ?>
            <div class="form-group">
                <label for="<?= $type === 'exception' ? 'exception_raison' : 'holiday_name' ?>">
                    <?= $type === 'holiday' ? 'Nom du jour férié' : 'Raison de l\'exception' ?>
                </label>
                <input type="text" 
                       name="<?= $type === 'exception' ? 'exception_raison' : 'holiday_name' ?>" 
                       id="<?= $type === 'exception' ? 'exception_raison' : 'holiday_name' ?>" 
                       class="form-control" 
                       value="<?= $type === 'exception' ? htmlspecialchars($exception->exception_raison) : htmlspecialchars($holiday->holiday_name) ?>" 
                       required
                       placeholder="<?= $type === 'holiday' ? 'Ex: Noël, Pâques...' : 'Ex: Réunion d\'équipe...' ?>">
                <?php if ($errors->has($type === 'exception' ? 'exception_raison' : 'holiday_name')) : ?>
                    <div class="error-message"><?= htmlspecialchars($errors->first($type === 'exception' ? 'exception_raison' : 'holiday_name')) ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Champ Fermé/Créneaux horaires -->
        <?php if ($type !== 'holiday') : ?>
            <div class="form-group">
                <label for="is_closed" class="checkbox-label">
                    <input type="checkbox" name="is_closed" id="is_closed" 
                           <?= $mainObject->is_closed ? 'checked' : '' ?>>
                    Fermé <?= $type === 'exception' ? 'exceptionnellement' : '' ?>
                </label>
            </div>

            <div id="time_slots_container" class="time-slots-container" 
                 style="<?= $mainObject->is_closed ? 'display: none;' : '' ?>">
                <h3>Créneaux horaires</h3>
                
                <?php if ($type === 'exception' && !empty($suggestedTimeSlots)) : ?>
                    <div class="suggested-slots">
                        <p>Suggestions basées sur les horaires réguliers:</p>
                        <div class="suggested-slots-buttons">
                            <button type="button" class="use-suggested-slots" onclick="useSuggestedSlots()">
                                Utiliser ces créneaux
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div id="time_slots">
                    <?php if (!empty($mainObject->time_slots)) : ?>
                        <?php foreach ($mainObject->time_slots as $index => $slot) : ?>
                            <div class="time-slot-group">
                                <div class="time-inputs">
                                    <input type="time" name="time_slots[<?= $index ?>][start]" 
                                           class="time-input" value="<?= htmlspecialchars($slot['start'] ?? '') ?>" required>
                                    <span class="time-separator">-</span>
                                    <input type="time" name="time_slots[<?= $index ?>][end]" 
                                           class="time-input" value="<?= htmlspecialchars($slot['end'] ?? '') ?>" required>
                                </div>
                                <button type="button" class="remove-slot" onclick="removeTimeSlot(this)">
                                    <i data-lucide="x"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
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
                    <?php endif; ?>
                </div>
                
              
            </div>
        <?php endif; ?>

        <div class="form-actions">
            <button type="submit" class="submit-btn">
                <i data-lucide="save"></i>
                Enregistrer les modifications
            </button>
            
            <?php
            $deleteRoute = $type === 'regular' ? route('admin.stores.schedules.edit', [$store, $schedule]) :
                          ($type === 'exception' ? route('admin.stores.schedules.edit', [$store, $exception]) :
                          route('admin.stores.schedules.edit', [$store, $holiday]));
            ?>

        </div>
    </form>
</div>

<script>
    // Gestion des créneaux horaires
    function addTimeSlot() {
        const container = document.getElementById('time_slots');
        const index = container.children.length;
        
        const slotGroup = document.createElement('div');
        slotGroup.className = 'time-slot-group';
        slotGroup.innerHTML = `
            <div class="time-inputs">
                <input type="time" name="time_slots[${index}][start]" class="time-input" required>
                <span class="time-separator">-</span>
                <input type="time" name="time_slots[${index}][end]" class="time-input" required>
            </div>
            <button type="button" class="remove-slot" onclick="removeTimeSlot(this)">
                <i data-lucide="x"></i>
            </button>
        `;
        
        container.appendChild(slotGroup);
        lucide.createIcons();
    }
    
    function removeTimeSlot(button) {
        if (document.querySelectorAll('.time-slot-group').length > 1) {
            button.closest('.time-slot-group').remove();
            // Reindexer les noms des champs
            document.querySelectorAll('.time-slot-group').forEach((group, index) => {
                group.querySelector('input[name^="time_slots"]').name = `time_slots[${index}][start]`;
                group.querySelector('input[name$="end]"]').name = `time_slots[${index}][end]`;
            });
        } else {
            alert('Vous devez avoir au moins un créneau horaire.');
        }
    }
    
    <?php if ($type === 'exception' && !empty($suggestedTimeSlots)) : ?>
    function useSuggestedSlots() {
        const container = document.getElementById('time_slots');
        container.innerHTML = '';
        
        <?php foreach ($suggestedTimeSlots as $index => $slot) : ?>
            const slotGroup<?= $index ?> = document.createElement('div');
            slotGroup<?= $index ?>.className = 'time-slot-group';
            slotGroup<?= $index ?>.innerHTML = `
                <div class="time-inputs">
                    <input type="time" name="time_slots[<?= $index ?>][start]" 
                           class="time-input" value="<?= $slot['start'] ?>" required>
                    <span class="time-separator">-</span>
                    <input type="time" name="time_slots[<?= $index ?>][end]" 
                           class="time-input" value="<?= $slot['end'] ?>" required>
                </div>
                <button type="button" class="remove-slot" onclick="removeTimeSlot(this)">
                    <i data-lucide="x"></i>
                </button>
            `;
            container.appendChild(slotGroup<?= $index ?>);
        <?php endforeach; ?>
        
        lucide.createIcons();
    }
    <?php endif; ?>
    
    // Gestion de l'affichage des créneaux quand "Fermé" est coché
    document.getElementById('is_closed')?.addEventListener('change', function() {
        document.getElementById('time_slots_container').style.display = 
            this.checked ? 'none' : 'block';
    });
</script>

@push('scripts')
<script>
    // Gestion des créneaux horaires
    function addTimeSlot() {
        const container = document.getElementById('time_slots');
        const index = container.children.length;
        
        const slotGroup = document.createElement('div');
        slotGroup.className = 'time-slot-group';
        slotGroup.innerHTML = `
            <div class="time-inputs">
                <input type="time" name="time_slots[${index}][start]" class="time-input" required>
                <span class="time-separator">-</span>
                <input type="time" name="time_slots[${index}][end]" class="time-input" required>
            </div>
            <button type="button" class="remove-slot" onclick="removeTimeSlot(this)">
                <i data-lucide="x"></i>
            </button>
        `;
        
        container.appendChild(slotGroup);
        lucide.createIcons();
    }
    
    function removeTimeSlot(button) {
        if (document.querySelectorAll('.time-slot-group').length > 1) {
            button.closest('.time-slot-group').remove();
            // Reindexer les noms des champs
            document.querySelectorAll('.time-slot-group').forEach((group, index) => {
                group.querySelector('input[name^="time_slots"]').name = `time_slots[${index}][start]`;
                group.querySelector('input[name$="end]"]').name = `time_slots[${index}][end]`;
            });
        } else {
            alert('Vous devez avoir au moins un créneau horaire.');
        }
    }
    
    @if($type === 'exception' && !empty($suggestedTimeSlots))
    function useSuggestedSlots() {
        const container = document.getElementById('time_slots');
        container.innerHTML = '';
        
        @foreach($suggestedTimeSlots as $index => $slot)
            const slotGroup{{ $index }} = document.createElement('div');
            slotGroup{{ $index }}.className = 'time-slot-group';
            slotGroup{{ $index }}.innerHTML = `
                <div class="time-inputs">
                    <input type="time" name="time_slots[{{ $index }}][start]" 
                           class="time-input" value="{{ $slot['start'] }}" required>
                    <span class="time-separator">-</span>
                    <input type="time" name="time_slots[{{ $index }}][end]" 
                           class="time-input" value="{{ $slot['end'] }}" required>
                </div>
                <button type="button" class="remove-slot" onclick="removeTimeSlot(this)">
                    <i data-lucide="x"></i>
                </button>
            `;
            container.appendChild(slotGroup{{ $index }});
        @endforeach
        
        lucide.createIcons();
    }
    @endif
    
    // Gestion de l'affichage des créneaux quand "Fermé" est coché
    document.getElementById('is_closed')?.addEventListener('change', function() {
        document.getElementById('time_slots_container').style.display = 
            this.checked ? 'none' : 'block';
    });
</script>
@endpush

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
        justify-content: space-between;
        align-items: center;
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

    .delete-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .delete-btn:hover {
        background-color: #c82333;
        transform: translateY(-1px);
    }

    .error-message {
        color: var(--error-color);
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .suggested-slots {
        margin-bottom: 1rem;
        padding: 0.75rem;
        background-color: #f8f9fa;
        border: 1px dashed var(--border-color);
        border-radius: 4px;
    }
    
    .suggested-slots p {
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        color: #666;
    }
    
    .suggested-slots-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .use-suggested-slots {
        padding: 0.5rem 0.75rem;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    
    .use-suggested-slots:hover {
        background-color: #6B2B00;
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
        
        .submit-btn, .delete-btn {
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
        
        if (isClosedCheckbox && timeSlotsContainer) {
            isClosedCheckbox.addEventListener('change', function() {
                timeSlotsContainer.style.display = this.checked ? 'none' : 'block';
                
                // Gérer les attributs required des champs de temps
                const timeInputs = timeSlotsContainer.querySelectorAll('input[type="time"]');
                timeInputs.forEach(input => {
                    input.required = !this.checked;
                });
            });
        }
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
    
    function useSuggestedSlots() {
        // Supprimer tous les créneaux existants
        const container = document.getElementById('time_slots');
        while (container.firstChild) {
            container.removeChild(container.firstChild);
        }
        
        // Ajouter les créneaux suggérés
        @if(isset($suggestedTimeSlots) && !empty($suggestedTimeSlots))
            @foreach($suggestedTimeSlots as $index => $slot)
                const timeSlotGroup = document.createElement('div');
                timeSlotGroup.className = 'time-slot-group';
                
                timeSlotGroup.innerHTML = `
                    <div class="time-inputs">
                        <input type="time" name="time_slots[${@json($index)}][start]" class="time-input" value="{{ $slot['start'] }}" required>
                        <span class="time-separator">-</span>
                        <input type="time" name="time_slots[${@json($index)}][end]" class="time-input" value="{{ $slot['end'] }}" required>
                    </div>
                    <button type="button" class="remove-slot" onclick="removeTimeSlot(this)">
                        <i data-lucide="x"></i>
                    </button>
                `;
                
                container.appendChild(timeSlotGroup);
            @endforeach
        @endif
        
        lucide.createIcons();
    }
</script>
@endsection
