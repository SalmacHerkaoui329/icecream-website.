<?php
include 'connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}
include 'add_cart.php';
if (isset($_POST['delete_item'])) {
    $wishlist_id = $_POST['wishlist_id'];
    $wishlist_id = filter_var($wishlist_id, FILTER_SANITIZE_STRING);

    $verify_delete = $conn->prepare("SELECT * FROM `wishlist` WHERE id = ?");
    $verify_delete->execute([$wishlist_id]);

    if ($verify_delete->rowCount() > 0) {
        $delete_wishlist_id = $conn->prepare("DELETE FROM `wishlist` WHERE id = ?");
        $delete_wishlist_id->execute([$wishlist_id]);
        $success_msg[] = 'item removed from wishlist successfully';
    } else {
        $warning_msg[] = ' item already removed or does not exict ';
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ice Cream Delights - user cart page</title>
    <link rel="stylesheet" type="text/css" href="../css/user_style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>

<?php include 'user_header.php'; ?>
<div class="banner">
    <div class="detail">
        <h1>my wishlist</h1>
        <p>Your shopping cart is where you can review and manage the items you've <br>selected. Easily adjust quantities, remove products, or proceed to checkout.</p>
        <span>
            <a href="home.php">home</a>
            <i class="bx bx-right-arrow-alt"></i>my wishlist
        </span>
    </div>
</div>
<div class="products">
    <div class="heading">
        <h1>My wishlistt</h1>
        <img src="../image/separator-img.png" >
    </div>
    <div class="box-container">
        <?php
        $grand_total=0;
        $select_wishlist= $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
       $select_wishlist->execute(['user_id']);
       if ($select_wishlist->rowCount() > 0) {
            while ($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)) {
                $select_products=$conn->prepare("SELECT * FROM `products` WHERE id = ? ");
                $select_products->execute([$fetch_wishlist['product_id']]);

                if ($select_products->rowCount() > 0) {
                $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
        ?>
        <form action="" method="post" class="box <?php if($fetch_products['stock'] == 0) { echo 'disabled'; } ?>">
            <input type="hidden" name="wishlist_id" value="<?= $fetch_wishlist['id']; ?>">
            <img src="../uploaded_files/<?= $fetch_products['name']; ?>" class="image" >
            <?php if ($fetch_products['stock'] > 9) { ?>
                <span class="stock" style="color: green;">In stock</span>
            <?php } elseif ($fetch_products['stock'] == 0) { ?>
                <span class="stock" style="color: red;">Out of stock</span>
            <?php } else { ?>
                <span class="stock" style="color: red;">Hurry, only <?= $fetch_products['stock']; ?> left!</span>
            <?php } ?>

            <div class="content">
                <img src="../image/shape-19.png" alt="" class="shap">
                <div>
                    <h3 class="name"> <?= $fetch_products['name']; ?></h3>
                </div>
                <div>
                    <button type="submit" name="add_to_cart">
                        <i class="bx bx-cart"></i>
                    </button>
                    <a href="view_page?pid=<?= $fetch_products['id']; ?>" class="bx bxs-show"></a>
                    <button type="submit" name="delete_item" class="btn" onclick="return confirm('Remove from wishlist?');"><i class="bx bx-x"></i></button>

                </div>
                <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>">
                <div class="flex">
               <p class="price">Price: <?= $fetch_products['price']; ?></p>
                </div>
                <div class="flex">
                    <input type="hidden" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty">
                    <a href="checkout.php?get_id=<?= $fetch_products['id']; ?>" class="btn">buy now</a>
            
                
                </div>
            </div>
        </form>
        <?php
                }
            }
            $grand_total+=$fetch_wishlist['price'];
        } else {
            echo '
            <div class="empty">
                <p>No products added yet!</p>
            </div>
            ';
        }
       
       
       ?>
    </div> 
    </div>
    <?php include 'footer.php'; ?>


<script src="js/user_script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include 'alert.php'; ?>
</body>
</html>