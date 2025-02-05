// Kvíz kérdések
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
// Kvíz indítása
async function startQuiz() {
    console.log("Kvíz indítása...");
    
    // Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
    const loggedIn = await isLoggedIn();
    if (!loggedIn) {
        alert("Csak bejelentkezett felhasználók tölthetik ki a kvízt.");
        return;
    }

    // Ellenőrizzük a localStorage-ban, hogy a felhasználó már kitöltötte-e a kvízt
    if (localStorage.getItem('quizCompleted') === 'true') {
        alert("Már kitöltötted a kvízt!");
        return;
    }

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

// Eredmény megjelenítése és kupon igénylése
async function showResult() {
    questionText.textContent = `Kvíz vége! Eredményed: ${score}/${shuffledQuestions.length}`;
    optionsContainer.innerHTML = "";

    let resultMessage = "";

    if (score === 5) {
        resultMessage = "Gratulálunk! Nyertél egy ingyenes borkóstolást!";
    } else if (score >= 2 && score <= 4) {
        resultMessage = "Gratulálunk! Nyertél 10%-os kedvezményt a következő vásárlásodhoz!";
        await fetchCoupon(); // Kupon igénylése
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

  // Mentés a localStorage-ba, hogy a felhasználó már kitöltötte a kvízt
  localStorage.setItem('quizCompleted', 'true');

    // Mentés a backendre
    await saveQuizResult();
}

// Kupon lekérése a szerverről
async function fetchCoupon() {
    try {
        const response = await fetch('get_coupon.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        });

        const text = await response.text(); // Először sima szövegként beolvassuk
        console.log("Szerver válasza:", text); // Debugging

        try {
            const data = JSON.parse(text); // Megpróbáljuk JSON-né alakítani
            if (data.status === "success") {
                const couponText = document.createElement("p");
                couponText.textContent = `Nyertél egy kupont! Kód: ${data.coupon}`;
                optionsContainer.appendChild(couponText);
            } else {
                console.error('Hiba történt:', data.message);
            }
        } catch (jsonError) {
            console.error("Hibás JSON válasz:", text); // Ha nem JSON, kiírjuk
        }

    } catch (error) {
        console.error('Hiba a kupon lekérésekor:', error);
    }
}

// Mentés a backendre, hogy a felhasználó kitöltötte a kvízt
async function saveQuizResult() {
    const userId = await getUserIdFromSession();
    if (userId !== null) {
        try {
            console.log("Felhasználói ID:", userId);
            const response = await fetch('saveQuizCompletion.php', {
                method: 'POST',
                body: JSON.stringify({ user_id: userId, quiz_completed: 1, score: score }),
                headers: { 'Content-Type': 'application/json' }
            });

            const responseData = await response.json();
            console.log('Backend válasz:', responseData);
            if (responseData.status === 'success') {
                console.log('Kvíz eredmény mentve a backendre');
            } else {
                console.error('Hiba történt a mentés során:', responseData.message);
            }
        } catch (error) {
            console.error('Hiba a kvíz eredményének mentésekor:', error);
        }
    }
}

// Felhasználói ID lekérése session-ból
async function getUserIdFromSession() {
    try {
        const response = await fetch('get_user_id.php', { method: 'GET' });
        if (!response.ok) {
            throw new Error('Hiba történt a kéréssel');
        }
        const data = await response.json();
        console.log('Felhasználói ID adat:', data);
        return data.user_id || null;
    } catch (error) {
        console.error('Hiba a felhasználói ID lekérésekor:', error);
        return null;
    }
}

// Ellenőrizzük, hogy be van-e jelentkezve a felhasználó
async function isLoggedIn() {
    try {
        const response = await fetch('get_user_id.php', { method: 'GET' });
        if (!response.ok) {
            throw new Error('Hiba történt a kéréssel');
        }
        const data = await response.json();
        console.log('Bejelentkezett felhasználó:', data);
        return data.user_id !== null;
    } catch (error) {
        console.error('Hiba a bejelentkezési állapot ellenőrzésekor:', error);
        return false;
    }
}

startQuiz();
