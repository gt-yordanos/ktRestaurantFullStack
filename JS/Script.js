const header = document.querySelector("header");
window.addEventListener("scroll", function(){
  header.classList.toggle("sticky", window.scrollY > 60);
});

const scroller = document.querySelector(".scroll");
window.addEventListener("scroll", function () {
  scroller.classList.toggle("scrollTop", window.scrollY > 190);
});

// Check if dark mode button exists before selecting it
const darkModeBtn = document.querySelector('.dark-mode');
if (darkModeBtn) {
  darkModeBtn.addEventListener('click', (event) => {
    event.preventDefault(); // Prevent page refresh
    document.documentElement.style.setProperty('--main-color', '#ff9f0d');
    document.documentElement.style.setProperty('--text-color', '#fff');
    document.documentElement.style.setProperty('--other-color', '#212121');
    document.documentElement.style.setProperty('--second-color', '#9e9e9e');
    document.documentElement.style.setProperty('--bg-color', '#111111');
    
    // Show light mode button, hide dark mode button
    const lightModeBtn = document.querySelector('.light-mode');
    if (lightModeBtn) {
      lightModeBtn.style.display = 'inline-block';
    }
    if (darkModeBtn) {
      darkModeBtn.style.display = 'none';
    }
  });
}

const lightModeBtn = document.querySelector('.light-mode');
const lightDarkIcon = document.querySelector('.light-mode i');

if (lightModeBtn) {
  lightModeBtn.addEventListener('click', (event) => {
    event.preventDefault(); // Prevent page refresh
    document.documentElement.style.setProperty('--main-color', '#ff9f0d');
    document.documentElement.style.setProperty('--text-color', '#000000');
    document.documentElement.style.setProperty('--other-color', '#7b8882');
    document.documentElement.style.setProperty('--second-color', '#111111');
    document.documentElement.style.setProperty('--bg-color', '#eafdf4');
    
    // Toggle dark mode icon
    if (lightDarkIcon) {
      lightDarkIcon.classList.toggle('bxs-sun');
      lightDarkIcon.classList.toggle('bxs-moon');
    }
    
    header.style.backgroundColor = '#7b8882';
  });
}

const menu = document.querySelector('#menu-icon');
const navlist = document.querySelector('.navlist');

menu.onclick = () => {
  menu.classList.toggle('bx-x');
  navlist.classList.toggle('open');
};

// Add event listener to bx-cart icon
const cartIcon = document.querySelector('.bx-cart');
if (cartIcon) {
  cartIcon.addEventListener('click', (event) => {
    event.preventDefault(); // Prevent the default behavior of the anchor element
    const cart = document.querySelector('.cart');
    const cartLeft = window.getComputedStyle(cart).getPropertyValue('left');
    if (cartLeft === '0px') {
      cart.style.left = '-600px'; // Hide the cart by setting left to -600px
    } else {
      cart.style.left = '0px'; // Display the cart by setting left to 0px
    }
  });
}

// Add event listener to hide cart button
const hideCartBtn = document.querySelector('.hide-cart-btn');
if (hideCartBtn) {
  hideCartBtn.addEventListener('click', (event) => {
    event.preventDefault(); // Prevent the default behavior of the anchor element
    const cart = document.querySelector('.cart');
    cart.style.left = '-600px'; // Move the cart to the left of -600px
  });
}

document.addEventListener('DOMContentLoaded', function () {
  const topIcons = document.querySelectorAll('.top-icon');
  const cartCount = document.getElementById('cart-count');
  const cartItems = document.getElementById('cart-items');
  let count = 0;

  function updateCartCount() {
    cartCount.textContent = count;
  }

  topIcons.forEach(icon => {
    icon.addEventListener('click', function (event) {
      event.preventDefault(); // Prevent the default behavior of the anchor element
      if (this.querySelector('i').classList.contains('bxs-cart-add')) {
        count++;
        updateCartCount();
        const item = this.closest('.row').querySelector('h3').textContent;
        const price = this.closest('.row').querySelector('.price').textContent;
        const imageSrc = this.closest('.row').querySelector('img').src;
        
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
        cartItems.appendChild(cartItem);
        
        // Change the icon class
        this.querySelector('i').classList.remove('bxs-cart-add');
        this.querySelector('i').classList.add('bxs-cart');
      } else if (this.querySelector('i').classList.contains('bxs-cart')) {
        count--;
        updateCartCount();
        const itemToRemove = this.closest('.row').querySelector('h3').textContent;
        const cartItemsToRemove = cartItems.querySelectorAll('.cart-item');
        cartItemsToRemove.forEach(cartItem => {
          if (cartItem.querySelector('p').textContent === itemToRemove) {
            cartItem.remove();
          }
        });

        // Change the icon class
        this.querySelector('i').classList.remove('bxs-cart');
        this.querySelector('i').classList.add('bxs-cart-add');
      }
    });
  });

  // Remove item from cart when remove button is clicked
  cartItems.addEventListener('click', (event) => {
    if (event.target.classList.contains('remove-btn')) {
      const itemToRemove = event.target.closest('.cart-item');
      const itemName = itemToRemove.querySelector('p').textContent;
      itemToRemove.remove();
      count--;
      updateCartCount();
      // Change the icon class back to bxs-cart-add
      topIcons.forEach(icon => {
        if (icon.closest('.row').querySelector('h3').textContent === itemName) {
          icon.querySelector('i').classList.remove('bxs-cart');
          icon.querySelector('i').classList.add('bxs-cart-add');
        }
      });
    }
  });

  // Order button functionality (just an alert for now)
  cartItems.addEventListener('click', (event) => {
    if (event.target.classList.contains('order-btn')) {
      alert('Order Placed!');
      // You can add further functionality here, like sending the order data to a server
      // or redirecting the user to a checkout page
    }
  });
});
