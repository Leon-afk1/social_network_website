
async function deleteNotification(id) {
  var ajax = await fetch("./AJAX/deleteNotification.php?id=" + id);
  var response = await ajax.text();
  if (response === "success") {
      var notification = document.getElementById(id);
      notification.remove();
  } else {
      alert(response);
  }
}

async function deleteAllNotifications(id) {
  var ajax = await fetch("./AJAX/deleteAllNotifications.php?idUser=" + id);
  var response = await ajax.text();
  if (response === "success") {
      console.log("Suppression réussie !");
      var notif = document.getElementById("allNotifications");
      console.log(notif);
      notif.style.display = "none";
  } else {
      alert(response);
  }
}

