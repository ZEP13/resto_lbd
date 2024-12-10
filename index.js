function togglePassword() {
  var passwordField = document.getElementById("mdp");
  var confirmPasswordField = document.getElementById("mdpconfirm");
  var showPasswordCheckbox = document.getElementById("showPassword");

  // Si la case est cochée, changer le type en texte, sinon en mot de passe
  if (showPasswordCheckbox.checked) {
    passwordField.type = "text";
    confirmPasswordField.type = "text";
  } else {
    passwordField.type = "password";
    confirmPasswordField.type = "password";
  }
}

const dateInput = document.getElementById("date");
const today = new Date();
const minDate = today.toISOString().split("T")[0]; // Aujourd'hui
dateInput.setAttribute("min", minDate); // Empêche la sélection des dates passées

dateInput.addEventListener("input", function () {
  const selectedDate = new Date(dateInput.value);
  const dayOfWeek = selectedDate.getDay(); // 0 = dimanche, 1 = lundi, ..., 6 = samedi

  // Si le jour n'est pas jeudi (4), vendredi (5) ou samedi (6), désactive la date
  if (![4, 5, 6].includes(dayOfWeek)) {
    alert("La réservation doit être effectuée un Jeudi, Vendredi ou Samedi.");
    dateInput.value = ""; // Réinitialise la date si elle est invalide
  }
});
