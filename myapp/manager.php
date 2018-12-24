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

$userDao = new UserDao();
$userList = $userDao->getAllUserScore();
?>
<!DOCTYPE html>
<html>
<head>
<title>Manager Page</title>
<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }

    th, td {
        padding: 5px;
    }

    th {
        text-align: left;
    }
</style>
</head>
<body>

<h1>Manager Page</h1>
<br>
<form action="./logout.php" method="post">
    <input type="submit" value="Logout">
</form>
<br>
<hr/>
<br>
<form action="./register.php" method="get">
    <input type="submit" value="Add User"/>
</form>
<br>
<hr/>
<br>
<table style="width:50%">
    <tr>
        <th>Employee</th>
        <th>Score</th> 
        <th>isScored</th>
    </tr>
    <?php
    foreach($userList as &$user) {
        $isScored = $user->isScored  ? "yes" : "not yet";
        echo "<tr><td>$user->account</td><td>" . (float)$user->score . "</td><td>$isScored</td></tr>";
    }
    ?>
</table>

</body>
</html>