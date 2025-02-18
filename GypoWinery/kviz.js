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

    if (localStorage.getItem('quizCompleted') === 'true') {
        alert("Már kitöltötted a kvízt!");
        return;
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
        const response = await fetch('fetch_questions.php'); // PHP backend a kérdések lekérésére
        const data = await response.json();

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

// Eredmény megjelenítése
async function showResult() {
    questionText.textContent = `Kvíz vége! Eredményed: ${score}/${shuffledQuestions.length}`;
    optionsContainer.innerHTML = "";

    let resultMessage = "";

    if (score === 5) {
        resultMessage = "Gratulálunk! Nyertél egy ingyenes borkóstolást!";
    } else if (score >= 2 && score <= 4) {
        resultMessage = "Gratulálunk! Nyertél 10%-os kedvezményt a következő vásárlásodhoz!";
        await fetchCoupon();
    } else {
        resultMessage = "Köszönjük, hogy játszottál!";
    }

    const resultText = document.createElement("p");
    resultText.textContent = resultMessage;
    optionsContainer.appendChild(resultText);

    const retryButton = document.createElement("button");
    retryButton.textContent = "Újrapróbálom";
    retryButton.classList.add("btn", "btn-success");
    retryButton.onclick = startQuiz;
    optionsContainer.appendChild(retryButton);

    localStorage.setItem('quizCompleted', 'true');

    await saveQuizResult();
}

// Kupon lekérése a szerverről
async function fetchCoupon() {
    try {
        const response = await fetch('get_coupon.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        });

        const text = await response.text();
        console.log("Szerver válasza:", text);

        try {
            const data = JSON.parse(text);
            if (data.status === "success") {
                const couponText = document.createElement("p");
                couponText.textContent = `Nyertél egy kupont! Kód: ${data.coupon}`;
                optionsContainer.appendChild(couponText);
            } else {
                console.error('Hiba történt:', data.message);
            }
        } catch (jsonError) {
            console.error("Hibás JSON válasz:", text);
        }

    } catch (error) {
        console.error('Hiba a kupon lekérésekor:', error);
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

// Kvíz indítása
startQuiz();
