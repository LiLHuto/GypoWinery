// Kosár tartalmának lekérése
function getCartItems() {
    fetch('cart.php')
      .then(response => response.json())
      .then(data => {
        updateCartUI(data); // Frissítjük a kosár tartalmát
      });
  }
  
  // Kosár frissítése UI szinten
  function updateCartUI(borok) {
    const cartContent = document.getElementById("cartContent");
    cartContent.innerHTML = ''; // Kosár tartalmának törlése
    
    borok.forEach(item => {
      const cartItemDiv = document.createElement("div");
      cartItemDiv.classList.add("cart-item");
  
      cartItemDiv.innerHTML = `
        <img src="${item.image}" alt="${item.nev}">
        <p>${item.nev} - ${item.ar} Ft</p>
        <button class="removeBtn" onclick="removeFromCart(${item.ID})">Törlés</button>
      `;
  
      cartContent.appendChild(cartItemDiv);
    });
  
    if (borok.length > 0) {
      document.getElementById("cartPanel").style.right = "0";
    } else {
      document.getElementById("cartPanel").style.right = "-400px";
    }
  }
  
  // Kosárba helyezés
  function addToCart(bor_id) {
    fetch('cart.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `bor_id=${bor_id}&action=add`
    })
    .then(response => response.json())
    .then(data => {
      getCartItems(); // Frissítjük a kosarat
    });
  }
  
  // Kosárból eltávolítás
  function removeFromCart(bor_id) {
    fetch('cart.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `bor_id=${bor_id}&action=remove`
    })
    .then(response => response.json())
    .then(data => {
      getCartItems(); // Frissítjük a kosarat
    });
  }
  
  // Kosárba tétel gomb esemény
  document.getElementById("addToCartBtn").addEventListener("click", function() {
    const bor_id = 1; // Teszt: az első terméket tesszük a kosárba
    addToCart(bor_id);
  });
  
  // Kosár frissítése indításkor
  getCartItems();
  