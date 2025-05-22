// Créez ce fichier pour gérer le chargement de la modal
document.addEventListener("DOMContentLoaded", () => {
  const bulkActionBtn = document.getElementById("bulk-action-btn")
  const modalContainer = document.getElementById("modal-container")

  if (bulkActionBtn) {
    bulkActionBtn.addEventListener("click", () => {
      // Charger la modal via AJAX
      fetch("/admin/stores/bulk-schedule-modal")
        .then((response) => {
          if (!response.ok) {
            throw new Error("Erreur lors du chargement de la modal: " + response.status)
          }
          return response.text()
        })
        .then((html) => {
          // Insérer le HTML de la modal dans le conteneur
          modalContainer.innerHTML = html

          // Afficher la modal
          const modal = document.getElementById("bulk-schedule-modal")
          if (modal) {
            modal.style.display = "block"

            // Mettre à jour la liste des magasins sélectionnés
            updateSelectedStoresList()

            // Ajouter l'écouteur pour fermer la modal
            const closeModal = modal.querySelector(".close-modal")
            if (closeModal) {
              closeModal.addEventListener("click", () => {
                modal.style.display = "none"
              })
            }

            // Fermer la modal en cliquant en dehors
            window.addEventListener("click", (event) => {
              if (event.target === modal) {
                modal.style.display = "none"
              }
            })

            // Initialiser les événements de la modal
            initModalEvents()
          }
        })
        .catch((error) => {
          console.error("Erreur:", error)
          alert("Erreur lors du chargement de la modal. Veuillez réessayer.")
        })
    })
  }

  // Fonction pour mettre à jour la liste des magasins sélectionnés dans la modal
  function updateSelectedStoresList() {
    const checkedStores = document.querySelectorAll(".store-selector:checked")
    const selectedCount = document.getElementById("selected-count")
    const selectedStoresList = document.getElementById("selected-stores-list")
    const selectedStoresInputs = document.getElementById("selected-stores-inputs")

    if (selectedCount && selectedStoresList && selectedStoresInputs) {
      selectedCount.textContent = checkedStores.length
      selectedStoresList.innerHTML = ""
      selectedStoresInputs.innerHTML = ""

      checkedStores.forEach((checkbox) => {
        const storeId = checkbox.dataset.storeId
        const storeName = checkbox.closest(".store-item").querySelector(".store-item-name").textContent

        // Ajouter un tag pour chaque magasin sélectionné
        const storeTag = document.createElement("div")
        storeTag.className = "selected-store-tag"
        storeTag.innerHTML = `
                    <span>${storeName}</span>
                    <span class="remove-tag" data-store-id="${storeId}">&times;</span>
                `
        selectedStoresList.appendChild(storeTag)

        // Ajouter un input caché pour chaque magasin sélectionné
        const storeInput = document.createElement("input")
        storeInput.type = "hidden"
        storeInput.name = "store_ids[]"
        storeInput.value = storeId
        selectedStoresInputs.appendChild(storeInput)

        // Ajouter un événement pour supprimer le tag
        storeTag.querySelector(".remove-tag").addEventListener("click", function () {
          const storeId = this.dataset.storeId
          const checkbox = document.querySelector(`.store-selector[data-store-id="${storeId}"]`)
          if (checkbox) {
            checkbox.checked = false
          }
          updateSelectedStoresList()
        })
      })
    }
  }

  // Initialiser les événements de la modal
  function initModalEvents() {
    // Gestion des types d'action
    const actionTypes = document.querySelectorAll('input[name="action_type"]')
    const regularScheduleSection = document.getElementById("regular-schedule-section")
    const exceptionSection = document.getElementById("exception-section")
    const temporaryClosureSection = document.getElementById("temporary-closure-section")
    const holidaySection = document.getElementById("holiday-section")

    actionTypes.forEach((radio) => {
      radio.addEventListener("change", function () {
        // Masquer toutes les sections
        regularScheduleSection.style.display = "none"
        exceptionSection.style.display = "none"
        temporaryClosureSection.style.display = "none"
        holidaySection.style.display = "none"

        // Afficher la section correspondante
        switch (this.value) {
          case "regular_schedule":
            regularScheduleSection.style.display = "block"
            break
          case "exception":
            exceptionSection.style.display = "block"
            break
          case "temporary_closure":
            temporaryClosureSection.style.display = "block"
            break
          case "holiday":
            holidaySection.style.display = "block"
            break
        }
      })
    })

    // Gestion des créneaux horaires
    setupTimeSlots()
  }

  // Configuration des créneaux horaires
  function setupTimeSlots() {
    // Ajouter des créneaux horaires
    const addTimeSlotBtn = document.getElementById("add-time-slot")
    if (addTimeSlotBtn) {
      addTimeSlotBtn.addEventListener("click", () => {
        addTimeSlot("time-slots-container")
      })
    }

    const addExceptionTimeSlotBtn = document.getElementById("add-exception-time-slot")
    if (addExceptionTimeSlotBtn) {
      addExceptionTimeSlotBtn.addEventListener("click", () => {
        addTimeSlot("exception-time-slots-container")
      })
    }

    const addAfterClosureSlotBtn = document.getElementById("add-after-closure-slot")
    if (addAfterClosureSlotBtn) {
      addAfterClosureSlotBtn.addEventListener("click", () => {
        addTimeSlot("after-closure-custom", "after_closure_slots")
      })
    }

    // Gestion de l'affichage des créneaux horaires en fonction de l'état "fermé"
    const isClosedRegular = document.getElementById("is_closed_regular")
    if (isClosedRegular) {
      isClosedRegular.addEventListener("change", function () {
        document.getElementById("time-slots-container").style.display = this.checked ? "none" : "block"
      })
    }

    const isClosedException = document.getElementById("is_closed_exception")
    if (isClosedException) {
      isClosedException.addEventListener("change", function () {
        document.getElementById("exception-time-slots-container").style.display = this.checked ? "none" : "block"
      })
    }

    // Gestion de l'affichage des créneaux horaires après fermeture temporaire
    const afterClosure = document.getElementById("after_closure")
    if (afterClosure) {
      afterClosure.addEventListener("change", function () {
        document.getElementById("after-closure-custom").style.display = this.value === "custom" ? "block" : "none"
      })
    }
  }

  // Fonction pour ajouter un créneau horaire
  function addTimeSlot(containerId, slotName = "time_slots") {
    const container = document.getElementById(containerId)
    if (!container) return

    const timeSlotsContainer = container.querySelector(".time-slots")
    const timeSlots = timeSlotsContainer.querySelectorAll(".time-slot")
    const newIndex = timeSlots.length

    const newTimeSlot = document.createElement("div")
    newTimeSlot.className = "time-slot"
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
        `

    timeSlotsContainer.appendChild(newTimeSlot)

    // Activer tous les boutons de suppression
    const removeButtons = timeSlotsContainer.querySelectorAll(".btn-remove-slot")
    removeButtons.forEach((button) => {
      button.disabled = removeButtons.length <= 1

      button.addEventListener("click", function () {
        this.closest(".time-slot").remove()

        // Mettre à jour les indices
        const updatedTimeSlots = timeSlotsContainer.querySelectorAll(".time-slot")
        updatedTimeSlots.forEach((slot, index) => {
          const startInput = slot.querySelector(`input[name*="[start]"]`)
          const endInput = slot.querySelector(`input[name*="[end]"]`)

          startInput.name = `${slotName}[${index}][start]`
          endInput.name = `${slotName}[${index}][end]`
        })

        // Désactiver le bouton de suppression s'il n'y a qu'un seul créneau
        const updatedRemoveButtons = timeSlotsContainer.querySelectorAll(".btn-remove-slot")
        updatedRemoveButtons.forEach((btn) => {
          btn.disabled = updatedRemoveButtons.length <= 1
        })
      })
    })
  }
})
