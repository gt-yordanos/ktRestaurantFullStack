class StickyHeader {
  constructor(selector) {
      this.element = document.querySelector(selector);
      this.scrollListener = this.toggleSticky.bind(this);
      window.addEventListener("scroll", this.scrollListener);
  }

  toggleSticky() {
      this.element.classList.toggle("sticky", window.scrollY > 60);
  }

  destroy() {
      window.removeEventListener("scroll", this.scrollListener);
  }
}

class ScrollTop {
  constructor(selector) {
      this.element = document.querySelector(selector);
      this.scrollListener = this.toggleScrollTop.bind(this);
      window.addEventListener("scroll", this.scrollListener);
  }

  toggleScrollTop() {
      this.element.classList.toggle("scrollTop", window.scrollY > 190);
  }

  destroy() {
      window.removeEventListener("scroll", this.scrollListener);
  }
}

class DarkMode {
  constructor(darkModeSelector, lightModeSelector) {
      this.darkModeBtn = document.querySelector(darkModeSelector);
      this.lightModeBtn = document.querySelector(lightModeSelector);
      if (this.darkModeBtn) {
          this.darkModeBtn.addEventListener('click', this.toggleDarkMode.bind(this));
      }
  }

  toggleDarkMode(event) {
      event.preventDefault(); // Prevent page refresh
      document.documentElement.style.setProperty('--main-color', '#ff9f0d');
      document.documentElement.style.setProperty('--text-color', '#fff');
      document.documentElement.style.setProperty('--other-color', '#212121');
      document.documentElement.style.setProperty('--second-color', '#9e9e9e');
      document.documentElement.style.setProperty('--bg-color', '#111111');

      if (this.lightModeBtn) {
          this.lightModeBtn.style.display = 'inline-block';
      }
      if (this.darkModeBtn) {
          this.darkModeBtn.style.display = 'none';
      }
  }
}

class LightMode extends DarkMode {
  constructor(lightModeSelector, lightDarkIconSelector) {
      super(lightModeSelector, lightDarkIconSelector);
      this.lightDarkIcon = document.querySelector(lightDarkIconSelector);
      if (this.lightDarkIcon) {
          this.lightDarkIcon.addEventListener('click', this.toggleDarkMode.bind(this));
      }
  }

  toggleDarkMode(event) {
      event.preventDefault(); // Prevent page refresh
      document.documentElement.style.setProperty('--main-color', '#ff9f0d');
      document.documentElement.style.setProperty('--text-color', '#000000');
      document.documentElement.style.setProperty('--other-color', '#7b8882');
      document.documentElement.style.setProperty('--second-color', '#111111');
      document.documentElement.style.setProperty('--bg-color', '#eafdf4');

      if (this.lightModeBtn) {
          this.lightModeBtn.style.display = 'none';
      }
      if (this.darkModeBtn) {
          this.darkModeBtn.style.display = 'inline-block';
      }
  }
}

class MenuToggle {
  constructor(menuSelector, navlistSelector) {
      this.menu = document.querySelector(menuSelector);
      this.navlist = document.querySelector(navlistSelector);
      if (this.menu) {
          this.menu.onclick = this.toggleMenu.bind(this);
      }
  }

  toggleMenu() {
      this.menu.classList.toggle('bx-x');
      this.navlist.classList.toggle('open');
  }
}

class CartManager {
  constructor(cartIconSelector, hideCartBtnSelector) {
      this.cartIcon = document.querySelector(cartIconSelector);
      this.cart = document.querySelector('.cart');
      this.hideCartBtn = document.querySelector(hideCartBtnSelector);
      if (this.cartIcon) {
          this.cartIcon.addEventListener('click', this.toggleCart.bind(this));
      }
      if (this.hideCartBtn) {
          this.hideCartBtn.addEventListener('click', this.hideCart.bind(this));
      }
  }

  toggleCart(event) {
      event.preventDefault(); // Prevent the default behavior of the anchor element
      const cartLeft = window.getComputedStyle(this.cart).getPropertyValue('left');
      this.cart.style.left = (cartLeft === '0px') ? '-600px' : '0px';
  }

  hideCart(event) {
      event.preventDefault(); // Prevent the default behavior of the anchor element
      this.cart.style.left = '-600px'; // Move the cart to the left of -600px
  }
}

class TopIcon {
  constructor(selector, cartItemsSelector, cartCountSelector) {
      this.icons = document.querySelectorAll(selector);
      this.cartItems = document.querySelector(cartItemsSelector);
      this.cartCount = document.querySelector(cartCountSelector);
      this.count = 0;
      this.icons.forEach(icon => {
          icon.addEventListener('click', this.handleClick.bind(this));
      });
  }

  handleClick(event) {
      event.preventDefault(); // Prevent the default behavior of the anchor element
      if (event.target.classList.contains('bxs-cart-add')) {
          this.count++;
          this.updateCartCount();
          const item = event.target.closest('.row').querySelector('h3').textContent;
          const price = event.target.closest('.row').querySelector('.price').textContent;
          const imageSrc = event.target.closest('.row').querySelector('img').src;
          this.addToCart(item, price, imageSrc);
          event.target.classList.remove('bxs-cart-add');
          event.target.classList.add('bxs-cart');
      } else if (event.target.classList.contains('bxs-cart')) {
          this.count--;
          this.updateCartCount();
          const itemToRemove = event.target.closest('.row').querySelector('h3').textContent;
          const cartItemsToRemove = this.cartItems.querySelectorAll('.cart-item');
          cartItemsToRemove.forEach(cartItem => {
              if (cartItem.querySelector('p').textContent === itemToRemove) {
                  cartItem.remove();
              }
          });
          event.target.classList.remove('bxs-cart');
          event.target.classList.add('bxs-cart-add');
      }
  }

  updateCartCount() {
      this.cartCount.textContent = this.count;
  }

  addToCart(item, price, imageSrc) {
      // Create cart item element
      const cartItem = document.createElement('div');
      cartItem.classList.add('cart-item');

      // Create image container
      const imageDiv = document.createElement('div');
      imageDiv.classList.add('item-image');
      const image = document.createElement('img');
      image.src = imageSrc;
      image.width = 200;
      image.height = 200;
      imageDiv.appendChild(image);

      // Create name and price
      const infoDiv = document.createElement('div');
      infoDiv.classList.add('item-info');
      infoDiv.innerHTML = `
          <p>${item}</p>
          <p>${price}</p>
      `;

      // Create remove button
      const removeBtn = document.createElement('button');
      removeBtn.textContent = 'Remove';
      removeBtn.classList.add('remove-btn');

      // Create order button
      const orderBtn = document.createElement('button');
      orderBtn.textContent = 'Order';
      orderBtn.classList.add('order-btn');

      // Create button container
      const btnDiv = document.createElement('div');
      btnDiv.classList.add('btnDiv');
      btnDiv.appendChild(removeBtn); // Append the remove button
      btnDiv.appendChild(orderBtn); // Append the order button

      // Append image, info, and button to cart item
      cartItem.appendChild(imageDiv);
      cartItem.appendChild(infoDiv);
      cartItem.appendChild(btnDiv);

      // Append cart item to cart
      this.cartItems.appendChild(cartItem);
  }
}

class prepareOrder {
  constructor(orderButtonSelector) {
      this.orderButton = document.querySelector(orderButtonSelector);
      if (this.orderButton) {
          this.orderButton.addEventListener('click', this.processOrder.bind(this));
      }
  }

  processOrder() {
      const foodName = document.getElementById("foodName").innerText;
      const priceText = document.getElementById("price").innerText;
      const price = parseFloat(priceText.replace(' Br', ''));
      const quantityInput = document.getElementById("quantity");
      const quantity = parseInt(quantityInput.value);
      const dormNumber = sessionStorage.getItem("dormNumber");
      const dormBlock = sessionStorage.getItem("dormBlock");
      const orderData = {
          foodName: foodName,
          price: price,
          quantity: quantity,
          dormNumber: dormNumber,
          dormBlock: dormBlock
      };
      fetch("process_order.php", {
          method: "POST",
          headers: {
              "Content-Type": "application/json"
          },
          body: JSON.stringify(orderData)
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              alert("Order placed successfully!");
          } else {
              alert("Failed to place order. Please try again later.");
          }
      })
      .catch(error => {
          console.error("Error processing order:", error);
          alert("An error occurred while processing the order. Please try again later.");
      });
  }
}

// Usage
const header = new StickyHeader("header");
const scroller = new ScrollTop(".scroll");
const darkMode = new DarkMode('.dark-mode', '.light-mode');
const lightMode = new LightMode('.light-mode', '.light-mode i');
const menuToggle = new MenuToggle('#menu-icon', '.navlist');
const cartManager = new CartManager('.bx-cart', '.hide-cart-btn');
const topIconManager = new TopIcon('.top-icon', '#cart-items', '#cart-count');
const orderManager = new prepareOrder("#orderButton");