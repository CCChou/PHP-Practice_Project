<?php
include "userdto.php";
include "user.php";

class UserDao {
    private $servername = "localhost:3306";
    private $username = "root";
    private $password = "root";
    private $dbname = "test";
    private $conn;
    
    // TODO CONNECTION POOL...
    function connect() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        } 
    }
    
    function getPwdByAccount($account) {
        try {
            $this->connect();
            // FIXME SQL INJECTION...
            $sql = "SELECT password FROM GS_User WHERE account = '" . $account . "'";
            $result = $this->conn->query($sql);
    
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row["password"] ;
            } else {
                return null;
            } 
        } finally {
            $this->close();
        }
    }

    function getRoleByAccount($account) {
        try {
            $this->connect();
            // FIXME SQL INJECTION...
            $sql = "SELECT roleId FROM GS_User WHERE account = '$account'";
            $result = $this->conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row["roleId"];
            } else {
                return null;
            } 
        } finally {
            $this->close();
        }
    }

    function getUserAccountsExceptSpecificAccount($account) {
        try {
            $this->connect();
            // FIXME SQL INJECTION...
            $sql = "SELECT account FROM GS_User WHERE roleId <> 1 AND account <> '$account'";
            $result = $this->conn->query($sql);
            $resultList = array();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    array_push($resultList, $row["account"]);
                }
            }
            return $resultList;
        } finally {
            $this->close();
        }
    }

    function getAllUserScore() {
        try {
            $this->connect();
            // FIXME SQL INJECTION...
            $sql = "SELECT user.id, user.account, IFNULL(score.score, 0) score FROM GS_User user LEFT JOIN (SELECT userid, sum(score) score FROM score GROUP BY userid) score ON user.id = score.userid WHERE user.roleId <> 1";
            $result = $this->conn->query($sql);
            $resultList = array();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $userDto = new UserDto($row["id"], $row["account"], $row["score"]);
                    array_push($resultList, $userDto);
                }
            }
            return $resultList;
        } finally {
            $this->close();
        }
    }

    function createUser($user) {
        try {
            $this->connect();
            // FIXME SQL INJECTION...
            $sql = "INSERT INTO GS_User(account, password, roleId) VALUES('$user->account', '$user->password', $user->roleId)";
            if($this->conn->query($sql) === TRUE) {
                return true;
            }
            return false;
        } finally {
            $this->close();
        } 
    }

    function close() {
        $this->conn->close();
    }
}
?>