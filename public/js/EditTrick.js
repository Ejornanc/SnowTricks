document.addEventListener('DOMContentLoaded', () => {
    // Gestionnaire pour les boutons d'édition
    document.querySelectorAll('.edit-media-button').forEach(button => {
        button.addEventListener('click', () => {
            const targetSelector = button.getAttribute('data-target');
            const field = document.querySelector(targetSelector);
            if (field) {
                // Si c'est un input file, déclencher le clic
                if (field.type === 'file') {
                    field.click();
                } else {
                    // Sinon basculer l'affichage
                    field.style.display = (field.style.display === 'block') ? 'none' : 'block';
                    field.focus();
                }
            } else {
                console.error('Target input not found for selector:', targetSelector);
            }
        });
    });

    // Gestionnaire pour les boutons de suppression avec confirmation
    document.querySelectorAll('.delete-media-button').forEach(button => {
        button.addEventListener('click', () => {
            const confirmed = confirm('Voulez-vous vraiment supprimer cette image/vidéo ?');
            if (!confirmed) {
                return; // Annuler si l'utilisateur refuse
            }

            // Trouver l'élément parent media-item-edit
            const mediaItem = button.closest('.media-item-edit');
            if (mediaItem) {
                // Cacher l'élément
                mediaItem.style.display = 'none';

                // Trouver le champ de formulaire associé et le marquer pour suppression
                const formField = mediaItem.querySelector('input[type="file"], input[type="hidden"]');
                if (formField) {
                    // Vider ou marquer le champ selon ton backend
                    formField.value = '';
                }
            }
        });
    });
});
