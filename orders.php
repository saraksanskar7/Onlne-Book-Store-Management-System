<?php

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="./css/hello.css">

<style>
/* ===== ORDERS SECTION ===== */

.placed-orders {
  padding: 60px 20px;
  background: linear-gradient(to right, #f8f9fa, #eef2f3);
  min-height: 100vh;
}

/* Title */
.placed-orders .title {
  text-align: center;
  margin-bottom: 40px;
  text-transform: uppercase;
  font-size: 42px;
  font-weight: 700;
  letter-spacing: 2px;
  background: linear-gradient(45deg, #ff416c, #ff4b2b);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* Container */
.placed-orders .box-container {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 30px;
}

/* Order Card */
.placed-orders .box-container .box {
  background: #ffffff;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
  transition: 0.3s ease-in-out;
  border-left: 6px solid #ff4b2b;
  position: relative;
  overflow: hidden;
}

/* Hover Effect */
.placed-orders .box-container .box:hover {
  transform: translateY(-8px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

/* Order Text */
.placed-orders .box-container .box p {
  font-size: 18px;
  color: #555;
  margin: 10px 0;
  line-height: 1.6;
  border-bottom: 1px solid #eee;
  padding-bottom: 8px;
}

/* Label Highlight */
.placed-orders .box-container .box p span {
  font-weight: 600;
  color: #111;
}

/* Empty Message */
.placed-orders .box-container .empty {
  text-align: center;
  font-size: 22px;
  color: #999;
  padding: 40px;
}

/* Status Badge Example */
.order-status {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 14px;
  font-weight: bold;
  margin-top: 10px;
}

/* Delivered */
.status-delivered {
  background-color: #d4edda;
  color: #155724;
}

/* Pending */
.status-pending {
  background-color: #fff3cd;
  color: #856404;
}

/* Cancelled */
.status-cancelled {
  background-color: #f8d7da;
  color: #721c24;
}

/* Responsive */
@media (max-width: 768px) {
  .placed-orders .title {
    font-size: 30px;
  }

  .placed-orders .box-container {
    grid-template-columns: 1fr;
  }
}
</style>

</head>
<body>
   
<?php include 'index_header.php'; ?>
<section class="placed-orders">

   <h1 class="title">placed orders</h1>

   <div class="box-container">

      <?php
        $select_book = mysqli_query($conn, "SELECT * FROM `confirm_order`WHERE user_id = '$user_id' ORDER BY order_date DESC") or die('query failed');
        if(mysqli_num_rows($select_book) > 0){
            while($fetch_book = mysqli_fetch_assoc($select_book)){
      ?>
      <div class="box">
         <p> Order Date : <span><?php echo $fetch_book['order_date']; ?></span> </p>
         <p> Order Id : <span># <?php echo $fetch_book['order_id']; ?> </p>
         <p> Name : <span><?php echo $fetch_book['name']; ?></span> </p>
         <p> Mobile Number : <span><?php echo $fetch_book['number']; ?></span> </p>
         <p> Email Id : <span><?php echo $fetch_book['email']; ?></span> </p>
         <p> Address : <span><?php echo $fetch_book['address']; ?></span> </p>
         <p> Payment Method : <span><?php echo $fetch_book['payment_method']; ?></span> </p>
         <p> Your orders : <span><?php echo $fetch_book['total_books']; ?></span> </p>
         <p> Total price : <span>₹ <?php echo $fetch_book['total_price']; ?>/-</span> </p>
         <p> Payment status : <span style="color:<?php if($fetch_book['payment_status'] == 'pending'){ echo 'orange'; }else{ echo 'green'; } ?>;"><?php echo $fetch_book['payment_status']; ?></span> </p>
         <p><a href="invoice.php?order_id=<?php echo $fetch_book['order_id']; ?>" target="_blank">Print Recipt</a></p>
         </div>
         <!-- <form action="" method="POST">
         <input type="hidden" name="order_id" value="<?php echo $fetch_book['order_id']; ?>">
         </form> -->
      <?php
       }
      }else{
         echo '<p class="empty">You have not placed any order yet!!!!</p>';
      }
      ?>
   </div>

</section>








<?php include 'index_footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>