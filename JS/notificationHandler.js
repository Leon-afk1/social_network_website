// Fonction asynchrone pour supprimer une notification spécifique
async function deleteNotification(id) {
  var ajax = await fetch("./AJAX/deleteNotification.php?id=" + id); // Effectuer la requête de suppression de la notification
  var response = await ajax.text(); // Récupérer la réponse de la requête
  // Supprimer les espaces blancs et les retours à la ligne supplémentaires de la réponse
  response = response.trim();
  if (response === "success") {
      var notification = document.getElementById(id);
      notification.remove(); // Supprimer la notification de l'interface utilisateur
  } else {
      alert(response); // Afficher une alerte en cas d'erreur
  }
}

// Fonction asynchrone pour supprimer toutes les notifications d'un utilisateur
async function deleteAllNotifications(id) {
  var ajax = await fetch("./AJAX/deleteAllNotifications.php?idUser=" + id); // Effectuer la requête de suppression de toutes les notifications
  var response = await ajax.text(); // Récupérer la réponse de la requête
  response = response.trim();
  if (response === "success") {
      console.log("Suppression réussie !"); // Afficher un message de confirmation dans la console
      var notif = document.getElementById("allNotifications");
      notif.style.display = "none"; // Masquer l'élément contenant toutes les notifications
  } else {
      alert(response); // Afficher une alerte en cas d'erreur
  }
}



