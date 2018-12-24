<!-- 
    check the user fill or not first 
    y: show message
    n: form to fill...
-->

<!--
    there's a problem
    what if an employee finish this form, but the manager add new employee
    make everyone fill this again??
    and if need to fill this again should score all employess again??
    bad requirement...
-->
<?php
session_start();
include "userdao.php";

$account = $_SESSION["user"];
if(empty($account)) {
    header("Location: ./login.php");
}

$userDao = new UserDao();
$userAccountList = $userDao->getUserAccountsExceptSpecificAccount($account);
?>
<!DOCTYPE html>
<html>
<head>
<title>Employee Page</title>
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

<h1>Employee Page</h1>
<br>
<form action="./logout.php" method="post">
    <input type="submit" value="Logout">
</form>
<br>
<hr/>
<br>
<form action="./employee.php" method="post">
    <table style="width:50%">
        <tr>
            <th>Employee</th>
            <th>Scores</th> 
        </tr>
        <?php
        foreach($userAccountList as &$account) {
            echo "<tr><td>$account</td><td>" .
                "<input type='radio' name='$account' value='1'> 1" .
                "<input type='radio' name='$account' value='2'> 2" .
                "<input type='radio' name='$account' value='3'> 3" .
                "<input type='radio' name='$account' value='4'> 4" .
                "<input type='radio' name='$account' value='5'> 5" .
                "</td></tr>";
        }
        ?>
    </table>
    <br><br>
    <input type="submit" value="Submit">
</form>
</body>
</html>