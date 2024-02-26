(function($) {
  "use strict"; // Start of use strict

  // Vos autres scripts ici...

})(jQuery); // Fin de l'utilisation stricte

// Fonction pour afficher la notification
function showNotification(message) {
  var notification = document.createElement('div');
  notification.classList.add('notification');
  notification.textContent = message;
  document.body.appendChild(notification);

  // Supprimer la notification après quelques secondes
  setTimeout(function() {
    notification.remove();
  }, 5000); // 5000 millisecondes = 5 secondes
}

// Afficher une notification de succès lorsque le rendez-vous est ajouté avec succès
showNotification("Le rendez-vous a été ajouté avec succès.");
