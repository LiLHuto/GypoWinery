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

    const loggedIn = await isLoggedIn();
    if (!loggedIn) {
        alert("Csak bejelentkezett felhasználók tölthetik ki a kvízt.");
        return;
    }

    const userId = await getUserIdFromSession();

    // 🔍 Lekérdezzük a kvíz státuszát és a kupont
    try {
        const response = await fetch('getQuizStatus.php');
        const data = await response.json();

        if (data.quiz_completed) {
            questionText.textContent = "Már kitöltötted a kvízt!";
            optionsContainer.innerHTML = "";

            if (data.coupon) {
                const couponText = document.createElement("p");
                couponText.textContent = `Kuponod: ${data.coupon}`;
                optionsContainer.appendChild(couponText);
            }
            return;
        }
    } catch (error) {
        console.error("Hiba a státusz lekérésekor:", error);
    }

    await fetchQuestions();

    if (shuffledQuestions.length > 0) {
        currentQuestionIndex = 0;
        score = 0;
        showQuestion();
    } else {
        questionText.textContent = "Hiba történt a kérdések betöltésekor.";
    }
}

// Kérdések lekérése adatbázisból
async function fetchQuestions() {
    try {
        const response = await fetch('fetch_questions.php'); 
        const text = await response.text(); // Az eredeti JSON válasz beolvasása
        console.log("fetch_questions.php válasz:", text); // Debugging

        const data = JSON.parse(text); // JSON-ná alakítjuk
        if (!data.error) {
            shuffledQuestions = data;
            console.log("Betöltött kérdések:", shuffledQuestions);
        } else {
            console.error("Hiba:", data.error);
        }
    } catch (error) {
        console.error("Hiba a kérdések lekérésekor:", error);
    }
}

// Kérdés megjelenítése
function showQuestion() {
    if (currentQuestionIndex < shuffledQuestions.length) {
        const currentQuestion = shuffledQuestions[currentQuestionIndex];
        questionText.textContent = currentQuestion.question_text;
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

async function showResult() {
    questionText.textContent = `Kvíz vége! Eredményed: ${score}/${shuffledQuestions.length}`;
    optionsContainer.innerHTML = "";

    let resultMessage = "";
    let couponCode = null;

    if (score === 5) {
        resultMessage = "Gratulálunk! Nyertél egy ingyenes borkóstolást!";
    } else if (score >= 2 && score <= 4) {
        resultMessage = "Gratulálunk! Nyertél 10%-os kedvezményt a következő vásárlásodhoz!";
    } else {
        resultMessage = "Köszönjük, hogy játszottál!";
    }

    const resultText = document.createElement("p");
    resultText.textContent = resultMessage;
    optionsContainer.appendChild(resultText);

    localStorage.setItem('quizCompleted', 'true');

    // 🔹 Eredmények mentése és kupon kérése egyben
    const userId = await getUserIdFromSession();
    if (userId !== null) {
        try {
            const response = await fetch('saveQuizCompletion.php', {
                method: 'POST',
                body: JSON.stringify({ user_id: userId, quiz_completed: 1, score: score }),
                headers: { 'Content-Type': 'application/json' }
            });

            const responseData = await response.json();
            console.log('saveQuizCompletion.php válasz:', responseData);
            
            if (responseData.status === "success" && responseData.coupon) {
                couponCode = responseData.coupon;
            }
        } catch (error) {
            console.error('Hiba a kvíz eredményének mentésekor:', error);
        }
    }

    // 🔹 Ha van kupon, kiírjuk
    if (couponCode) {
        const couponText = document.createElement("p");
        couponText.textContent = `Nyertél egy kupont! Kód: ${couponCode}`;
        couponText.classList.add("alert", "alert-info", "mt-3");
        optionsContainer.appendChild(couponText);
    }
}

// Kvíz eredmény mentése a backendre
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
            console.log('saveQuizCompletion.php válasz:', responseData);
        } catch (error) {
            console.error('Hiba a kvíz eredményének mentésekor:', error);
        }
    }
}

// Felhasználói ID lekérése session-ból
async function getUserIdFromSession() {
    try {
        const response = await fetch('get_user_id.php', { method: 'GET' });
        const data = await response.json();
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
        const data = await response.json();
        return data.user_id !== null;
    } catch (error) {
        console.error('Hiba a bejelentkezési állapot ellenőrzésekor:', error);
        return false;
    }
}

// Kvíz indítása
startQuiz();
