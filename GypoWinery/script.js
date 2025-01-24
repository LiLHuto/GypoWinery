document.addEventListener("DOMContentLoaded", function () {
    const wineList = document.getElementById("wine-list");
    const cartContent = document.getElementById("cartContent");
    let cart = []; // Kosár tartalom

    // Borok betöltése az adatbázisból
    fetch("cart.php")
        .then(response => response.json())
        .then(data => {
            data.forEach(wine => {
                const wineCard = document.createElement("div");
                wineCard.className = "col-12 col-sm-6 col-md-4 col-lg-4";
                wineCard.innerHTML = `
                    <div class="card">
                        <img src="bor.jpg" class="card-img-top" alt="${wine.nev}">
                        <div class="card-body">
                            <h5 class="card-title">${wine.nev}</h5>
                            <p class="card-text">${wine.leiras}</p>
                            <p class="text-muted">Ár: ${wine.ar} Ft</p>
                            <p>Készlet: <span id="stock-${wine.ID}">${wine.keszlet}</span></p>
                            <button class="btn btn-primary add-to-cart" data-id="${wine.ID}">Kosárba</button>
                        </div>
                    </div>
                `;
                wineList.appendChild(wineCard);
            });

            // Kosárba gombok eseménykezelői
            const addToCartButtons = document.querySelectorAll(".add-to-cart");
            addToCartButtons.forEach(button => {
                button.addEventListener("click", function () {
                    const wineId = this.getAttribute("data-id");
                    updateCart(wineId, "add");
                });
            });
        });

    // Kosár frissítése: hozzáadás vagy eltávolítás
    function updateCart(wineId, action) {
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
                        addToCartUI(wineId);
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
    function addToCartUI(wineId) {
        const existingItem = cart.find(item => item.id === wineId);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({ id: wineId, quantity: 1 });
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
                cartItem.innerHTML = `
                    <p>Termék ID: ${item.id}, Mennyiség: ${item.quantity}</p>
                    <button class="btn btn-danger btn-sm" onclick="updateCart(${item.id}, 'remove')">Eltávolítás</button>
                `;
                cartContent.appendChild(cartItem);
            });
        }
    }
    const questions = [
        {
            text: "Hol alakult meg a GypoWinery?",
            options: ["Csévharaszti régió", "Tokaji régió", "Villány", "Eger"],
            correctAnswerIndex: 0
        },
        {
            text: "Mikor ültették el a GypoWinery első szőlőültetvényét?",
            options: ["1985", "1990", "2000", "2005"],
            correctAnswerIndex: 1
        },
        {
            text: "Milyen technológiai újítást vezetett be a GypoWinery 2005-ben?",
            options: ["Organikus gazdálkodási módszerek", "Modern borászat-technológiai újítások", "Új szőlőültetvények elhelyezése", "Nemzetközi forgalmazás"],
            correctAnswerIndex: 1
        },
        {
            text: "Mi a GypoWinery küldetése?",
            options: ["A világ legnépszerűbb borát készíteni", "Bemutatni a Csévharaszti terroir egyedülálló ízvilágát", "Új technológiák bevezetése a borászatban", "Csak vörösborokra koncentrálni"],
            correctAnswerIndex: 1
        },
        {
            text: "Mi a GypoWinery elköteleződése?",
            options: ["Fenntarthatóság és a helyi közösségek támogatása", "Nemzetközi piacokra való terjeszkedés", "Csak hagyományos borászmódszerek alkalmazása", "Csak édes borok készítése"],
            correctAnswerIndex: 0
        }
    ];
});
