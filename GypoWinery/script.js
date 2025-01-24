document.addEventListener("DOMContentLoaded", function () {
    const wineList = document.getElementById("wine-list");
    const cartContent = document.getElementById("cartContent");
    const cartPanel = document.getElementById("cartPanel");
    const closeCartBtn = document.getElementById("closeCartBtn");
    let cart = []; // Kosár tartalma

    // Borok betöltése az adatbázisból
    fetch("cart.php")
        .then(response => response.json())
        .then(data => {
            for (let i = 0; i < data.length; i++) {
                const wine = data[i];
                const wineCard = document.createElement("div");
                wineCard.className = "col-12 col-sm-6 col-md-4 col-lg-4";
                wineCard.innerHTML = `
                    <div class="card">
                        <img src="bor${i + 1}.jfif" class="card-img-top" alt="${wine.nev}">
                        <div class="card-body">
                            <h5 class="card-title">${wine.nev}</h5>
                            <p class="card-text">${wine.leiras}</p>
                            <p class="text-muted">Ár: ${wine.ar} Ft</p>
                            <p>Készlet: <span id="stock-${wine.ID}">${wine.keszlet}</span></p>
                            <button class="btn btn-primary add-to-cart" data-id="${wine.ID}" data-name="${wine.nev}">Kosárba</button>
                        </div>
                    </div>
                `;
                wineList.appendChild(wineCard);
            }

            // Kosárba gombok eseménykezelői
            document.querySelectorAll(".add-to-cart").forEach(button => {
                button.addEventListener("click", function () {
                    const wineId = this.getAttribute("data-id");
                    const wineName = this.getAttribute("data-name");
                    updateCart(wineId, wineName, "add");
                });
            });
        });

    // Kosár frissítése: hozzáadás vagy eltávolítás
    function updateCart(wineId, wineName, action) {
        fetch("cart.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `bor_id=${wineId}&action=${action}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    if (action === "add") {
                        addToCartUI(wineId, wineName);
                    } else if (action === "remove") {
                        removeFromCartUI(wineId);
                    }
                    updateStockDisplay(wineId, action);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error("Hiba:", error));
    }

    // Készlet frissítése a megjelenítésben
    function updateStockDisplay(wineId, action) {
        const stockElement = document.getElementById(`stock-${wineId}`);
        let currentStock = parseInt(stockElement.textContent);

        if (action === "add") {
            currentStock -= 1;
        } else if (action === "remove") {
            currentStock += 1;
        }

        stockElement.textContent = currentStock;
    }

    // Kosár UI frissítése
    function addToCartUI(wineId, wineName) {
        const existingItem = cart.find(item => item.id === wineId);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({ id: wineId, name: wineName, quantity: 1 });
        }
        renderCart();
    }

    // Kosárból eltávolítás UI szinten
    function removeFromCartUI(wineId) {
        const itemIndex = cart.findIndex(item => item.id === wineId);
        if (itemIndex !== -1) {
            cart[itemIndex].quantity -= 1;
            if (cart[itemIndex].quantity <= 0) {
                cart.splice(itemIndex, 1);
            }
        }
        renderCart();
    }

    // Kosár tartalom megjelenítése
    function renderCart() {
        cartContent.innerHTML = "";
        if (cart.length === 0) {
            cartContent.innerHTML = "<p>A kosár üres.</p>";
        } else {
            cart.forEach(item => {
                const cartItem = document.createElement("div");
                cartItem.className = "cart-item";
                cartItem.innerHTML = `
                    <p>${item.name}, Mennyiség: ${item.quantity}</p>
                    <button class="btn btn-danger btn-sm remove-from-cart" data-id="${item.id}" data-name="${item.name}">Eltávolítás</button>
                `;
                cartContent.appendChild(cartItem);
            });

            // Eltávolítás gombok eseménykezelői
            document.querySelectorAll(".remove-from-cart").forEach(button => {
                button.addEventListener("click", function () {
                    const wineId = this.getAttribute("data-id");
                    const wineName = this.getAttribute("data-name");
                    updateCart(wineId, wineName, "remove");
                });
            });
        }
        cartPanel.style.display = "block"; // Kosár panel megjelenítése
    }

    // Kosár panel bezárása
    closeCartBtn.addEventListener("click", function () {
        cartPanel.style.display = "none";
    });
});
