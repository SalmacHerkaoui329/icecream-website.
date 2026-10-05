<?php
if (isset($_COOKIE['seller_id'])) {
    $seller_id = $_COOKIE['seller_id'];
} else {
    $seller_id = ''; // aw tqeddi tdiri header('Location: login.php') ila bghiti t-outorisi ghir li mlogyin
}
?>


<header>
    
    <div class="logo">
        <img src="../image/logo.png" width="200">
    </div>
    
    <div class="right">
        <div class="bx bxs-user" id="user-btn"></div>
        <div class="toggle-btn"><i class="bx bxs-menu"></i></div>
    </div>
    
    <div class="profile-detail">
        <?php
            $select_profile = $conn->prepare("SELECT * FROM `sellers` WHERE id = ?");
            $select_profile->execute([$seller_id]);
            if($select_profile->rowCount() > 0){
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
        
        <div class="profile">
            <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" class="logo-img" width="150">
            <p><?= $fetch_profile['name']; ?></p>
            <div class="flex-btn">
                <a href="profile.php" class="btn">profile</a>
                <a href="../components/admin_logout.php" onclick="return confirm('logout from this website');" class="btn">logout</a>
            </div>
        </div>
        <?php } ?>
    </div>
</header>
<div class="sidebar-container">
    <div class="sidebar">
        <?php
            $select_profile = $conn->prepare("SELECT * FROM `sellers` WHERE id = ?");
            $select_profile->execute([$seller_id]);
            if($select_profile->rowCount() > 0){
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
         <div class="profile">
            <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" class="logo-img" width="100">
            <p><?= $fetch_profile['name']; ?></p>
        </div>
        <?php } ?>
        <h5>Menu</h5>
        <div class="navbar">
            <ul>
            <li><a href="dashboard.php"><i class="bx bxs-home-smile"></i>Dashboard</a></li>
            <li><a href="add_products.php"><i class="bx bxs-shopping-bags"></i>Add products</a></li>
            <li><a href="view_products.php"><i class="bx bxs-food-menu"></i>view products</a></li>
            <li><a href="user_accounts.php"><i class="bx bxs-user-detail"></i>Accounts</a></li>
            <li><a href="../components/admin_logout.php"  onclick =" return confirm('logout from this website');"><i class="bx bxs-log-out"></i>Logout</a></li>
            </ul>
        </div>
        <h5>Find us</h5>
        <div class="social-links">
            <i class="bx bxl-facebook"></i>
            <i class="bx bxl-instagram"></i>
            <i class="bx bxl-linkedin"></i>
            <i class="bx bxl-twitter"></i>
            <i class="bx bxl-pinterest-alt"></i>
        </div>


    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
   let userBtn = document.querySelector('#user-btn');
   let profileDetail = document.querySelector('.profile-detail');

   if(userBtn && profileDetail) {
      userBtn.onclick = function(e) {
         e.stopPropagation(); // Kat-7mi l-clic y-mchi l blasa khrâ
         profileDetail.classList.toggle('active');
         console.log("L-clic khdam n9i!"); // Gha t-ban lik f console ila t-clika
      }
      

      document.addEventListener('click', function() {
         profileDetail.classList.remove('active');
      });
      profileDetail.onclick = function(e) {
         e.stopPropagation();
      }
   } else {
      console.log("Malkinahomch! Chofi wash s-smiyat s7a7");
   }
});
</script>