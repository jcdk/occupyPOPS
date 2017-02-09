<?php

// Load secret config settings.
require("config.php");

//put sha1() encrypted password here - example is 'pops!'
$password = SHAPASS;

session_start();

if ($_GET['sign'] == "out") { $_SESSION['loggedIn']=false; }

if (!isset($_SESSION['loggedIn'])) {
    $_SESSION['loggedIn'] = false;
}

if (isset($_POST['password'])) {

    if (sha1($_POST['password']) == $password) {
        $_SESSION['loggedIn'] = true;
    } else {
        die ('Incorrect password');
    }
} 

if (!$_SESSION['loggedIn']) : ?>

<html><head><title>Login</title></head>
  <body>
    <p>You need to login</p>
    <form method="post" action="index.php">
      Password: <input type="password" name="password"> <br />
      <input type="submit" name="submit" value="Login">
    </form>
  </body>
</html>

<?php
exit();
endif;
?>
