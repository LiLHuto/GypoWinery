// Kosár objektum az elemek tárolásához
const cart = [];

// HTML elemek hivatkozásai
const cartPanel = document.getElementById("cartPanel");
const cartContent = document.getElementById("cartContent");
const closeCartBtn = document.getElementById("closeCartBtn");
const addToCartBtns = document.querySelectorAll("#addToCartBtn");

// "Kosárba" gombok eseménykezelői
addToCartBtns.forEach((btn) => {
    btn.addEventListener("click", (event) => {
        const card = event.target.closest(".card");
        const title = card.querySelector(".card-title").textContent;
        const price = card.querySelector(".text-muted").textContent;

        // Elem hozzáadása a kosárhoz
        addToCart(title, price);
    });
});

// Elem hozzáadása a kosárhoz
function addToCart(title, price) {
    // Ellenőrizzük, hogy az elem már szerepel-e a kosárban
    const existingItem = cart.find((item) => item.title === title);
    if (existingItem) {
        existingItem.quantity += 1; // Ha már van, növeljük a mennyiséget
    } else {
        // Ha nincs, hozzáadjuk új elemként
        cart.push({ title, price, quantity: 1 });
    }

    updateCartUI();
    openCart();
}

// Kosár frissítése a UI-on
function updateCartUI() {
    cartContent.innerHTML = ""; // Először kiürítjük

    if (cart.length === 0) {
        cartContent.innerHTML = "<p>A kosár üres.</p>";
        return;
    }

    cart.forEach((item, index) => {
        const cartItem = document.createElement("div");
        cartItem.className = "cart-item";
        cartItem.innerHTML = `
            <p><strong>${item.title}</strong></p>
            <p>Ár: ${item.price}</p>
            <p>Mennyiség: ${item.quantity}</p>
            <button class="remove-btn" data-index="${index}">Eltávolítás</button>
        `;
        cartContent.appendChild(cartItem);
    });

    // Eltávolítás gombok kezelése
    const removeBtns = document.querySelectorAll(".remove-btn");
    removeBtns.forEach((btn) => {
        btn.addEventListener("click", (event) => {
            const index = event.target.getAttribute("data-index");
            removeFromCart(index);
        });
    });
}

// Elem eltávolítása a kosárból
function removeFromCart(index) {
    cart.splice(index, 1); // Elem eltávolítása a tömbből
    updateCartUI(); // Kosár frissítése
}

// Kosár megnyitása
function openCart() {
    cartPanel.style.display = "block";
}

// Kosár bezárása
closeCartBtn.addEventListener("click", () => {
    cartPanel.style.display = "none";
});
