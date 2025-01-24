// Kérdések definiálása
const questions = [
    { 
        text: "Hol alakult meg a GypoWinery?",
        options: ["Csévharaszti régió", "Tokaji régió", "Villány", "Eger"],
        correct: 0
    },
    { 
        text: "Mikor ültették el a GypoWinery első szőlőültetvényét?",
        options: ["1985", "1990", "2000", "2005"],
        correct: 1
    },
    { 
        text: "Milyen technológiai újítást vezetett be a GypoWinery 2005-ben?",
        options: ["Organikus gazdálkodási módszerek", "Modern borászat-technológiai újítások", "Új szőlőültetvények elhelyezése", "Nemzetközi forgalmazás"],
        correct: 1
    },
    { 
        text: "Mi a GypoWinery küldetése?",
        options: ["A világ legnépszerűbb borát készíteni", "Bemutatni a Csévharaszti terroir egyedülálló ízvilágát", "Új technológiák bevezetése a borászatban", "Csak vörösborokra koncentrálni"],
        correct: 1
    },
    { 
        text: "Mi a GypoWinery elköteleződése?",
        options: ["Fenntarthatóság és a helyi közösségek támogatása", "Nemzetközi piacokra való terjeszkedés", "Csak hagyományos borászmódszerek alkalmazása", "Csak édes borok készítése"],
        correct: 0
    },
    { 
        text: "Milyen jövőbeli tervei vannak a GypoWinerynek?",
        options: ["Új helyszínek nyitása világszerte", "Új borfajták bevezetése és a borászat bővítése", "Csak vörösborokra koncentrálni", "Borok kizárólagos online értékesítése"],
        correct: 1
    },
    { 
        text: "Melyik vörösborunk illik legjobban steakhez vagy grillezett húsokhoz?",
        options: ["Csévharaszti Kékfrankos", "Csévharaszti Cabernet Sauvignon", "Csévharaszti Rosé", "Csévharaszti Olaszrizling"],
        correct: 0
    },
    { 
        text: "Milyen hőmérsékleten kell tálalni a GypoWinery Csévharaszti Zöld Veltelinit?",
        options: ["8-10°C", "10-12°C", "16-18°C", "18-20°C"],
        correct: 1
    },
    { 
        text: "Melyik borunkban található eper és cseresznye ízvilág, és tökéletes a nyári fogásokhoz?",
        options: ["Csévharaszti Cabernet Sauvignon", "Csévharaszti Rosé", "Csévharaszti Olaszrizling", "Csévharaszti Kékfrankos"],
        correct: 1
    },
    { 
        text: "Mi az ideális hőmérséklet a Csévharaszti Jégbor tálalásához?",
        options: ["6-8°C", "8-10°C", "16-18°C", "18-20°C"],
        correct: 0
    },
    { 
        text: "Melyik idézet fejezi ki a bor élvezetének fontosságát?",
        options: [
            "A legjobb módja, hogy élvezd egy pohár bort, ha megosztod egy barátoddal.",
            "Az élet túl rövid ahhoz, hogy rossz bort igyunk.",
            "A bor folyamatos bizonyítéka annak, hogy Isten szeret minket.",
            "A bor minden étkezést alkalmassá tesz."
        ],
        correct: 1
    }
];

// Állapotváltozók
let shuffledQuestions = [];
let currentQuestionIndex = 0;
let score = 0;

// HTML elemek
const questionContainer = document.getElementById("question-container");
const questionText = document.getElementById("question-text");
const optionsContainer = document.getElementById("options-container");

// Játék inicializálása
function startQuiz() {
    // Kérdések randomizálása
    shuffledQuestions = [...questions].sort(() => Math.random() - 0.5).slice(0, 5);
    currentQuestionIndex = 0;
    score = 0;
    showQuestion();
}

// Kérdés megjelenítése
function showQuestion() {
    if (currentQuestionIndex < shuffledQuestions.length) {
        const currentQuestion = shuffledQuestions[currentQuestionIndex];
        questionText.textContent = currentQuestion.text;
        optionsContainer.innerHTML = "";

        // Opciók hozzáadása
        currentQuestion.options.forEach((option, index) => {
            const button = document.createElement("button");
            button.textContent = option;
            button.classList.add("btn", "btn-outline-primary", "mb-2");
            button.onclick = () => handleAnswer(index);
            optionsContainer.appendChild(button);
        });
    } else {
        showResult();
    }
}

// Válasz ellenőrzése
function handleAnswer(selectedIndex) {
    const currentQuestion = shuffledQuestions[currentQuestionIndex];
    if (selectedIndex === currentQuestion.correct) {
        score++;
    }
    currentQuestionIndex++;
    showQuestion();
}

// Eredmény megjelenítése
function showResult() {
    questionText.textContent = `Kvíz vége! Eredményed: ${score}/${shuffledQuestions.length}`;
    optionsContainer.innerHTML = "";

    let resultMessage = "";

    // Üzenet a válaszok alapján
    if (score === 5) {
        resultMessage = "Gratulálunk! Nyertél egy ingyenes borkóstolást!";
    } else if (score >= 2 && score <= 4) {
        resultMessage = "Gratulálunk! Nyertél 10%-os kedvezményt a következő vásárlásodhoz!";
    } else {
        resultMessage = "Köszönjük, hogy játszottál!";
    }

    // Eredmény megjelenítése
    const resultText = document.createElement("p");
    resultText.textContent = resultMessage;
    optionsContainer.appendChild(resultText);

    // Újrapróbálás gomb
    const retryButton = document.createElement("button");
    retryButton.textContent = "Újrapróbálom";
    retryButton.classList.add("btn", "btn-success");
    retryButton.onclick = startQuiz;
    optionsContainer.appendChild(retryButton);
}

// Játék indítása
startQuiz();
