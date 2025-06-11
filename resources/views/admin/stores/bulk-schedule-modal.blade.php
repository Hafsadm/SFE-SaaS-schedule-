<!-- Modal pour la gestion des horaires en masse -->
<style>

   /* Styles pour le formulaire dans la modal */
    .form-section {
        
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border);
    }

    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .form-section h3 {
        color: #e9ecef;
        font-size: 1.2rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .form-section h4 {
        color: var(--secondary);
        font-size: 1rem;
        margin-bottom: 0.8rem;
        font-weight: 500;
    }

    .action-types {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .action-type {
        position: relative;
    }

    .action-type input[type="radio"] {
        display: none;
    }

    .action-type label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1.2rem 1rem;
        background-color: var(--light-beige);
        border: 1px solid var(--border);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        gap: 0.5rem;
        height: 100%;
        color: var(--text-dark);
    }

    .action-type label i {
        font-size: 1.5rem;
        color: var(--primary);
    }

    .action-type input[type="radio"]:checked + label {
        background-color: rgba(66, 145, 130, 0.1);
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(66, 145, 130, 0.2);
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .form-row .form-group {
        flex: 1;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--text-dark);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border);
        border-radius: 4px;
        font-size: 1rem;
        transition: all 0.3s ease;
        color: var(--text-dark);
        background-color: var(--text-light);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(66, 145, 130, 0.2);
    }

    .checkbox-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .checkbox-container input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--primary);
    }

    .checkbox-container label {
        margin-bottom: 0;
        cursor: pointer;
    }

    .time-slots {
        margin-bottom: 1rem;
    }

    .time-slot {
        display: flex;
        gap: 1rem;
        align-items: flex-end;
        margin-bottom: 1rem;
        padding: 1rem;
        background-color: var(--light-beige);
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .btn-remove-slot {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--error);
        color: var(--text-light);
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-remove-slot:hover {
        background-color: #c82333;
    }

    .btn-remove-slot:disabled {
        background-color: #e9ecef;
        color: #6c757d;
        cursor: not-allowed;
    }

    .btn-add-slot {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background-color: var(--light-beige);
        border: 1px dashed var(--border);
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
        color: var(--text-dark);
    }

    .btn-add-slot:hover {
        background-color: rgba(66, 145, 130, 0.1);
        border-color: var(--primary);
    }

    .holiday-info {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        background-color: rgba(66, 145, 130, 0.1);
        border-radius: 8px;
    }

    .holiday-info i {
        color: var(--primary);
        font-size: 1.2rem;
        margin-top: 0.2rem;
    }

    .holiday-info p {
        margin: 0;
        line-height: 1.5;
        color: var(--text-dark);
    }

    .form-actions {
        margin-top: 1.5rem;
        text-align: center;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: var(--text-light);
        border: none;
        border-radius: 30px 0;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--card-shadow);
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #4BA793 0%, #2A6A7D 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(66, 145, 130, 0.3);
    }

    /* Styles pour la liste des magasins sélectionnés */
    .selected-stores-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
        max-height: 150px;
        overflow-y: auto;
        padding: 0.5rem;
        background-color: var(--light-beige);
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .selected-store-tag {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        background-color: var(--text-light);
        border: 1px solid var(--border);
        border-radius: 30px;
        font-size: 0.9rem;
        color: var(--text-dark);
    }

    .selected-store-tag .remove-tag {
        cursor: pointer;
        color: var(--error);
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .selected-store-tag .remove-tag:hover {
        color: #c82333;
    }


</style>
<div id="bulk-schedule-modal" class="modal" >
    <div class="modal-content" style="background-color: #fff;">
        <div class="modal-header">
            <h2 style="color:#fff ;">Gestion des horaires en masse</h2>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <form id="bulk-schedule-form" action="{{ route('admin.stores.apply-bulk-schedule') }}" method="POST">
                @csrf
                <div id="selected-stores-container">
                    <h3>Points de vente sélectionnés (<span id="selected-count">0</span>)</h3>
                    <div id="selected-stores-list" class="selected-stores-list"></div>
                    <div id="selected-stores-inputs"></div>
                </div>
                
                <div class="form-section">
                    <h3>Type d'action</h3>
                    <div class="action-types">
                        <div class="action-type">
                            <input type="radio" name="action_type" id="regular_schedule" value="regular_schedule" checked>
                            <label for="regular_schedule">
                                <i class="fas fa-calendar"></i>
                                <span>Horaire régulier</span>
                            </label>
                        </div>
                        
                        <div class="action-type">
                            <input type="radio" name="action_type" id="exception" value="exception">
                            <label for="exception">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Exception</span>
                            </label>
                        </div>
                        
                        <div class="action-type">
                            <input type="radio" name="action_type" id="temporary_closure" value="temporary_closure">
                            <label for="temporary_closure">
                                <i class="fas fa-clock"></i>
                                <span>Fermeture temporaire</span>
                            </label>
                        </div>
                        
                        <div class="action-type">
                            <input type="radio" name="action_type" id="holiday" value="holiday">
                            <label for="holiday">
                                <i class="fas fa-calendar-times"></i>
                                <span>Jour férié</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Section pour les horaires réguliers -->
                <div class="form-section" id="regular-schedule-section">
                    <h3>Configuration de l'horaire régulier</h3>
                    
                    <div class="form-group">
                        <label for="day_of_week">Jour de la semaine</label>
                        <select name="day_of_week" id="day_of_week" class="form-control">
                            <option value="monday">Lundi</option>
                            <option value="tuesday">Mardi</option>
                            <option value="wednesday">Mercredi</option>
                            <option value="thursday">Jeudi</option>
                            <option value="friday">Vendredi</option>
                            <option value="saturday">Samedi</option>
                            <option value="sunday">Dimanche</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-container">
                            <input type="checkbox" name="is_closed" id="is_closed_regular" value="1">
                            <label for="is_closed_regular">Fermé ce jour</label>
                        </div>
                    </div>
                    
                    <div id="time-slots-container">
                        <h4>Créneaux horaires</h4>
                        
                        <div class="time-slots">
                            <div class="time-slot">
                                <div class="form-group">
                                    <label>Ouverture</label>
                                    <input type="time" name="time_slots[0][start]" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Fermeture</label>
                                    <input type="time" name="time_slots[0][end]" class="form-control">
                                </div>
                                <button type="button" class="btn-remove-slot" disabled>
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <button type="button" id="add-time-slot" class="btn-add-slot">
                            <i class="fas fa-plus"></i>
                            Ajouter un créneau
                        </button>
                    </div>
                </div>
                
                <!-- Section pour les exceptions -->
                <div class="form-section" id="exception-section" style="display: none;">
                    <h3>Configuration de l'exception</h3>
                    
                    <div class="form-group">
                        <label for="exception_date">Date de l'exception</label>
                        <input type="date" name="exception_date" id="exception_date" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="exception_raison">Raison de l'exception</label>
                        <input type="text" name="exception_raison" id="exception_raison" class="form-control" placeholder="Ex: Inventaire, Travaux...">
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-container">
                            <input type="checkbox" name="is_closed" id="is_closed_exception" value="1">
                            <label for="is_closed_exception">Fermé ce jour</label>
                        </div>
                    </div>
                    
                    <div id="exception-time-slots-container">
                        <h4>Créneaux horaires exceptionnels</h4>
                        
                        <div class="time-slots">
                            <div class="time-slot">
                                <div class="form-group">
                                    <label>Ouverture</label>
                                    <input type="time" name="time_slots[0][start]" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Fermeture</label>
                                    <input type="time" name="time_slots[0][end]" class="form-control">
                                </div>
                                <button type="button" class="btn-remove-slot" disabled>
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <button type="button" id="add-exception-time-slot" class="btn-add-slot">
                            <i class="fas fa-plus"></i>
                            Ajouter un créneau
                        </button>
                    </div>
                </div>
                
                <!-- Section pour les fermetures temporaires -->
                <div class="form-section" id="temporary-closure-section" style="display: none;">
                    <h3>Configuration de la fermeture temporaire</h3>
                    
                    <div class="form-group">
                        <label for="closure_date">Date de fermeture</label>
                        <input type="date" name="closure_date" id="closure_date" class="form-control">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="closure_start_time">Heure de début</label>
                            <input type="time" name="closure_start_time" id="closure_start_time" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="closure_end_time">Heure de fin</label>
                            <input type="time" name="closure_end_time" id="closure_end_time" class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="closure_reason">Raison de la fermeture</label>
                        <input type="text" name="closure_reason" id="closure_reason" class="form-control" placeholder="Ex: Réunion d'équipe, Formation...">
                    </div>
                    
                    <div class="form-group">
                        <label for="after_closure">Après la fermeture temporaire</label>
                        <select name="after_closure" id="after_closure" class="form-control">
                            <option value="regular">Retour à l'horaire régulier</option>
                            <option value="closed">Rester fermé pour la journée</option>
                            <option value="custom">Horaire personnalisé</option>
                        </select>
                    </div>
                    
                    <div id="after-closure-custom" style="display: none;">
                        <h4>Horaire après la fermeture</h4>
                        
                        <div class="time-slots">
                            <div class="time-slot">
                                <div class="form-group">
                                    <label>Ouverture</label>
                                    <input type="time" name="after_closure_slots[0][start]" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Fermeture</label>
                                    <input type="time" name="after_closure_slots[0][end]" class="form-control">
                                </div>
                                <button type="button" class="btn-remove-slot" disabled>
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <button type="button" id="add-after-closure-slot" class="btn-add-slot">
                            <i class="fas fa-plus"></i>
                            Ajouter un créneau
                        </button>
                    </div>
                </div>
                
                <!-- Section pour les jours fériés -->
                <div class="form-section" id="holiday-section" style="display: none;">
                    <h3>Configuration du jour férié</h3>
                    
                    <div class="form-group">
                        <label for="holiday_date">Date du jour férié</label>
                        <input type="date" name="holiday_date" id="holiday_date" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="holiday_name">Nom du jour férié</label>
                        <input type="text" name="holiday_name" id="holiday_name" class="form-control" placeholder="Ex: Noël, Jour de l'An...">
                    </div>
                    
                    <div class="holiday-info">
                        <i class="fas fa-info-circle"></i>
                        <p>Les points de vente seront automatiquement fermés pendant les jours fériés.</p>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i>
                        Appliquer aux points de vente sélectionnés
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des types d'action
    const actionTypes = document.querySelectorAll('input[name="action_type"]');
    const regularScheduleSection = document.getElementById('regular-schedule-section');
    const exceptionSection = document.getElementById('exception-section');
    const temporaryClosureSection = document.getElementById('temporary-closure-section');
    const holidaySection = document.getElementById('holiday-section');
    
    actionTypes.forEach(radio => {
        radio.addEventListener('change', function() {
            // Masquer toutes les sections
            regularScheduleSection.style.display = 'none';
            exceptionSection.style.display = 'none';
            temporaryClosureSection.style.display = 'none';
            holidaySection.style.display = 'none';
            
            // Afficher la section correspondante
            switch (this.value) {
                case 'regular_schedule':
                    regularScheduleSection.style.display = 'block';
                    break;
                case 'exception':
                    exceptionSection.style.display = 'block';
                    break;
                case 'temporary_closure':
                    temporaryClosureSection.style.display = 'block';
                    break;
                case 'holiday':
                    holidaySection.style.display = 'block';
                    break;
            }
        });
    });
    
    // Gestion de l'affichage des créneaux horaires après fermeture temporaire
    const afterClosure = document.getElementById('after_closure');
    const afterClosureCustom = document.getElementById('after-closure-custom');
    
    if (afterClosure) {
        afterClosure.addEventListener('change', function() {
            afterClosureCustom.style.display = this.value === 'custom' ? 'block' : 'none';
        });
    }
    
    // Gestion des créneaux horaires pour les horaires réguliers
    const addTimeSlotBtn = document.getElementById('add-time-slot');
    if (addTimeSlotBtn) {
        addTimeSlotBtn.addEventListener('click', function() {
            addTimeSlot('time-slots-container');
        });
    }
    
    // Gestion des créneaux horaires pour les exceptions
    const addExceptionTimeSlotBtn = document.getElementById('add-exception-time-slot');
    if (addExceptionTimeSlotBtn) {
        addExceptionTimeSlotBtn.addEventListener('click', function() {
            addTimeSlot('exception-time-slots-container');
        });
    }
    
    // Gestion des créneaux horaires pour après fermeture
    const addAfterClosureSlotBtn = document.getElementById('add-after-closure-slot');
    if (addAfterClosureSlotBtn) {
        addAfterClosureSlotBtn.addEventListener('click', function() {
            addTimeSlot('after-closure-custom', 'after_closure_slots');
        });
    }
    
    // Fonction pour ajouter un créneau horaire
    function addTimeSlot(containerId, slotName = 'time_slots') {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        const timeSlotsContainer = container.querySelector('.time-slots');
        const timeSlots = timeSlotsContainer.querySelectorAll('.time-slot');
        const newIndex = timeSlots.length;
        
        const newTimeSlot = document.createElement('div');
        newTimeSlot.className = 'time-slot';
        newTimeSlot.innerHTML = `
            <div class="form-group">
                <label>Ouverture</label>
                <input type="time" name="${slotName}[${newIndex}][start]" class="form-control">
            </div>
            <div class="form-group">
                <label>Fermeture</label>
                <input type="time" name="${slotName}[${newIndex}][end]" class="form-control">
            </div>
            <button type="button" class="btn-remove-slot">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        timeSlotsContainer.appendChild(newTimeSlot);
        
        // Activer tous les boutons de suppression
        const removeButtons = timeSlotsContainer.querySelectorAll('.btn-remove-slot');
        removeButtons.forEach(button => {
            button.disabled = removeButtons.length <= 1;
            
            button.addEventListener('click', function() {
                this.closest('.time-slot').remove();
                
                // Mettre à jour les indices
                const updatedTimeSlots = timeSlotsContainer.querySelectorAll('.time-slot');
                updatedTimeSlots.forEach((slot, index) => {
                    const startInput = slot.querySelector(`input[name*="[start]"]`);
                    const endInput = slot.querySelector(`input[name*="[end]"]`);
                    
                    startInput.name = `${slotName}[${index}][start]`;
                    endInput.name = `${slotName}[${index}][end]`;
                });
                
                // Désactiver le bouton de suppression s'il n'y a qu'un seul créneau
                const updatedRemoveButtons = timeSlotsContainer.querySelectorAll('.btn-remove-slot');
                updatedRemoveButtons.forEach(btn => {
                    btn.disabled = updatedRemoveButtons.length <= 1;
                });
            });
        });
    }
    
    // Gestion de l'affichage des créneaux horaires en fonction de l'état "fermé"
    const isClosedRegular = document.getElementById('is_closed_regular');
    if (isClosedRegular) {
        isClosedRegular.addEventListener('change', function() {
            document.getElementById('time-slots-container').style.display = this.checked ? 'none' : 'block';
        });
    }
    
    const isClosedException = document.getElementById('is_closed_exception');
    if (isClosedException) {
        isClosedException.addEventListener('change', function() {
            document.getElementById('exception-time-slots-container').style.display = this.checked ? 'none' : 'block';
        });
    }
});
</script>
