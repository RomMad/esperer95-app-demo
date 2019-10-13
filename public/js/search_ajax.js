// Recherche instannée Ajax
class Search_ajax {
    constructor(lengthSearch, time) {
        this.searchElt = document.getElementById("search");
        this.resultsSearchElt = document.getElementById("results_search");
        this.lengthSearch = lengthSearch;
        this.time = time;
        this.countdownID = null;
        this.init();
    }

    init() {
        this.searchElt.addEventListener("keyup", this.timer.bind(this));
        this.searchElt.addEventListener("click", function () {
            this.resultsSearchElt.classList.replace("fade-out", "fade-in");
            setTimeout(this.hideListResults.bind(this), 100);
        }.bind(this));
    }

    // Timer avant de lancer la requête Ajax
    timer() {
        clearInterval(this.countdownID);
        this.countdownID = setTimeout(this.count.bind(this), this.time);
    }

    // Compte le nombre de caratères saisis et lance la requête Ajax<
    count() {
        let valueSearch = this.searchElt.value;
        if (valueSearch.length > this.lengthSearch) {
            let url = "/search/person?search=" + valueSearch;
            this.ajax(url);
            this.hideListResults();
        }
    }

    // Récupère les résultats de la requête ajax
    ajax(url) {
        let ajaxRequest = new XMLHttpRequest();
        ajaxRequest.open("GET", url);
        ajaxRequest.onload = this.addResults.bind(this, ajaxRequest);
        ajaxRequest.send();
    }

    // Affiche les résultats de la rêquête
    addResults(ajaxRequest) {
        let response = JSON.parse(ajaxRequest.responseText);
        this.resultsSearchElt.innerHTML = "";
        if (response.nb_results > 0) {
            this.addItem(response);
        } else {
            this.noResult();
        }
        this.resultsSearchElt.classList.replace("fade-out", "fade-in");
    }

    // Ajoute un élément à la liste des résultats
    addItem(response) {
        response.results.forEach(person => {
            let aElt = document.createElement("a");
            aElt.textContent = person.lastname + " " + person.firstname;
            aElt.href = "/person/" + person.id;
            aElt.className = "list-group-item list-group-item-action font-size-10 pl-3 pr-1 py-1";
            this.resultsSearchElt.appendChild(aElt);
            aElt.addEventListener("click", function () {
                aElt.classList.add("active");
            }.bind(this));
        });
    }

    // Affiche 'Aucun résultat'
    noResult() {
        let spanElt = document.createElement("p");
        spanElt.textContent = "Aucun résultat.";
        spanElt.className = "list-group-item list-group-item-light pl-3 py-2";
        this.resultsSearchElt.appendChild(spanElt);
    }

    // Supprime la liste des résultats au click
    hideListResults() {
        window.addEventListener("click", function (e) {
            this.resultsSearchElt.classList.replace("fade-in", "fade-out");
        }.bind(this), {
            once: true
        });
    }
}

// axios.get(url).then(function (response) {
//     if (response.data.nb_results > 0) {
//         response.data.results.forEach(person => {
//         });
//     } else {
//     }
// }).catch(function (error) {
//     if (error.status === 403) {
//         // console.log("Non connecté.");
//     } else {
//         console.log("Aucun résultat.");
//     }
// })
// this.resultsSearchElt.appendChild(ulElt);