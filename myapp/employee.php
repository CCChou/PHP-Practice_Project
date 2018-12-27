<?php
session_start();
include "service/employeeservice.php";

$account = $_SESSION["user"];
if(empty($account)) {
    header("Location: ./login.php");
}

$employeeService = new EmployeeService();
if($employeeService->isScored($account)) {
    echo "you have done this!!";
    unset($_SESSION["user"]);  // logout for test
    return;
}

$employeeErr = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userList = $employeeService->getOtherEmployees($account);
    if(isParamVaild($userList)) {
        $userScoreList = createUserScoreList($userList);
        $employeeService->saveScore($account, $userScoreList);
        
        echo "Congratulations you have done this!!";
        unset($_SESSION["user"]);  // logout for test
        return;
    } else {
        $employeeErr = "please rate everyone!!";
    }
}

$userList = $employeeService->getOtherEmployees($account);

function isParamVaild($userList) {
    foreach($userList as $user) {
        $score = $_POST[$user->id];
        if(empty($score)) {
            return false;
        } else if($score > 5 || $score < 1) {
            return false;
        }
    }
    return true;
}

function createUserScoreList($userList) {
    $userScoreList = array();
    foreach($userList as $user) {
        $userScoreList[$user->id] = $_POST[$user->id];
    }
    return $userScoreList;
}
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
<?php echo $employeeErr; ?>
<form action="./employee.php" method="post">
    <table style="width:50%">
        <tr>
            <th>Employee</th>
            <th>Scores</th> 
        </tr>
        <?php
        foreach($userList as &$user) {
            echo "<tr><td>$user->account</td><td>" .
                "<input type='radio' name='$user->id' value='1' required> 1" .
                "<input type='radio' name='$user->id' value='2'> 2" .
                "<input type='radio' name='$user->id' value='3' checked='checked'> 3" .
                "<input type='radio' name='$user->id' value='4'> 4" .
                "<input type='radio' name='$user->id' value='5'> 5" .
                "</td></tr>";
        }
        ?>
    </table>
    <br><br>
    <input type="submit" value="Submit">
</form>
</body>
</html>