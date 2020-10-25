const addfraisButton = document.querySelector(".addfrais-button");
const addfraisForm = document.querySelector(".addfrais-form");

addfraisForm.addEventListener("change", (e) => {
    const allEnForfait = document.querySelectorAll(".en-forfait");
    const allHorsForfait = document.querySelectorAll(".hors-forfait");
    if(e.target.name === "type-frais") {
        if(e.target.value === "horsforfait") {
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

