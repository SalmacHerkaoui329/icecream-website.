<?php
   include '../components/connect.php';
   if(isset($_COOKIE['seller_id'])) {
      $seller_id = $_COOKIE['seller_id'];
   } else {
      $seller_id = '';
      header('Location: login.php');
      exit();
   }

$select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE id = ? LIMIT 1");
    $select_seller->execute([$seller_id]);
    $fetch_seller = $select_seller->fetch(PDO::FETCH_ASSOC);

    $prev_pass = $fetch_seller['password'];
    $prev_image = $fetch_seller['image'];

if (isset($_POST['submit'])) {
    

    $name = $_POST['name'];

    $name = filter_var($name, FILTER_SANITIZE_STRING); 

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_DEFAULT);

    if (!empty($name)) {
        $update_name=$conn->prepare("UPDATE `sellers` set name =? WHERE id =?");
        $update_name->execute([$name,$seller_id]);
        $success_msg[]='Name updated successfully';
    }

   if (!empty($email)) {
    $select_email = $conn->prepare("SELECT * FROM `sellers` WHERE id != ? AND email = ?");
    $select_email->execute([$seller_id, $email]);
    if ($select_email->rowCount() > 0) {
        $warning_msg[] = 'Email already exists';
    } else {
        $update_email = $conn->prepare("UPDATE `sellers` SET email = ? WHERE id = ?");
        $update_email->execute([$email, $seller_id]);
        $success_msg[] = 'Email updated successfully';
    }
}

$image = $_FILES['image']['name'];
$image = filter_var($image, FILTER_DEFAULT);
$ext = pathinfo($image, PATHINFO_EXTENSION);
$rename = unique_id().'.'.$ext;
$image_size = $_FILES['image']['size'];
$image_tmp_name = $_FILES['image']['tmp_name'];
$image_folder = '../uploaded_files/'.$rename;

if (!empty($image)) {
    if ($image_size > 2000000) {
        $warning_msg[] = 'Image size is too large';
    } else {
        $update_image = $conn->prepare("UPDATE `sellers` SET image = ? WHERE id = ?");
        $update_image->execute([$rename, $seller_id]);
        move_uploaded_file($image_tmp_name, $image_folder);

        if ($prev_image != '' && $prev_image != $rename) {
            unlink('../uploaded_files/'.$prev_image);
        }
        $success_msg[] = 'image updated successfully'; 
    }
}

$empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';

$old_pass = sha1($_POST['old_pass']);
$old_pass = filter_var($old_pass, FILTER_DEFAULT);

$new_pass = sha1($_POST['new_pass']);
$new_pass = filter_var($new_pass, FILTER_DEFAULT);

$cpass = sha1($_POST['cpass']);
$cpass = filter_var($cpass, FILTER_DEFAULT);

if ($old_pass != $empty_pass) {
    if ($old_pass != $prev_pass) {
        $warning_msg[] = 'Old password not matched';
    } elseif ($new_pass != $cpass) {
        $warning_msg[] = 'Password do not match';
    } else {
        if ($new_pass != $empty_pass) {
            $update_pass = $conn->prepare("UPDATE `sellers` SET password = ? WHERE id = ?");
            $update_pass->execute([$cpass, $seller_id]);
            $success_msg[] = 'Password updated successfully';
        } else {
            $warning_msg[] = 'Please enter a new password';
        }
    }
}
    $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE id = ? LIMIT 1");
    $select_seller->execute([$seller_id]);
    $fetch_seller = $select_seller->fetch(PDO::FETCH_ASSOC);

    $prev_pass = $fetch_seller['password'];
    $prev_image = $fetch_seller['image'];

}
?>

 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="form-container">
            <div class="heading">
                <h1>Update profile Details</h1>
                <img src="../image/separator-img.png">
            </div>
            <form action="" method="POST"     enctype="multipart/form-data" class="register">
                <div class="img-box">
                    <img src="../uploaded_files/<?= $fetch_seller['image'];?>" >
                </div>
                <div class="flex">
                    <div class="col">
                        <div class="input-field">
                            <p> Your Name<span>*</span></p>
                            <input type="text" name="name" placeholder="<?= $fetch_seller['name']; ?>" class="box">
                        </div>
                        <div class="input-field">
                            <p> Your Email<span>*</span></p>
                            <input type="text" name="email" placeholder="<?= $fetch_seller['email']; ?>" class="box">
                        </div>
                        <div class="input-field">
                            <p> Select picture<span>*</span></p>
                            <input type="file" name="image" accept="image/*" class="box">
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-field">
                            <p> Old Password<span>*</span></p>
                            <input type="password" name="old_pass" placeholder="Entrer your old password" class="box">
                        </div>
                        <div class="input-field">
                            <p> New Password<span>*</span></p>
                            <input type="password" name="new_pass" placeholder="Entrer your new password" class="box">
                        </div>
                        <div class="input-field">
                            <p> Confirm Password<span>*</span></p>
                            <input type="password" name="cpass" placeholder="Confirm your old password" class="box">
                        </div>
                    </div>
                </div>
                <input type="submit" name="submit" value="update profil" class="btn">
            </form>
        </section>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

   <?php include '../components/alert.php'; ?>
   <!--custom js link-->
   <script src ="../js/admin_script.js"></script>
</body>
</html>