function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'hu', 
        includedLanguages: 'en,de,hu', 
        autoDisplay: false
    }, 'google_translate_element');
}

function translatePage(lang) {
    var select = document.querySelector(".goog-te-combo");
    if (select) {
        select.value = lang;
        select.dispatchEvent(new Event('change'));
    } else {
        alert("A fordító még nem töltődött be! Próbáld újra egy pillanat múlva.");
    }
}

// Google Translate API betöltése
(function() {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = "//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit";
    document.head.appendChild(script);
})();

// Zászlók hozzáadása az oldalhoz
document.addEventListener("DOMContentLoaded", function() {
    var translateContainer = document.createElement("div");
    translateContainer.id = "google_translate_element";
    document.body.appendChild(translateContainer);

    var flagsContainer = document.createElement("div");
    flagsContainer.style.position = "fixed";
    flagsContainer.style.top = "10px";
    flagsContainer.style.right = "10px";
    flagsContainer.style.display = "flex";
    flagsContainer.style.gap = "10px"; // Egyenletes távolság a zászlók között
    document.body.appendChild(flagsContainer);

    var flags = [
        { src: "../kepek/UKflag.png", lang: "en", alt: "English" },
        { src: "../kepek/germanflag.png", lang: "de", alt: "Deutsch" },
        { src: "../kepek/hungaryflag.png", lang: "hu", alt: "Magyar" }
    ];

    flags.forEach(flag => {
        var translateButton = document.createElement("img");
        translateButton.src = flag.src;
        translateButton.alt = flag.alt;
        translateButton.width = 30;
        translateButton.style.cursor = "pointer";
        translateButton.style.margin = "5px"; // Kisebb margó, hogy szebben nézzen ki
        translateButton.onclick = function() { translatePage(flag.lang); };
        flagsContainer.appendChild(translateButton);
    });
});
