/* POPUP CODE */

const popupDiv = document.getElementById("popup-info");
let popupIntervalId;

console.log(typeof popupIntervalId);

function displayPopup(message) {

    if (typeof popupIntervalId !== "undefined") {
        clearInterval(popupIntervalId);
    }

    popupDiv.textContent = message;

    popupDiv.style.visibility = "visible";

    popupIntervalId = setInterval(() => {
        popupDiv.style.visibility = "hidden";
    }, 5000);

}

/* SPOILER code */

// liste des boutons trigger spoiler
/** @type {Element[]} */
const spoilerButtons = [];

for (const button of document.getElementsByTagName("button")) {

    if (button.className.includes("spoilertrigger")) {
        let buttonIndex = null;
        // récupérer le numéro du trigger
        const classes = button.className.split(" ").join("-").split("-");

        for (const str of classes) {
            if (classes[classes.indexOf(str) - 1] == "spoilertrigger") {
                buttonIndex = parseInt(str);
            }
        }

        button.addEventListener("click", (e) => {
            const spoilerContent = document.getElementsByClassName(`spoilercontent-${buttonIndex}`)

            if (spoilerContent[0] != null) {
                if (spoilerContent[0].style.display == "none") {
                    spoilerContent[0].style.display = "block";
                } else {
                    spoilerContent[0].style.display = "none";
                }

            }

        })
    }
}

// FORMS verifications
const addfraisForm = document.querySelector(".addfrais-form");

addfraisForm.addEventListener("change", (e) => {
    const allEnForfait = document.querySelectorAll(".en-forfait");
    const allHorsForfait = document.querySelectorAll(".hors-forfait");
    if (e.target.name === "type-frais") {
        if (e.target.value === "horsforfait") {
            for (const enForfait of Array.from(allEnForfait)) {
                enForfait.style.display = "none";
            }

            for (const horsForfait of Array.from(allHorsForfait)) {
                horsForfait.style.display = "block";
            }
        } else if (e.target.value === "forfait") {
            for (const enForfait of Array.from(allEnForfait)) {
                enForfait.style.display = "block";
            }

            for (const horsForfait of Array.from(allHorsForfait)) {
                horsForfait.style.display = "none";
            }
        }
    }
});

addfraisForm.addEventListener("submit", (e) => {
    e.preventDefault();
    if (addfraisForm["type-frais"].value === "forfait") {
        if (addfraisForm["quantite-frais"].value.length == 0) {
            displayPopup("ne laissez pas de champs vides, si vous voulez modifier une entrée en laissant inchangée un champ mettez 0.");
        } else {
            addfraisForm.submit();
        }
    } else if (addfraisForm["type-frais"].value === "horsforfait") {
        if (
            addfraisForm["nom-frais"].value.length == 0 ||
            addfraisForm["quantite-frais"].value.length == 0 ||
            addfraisForm["prix-frais"].value.length == 0
        ) {
            displayPopup("ne laissez pas de champs vides, si vous voulez modifier une entrée en laissant inchangée un champ mettez 0.");
        } else {
            addfraisForm.submit();
            return;
        }
    }
});
