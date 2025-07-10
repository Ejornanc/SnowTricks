document.addEventListener('DOMContentLoaded', () => {
    // Gestionnaire pour l'ajout d'images
    const imagesCollection = document.getElementById('images-collection');
    const addImageBtn = document.getElementById('add-image-btn');

    if (imagesCollection && addImageBtn) {
        // Calculer l'index en fonction des éléments existants
        let imageIndex = document.querySelectorAll('.media-item-edit').length;

        addImageBtn.addEventListener('click', function() {
            // Créer un nouvel élément media-item pour l'image
            const prototype = imagesCollection.dataset.prototype;
            const newFormHtml = prototype.replace(/__name__/g, imageIndex);

            // Créer le conteneur pour le nouvel élément
            const newMediaItem = document.createElement('div');
            newMediaItem.classList.add('media-item', 'media-item-edit', 'new-media-item');

            // Ajouter une image par défaut
            newMediaItem.innerHTML = `
                <img src="${window.location.origin}/Images/no_image_available.png" alt="Nouvelle image">

                <div class="media-actions">
                    <button type="button" class="edit-media-button" data-target="#image-field-new-${imageIndex}" title="Modifier">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black" viewBox="0 0 24 24">
                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1.003 1.003 0 0 0 0-1.41l-2.34-2.34a1.003 1.003 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"></path>
                        </svg>
                    </button>

                    <button type="button" class="delete-media-button" title="Supprimer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black" viewBox="0 0 24 24">
                            <path d="M3 6h18v2H3V6zm2 3h14v13H5V9zm5 2v9h2v-9H10zm4 0v9h2v-9h-2z"></path>
                        </svg>
                    </button>
                </div>

                <div id="image-field-new-${imageIndex}" style="display: block;">
                    ${newFormHtml}
                </div>
            `;

            // Ajouter le nouvel élément à la galerie
            document.querySelector('.media-gallery').appendChild(newMediaItem);

            // Mettre à jour les gestionnaires d'événements
            setupMediaButtonHandlers();
            setupFeaturedCheckboxes();
            setupDeleteButtonHandlers();

            imageIndex++;
        });
    }

    // Gestionnaire pour l'ajout de vidéos
    const videosCollection = document.getElementById('videos-collection');
    const addVideoBtn = document.getElementById('add-video-btn');

    if (videosCollection && addVideoBtn) {
        // Calculer l'index en fonction des éléments existants
        let videoIndex = document.querySelectorAll('.media-item-edit').length;

        addVideoBtn.addEventListener('click', function() {
            // Créer un nouvel élément media-item pour la vidéo
            const prototype = videosCollection.dataset.prototype;
            const newFormHtml = prototype.replace(/__name__/g, videoIndex);

            // Créer le conteneur pour le nouvel élément
            const newMediaItem = document.createElement('div');
            newMediaItem.classList.add('media-item', 'media-item-edit', 'new-media-item');

            // Ajouter une image par défaut pour la vidéo
            newMediaItem.innerHTML = `
                <img src="${window.location.origin}/Images/no_video_available.png" alt="Nouvelle vidéo">

                <div class="media-actions">
                    <button type="button" class="edit-media-button" data-target="#video-field-new-${videoIndex}" title="Modifier">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black" viewBox="0 0 24 24">
                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1.003 1.003 0 0 0 0-1.41l-2.34-2.34a1.003 1.003 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"></path>
                        </svg>
                    </button>

                    <button type="button" class="delete-media-button" title="Supprimer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black" viewBox="0 0 24 24">
                            <path d="M3 6h18v2H3V6zm2 3h14v13H5V9zm5 2v9h2v-9H10zm4 0v9h2v-9h-2z"></path>
                        </svg>
                    </button>
                </div>

                <div id="video-field-new-${videoIndex}" style="display: block;">
                    ${newFormHtml}
                </div>
            `;

            // Ajouter le nouvel élément à la galerie
            document.querySelector('.media-gallery').appendChild(newMediaItem);

            // Mettre à jour les gestionnaires d'événements
            setupMediaButtonHandlers();
            setupDeleteButtonHandlers();

            // Marquer la vidéo comme modifiée
            const isModifiedField = newMediaItem.querySelector('.video-is-modified');
            if (isModifiedField) {
                isModifiedField.value = '1';
            }

            videoIndex++;
        });
    }

    // Fonction pour configurer les gestionnaires d'événements pour les boutons de média
    function setupMediaButtonHandlers() {
        // Gestionnaire pour les boutons d'édition
        document.querySelectorAll('.edit-media-button').forEach(button => {
            button.addEventListener('click', () => {
                const targetSelector = button.getAttribute('data-target');
                const fieldContainer = document.querySelector(targetSelector);
                if (fieldContainer) {
                    // Basculer l'affichage du conteneur
                    fieldContainer.style.display = (fieldContainer.style.display === 'none' || fieldContainer.style.display === '') ? 'block' : 'none';

                    // Si c'est un conteneur d'image, trouver l'input file et le focus
                    const fileInput = fieldContainer.querySelector('input[type="file"]');
                    if (fileInput) {
                        fileInput.focus();
                    } else {
                        // Sinon, trouver le premier input/textarea et le focus
                        const input = fieldContainer.querySelector('input, textarea');
                        if (input) {
                            input.focus();
                        }
                    }
                } else {
                    console.error('Target container not found for selector:', targetSelector);
                }
            });
        });
    }

    // Ajouter des écouteurs d'événements pour les champs de vidéo
    function setupVideoInputHandlers() {
        document.querySelectorAll('.video-url-input').forEach(input => {
            input.addEventListener('input', function() {
                // Marquer la vidéo comme modifiée
                const container = this.closest('div[id^="video-field"]');
                if (container) {
                    const isModifiedField = container.querySelector('.video-is-modified');
                    if (isModifiedField) {
                        isModifiedField.value = '1';
                    }
                }
            });
        });
    }

    // Fonction pour configurer les checkboxes d'image à la une
    function setupFeaturedCheckboxes() {
        document.querySelectorAll('.featured-image-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    // Décocher toutes les autres checkboxes
                    document.querySelectorAll('.featured-image-checkbox').forEach(otherCheckbox => {
                        if (otherCheckbox !== this) {
                            otherCheckbox.checked = false;
                        }
                    });
                }
            });
        });
    }

    // Gestionnaire pour les boutons de suppression avec confirmation
    function setupDeleteButtonHandlers() {
        // D'abord, supprimer tous les gestionnaires d'événements existants
        document.querySelectorAll('.delete-media-button').forEach(button => {
            // Cloner le bouton pour supprimer tous les écouteurs d'événements
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);
        });

        // Ensuite, ajouter les nouveaux gestionnaires d'événements
        document.querySelectorAll('.delete-media-button').forEach(button => {
            button.addEventListener('click', function(e) {
                // Empêcher la propagation de l'événement
                e.stopPropagation();

                const confirmed = confirm('Voulez-vous vraiment supprimer cette image/vidéo ?');
                if (!confirmed) {
                    return; // Annuler si l'utilisateur refuse
                }

                // Trouver l'élément parent media-item-edit
                const mediaItem = this.closest('.media-item-edit');
                if (mediaItem) {
                    // Cacher l'élément
                    mediaItem.style.display = 'none';

                    // Trouver tous les champs de formulaire et les marquer pour suppression
                    const formFields = mediaItem.querySelectorAll('input[type="file"], input[type="hidden"], textarea');
                    formFields.forEach(field => {
                        // Pour les vidéos, marquer comme modifiée avant de vider
                        if (field.classList.contains('video-is-modified')) {
                            field.value = '1';
                        } else {
                            // Vider les autres champs
                            field.value = '';
                        }
                    });

                    // Réinitialiser les gestionnaires d'événements pour les boutons restants
                    setupMediaButtonHandlers();
                    setupFeaturedCheckboxes();
                }
            });
        });
    }

    // Initialiser tous les gestionnaires d'événements
    setupMediaButtonHandlers();
    setupVideoInputHandlers();
    setupFeaturedCheckboxes();
    setupDeleteButtonHandlers();
});
