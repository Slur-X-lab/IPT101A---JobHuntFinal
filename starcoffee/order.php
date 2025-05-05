<?php
// Start session to store order information
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Handle adding products to cart
if (isset($_GET['product']) && isset($_GET['price'])) {
    $product = $_GET['product'];
    $price = $_GET['price'];
    
    // Add to cart or increment quantity
    if (isset($_SESSION['cart'][$product])) {
        $_SESSION['cart'][$product]['quantity']++;
    } else {
        $_SESSION['cart'][$product] = array(
            'price' => $price,
            'quantity' => 1
        );
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order - StarCoffee</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .order-container {
            max-width: 1200px;
            margin: 120px auto 50px;
            padding: 20px;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .product-card {
            border-radius: 15px;
            padding: 20px;
            background-color: #f9f9f9;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .product-card img {
            max-width: 150px;
            margin: 0 auto;
        }
        .quantity-control {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 15px 0;
        }
        .quantity-btn {
            width: 30px;
            height: 30px;
            background: #7E6A56;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .quantity-input {
            width: 50px;
            text-align: center;
            margin: 0 10px;
            border: 1px solid #ddd;
            padding: 5px;
        }
        .checkout-btn {
            display: block;
            width: 200px;
            margin: 30px auto;
            padding: 15px;
            background-color: #7E6A56;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Header with navigation -->
    <header class="header" id="header">
        <nav class="nav container">
            <a href="index.html" class="nav--logo">STARCOFFEE</a>

            <div class="nav--menu" id="nav-menu">
               <ul class="nav--list">
                  <li>
                     <a href="index.html#home" class="nav--link">HOME</a>
                  </li>

                  <li>
                     <a href="index.html#popular" class="nav--link">POPULAR</a>
                  </li>

                  <li>
                     <a href="index.html#about" class="nav--link">ABOUT US</a>
                  </li>

                  <li>
                     <a href="index.html#products" class="nav--link">PRODUCTS</a>
                  </li>

                  <li>
                     <a href="index.html#contact" class="nav--link">CONTACT</a>
                  </li>
               </ul>

               <!--close button-->
               <div class="nav--close" id="nav-close">
                  <i class="ri-close-large-line"></i>
               </div>
            </div>

            <!--toggle button-->
            <div class="nav--toggle" id="nav-toggle">
               <i class="ri-apps-2-fill"></i>
            </div>
        </nav>
    </header>

    <main>
        <div class="order-container">
            <h2 class="section__title">OUR PRODUCTS</h2>
            
            <div class="product-grid">
                <!-- Product 1 -->
                <div class="product-card">
                    <img src="assets/img/products-coffee-1.png" alt="ICED COFFEE MOCHA">
                    <h3>ICED COFFEE MOCHA</h3>
                    <p>Rich espresso combined with chocolate flavor, milk and ice.</p>
                    <p>₱49.90</p>
                    <div class="quantity-control">
                        <button class="quantity-btn minus" data-product="ICED COFFEE MOCHA">-</button>
                        <input type="number" class="quantity-input" value="<?php echo isset($_SESSION['cart']['ICED COFFEE MOCHA']) ? $_SESSION['cart']['ICED COFFEE MOCHA']['quantity'] : 0; ?>" min="0" data-product="ICED COFFEE MOCHA" data-price="49.90">
                        <button class="quantity-btn plus" data-product="ICED COFFEE MOCHA">+</button>
                    </div>
                </div>
                
                <!-- Product 2 -->
                <div class="product-card">
                    <img src="assets/img/products-coffee-2.png" alt="COFFEE WITH CREAM">
                    <h3>COFFEE WITH CREAM</h3>
                    <p>Smooth coffee topped with rich, velvety cream.</p>
                    <p>₱59.90</p>
                    <div class="quantity-control">
                        <button class="quantity-btn minus" data-product="COFFEE WITH CREAM">-</button>
                        <input type="number" class="quantity-input" value="<?php echo isset($_SESSION['cart']['COFFEE WITH CREAM']) ? $_SESSION['cart']['COFFEE WITH CREAM']['quantity'] : 0; ?>" min="0" data-product="COFFEE WITH CREAM" data-price="59.90">
                        <button class="quantity-btn plus" data-product="COFFEE WITH CREAM">+</button>
                    </div>
                </div>
                
                <!-- Product 3 -->
                <div class="product-card">
                    <img src="assets/img/products-coffee-3.png" alt="CAPPUCCINO COFFEE">
                    <h3>CAPPUCCINO COFFEE</h3>
                    <p>Classic cappuccino with the perfect balance of espresso and foam.</p>
                    <p>₱69.90</p>
                    <div class="quantity-control">
                        <button class="quantity-btn minus" data-product="CAPPUCCINO COFFEE">-</button>
                        <input type="number" class="quantity-input" value="<?php echo isset($_SESSION['cart']['CAPPUCCINO COFFEE']) ? $_SESSION['cart']['CAPPUCCINO COFFEE']['quantity'] : 0; ?>" min="0" data-product="CAPPUCCINO COFFEE" data-price="69.90">
                        <button class="quantity-btn plus" data-product="CAPPUCCINO COFFEE">+</button>
                    </div>
                </div>
                
                <!-- Product 4 -->
                <div class="product-card">
                    <img src="assets/img/products-coffee-4.png" alt="COFFEE WITH MILK">
                    <h3>COFFEE WITH MILK</h3>
                    <p>Our signature coffee blend complemented with creamy milk.</p>
                    <p>₱79.90</p>
                    <div class="quantity-control">
                        <button class="quantity-btn minus" data-product="COFFEE WITH MILK">-</button>
                        <input type="number" class="quantity-input" value="<?php echo isset($_SESSION['cart']['COFFEE WITH MILK']) ? $_SESSION['cart']['COFFEE WITH MILK']['quantity'] : 0; ?>" min="0" data-product="COFFEE WITH MILK" data-price="79.90">
                        <button class="quantity-btn plus" data-product="COFFEE WITH MILK">+</button>
                    </div>
                </div>
                
                <!-- Product 5 -->
                <div class="product-card">
                    <img src="assets/img/products-coffee-5.png" alt="CLASSIC ICED COFFEE">
                    <h3>CLASSIC ICED COFFEE</h3>
                    <p>Our traditional coffee served cold over ice for a refreshing experience.</p>
                    <p>₱89.90</p>
                    <div class="quantity-control">
                        <button class="quantity-btn minus" data-product="CLASSIC ICED COFFEE">-</button>
                        <input type="number" class="quantity-input" value="<?php echo isset($_SESSION['cart']['CLASSIC ICED COFFEE']) ? $_SESSION['cart']['CLASSIC ICED COFFEE']['quantity'] : 0; ?>" min="0" data-product="CLASSIC ICED COFFEE" data-price="89.90">
                        <button class="quantity-btn plus" data-product="CLASSIC ICED COFFEE">+</button>
                    </div>
                </div>
                
                <!-- Product 6 -->
                <div class="product-card">
                    <img src="assets/img/products-coffee-6.png" alt="ICED COFFEE FRAPPE">
                    <h3>ICED COFFEE FRAPPE</h3>
                    <p>Blended iced coffee with a creamy texture for the ultimate treat.</p>
                    <p>₱99.90</p>
                    <div class="quantity-control">
                        <button class="quantity-btn minus" data-product="ICED COFFEE FRAPPE">-</button>
                        <input type="number" class="quantity-input" value="<?php echo isset($_SESSION['cart']['ICED COFFEE FRAPPE']) ? $_SESSION['cart']['ICED COFFEE FRAPPE']['quantity'] : 0; ?>" min="0" data-product="ICED COFFEE FRAPPE" data-price="99.90">
                        <button class="quantity-btn plus" data-product="ICED COFFEE FRAPPE">+</button>
                    </div>
                </div>
            </div>
            
            <a href="checkout.php" class="checkout-btn">PROCEED TO CHECKOUT</a>
        </div>
    </main>
    
    <script>
        // JavaScript to handle quantity changes
        document.addEventListener('DOMContentLoaded', function() {
            // Plus button click
            document.querySelectorAll('.plus').forEach(button => {
                button.addEventListener('click', function() {
                    const product = this.getAttribute('data-product');
                    const input = document.querySelector(`input[data-product="${product}"]`);
                    input.value = parseInt(input.value) + 1;
                    updateCart(product, input.value, input.getAttribute('data-price'));
                });
            });
            
            // Minus button click
            document.querySelectorAll('.minus').forEach(button => {
                button.addEventListener('click', function() {
                    const product = this.getAttribute('data-product');
                    const input = document.querySelector(`input[data-product="${product}"]`);
                    if (parseInt(input.value) > 0) {
                        input.value = parseInt(input.value) - 1;
                        updateCart(product, input.value, input.getAttribute('data-price'));
                    }
                });
            });
            
            // Function to update cart via AJAX
            function updateCart(product, quantity, price) {
                fetch('update_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product=${encodeURIComponent(product)}&quantity=${quantity}&price=${price}`
                });
            }
        });
    </script>
</body>
</html>