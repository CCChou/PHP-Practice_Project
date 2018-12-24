<?php
session_start();
include "loginservice.php";

$loginService = new LoginService();
$account = $_SESSION["user"];
if(empty($account)) {
    header("Location: ./login.php");
} else if(!$loginService->isManager($account)) {
    echo "Not Permitted";
    return;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Register Page</title>
</head>
<body>

<h1>Register Page</h1>

<form action="./register.php" method="post">
    Account:<br>
    <input type="text" name="account">
    <br><br>
    Password:<br>
    <input type="password" name="password">
    <br><br>
    <input type="submit" value="Register">
    <br><br>
</form>

</body>
</html>