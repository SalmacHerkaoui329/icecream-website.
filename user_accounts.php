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
    <title>Ice Cream Delights - User Accounts</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Boxicons CDN link -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>

    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>

        <section class="user-container">
            <div class="heading">
                <h1>Registered Users</h1>
                <img src="../image/separator-img.png" alt="">
            </div>

            <div class="box-container">
                <?php
                $select_users = $conn->prepare("SELECT * FROM `users`");
                $select_users->execute();

                if ($select_users->rowCount() > 0) {
                    while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)) {
                        $user_id = $fetch_users['user_id'];
                ?>
                <div class="box">
                    <img src="../uploaded_files/<?= htmlspecialchars($fetch_users['image']); ?>" alt="User Image">
                    <p>User ID: <span><?= htmlspecialchars($user_id); ?></span></p>
                    <p>User name: <span><?= htmlspecialchars($fetch_users['name']); ?></span></p>
                    <p>User Email: <span><?= htmlspecialchars($fetch_users['email']); ?></span></p>
                </div>
                <?php
                    }
                } else {
                    echo '<div class="empty">
                        <p>No users registered yet!</p>
                    </div>';
                }
                ?>
            </div>
        </section>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</body>
</html>