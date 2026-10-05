<?php
include'connect.php';
if (isset($_COOKIE['user_id'])){
    $user_id=$_COOKIE['user_id'];
}else{
    $user_id='';
    
}
if (isset($_POST['add_to_cart'])) {
    if ($user_id != '') {
        $id = unique_id();
        $product_id = $_POST['product_id'];
        $qty=$_POST['qty'];
        $qty=filter_var($qty);




        $verify_cart = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ? AND product_id = ?");
        $verify_cart->execute([$user_id, $product_id]);

        $max_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $max_cart_items->execute([$user_id]);

        if ($verify_cart->rowCount() > 0) {
            $warning_msg[] = 'Product already exists in your cart';
        } elseif ($max_cart_items->rowCount() > 20) {
            $warning_msg[] = ' your cart is full';
        } elseif ($user_id != '') {
            $select_price = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
            $select_price->execute([$product_id]);

            $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);
            $insert_cart = $conn->prepare("INSERT INTO `cart`(id, user_id, product_id, price,qty) VALUES (?,?,?,?,?)");
            $insert_cart->execute([$id, $user_id, $product_id, $fetch_price['price'],$qty]);
            $success_msg[] = 'Product added to your cart successfully';
        }

    } else {
        
    }
}

?>