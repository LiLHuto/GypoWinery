function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'hu', 
        includedLanguages: 'en', 
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE
    }, 'google_translate_element');
}

function translatePage() {
    var select = document.querySelector(".goog-te-combo");
    if (select) {
        select.value = 'en';
        select.dispatchEvent(new Event('change'));
    }
}

// Google Translate API betöltése
(function() {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = "//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit";
    document.head.appendChild(script);
})();
