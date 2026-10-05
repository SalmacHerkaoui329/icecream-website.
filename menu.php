<?php
include 'connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}

include 'add_wishlist.php';
include 'add_cart.php';
?>

<!DOCTYPE TYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ice Cream Delights - Our shop page</title>
    <link rel="stylesheet" type="text/css" href="../css/user_style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Boxicons CDN link -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>

<?php include 'user_header.php'; ?>
<div class="banner">
    <div class="detail">
        <h1>our shop</h1>
        <p>Explore our wide range of products. Whether you're looking for new arrivals, <br> bestsellers, or exclusive items, we have something for everyone. Shop now and enjoy great deals.</p>
        <span>
            <a href="home.php">home</a>
            <i class="bx bx-right-arrow-alt"></i>our shop
        </span>
    </div>
</div>

<div class="products">
    <div class="heading">
        <h1>Our Latest Flavour</h1>
        <img src="../image/separator-img.png">
    </div>

    <div class="box-container">
        <?php
        $select_products = $conn->prepare("SELECT * FROM `products` WHERE statuts = ?");
        $select_products->execute(['active']);
        if ($select_products->rowCount() > 0) {
            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <form action="" method="post" class="box <?php if($fetch_products['stock'] == 0) { echo 'disabled'; } ?>">
            <img src="../uploaded_files/<?= $fetch_products['image']; ?>" class="image">
            <?php if ($fetch_products['stock'] > 9) { ?>
                <span class="stock" style="color: green;">In stock</span>
            <?php } elseif ($fetch_products['stock'] == 0) { ?>
                <span class="stock" style="color: red;">Out of stock</span>
            <?php } else { ?>
                <span class="stock" style="color: red;">Hurry, only <?= $fetch_products['stock']; ?> left!</span>
            <?php } ?>

            <div class="content">
                <img src="../image/shape-19.png" alt="" class="shap">
                <div class="button">
                    <div>
                        <h3 class="name"><?= $fetch_products['name']; ?></h3>
                    </div>
                    <button type="submit" name="add_to_cart"><i class="bx bx-cart"></i></button>
                    <button type="submit" name="add_to_wishlist"><i class="bx bx-heart"></i></button>
                    <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="bx bxs-show"></a>
                </div>
                <p class="price">Price: $<?= $fetch_products['price']; ?></p>
                <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>">
                <div class="flex-btn">
                    <a href="checkout.php?get_id=<?= $fetch_products['id']; ?>" class="btn">Buy Now</a>
                    <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty">
                </div>
            </div>
        </form>
        <?php
            }
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
<script src="http://unpk.com/swiper@7/swiper-bundle.min.js"></script>

<?php include 'footer.php'; ?>


<script src="js/user_script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include 'alert.php'; ?>
</body>
</html>