// Fonctions pour la gestion des détails des pivots

// Fonction pour afficher les détails d'un pivot
function selectPivot(pivotName) {
  const pivotCards = document.querySelectorAll('.pivot-card');
  pivotCards.forEach(card => {
    const name = card.querySelector('.pivot-name').textContent;
    const etat = getEtatPivot(name);

    card.classList.remove('active', 'probleme');

    if (name === pivotName) {
      card.classList.add('active');
    }

    if (etat === "probleme") {
      card.classList.add('probleme');
    }
  });

  // Récupérer les détails du pivot
  const pivotDetails = JSON.parse(localStorage.getItem('pivotDetails')) || {};
  const pivotDetail = pivotDetails[pivotName] || {
    name: pivotName,
    surface: 4,
    crop: 'Non spécifié',
    location: 'Béchar - Secteur Sud',
    notes: '',
    createdAt: new Date().toISOString()
  };

  // Récupérer les tâches du pivot
  const events = JSON.parse(localStorage.getItem('agricultureEvents')) || [];
  const pivotTasks = events.filter(event => event.pivot === pivotName);

  // Générer le HTML pour les tâches
  let tasksHtml = '';
  if (pivotTasks.length > 0) {
    tasksHtml = `
      <table class="table table-sm table-bordered mt-3">
        <thead class="table-light">
          <tr>
            <th>Date</th>
            <th>Heure</th>
            <th>Tâche</th>
            <th>Catégorie</th>
          </tr>
        </thead>
        <tbody>
    `;
    pivotTasks.forEach(task => {
      const taskDate = new Date(task.date);
      const formattedDate = taskDate.toLocaleDateString('fr-FR');
      const formattedTime = task.category === 'irrigation'
        ? `${task.startTime} - ${task.endTime}`
        : 'Toute la journée';

      tasksHtml += `
        <tr>
          <td>${formattedDate}</td>
          <td>${formattedTime}</td>
          <td>${task.title}</td>
          <td>${getCategoryLabel(task.category)}</td>
        </tr>
      `;
    });
    tasksHtml += `</tbody></table>`;
  } else {
    tasksHtml = `<p class="text-muted mt-3">Aucune tâche planifiée pour ce pivot.</p>`;
  }

  // État technique et d'irrigation
  let etatPivot = getEtatPivot(pivotName);
  let etatIrrigation = getEtatIrrigation(pivotName);

  let etatTechText = etatPivot === "bon"
    ? '<span class="text-success"><i class="fas fa-check-circle me-1"></i>Bon état</span>'
    : '<span class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>Problème technique</span>';

  let etatIrrigText = etatIrrigation === "active"
    ? '<span class="text-success"><i class="fas fa-play me-1"></i>En cours d\'irrigation</span>'
    : '<span class="text-secondary"><i class="fas fa-pause me-1"></i>Irrigation désactivée</span>';

  const bgEtatColor = etatPivot === "bon" ? "#e6f4ea" : "#f8d7da";
  const borderEtatColor = etatPivot === "bon" ? "#c3e6cb" : "#f5c6cb";

  // Formater la date de création
  const createdDate = new Date(pivotDetail.createdAt);
  const formattedCreatedDate = createdDate.toLocaleDateString('fr-FR');

  // Calculer la dernière utilisation (date la plus récente parmi les tâches)
  let lastUsageDate = new Date().toLocaleDateString('fr-FR');
  if (pivotTasks.length > 0) {
    const sortedTasks = [...pivotTasks].sort((a, b) => new Date(b.date) - new Date(a.date));
    if (sortedTasks[0]) {
      lastUsageDate = new Date(sortedTasks[0].date).toLocaleDateString('fr-FR');
    }
  }

  // --- SweetAlert Popup ---
  Swal.fire({
    title: `<h5 class="text-primary mb-2"><i class="fas fa-tint me-2"></i>${pivotName}</h5>`,
    html: `
      <div class="text-start text-muted" style="font-size: 0.95rem;">
        <div class="mb-3 p-3 rounded border" style="background-color: #e6f4ea; border: 1px solid #c3e6cb">
          <strong class="text-dark d-block mb-2">📍 Informations générales</strong>
          <div><i class="fas fa-map-marker-alt me-2 text-info"></i> Localisation: ${pivotDetail.location}</div>
          <div><i class="fas fa-ruler-combined me-2 text-success"></i> Surface: ${pivotDetail.surface} hectares</div>
          <div><i class="fas fa-seedling me-2 text-primary"></i> Culture: ${pivotDetail.crop}</div>
          <div><i class="fas fa-calendar-alt me-2 text-secondary"></i> Créé le: ${formattedCreatedDate}</div>
          <div><i class="fas fa-clock me-2 text-muted"></i> Dernière utilisation: ${lastUsageDate}</div>
          ${pivotDetail.notes ? `<div class="mt-2 p-2 bg-light rounded"><i class="fas fa-sticky-note me-2 text-warning"></i> Notes: ${pivotDetail.notes}</div>` : ''}
        </div>

        <div class="mb-3 p-3 rounded" style="background-color: ${bgEtatColor}; border: 1px solid ${borderEtatColor}">
          <strong class="text-dark d-block mb-2">⚙️ État technique</strong>
          <div id="etat-pivot" class="mb-2">${etatTechText}</div>
          <button id="btn-changer-etat" class="btn btn-sm btn-outline-${etatPivot === 'bon' ? 'success' : 'danger'}">
            <i class="fas fa-exchange-alt me-1"></i> Changer l'état
          </button>
        </div>

        <div class="mb-3 p-3 rounded border" style="background-color: #e6f4ea;">
          <strong class="text-dark d-block mb-2">💧 État d'irrigation</strong>
          <div id="etat-irrigation" class="mb-2">${etatIrrigText}</div>
          <button id="btn-changer-irrigation" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-power-off me-1"></i> Activer / Désactiver
          </button>
        </div>

        <div class="mb-3 p-3 rounded border" style="background-color: #e6f4ea;">
          <strong class="text-dark d-block mb-2">📊 Capteurs en temps réel</strong>
          <div class="row">
            <div class="col-6">
              <div class="mb-2"><i class="fas fa-temperature-high me-2 text-danger"></i> Température du sol: <span id="temp-sol" class="fw-bold">--</span> °C</div>
              <div><i class="fas fa-tint me-2 text-primary"></i> Humidité du sol: <span id="humid-sol" class="fw-bold">--</span> %</div>
            </div>
            <div class="col-6">
              <div class="mb-2"><i class="fas fa-sun me-2 text-warning"></i> Ensoleillement: <span id="sun-exposure" class="fw-bold">--</span> %</div>
              <div><i class="fas fa-wind me-2 text-info"></i> Vent: <span id="wind-speed" class="fw-bold">--</span> km/h</div>
            </div>
          </div>
        </div>

        <div class="mt-4">
          <h6 class="text-dark mb-3"><i class="fas fa-tasks me-2"></i> Historique de planification</h6>
          ${tasksHtml}
        </div>

        <div class="mt-3 d-flex justify-content-end">
          <button id="btn-edit-pivot" class="btn btn-sm btn-outline-primary me-2">
            <i class="fas fa-edit me-1"></i> Modifier
          </button>
          <button id="btn-plan-task" class="btn btn-sm btn-success" onclick="window.location.href='planification.html'">
            <i class="fas fa-calendar-plus me-1"></i> Planifier une tâche
          </button>
        </div>
      </div>
    `,
    showCloseButton: true,
    confirmButtonText: 'Fermer',
    customClass: {
      popup: 'border rounded shadow',
      confirmButton: 'btn btn-sm btn-primary'
    },
    width: 650,
    background: '#fff',
    focusConfirm: false,

    didOpen: () => {
      // Fonction pour mettre à jour les données des capteurs
      function updateSensorData() {
        document.getElementById('temp-sol').textContent = getRandomValue(intervalleCapteurs.tempMin, intervalleCapteurs.tempMax);
        document.getElementById('humid-sol').textContent = getRandomValue(intervalleCapteurs.humidMin, intervalleCapteurs.humidMax);
        document.getElementById('sun-exposure').textContent = getRandomValue(50, 95);
        document.getElementById('wind-speed').textContent = getRandomValue(0, 25);
      }

      // Mettre à jour les données des capteurs immédiatement et toutes les 10 secondes
      updateSensorData();
      const intervalId = setInterval(updateSensorData, 10000);

      // Nettoyer l'intervalle lorsque la popup est fermée
      Swal.getPopup().addEventListener('mouseleave', () => clearInterval(intervalId));
      Swal.getCloseButton().addEventListener('click', () => clearInterval(intervalId));

      // Gérer le changement d'état technique
      document.getElementById('btn-changer-etat').addEventListener('click', () => {
        // Inverser l'état
        etatPivot = (etatPivot === "bon") ? "probleme" : "bon";
        localStorage.setItem(`etat_${pivotName}`, etatPivot);

        // Mettre à jour le texte
        const newText = etatPivot === "bon"
          ? '<span class="text-success"><i class="fas fa-check-circle me-1"></i>Bon état</span>'
          : '<span class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>Problème technique</span>';
        document.getElementById('etat-pivot').innerHTML = newText;

        // Changer la couleur de la section
        const sectionEtat = document.getElementById('etat-pivot').parentElement;
        sectionEtat.style.backgroundColor = (etatPivot === "bon") ? "#e6f4ea" : "#f8d7da";
        sectionEtat.style.border = (etatPivot === "bon") ? "1px solid #c3e6cb" : "1px solid #f5c6cb";

        // Changer la couleur du bouton
        document.getElementById('btn-changer-etat').className =
          `btn btn-sm btn-outline-${etatPivot === 'bon' ? 'success' : 'danger'}`;

        // Mettre à jour la carte du pivot
        const card = [...document.querySelectorAll('.pivot-card')].find(card =>
          card.querySelector('.pivot-name').textContent === pivotName
        );
        if (card) {
          card.classList.toggle('probleme', etatPivot === "probleme");
        }
      });

      // Gérer le changement d'état d'irrigation
      document.getElementById('btn-changer-irrigation').addEventListener('click', () => {
        etatIrrigation = (etatIrrigation === "active") ? "desactive" : "active";
        localStorage.setItem(`irrigation_${pivotName}`, etatIrrigation);

        const newText = etatIrrigation === "active"
          ? '<span class="text-success"><i class="fas fa-play me-1"></i>En cours d\'irrigation</span>'
          : '<span class="text-secondary"><i class="fas fa-pause me-1"></i>Irrigation désactivée</span>';

        document.getElementById('etat-irrigation').innerHTML = newText;
      });

      // Gérer le bouton de modification du pivot
      document.getElementById('btn-edit-pivot').addEventListener('click', () => {
        // Fermer la popup actuelle
        Swal.close();

        // Ouvrir le formulaire de modification
        editPivot(pivotName);
      });
    }
  });
}

// Fonction pour modifier un pivot existant
function editPivot(pivotName) {
  // Liste des cultures disponibles
  const cropTypes = [
    'Maïs',
    'Blé',
    'Orge',
    'Tomates',
    'Pommes de terre',
    'Oignons',
    'Carottes',
    'Luzerne',
    'Palmiers dattiers',
    'Agrumes',
    'Oliviers',
    'Autre'
  ];

  // Récupérer les détails du pivot
  const pivotDetails = JSON.parse(localStorage.getItem('pivotDetails')) || {};
  const pivotDetail = pivotDetails[pivotName] || {
    name: pivotName,
    surface: 4,
    crop: 'Non spécifié',
    location: 'Béchar - Secteur Sud',
    notes: '',
    createdAt: new Date().toISOString()
  };

  // Créer les options pour le sélecteur de cultures
  const cropOptions = cropTypes.map(crop =>
    `<option value="${crop}" ${pivotDetail.crop === crop ? 'selected' : ''}>${crop}</option>`
  ).join('');

  Swal.fire({
    title: '<h5 class="text-primary mb-3"><i class="fas fa-edit me-2"></i>Modifier le pivot</h5>',
    html: `
      <form id="editPivotForm" class="text-start">
        <div class="mb-3">
          <label for="pivotName" class="form-label fw-bold">Nom du pivot <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="pivotName" value="${pivotDetail.name}" required>
          <div class="invalid-feedback">Veuillez entrer un nom pour le pivot</div>
        </div>

        <div class="mb-3">
          <label for="pivotSurface" class="form-label fw-bold">Surface (hectares) <span class="text-danger">*</span></label>
          <input type="number" class="form-control" id="pivotSurface" value="${pivotDetail.surface}" min="0.1" step="0.1" required>
          <div class="invalid-feedback">Veuillez entrer une surface valide</div>
        </div>

        <div class="mb-3">
          <label for="pivotCrop" class="form-label fw-bold">Culture <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="pivotCrop" value="${pivotDetail.crop}" placeholder="Ex: Maïs, Blé, Tomates..." list="cropSuggestions" required>
          <datalist id="cropSuggestions">
            ${cropOptions}
          </datalist>
          <div class="invalid-feedback">Veuillez indiquer la culture</div>
        </div>

        <div class="mb-3">
          <label for="pivotLocation" class="form-label fw-bold">Localisation</label>
          <input type="text" class="form-control" id="pivotLocation" value="${pivotDetail.location}">
        </div>

        <div class="mb-3">
          <label for="pivotNotes" class="form-label fw-bold">Notes</label>
          <textarea class="form-control" id="pivotNotes" rows="2">${pivotDetail.notes}</textarea>
        </div>
      </form>
    `,
    showCancelButton: true,
    confirmButtonColor: '#88c9a1',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Enregistrer',
    cancelButtonText: 'Annuler',
    focusConfirm: false,
    preConfirm: () => {
      // Récupérer les valeurs du formulaire
      const name = document.getElementById('pivotName').value.trim();
      const surface = document.getElementById('pivotSurface').value;
      const crop = document.getElementById('pivotCrop').value;
      const location = document.getElementById('pivotLocation').value.trim();
      const notes = document.getElementById('pivotNotes').value.trim();

      // Valider les champs obligatoires
      let isValid = true;

      if (!name) {
        document.getElementById('pivotName').classList.add('is-invalid');
        isValid = false;
      } else {
        document.getElementById('pivotName').classList.remove('is-invalid');
      }

      if (!surface || isNaN(surface) || parseFloat(surface) <= 0) {
        document.getElementById('pivotSurface').classList.add('is-invalid');
        isValid = false;
      } else {
        document.getElementById('pivotSurface').classList.remove('is-invalid');
      }

      if (!crop) {
        document.getElementById('pivotCrop').classList.add('is-invalid');
        isValid = false;
      } else {
        document.getElementById('pivotCrop').classList.remove('is-invalid');
      }

      if (!isValid) {
        return false;
      }

      // Retourner les données du pivot
      return {
        name: name,
        surface: parseFloat(surface),
        crop: crop,
        location: location || 'Non spécifié',
        notes: notes || '',
        createdAt: pivotDetail.createdAt // Conserver la date de création originale
      };
    }
  }).then((result) => {
    if (result.isConfirmed && result.value) {
      // Récupérer les données des pivots existants
      const pivotDetails = JSON.parse(localStorage.getItem('pivotDetails')) || {};

      // Si le nom a changé, mettre à jour la liste des pivots
      if (result.value.name !== pivotName) {
        const pivotIndex = pivots.indexOf(pivotName);
        if (pivotIndex !== -1) {
          pivots[pivotIndex] = result.value.name;
        }

        // Supprimer l'ancien pivot et ajouter le nouveau
        delete pivotDetails[pivotName];
      }

      // Sauvegarder les détails du pivot
      pivotDetails[result.value.name] = result.value;

      // Sauvegarder dans localStorage
      localStorage.setItem('pivotDetails', JSON.stringify(pivotDetails));
      savePivots();

      // Mettre à jour les tâches si le nom du pivot a changé
      if (result.value.name !== pivotName) {
        const events = JSON.parse(localStorage.getItem('agricultureEvents')) || [];
        const updatedEvents = events.map(event => {
          if (event.pivot === pivotName) {
            return { ...event, pivot: result.value.name };
          }
          return event;
        });
        localStorage.setItem('agricultureEvents', JSON.stringify(updatedEvents));
      }

      showAlert('success', `Pivot "${result.value.name}" modifié avec succès!`);

      // Réouvrir la vue détaillée du pivot avec le nouveau nom
      setTimeout(() => selectPivot(result.value.name), 500);
    }
  });
}
