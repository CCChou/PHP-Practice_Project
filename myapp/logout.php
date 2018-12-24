<?php
session_start();

$account = $_SESSION["user"];
if(!empty($account)) {
    unset($_SESSION["user"]);
    header("Location: ./login.php");
}
?>