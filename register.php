<?php
include'connect.php';
if (isset($_COOKIE['user_id'])){
    $user_id=$_COOKIE['user_id'];
}else{
    $user_id='';
    
}

if(isset($_POST['submit'])){
    $id = unique_id();


$name = $_POST['name'];
$name = isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') : '';


$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : '';
$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');


$pass = $_POST['pass'];
$pass = isset($_POST['pass']) ? htmlspecialchars($_POST['pass'], ENT_QUOTES, 'UTF-8') : '';


$cpass = $_POST['cpass'];
$cpass = isset($_POST['cpass']) ? htmlspecialchars($_POST['cpass'], ENT_QUOTES, 'UTF-8') : '';


$image = $_FILES['image']['name'];
$image = isset($_FILES['image']['name']) ? htmlspecialchars($_FILES['image']['name'], ENT_QUOTES, 'UTF-8') : '';


$ext = pathinfo($image, PATHINFO_EXTENSION);

$rename = unique_id() . '.' . $ext;


$image_size = $_FILES['image']['size'];
$image_tmp_name = $_FILES['image']['tmp_name'];
$image_folder = '../uploaded_files/' . $rename;


$select_user = $conn->prepare("SELECT * FROM users WHERE email = ?");
$select_user->execute([$email]);

if($select_user->rowCount() > 0) {
    $warning_msg[] = 'Email already exists';
} else {
    if($pass != $cpass) {
        $warning_msg[] = 'Confirm password does not match!';
    } else {
        $hashed_pass=sha1($pass);
        $insert_user = $conn->prepare("INSERT INTO users(user_id, name, email, password, image) VALUES (?, ?, ?, ?, ?)");
        $insert_user->execute([$id, $name, $email, $hashed_pass, $rename]);
        
        if ($insert_user) {

            move_uploaded_file($image_tmp_name, $image_folder);
            $success_msg[] = '  New User Registered !';
        } else {
            $error_msg[] = 'Registration failed!';
        }
    }
}
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ice Cream Delights - Registration Page</title>
     <link rel="stylesheet" type="text/css" href="../css/user_style.css">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
      <?php include'user_header.php';?>
      <div class="banner">
    <div class="detail">
        <h1 >register</h1>
        <p>join us today to get access to exclusive offers, features, and more, it's quik and<br>easy to create an account and you'll be able  to enjoy all the </p>
        <span>
            <a href="home.php">home</a>
            <i class="bx bx-right-arrow-alt"></i>register
        </span>

    </div>

</div>

    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data" class="register">
            <h2>Register Now</h2>
            <div class="flex">
                <div class="col">
                    <div class="input-field">
                        <p>Your Name <span>*</span></p>
                        <input type="text" name="name" placeholder="Enter your name" maxlength="50" required class="box">
                    </div>
                    <div class="input-field">
                        <p>Your Email <span>*</span></p>
                        <input type="text" name="email" placeholder="Enter your email" maxlength="50" required class="box">
                    </div>
                </div>

                <div class="col">
                    <div class="input-field">
                        <p>Your Password <span>*</span></p>
                        <input type="password" name="pass" placeholder="Enter your password" maxlength="50" required class="box">
                    </div>
                    <div class="input-field">
                        <p>Confirm Password <span>*</span></p>
                        <input type="password" name="cpass" placeholder="Confirm your password" maxlength="50" required class="box">
                    </div>
                </div>
            </div>

            <div class="input-field">
                <p>Your Picture <span>*</span></p>
                <input type="file" name="image" accept="image/*" required class="box">
            </div>

            <p class="link">Already have an account? <a href="login.php">login now</a></p>
            <input type="submit" name="submit" value="register now" class="btn">
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

   <?php include 'alert.php'; ?>
   <!--custom js link-->
   <script >let profile = document.querySelector('.header .flex .profile-detail');
document.querySelector('#user-btn').onclick = () => {
    profile.classList.toggle('active');
    searchForm.classList.remove('active');
};

let searchForm = document.querySelector('.header .flex .search-form');
document.querySelector('#search-btn').onclick = () => {
    searchForm.classList.toggle('active');
    profile.classList.remove('active');
};

let navbar = document.querySelector('.navbar');
document.querySelector('#menu-btn').onclick = () => {
    navbar.classList.toggle('active');
};</script>
   <?php include 'footer.php'; ?>

</body>
</html>