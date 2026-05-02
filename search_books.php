<?php
include 'config.php';
session_start();

/* ==========================================
   ADD TO CART
========================================== */
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];

    if(isset($_POST['add_to_cart'])){

        $book_id = $_POST['book_id'];
        $book_name = $_POST['book_name'];
        $book_image = $_POST['book_image'];
        $book_price = $_POST['book_price'];
        $quantity = 1;
        $total = $book_price * $quantity;

        // Secure duplicate check
        $stmt = $conn->prepare("SELECT id FROM cart WHERE book_id=? AND user_id=?");
        $stmt->bind_param("ii", $book_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            echo "<script>alert('Book already in cart');</script>";
        }else{
            $insert = $conn->prepare("INSERT INTO cart 
                (user_id,book_id,name,price,image,quantity,total)
                VALUES (?,?,?,?,?,?,?)");

            $insert->bind_param("iisssii",
                $user_id,
                $book_id,
                $book_name,
                $book_price,
                $book_image,
                $quantity,
                $total
            );
            $insert->execute();

            echo "<script>alert('Book Added Successfully');</script>";
        }
    }
}

/* ==========================================
   LIVE SEARCH AJAX
========================================== */
if(isset($_POST['live_search'])){

    $search = "%".$_POST['live_search']."%";

    $stmt = $conn->prepare("
        SELECT * FROM book_info
        WHERE name LIKE ?
        OR title LIKE ?
        OR category LIKE ?
    ");
    $stmt->bind_param("sss",$search,$search,$search);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        while($book = $result->fetch_assoc()){
?>

<div class="box">
    <a href="book_details.php?details=<?php echo $book['bid'].'-name='.$book['name']; ?>">
        <img src="added_books/<?php echo $book['image']; ?>">
    </a>

    <div class="name">Author: <?php echo $book['title']; ?></div>
    <div class="name2"><?php echo $book['name']; ?></div>
    <div class="price">₹ <?php echo $book['price']; ?>/-</div>

    <form method="POST">
        <input type="hidden" name="book_id" value="<?php echo $book['bid']; ?>">
        <input type="hidden" name="book_name" value="<?php echo $book['name']; ?>">
        <input type="hidden" name="book_image" value="<?php echo $book['image']; ?>">
        <input type="hidden" name="book_price" value="<?php echo $book['price']; ?>">
        <button type="submit" name="add_to_cart">Add To Cart</button>
    </form>
</div>

<?php
        }
    }else{
        echo "<p class='empty'>No Book Found</p>";
    }
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Books</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        .search-form{
            max-width:900px;
            margin:40px auto;
        }
        .search-form input{
            width:100%;
            padding:12px;
            font-size:18px;
            border:2px solid #00a7f5;
            border-radius:5px;
        }
        .box-container{
            display:flex;
            flex-wrap:wrap;
            gap:20px;
            justify-content:center;
        }
        .box{
            width:240px;
            padding:15px;
            border:1px solid #00a7f5;
            border-radius:8px;
            text-align:center;
        }
        .box img{
            height:170px;
        }
        .price{
            color:#e74c3c;
            font-weight:bold;
        }
        button{
            margin-top:10px;
            padding:7px 12px;
            background:#00a7f5;
            color:white;
            border:none;
            cursor:pointer;
            border-radius:4px;
        }
    </style>
</head>
<body>

<?php include 'index_header.php'; ?>

<section class="search-form">
    <input type="text" id="search_box" placeholder="Search books...">
</section>

<section class="show-products">
    <div class="box-container" id="search_result"></div>
</section>

<?php include 'index_footer.php'; ?>
   <script>
let timer;

document.getElementById("search_box").addEventListener("keyup", function(){

    clearTimeout(timer);
    let query = this.value;

    timer = setTimeout(function(){

        if(query.length >= 1){

            let xhr = new XMLHttpRequest();
            xhr.open("POST","",true);
            xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");

            xhr.onload = function(){
                if(this.status == 200){
                    document.getElementById("search_result").innerHTML = this.responseText;
                }
            };

            xhr.send("live_search="+query);

        }else{
            document.getElementById("search_result").innerHTML="";
        }

    },300); // 300ms debounce
});
</script>

</body>
</html>