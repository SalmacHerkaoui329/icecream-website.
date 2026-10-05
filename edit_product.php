<?php
   include '../components/connect.php';
   if(isset($_COOKIE['seller_id'])) {
      $seller_id = $_COOKIE['seller_id'];
   } else {
      $seller_id = '';
      header('Location: login.php');
      exit();
   }
   if(isset($_POST['product_id'])){
    $product_id=$_POST['product_id'];
   }elseif(isset($_GET['id'])){
    $product_id=$_GET['id'];
   }else{
    $product_id='';
   }

   
   if(isset($_POST['update'])) {
    $_name = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
     $price = filter_var($_POST['price'],FILTER_SANITIZE_STRING);
      $description = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
       $stock = filter_var($_POST['stock'],FILTER_SANITIZE_STRING);
       $status = filter_var($_POST['statuts'],FILTER_SANITIZE_STRING);
     $update_product=$conn->prepare("UPDATE `products` set name=?, price=?,product_detail=?,stock=?,statuts=? WHERE id=?");
     $update_product->execute([$_name,$price,$description,$stock,$status,$product_id]);
     $success_msg[]='Product update successfully';

     $old_image = $_POST['old_image'];
$image = $_FILES['image']['name'];
$image = filter_var($image, FILTER_DEFAULT);
$image_size = $_FILES['image']['size'];
$image_tmp_name = $_FILES['image']['tmp_name'];
$image_folder = '../uploaded_files/'.$image;

$select_image = $conn->prepare("SELECT * FROM products WHERE image = ? AND seller_id = ?");
$select_image->execute([$image, $seller_id]);

if(!empty($image)) {

    if($image_size > 2000000) {
        $warning_msg[] = 'Image size is too large';
    } elseif($select_image->rowCount() > 0 && $image != '') {
        $warning_msg[] = 'Please rename the image to avoid conflicts';
    } else {
        $update_image = $conn->prepare("UPDATE products SET image = ? WHERE id = ?");
        $update_image->execute([$image, $product_id]);

        move_uploaded_file($image_tmp_name, $image_folder);

        
        if($old_image != $image && !empty($old_image)) {
            unlink('../uploaded_files/'.$old_image);
        }

        $success_msg[] = 'Image updated successfully';
    }
}
   }
   if(isset($_POST['delete_image'])) {
    $product_id = $_POST['product_id'];
    $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);

    $delete_image = $conn->prepare("SELECT image FROM products WHERE id = ? AND seller_id = ?");
    $delete_image->execute([$product_id, $seller_id]);
    $fetch_image=$delete_image->fetch(PDO::FETCH_ASSOC);
    if($fetch_image && !empty($fetch_image['image'])){
        unlink('../uploaded_files/' .$fetch_image['image']);

        $update_image = $conn->prepare("UPDATE products SET image = ? WHERE id = ?");
        $update_image->execute([ $image,$product_id]);
        $success_msg[]= 'Image deleted successfully';
    }
}
if(isset($_POST['delete_product'])){
    $product_id=$_POST['product_id'];
    $product_id=filter_var($product_id, FILTER_SANITIZE_STRING);

    $delete_image=$conn->prepare("SELECT *FROM `products` WHERE id=?");
    $delete_image->execute([$product_id]);
    $fetch_delete_image=$delete_image->fetch(PDO::FETCH_ASSOC);

    if($fetch_delete_image['image'] !=''){
        unlink('../uploaded_files/' .$fetch_delete_image['image']);
    }
    

    $delete_products = $conn->prepare("DELETE FROM `products` WHERE id = ?");
    $delete_products->execute([$product_id]);

    $success_msg[] = 'Product deleted successfully';
     
    header('location:view_products.php');
    exit();
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
      <section class="post-editor">
        <div class="heading">
            <h1>edit product</h1>
            <img src="../image/separator-img.png">
        </div>
    <div class="box-container">
        <?php
       $product_id=$_GET['id'];
       $select_product=$conn->prepare("SELECT * FROM `products` WHERE id=? AND seller_id=?");
       $select_product->execute([$product_id,$seller_id]);
        
       if($select_product->rowCount()>0){
        while($fetch_product=$select_product->fetch(PDO::FETCH_ASSOC)){

        ?>
        <div class="form-container">
            <form action=""   method="POST" enctype="multipart/form-data" class="register">
                <input type="hidden" name="old_image" value="<?=$fetch_product['image'];?>">
                <input type="hidden" name="product_id" value="<?=$fetch_product['id'];?>">
                <select name="status" class="box">
                    <option value="<?=$fetch_product['statuts'];?>" selected><?=$fetch_product['statuts'];?></option>
                    <option value="active">active</option>
                    <option value="active">deactive</option>

                </select>
                <div class="input-field">
                    <p>product name <span>*</span></p>
                    <input type="text" name="name" maxlength="100" value="<?=$fetch_product['name'];?>" required class="box">
                </div>
                 <div class="input-field">
                    <p>product price<span>*</span></p>
                    <input type="number" name="price" maxlength="100" value="<?=$fetch_product['price'];?>"  class="box">
                </div>
                 <div class="input-field">
                    <p>product detail <span>*</span></p>
                    <textarea name="description"  class="box"    <?=$fetch_product['product_detail'];?>>  </textarea>
                </div>
                <div class="input-field">
                    <p>product stock<span>*</span></p>
                    <input type="number"  name="stock" value="<?=$fetch_product['stock'];?>" min="0" max="999999999"  maxlength="10"    class="box">
                </div>
                <div class="input-field">
                    <p>product image<span>*</span></p>
                    <input type="file" name="image" accept="image/*"  class="box">
                    <?php if ($fetch_product['image'] !=''){ ?>
                  <img src="../uploaded_files/<?= $fetch_product['image'] ;?>" class="image" >
                  <div class="flex-btn">
                    <input type="submit" name="delete_image" class="btn" value="delete image">
                    <a href="view_products.php" class="btn" style="width: 49%; text-align:center; height:3rem; margin-top:.7rem; "> go back</a>
                  </div>
                  <?php
                    }?>
                </div>
                <div class="flex-btn">
                    <input type="submit" name="update"  class="btn"   value="update product" >
                    <input type="submit" name="delete_product" class="btn " value="delete product" >
                </div>


                
            </form>
            <?php 

            }
            }else{
                ?>
            
            <div class="empty">
                <p>no product added yet!</p>
            </div>
            <?php
            }
            ?>

            </div>
        </div>
       </section>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
   <?php include '../components/alert.php'; ?>
   
   <script src ="../js/admin_script.js"></script>
    
</body>
</html>