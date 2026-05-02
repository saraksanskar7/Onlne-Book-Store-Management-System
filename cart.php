<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];
$user_name =$_SESSION['user_name'];

if(!isset($user_id)){
   header('location:login.php');
}


if(isset($_GET['remove'])){
    $remove_id=$_GET['remove'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id='$remove_id'") or die('query failed');
    $message[]='Removed Successfully';
    header('location:cart.php');
}
if(isset($_POST['update'])){
    $update_cart_id =$_POST['cart_id'];
    $book_price=$_POST['book_price'];
    $update_quantity =$_POST['update_quantity'];
    $total_price =$book_price * $update_quantity;
    mysqli_query($conn, "UPDATE `cart` SET `quantity`='$update_quantity', `total`='$total_price' WHERE `id`='$update_cart_id'") or die('query failed');
    
    $message[]=''.$user_name.' your cart updated successfully';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="css/hello.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <style>
        /* ===============================
        CART PAGE DESIGN
================================ */

.cart-section {
  padding: 60px 20px;
  background: #f8fafc;
  min-height: 100vh;
  font-family: 'Segoe UI', sans-serif;
}

/* ================= BUTTONS ================= */

.cart-btn1,
.cart-btn2 {
  display: inline-block;
  padding: 12px 22px;
  font-size: 15px;
  font-weight: 600;
  border-radius: 8px;
  text-transform: capitalize;
  cursor: pointer;
  transition: all 0.3s ease;
  border: none;
  letter-spacing: 0.5px;
}

/* Checkout Button */
.cart-btn1 {
  background: linear-gradient(135deg, #ff9a00, #ff6a00);
  color: #fff;
  box-shadow: 0 8px 18px rgba(255, 122, 0, 0.3);
}

.cart-btn1:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 25px rgba(255, 122, 0, 0.4);
}

/* Continue Shopping */
.cart-btn2 {
  background: linear-gradient(135deg, #007cf0, #00dfd8);
  color: #fff;
  box-shadow: 0 8px 18px rgba(0, 124, 240, 0.3);
}

.cart-btn2:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 25px rgba(0, 124, 240, 0.4);
}

/* ================= MESSAGE ALERT ================= */

.message {
  position: fixed;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  width: auto;
  min-width: 350px;
  max-width: 600px;
  padding: 14px 20px;
  background: #ffffff;
  border-radius: 10px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  display: flex;
  justify-content: space-between;
  align-items: center;
  z-index: 9999;
  border-left: 6px solid #4a6cf7;
  animation: slideDown 0.4s ease;
}

/* Message Animation */
@keyframes slideDown {
  from {
    opacity: 0;
    transform: translate(-50%, -20px);
  }
  to {
    opacity: 1;
    transform: translate(-50%, 0);
  }
}

.message span {
  font-size: 16px;
  font-weight: 600;
  color: #333;
}

.message i {
  cursor: pointer;
  font-size: 18px;
  color: #4a6cf7;
  transition: 0.3s;
}

.message i:hover {
  color: #ff3b3b;
}

/* ================= CART CARD STYLE ================= */

.cart-box {
  background: #ffffff;
  padding: 20px;
  border-radius: 14px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.05);
  transition: 0.3s ease;
  margin-bottom: 25px;
}

.cart-box:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 30px rgba(0,0,0,0.08);
}

/* ================= TOTAL SECTION ================= */

.cart-total {
  background: #ffffff;
  padding: 25px;
  border-radius: 14px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.05);
  text-align: center;
  margin-top: 30px;
}

.cart-total h3 {
  font-size: 22px;
  margin-bottom: 15px;
  color: #333;
}
    </style>
</head>

<body>
    <?php
    include 'index_header.php';
    ?>
    <div class="cart_form">
    <?php
    if(isset($message)){
      foreach($message as $message){
        echo '
        <div class="message" id="messages"><span>'.$message.'</span>
        </div>
        ';
      }
    }
    ?>
        <table style="width: 70%; align-items:center; margin:10px auto;" >
            <thead>
                <th>Image</th>
                <th>Name</th>
                <th>price</th>
                <th>Quatity</th>
                <th>Total (₹)</th>
            </thead>
            <tbody>
                
                <?php
                $total = 0;
                $select_book = $conn->query("SELECT id, name,price, image ,quantity,total  FROM cart Where user_id= $user_id");
                if ($select_book->num_rows  > 0) {

                    while ($row = $select_book->fetch_assoc()) {
                ?>
                        <tr>
                            <td><img style="height: 90px;" src="./added_books/<?php echo $row['image']; ?>" alt=""></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['price']; ?></td>
                            <td>
                                <form action="" method="POST">
                                    <input type="number" name="update_quantity" min="1" max="10" value="<?php echo $row['quantity']; ?>">
                                    <input type="hidden" name="cart_id" value="<?php echo $row['id']; ?>">
                                    <input class="hidden_input" type="hidden" name="book_price" value="<?php echo $row['price'] ?>">
                                <!-- <input type="submit" name="update" value="update"> -->
                                <button style="background:transparent ;" name="update"><img style="height: 26px; cursor:pointer;" src="./images/update1.png" alt="update"></button> | 
                                <a style="color: red;" href="cart.php?remove=<?php echo $row['id'];?>"> Remove</a>
                                </form>
                           
                            
                        </td>
                            <td><?php $sub_total=$row['price']*$row['quantity']; echo $subtotal=number_format($row['price']*$row['quantity']); ?></td>
                            </tr>

                <?php
                $total += $sub_total;
                    }
                } else {
                    echo '<p class="empty">There is nothing in cart yet !!!!!!!!</p>';
                }
                ?>
                <tr>
                    <th style="text-align:center;" colspan="3">Total</th>
                    <th colspan="2">₹ <?php echo $total; ?>/- </th>

                </tr>
                
                
            </tbody>
        </table>
        <a href="checkout.php" class="btn cart-btn1" style="display:<?php if($total>1){ echo 'inline-block'; }else{ echo 'none'; };?>" > &nbsp; Proceed to Checkout</a> <a class="cart-btn2" href="index.php">Continue Shoping</a>
    </div>
    <?php include'index_footer.php'; ?>
    
    <script>
setTimeout(() => {
  const box = document.getElementById('messages');

  // 👇️ hides element (still takes up space on page)
  box.style.display = 'none';
}, 5000);
</script>

</body>

</html>