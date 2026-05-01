//Массив товаров
const products = [
  { id: 1, name: 'iPhone 17 Pro', category: 'Смартфоны', price: 131999, image: 'images/premphone.jpg', link: 'catalog.html#tovar1', desc: 'Современный смартфон с хорошей камерой.' },
  { id: 2, name: 'Samsung Galaxy Z-Flip 7', category: 'Смартфоны', price: 99499, image: 'images/smartphone.jpg', link: 'catalog.html#tovar2', desc: 'Флагманский смартфон с профессиональной камерой.' },
  { id: 3, name: 'Xiaomi Redmi Note 13 Pro', category: 'Смартфоны', price: 23199, image: 'images/xiaomi.jpg', link: 'catalog.html#tovar3', desc: 'Стильный смартфон с 200-мегапиксельной камерой.' },
  { id: 4, name: 'Apple MacBook Air 15 M4', category: 'Ноутбуки', price: 95899, image: 'images/macbook.jpg', link: 'catalog.html#tovar4', desc: 'Тонкий и легкий ноутбук.' },
  { id: 5, name: 'ASUS ROG Strix G16', category: 'Ноутбуки', price: 166999, image: 'images/asus-rog.png', link: 'catalog.html#tovar5', desc: 'Игровой ноутбук с мощной видеокартой.' },
  { id: 6, name: 'Dell XPS 13 Plus', category: 'Ноутбуки', price: 190999, image: 'images/dell-xps.jpg', link: 'catalog.html#tovar6', desc: 'Премиальный ультрабук.' },
  { id: 7, name: 'Sony WH-1000XM5', category: 'Наушники', price: 29990, image: 'images/sony.jpg', link: 'catalog.html#tovar7', desc: 'Лучшие наушники с шумоподавлением.' },
  { id: 8, name: 'Apple AirPods Max', category: 'Наушники', price: 54990, image: 'images/airpods-max.jpg', link: 'catalog.html#tovar8', desc: 'Студийные наушники.' },
  { id: 9, name: 'JBL Tune 760NC', category: 'Наушники', price: 7990, image: 'images/jbl.jpg', link: 'catalog.html#tovar9', desc: 'Доступные наушники с шумоподавлением.' }
];

//Функции корзины
function getCart() {
  let cart = localStorage.getItem('cart');
  return cart ? JSON.parse(cart) : [];
}

function saveCart(cart) {
  localStorage.setItem('cart', JSON.stringify(cart));
}

function addToCart(productId) {
  let product = products.find(p => p.id == productId);
  if (!product) return;
  let cart = getCart();
  cart.push(product);
  saveCart(cart);
  updateCartCounter();
  alert('Товар добавлен в корзину');
}

function updateCartCounter() {
  let counter = document.getElementById('cart-counter');
  if (counter) {
    let cart = getCart();
    counter.textContent = cart.length;
  }
}

function removeFromCart(index) {
  let cart = getCart();
  cart.splice(index, 1);
  saveCart(cart);
  updateCartCounter();
  if (window.location.pathname.includes('cart.html')) {
    showCart();
  }
}

function clearCart() {
  saveCart([]);
  updateCartCounter();
  if (window.location.pathname.includes('cart.html')) {
    showCart();
  }
}

const calculateTotal = () => {
  let cart = getCart();
  let total = 0;
  cart.forEach(item => total += item.price);
  return total;
};

function checkout() {
  let cart = getCart();
  if (cart.length === 0) {
    alert('Корзина пуста');
    return;
  }
  alert('Покупка прошла успешно!');
  clearCart();
}

//Отображение корзины

function showCart() {
  let cart = getCart();
  let container = document.getElementById('cart-items');
  let totalDiv = document.getElementById('cart-total');

  if (!container) return;

  if (cart.length === 0) {
    container.innerHTML = '<p>Корзина пуста</p>';
    totalDiv.innerHTML = '';
    return;
  }

  let html = '';
  for (let i = 0; i < cart.length; i++) {
    let item = cart[i];
    html += `
      <div class="cart-item">
        <a href="${item.link}">
          <img src="${item.image}" alt="${item.name}" width="50">
        </a>
        <span><a href="${item.link}">${item.name}</a></span>
        <span>Цена: ${item.price} ₽</span>
        <button class="remove-item" data-index="${i}">Удалить</button>
      </div>
      <hr>
    `;
  }
  container.innerHTML = html;

  let total = calculateTotal();
  totalDiv.innerHTML = `<strong>Общая сумма: ${total} ₽</strong>`;

  document.querySelectorAll('.remove-item').forEach(btn => {
    btn.addEventListener('click', function() {
      removeFromCart(this.dataset.index);
      showCart();
    });
  });
}

//Фильтрация

function filterProducts() {
  let categoryFilter = document.getElementById('category-filter');
  if (!categoryFilter) return;

  let category = categoryFilter.value;
  let minPrice = Number(document.getElementById('min-price').value);
  let maxPrice = Number(document.getElementById('max-price').value);
  let wrappers = document.querySelectorAll('.product-wrapper');

  wrappers.forEach(wrapper => {
    let wrapperCategory = wrapper.dataset.category;
    let wrapperPrice = Number(wrapper.dataset.price);

    let match = true;
    if (category !== 'all' && wrapperCategory !== category) match = false;
    if (wrapperPrice < minPrice || wrapperPrice > maxPrice) match = false;

    wrapper.style.display = match ? '' : 'none';
  });
}

//Обработчики при загрузке

document.addEventListener('click', function(e) {
  if (e.target.classList.contains('add-to-cart')) {
    addToCart(e.target.dataset.id);
  }
});

document.addEventListener('DOMContentLoaded', function() {
  updateCartCounter();

  //Инициализация фильтра на странице каталога
  if (document.getElementById('apply-filter')) {
    document.getElementById('apply-filter').addEventListener('click', filterProducts);
    filterProducts();
  }

  // Инициализация корзины на странице cart.html
  if (window.location.pathname.includes('cart.html')) {
    showCart();

    document.getElementById('clear-cart')?.addEventListener('click', () => {
      clearCart();
      showCart();
    });

    document.getElementById('checkout')?.addEventListener('click', () => {
      checkout();
      showCart();
    });
  }
});