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

$userListDao = new UserListDao();
$userList = $userListDao->getAllUserScore();
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
<form action="./register.php" method="post">
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
        echo "<tr><td>$user->account</td><td>" . (float)$user->score . "</td><td></td></tr>";
    }
    ?>
</table>

</body>
</html>