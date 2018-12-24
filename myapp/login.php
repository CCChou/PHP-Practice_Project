<?php
session_start();
include "loginservice.php";

// TODO check login or not first

$accountErr = "";
$passwordErr = "";
$loginError = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // double check in backend
    if(isLoginParamEmpty()) {
        setErrorMsg();
    } else {
        $account = $_POST["account"];
        $password = $_POST["password"];

        $loginService = new LoginService();
        if($loginService->login($account, $password)) {
            $_SESSION["user"] = $account;
            if($loginService->isManager($account)) {
                header("Location: ./manager.php");
            } else {
                header("Location: ./employee.php");
            }
        } else {
            $loginError = "Account not exist or Password incorrect";
        }
    }
}

function isLoginParamEmpty() {
    return empty($_POST["account"]) || empty($_POST["password"]);
}

function setErrorMsg() {
    if (empty($_POST["account"])) {
        $GLOBALS['accountErr'] = "Account is required";
    } 

    if (empty($_POST["password"])) {
        $GLOBALS['passwordErr'] = "Password is required";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login Page</title>
</head>
<body>

<h1>Login Page</h1>

<form action="./login.php" method="post">
    Account:<br>
    <input type="text" name="account" required> <?php echo $accountErr ?>
    <br><br>
    Password:<br>
    <input type="password" name="password" required> <?php echo $passwordErr ?>
    <br><br>
    <input type="submit" value="Login">
    <br><br>
    <?php echo $loginError; ?>
</form>

</body>
</html>