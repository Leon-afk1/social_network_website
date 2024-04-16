
previousText2 = "";
timer2 = 0;

function TimerIncrease_fetch() {
  timer2+=200;
  setTimeout('TimerIncrease_fetch()',200);
}
TimerIncrease_fetch();

async function suggestFromInput_fetch(currentText) {
  if (document.getElementById("rechercheUser").checked){
    suggestNamesFromInput_fetch(currentText);
  }else{
    suggestPostsFromInput_fetch(currentText);
  }
}

async function suggestPostsFromInput_fetch(currentText) {
  autocomplete = document.getElementById('autocomplete');
  if (currentText != previousText2 && timer2 >= 200 ){
    var AJAXresult = await fetch("./AJAX/rechercherPost.php?var=" + currentText);
    document.getElementById("suggestions2").innerHTML = await AJAXresult.text();

      previousText2 = currentText;
      timer2 = 0;
  }else {
    document.getElementById("suggestions2").innerHTML = ''; 
  }
}

async function suggestNamesFromInput_fetch(currentText) {

  if (currentText != previousText2 && timer2 >= 200 ){
    
    var AJAXresult = await fetch("./AJAX/rechercherUser.php?var=" + currentText);
    document.getElementById("suggestions2").innerHTML = await AJAXresult.text();

      previousText2 = currentText;
      timer2 = 0;
  }else {
    document.getElementById("suggestions2").innerHTML = ''; 
  }
}

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

document.addEventListener("DOMContentLoaded", function() {
    if (executeToggleLoginFormIfNeeded) {
        toggleLoginForm();
    }
});

document.addEventListener("DOMContentLoaded", function() {
    if (executeToggleNewLoginFormIfNeeded) {
        toggleNewLoginForm();
    }
});


