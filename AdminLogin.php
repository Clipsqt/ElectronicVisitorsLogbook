<?php
    include ('login_server.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bookman+Old+Style">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="Adminlogin.css">
    <title>Admin Log in</title>
</head>
<body>
    <div class="container">
        <label for="email"><i class='bx bxs-user'></i></label> <!-- Add "for" attribute with a valid input "id" -->
        <h1 class="label">LOGIN</h1>
        <form class="login_form" method="post" name="form" onsubmit="return validated()">
            <div class="emailfont">Email</div>
            <input autocomplete="on" type="text" name="email" id="email" placeholder="Enter your email" required>
            <div class="passfont">Password</div>
            <input type="password" name="password" placeholder="Enter your password" required>
            <button type="submit" name="login" id="loginButton">Login</button> <!-- Change "type" attribute to "submit" -->
        </form>
    </div>
    <script src="login.js"></script> 
</body>
</html>
