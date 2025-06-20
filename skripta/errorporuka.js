document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("singupforma");
    const emailInput = document.getElementById("email");
    const errorDiv = document.getElementById("erroremail");
    const gmailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;

    function showError(message) {
        errorDiv.textContent = message;
        errorDiv.style.display = "block";
        emailInput.style.border = "2px solid red";
        document.body.style.pointerEvents = "none";
        emailInput.style.pointerEvents = "auto";
        emailInput.focus();
    }

    function clearError() {
        errorDiv.style.display = "none";
        emailInput.style.border = "";
        document.body.style.pointerEvents = "auto";
    }

    emailInput.addEventListener("blur", function () {
        const value = emailInput.value.trim();
        if (!gmailRegex.test(value)) {
            showError("Unesite ispravnu email adresu!");
        } else {
            clearError();
        }
    });

    emailInput.addEventListener("input", function () {
        const value = emailInput.value.trim();
        if (gmailRegex.test(value)) {
            clearError();
        }
    });

    form.addEventListener("submit", function (event) {
        const value = emailInput.value.trim();
        if (!gmailRegex.test(value)) {
            event.preventDefault();
            showError("Morate uneti validnu Gmail adresu pre slanja!");
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("password");
    const confirmInput = document.getElementById("confirm_password");
    const passError = document.getElementById("errorpass");
    const confirmError = document.getElementById("errorconfirm");

    // Provera dužine lozinke
passwordInput.addEventListener("blur", function () {
    if (passwordInput.value.length < 8) {
        passError.textContent = "Lozinka mora imati najmanje 8 karaktera.";
        passError.style.display = "block";
        passwordInput.style.border = "2px solid red";
        document.body.style.pointerEvents = "none";
        passwordInput.style.pointerEvents = "auto";
        passwordInput.focus();
    } else {
        passError.style.display = "none";
        passwordInput.style.border = "";
        document.body.style.pointerEvents = "auto";
    }
});

    // Provera poklapanja lozinki
    confirmInput.addEventListener("blur", function () {
        if (confirmInput.value && confirmInput.value !== passwordInput.value) {
            confirmInput.value = ""; // obriši uneto
            confirmError.textContent = "Lozinke se ne poklapaju — pokušajte ponovo.";
            confirmError.style.display = "block";
            confirmInput.style.border = "2px solid red";
        } else {
            confirmError.style.display = "none";
            confirmInput.style.border = "";
        }
    });

    // Dok kuca — uklanja grešku ako lozinke postanu iste
    confirmInput.addEventListener("input", function () {
        if (confirmInput.value === passwordInput.value) {
            confirmError.style.display = "none";
            confirmInput.style.border = "";
        }
    });
});