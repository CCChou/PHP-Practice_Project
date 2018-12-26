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

$accountErr = "";
$passwordErr = "";
$confirmErr = "";
$registerMsg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $account = $_POST["account"];
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    if(isLoginParamEmpty()) {
        setErrorMsg();
    } else if(strcmp($password, $confirm) !== 0) {
        $registerMsg = "Password and Confirm is not the same";
    } else {
        $user = new User();
        $user->account = $account;
        $user->password = $password;
        $user->roleId = 2;
        $userDao = new UserDao();
        if($userDao->createUser($user)) {
            $registerMsg = "Create User Success";
        } else {
            $registerMsg = "Account Duplicate";
        }
    }
}

function isLoginParamEmpty() {
    return empty($_POST["account"]) || empty($_POST["password"]) || empty($_POST["confirm"]);
}

function setErrorMsg() {
    if (empty($_POST["account"])) {
        $GLOBALS['accountErr'] = "Account is required";
    } 

    if (empty($_POST["password"])) {
        $GLOBALS['passwordErr'] = "Password is required";
    }

    if (empty($_POST["confirm"])) {
        $GLOBALS['confirmErr'] = "Confirm is required";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Register Page</title>
</head>
<body>

<h1>Register Page</h1>
<br>
<form action="./manager.php" method="get">
    <input type="submit" value="back manager page">
</form>
<br>
<hr>
<br>
<form action="./register.php" method="post">
    Account:<br>
    <input type="text" name="account" required>  <?php echo $accountErr ?>
    <br><br>
    Password:<br>
    <input type="password" name="password" required>  <?php echo $passwordErr ?>
    <br><br>
    Confirm:<br>
    <input type="password" name="confirm" required>  <?php echo $confirmErr ?>
    <br><br>
    <input type="submit" value="Register">
    <br><br>
    <?php echo $registerMsg; ?>
</form>

</body>
</html>