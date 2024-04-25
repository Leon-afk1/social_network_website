// Déclaration des variables globales
var previousText2 = ""; // Stocke le texte précédent sa mise à jour
var timer2 = 0; // Compteur de temps pour limiter les requêtes

// Fonction pour incrémenter le compteur de temps
function TimerIncrease_fetch() {
  timer2 += 200; // Incrémente le compteur toutes les 200 millisecondes
  setTimeout('TimerIncrease_fetch()', 200); // Lance récursivement la fonction toutes les 200 ms
}
TimerIncrease_fetch(); // Appel initial de la fonction TimerIncrease_fetch

// Fonction asynchrone pour suggérer des éléments à partir de l'entrée actuelle
async function suggestFromInput_fetch(currentText) {
  // Vérifier si la recherche porte sur les utilisateurs ou les publications
  if (document.getElementById("rechercheUser").checked){
    suggestNamesFromInput_fetch(currentText); // Suggérer des noms d'utilisateurs
  } else {
    suggestPostsFromInput_fetch(currentText); // Suggérer des publications
  }
}

// Fonction asynchrone pour suggérer des publications à partir de l'entrée actuelle
async function suggestPostsFromInput_fetch(currentText) {
  // Récupérer l'élément de suggestion
  var autocomplete = document.getElementById('autocomplete');
  // Vérifier si le texte actuel diffère du texte précédent et si le compteur dépasse 200 ms
  if (currentText != previousText2 && timer2 >= 200 ){
    // Effectuer une requête fetch asynchrone pour récupérer les suggestions de publications
    var AJAXresult = await fetch("./AJAX/rechercherPost.php?var=" + currentText);
    // Mettre à jour la zone de suggestions avec les résultats de la requête
    document.getElementById("suggestions2").innerHTML = await AJAXresult.text();
    // Mettre à jour le texte précédent et réinitialiser le compteur
    previousText2 = currentText;
    timer2 = 0;
  } else {
    // Si les conditions ne sont pas remplies, effacer la zone de suggestions
    document.getElementById("suggestions2").innerHTML = ''; 
  }
}

// Fonction asynchrone pour suggérer des noms d'utilisateurs à partir de l'entrée actuelle
async function suggestNamesFromInput_fetch(currentText) {
  // Vérifier si le texte actuel diffère du texte précédent et si le compteur dépasse 200 ms
  if (currentText != previousText2 && timer2 >= 200 ){
    // Effectuer une requête fetch asynchrone pour récupérer les suggestions de noms d'utilisateurs
    var AJAXresult = await fetch("./AJAX/rechercherUser.php?var=" + currentText);
    // Mettre à jour la zone de suggestions avec les résultats de la requête
    document.getElementById("suggestions2").innerHTML = await AJAXresult.text();
    // Mettre à jour le texte précédent et réinitialiser le compteur
    previousText2 = currentText;
    timer2 = 0;
  } else {
    // Si les conditions ne sont pas remplies, effacer la zone de suggestions
    document.getElementById("suggestions2").innerHTML = ''; 
  }
}

// Fonction pour basculer l'affichage du formulaire de connexion
function toggleLoginForm() {
    var loginForm = document.getElementById("loginForm");
    var overlay = document.getElementById("overlay");
    var newLoginForm = document.getElementById("newLoginForm");
    var overlay1 = document.getElementById("overlay1");
    var mainContent = document.getElementById("mainContent"); 
    if (loginForm.style.display === "none") {
        loginForm.style.display = "block";
        overlay.style.display = "block";
        newLoginForm.style.display = "none";
        overlay1.style.display = "none";
        mainContent.classList.add("blur");
    } else {
        loginForm.style.display = "none";
        overlay.style.display = "none";
        mainContent.classList.remove("blur"); 
    }
}

// Fonction pour masquer le formulaire de connexion
function hideLoginForm(event) {
    var loginForm = document.getElementById("loginForm");
    var overlay = document.getElementById("overlay");
    var mainContent = document.getElementById("mainContent");
    if (!loginForm.contains(event.target)) {
        loginForm.style.display = "none";
        overlay.style.display = "none";
        mainContent.classList.remove("blur"); 
    }
}

// Fonction pour basculer l'affichage du nouveau formulaire de connexion
function toggleNewLoginForm() {
    var newLoginForm = document.getElementById("newLoginForm");
    var overlay = document.getElementById("overlay1");
    var loginForm = document.getElementById("loginForm");
    var overlay1 = document.getElementById("overlay");
    var mainContent = document.getElementById("mainContent"); 
    if (newLoginForm.style.display === "none") {
        newLoginForm.style.display = "block";
        overlay.style.display = "block";
        loginForm.style.display = "none";
        overlay1.style.display = "none";
        mainContent.classList.add("blur"); 
    } else {
        newLoginForm.style.display = "none";
        overlay.style.display = "none";
        mainContent.classList.remove("blur"); 
    }
}

// Fonction pour masquer le nouveau formulaire de connexion
function hideNewLoginForm(event) {
    var newLoginForm = document.getElementById("newLoginForm");
    var overlay = document.getElementById("overlay1");
    var mainContent = document.getElementById("mainContent");
    if (!newLoginForm.contains(event.target)) {
        newLoginForm.style.display = "none";
        overlay.style.display = "none";
        mainContent.classList.remove("blur"); 
    }
}

// Événement DOMContentLoaded pour exécuter la fonction toggleLoginForm ou toggleNewLoginForm si nécessaire
document.addEventListener("DOMContentLoaded", function() {
    if (executeToggleLoginFormIfNeeded) {
        toggleLoginForm();
    }
    if (executeToggleNewLoginFormIfNeeded) {
      toggleNewLoginForm();
  }
});


// Fonction pour mettre à jour l'affichage des notifications
function updateNotificationDisplay(nbNotifications) {
  var notifElement = document.getElementById("notif");
  // Vérifier si le nombre de notifications est nul
  if (nbNotifications === 0) {
      notifElement.style.display = "none"; // Masquer l'élément de notification
  } else {
      notifElement.style.display = "block"; // Afficher l'élément de notification
  }
}

function submitForm() {
  document.getElementById("statistiquesForm").submit(); // Envoyer le formulaire
}