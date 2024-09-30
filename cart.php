<?php
  
    
  
    $customerId = $_SESSION['customer_id']; // Assuming you store customer ID in the session
    
    // Fetch cart items
    $cartItems = $admin->getCartItemsByCustomerId($customerId);
    
    // Display cart items
    if (!empty($cartItems)) {
        foreach ($cartItems as $item) {
            echo "<li class='header-cart-item flex-w flex-t m-b-12'>
                    <div class='header-cart-item-img'>
                        <img src='images/item-cart-01.jpg' alt='IMG'>
                    </div>
                    <div class='header-cart-item-txt p-t-8'>
                        <a href='#' class='header-cart-item-name m-b-18 hov-cl1 trans-04'>
                            {$item['name']}
                        </a>
                        <span class='header-cart-item-info'>
                            1 x \${$item['price']}
                        </span>
                    </div>
                  </li>";
        }
    } else {
        echo "Your cart is empty.";
    }
    
    // Example of adding a package to cart
    if (isset($_POST['add_to_cart'])) {
        $packageId = $_POST['package_id']; // Assume this comes from your package details form
        $result = $admin->addToCart($customerId, $packageId);
        echo $result; // Show feedback message
    }
?>

<div class="wrap-header-cart js-panel-cart">
    <div class="s-full js-hide-cart"></div>

    <div class="header-cart flex-col-l p-l-65 p-r-25">
        <div class="header-cart-title flex-w flex-sb-m p-b-8">
            <span class="mtext-103 cl2">Your Cart</span>

            <div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
                <i class="zmdi zmdi-close"></i>
            </div>
        </div>

        <div class="header-cart-content flex-w js-pscroll">
            <ul class="header-cart-wrapitem w-full">
                <?php
                include 'adminback.php';
                $obj = new adminback();
                
                // Assume customer_id is stored in session after login
                $customer_id = $_SESSION['customer_id']; 
                $cartItems = $obj->getCartItemsByCustomerId($customer_id); // Method to get cart items by customer ID
                
                if (!empty($cartItems)) {
                    foreach ($cartItems as $item) {
                        echo '<li class="header-cart-item flex-w flex-t m-b-12">';
                        echo '    <div class="header-cart-item-img">';
                        echo '        <img src="images/' . $item['image'] . '" alt="' . $item['name'] . '">';
                        echo '    </div>';
                        echo '    <div class="header-cart-item-txt p-t-8">';
                        echo '        <a href="#" class="header-cart-item-name m-b-18 hov-cl1 trans-04">' . $item['name'] . '</a>';
                        echo '        <span class="header-cart-item-info">' . $item['quantity'] . ' x $' . $item['price'] . '</span>';
                        echo '    </div>';
                        echo '</li>';
                    }
                } else {
                    echo '<li class="header-cart-item flex-w flex-t m-b-12">Your cart is empty.</li>';
                }
                ?>
            </ul>
            
            <div class="w-full">
                <div class="header-cart-total w-full p-tb-40">
                    <?php
                    $total = $obj->getCartTotalByCustomerId($customer_id); // Method to get total cart amount
                    echo 'Total: $' . $total;
                    ?>
                </div>

                <div class="header-cart-buttons flex-w w-full">
                    <a href="shopping-cart.php" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
                        View Cart
                    </a>

                    <a href="checkout.php" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
                        Check Out
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

