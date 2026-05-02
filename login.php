<?php
include 'config.php';
session_start();

if(isset($_POST['login'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $password = hash('sha256', $_POST['password']);

   $select_users = mysqli_query($conn, 
      "SELECT * FROM users_info 
       WHERE email = '$email' AND password = '$password'"
   );

   if(mysqli_num_rows($select_users) > 0){

   $row = mysqli_fetch_assoc($select_users);

   if($row['user_type'] == 'Admin'){

      $_SESSION['admin_name']  = $row['name'];
      $_SESSION['admin_email'] = $row['email'];
      $_SESSION['admin_id']    = $row['Id'];

      $success = "Admin Login Successful!";
      $redirect_page = "admin_index.php";

   }else if($row['user_type'] == 'user'){

      $_SESSION['user_name']  = $row['name'];
      $_SESSION['user_email'] = $row['email'];
      $_SESSION['user_id']    = $row['Id'];

      $success = "Login Successful!";
      $redirect_page = "index.php";
   }

}else{
   $error = "Incorrect Email or Password!";
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: 'Poppins', sans-serif;
}

/* Background */
body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:
linear-gradient(rgba(40,20,0,0.7), rgba(0,0,0,0.85)),
url('bgimg/2.jpg');
background-size: cover;
background-position: center;
}

/* Glass Card */
.container{
    width:330px;
    padding:25px 25px;
    border-radius:18px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(15px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.5);
    border:1px solid rgba(255,255,255,0.2);
}

/* Heading */
.container h3{
    text-align:center;
    color:#fff;
    font-size:22px;
    margin-bottom:18px;
}

/* Inputs */
.container input{
    width:100%;
    padding:10px 12px;
    margin:8px 0;
    border:none;
    border-radius:12px;
    font-size:14px;
    background: rgba(255,255,255,0.2);
    color:white;
    transition:0.3s;
}

.container input::placeholder{
    color:#eee;
}

.container input:focus{
    background: rgba(255,255,255,0.35);
    transform: scale(1.03);
    outline:none;
}

/* Button */
.container input[type="submit"]{
    margin-top:12px;
    background: linear-gradient(45deg,#00c6ff,#0072ff);
    font-weight:600;
    cursor:pointer;
}

.container input[type="submit"]:hover{
    transform: scale(1.05);
    box-shadow:0 8px 20px rgba(0,0,0,0.5);
}

/* Error Message */
.message{
    position: fixed;
    top: 25px;
    left: 50%;
    transform: translateX(-50%);
    padding: 14px 25px;
    background: linear-gradient(45deg,#ff416c,#ff4b2b);
    color: white;
    font-weight: 600;
    border-radius: 30px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.4);
    animation: slideDown 0.5s ease, fadeOut 0.5s ease 4s forwards;
    z-index: 9999;
}

/* Animations */
@keyframes slideDown{
    from{
        opacity:0;
        transform: translate(-50%, -30px);
    }
    to{
        opacity:1;
        transform: translate(-50%, 0);
    }
}

@keyframes fadeOut{
    to{
        opacity:0;
        transform: translate(-50%, -20px);
    }
}
.success-msg{
    position: fixed;
    top: 25px;
    left: 50%;
    transform: translateX(-50%);
    padding: 14px 25px;
    background: linear-gradient(45deg,#00b09b,#96c93d);
    color: white;
    font-weight: 600;
    border-radius: 30px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.4);
    animation: slideDown 0.5s ease, fadeOut 0.3s ease 1s forwards;
    z-index: 9999;
}
.error-msg{
    position: fixed;
    top: 25px;
    left: 50%;
    transform: translateX(-50%);
    padding: 14px 25px;
    background: linear-gradient(45deg,#ff416c,#ff4b2b);
    color: white;
    font-weight: 600;
    border-radius: 30px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.4);
    animation: slideDown 0.5s ease, fadeOut 0.3s ease 1s forwards;
    z-index: 9999;
}
.back-glass{
    position: absolute;
    top: 20px;
    left: 20px;
    padding: 10px 20px;
    border-radius: 30px;
    backdrop-filter: blur(10px);
    background: rgba(255,255,255,0.1);
    color: white;
    font-weight: bold;
    text-decoration: none;
    border: 1px solid rgba(255,255,255,0.3);
    transition: 0.3s;
}

.back-glass:hover{
    background: rgba(0,255,255,0.3);
    transform: scale(1.05);
}

</style>

</head>
<body>

<?php
if(isset($error)){
   echo "<div class='error-msg'>$error</div>";
}

if(isset($success)){
   echo "<div class='success-msg'>$success</div>";
   echo "<script>
            setTimeout(function(){
                window.location.href='$redirect_page';
            }, 2000);
         </script>";
}
?>



<div class="container">
<form method="post">
    <h3>Login</h3>
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Password">
    <input type="submit" name="login" value="Login">
    
    
    
</form>

</div>
<a href="index.php" class="back-glass">
   ⬅ Back
</a>


</body>
</html>
