<?php
   include '../components/connect.php';

   if(isset($_COOKIE['seller_id'])) {
      $seller_id = $_COOKIE['seller_id'];
   } else {
      $seller_id = '';
      header('Location: login.php');
      exit();
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ice Cream Delights - Seller Accounts</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Boxicons CDN link -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>

    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>

        <section class="seller-container">
            <div class="heading">
                <h1>Registered Sellers</h1>
                <img src="../image/separator-img.png" alt="">
            </div>

            <div class="box-container">
                <?php
                $select_sellers = $conn->prepare("SELECT * FROM `sellers`");
                $select_sellers->execute();

                if ($select_sellers->rowCount() > 0) {
                    while($fetch_sellers = $select_sellers->fetch(PDO::FETCH_ASSOC)) {
                        $Seller_id = $fetch_sellers['id'];
                ?>
                <div class="box">
                    <img src="../uploaded_files/<?= htmlspecialchars($fetch_sellers['image']); ?>" alt="Seller Image">
                    <p>Seller ID: <span><?= htmlspecialchars($Seller_id); ?></span></p>
                    <p>Seller name: <span><?= htmlspecialchars($fetch_sellers['name']); ?></span></p>
                    <p>Seller Email: <span><?= htmlspecialchars($fetch_sellers['email']); ?></span></p>
                </div>
                <?php
                    }
                } else {
                    echo '<div class="empty">
                        <p>No Sellers registered yet!</p>
                    </div>';
                }
                ?>
            </div>
        </section>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</body>
</html>