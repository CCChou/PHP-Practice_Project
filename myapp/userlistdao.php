<?php
include "userdto.php";

class UserListDao {
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
            $sql = "SELECT password FROM UserList WHERE account = '" . $account . "'";
            $result = mysqli_query($this->conn, $sql);
    
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
            $sql = "SELECT roleId FROM UserList WHERE account = '$account'";
            $result = mysqli_query($this->conn, $sql);

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
            $sql = "SELECT account FROM UserList WHERE roleId <> 1 AND account <> '$account'";
            $result = mysqli_query($this->conn, $sql);
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
            $sql = "SELECT user.id, user.account, IFNULL(score.score, 0) score FROM UserList user LEFT JOIN (SELECT userid, sum(score) score FROM score GROUP BY userid) score ON user.id = score.userid WHERE user.roleId <> 1";
            $result = mysqli_query($this->conn, $sql);
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

    function createUser() {
        
    }

    function close() {
        $this->conn->close();
    }
}
?>