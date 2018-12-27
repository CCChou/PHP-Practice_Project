<?php
include "entity/userdto.php";
include "entity/user.php";
include "entity/score.php";

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

    function isScored($account) {
        try {
            $this->connect();
            // FIXME SQL INJECTION...
            $sql = "SELECT isScored FROM GS_User WHERE account = '$account'";
            $result = $this->conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row["isScored"] == 1;
            } else {
                return false;
            } 
        } finally {
            $this->close();
        }
    }

    function getAllUserExceptSpecificAccount($account) {
        try {
            $this->connect();
            // FIXME SQL INJECTION...
            $sql = "SELECT * FROM GS_User WHERE account <> '$account' AND roleId <> 1";
            $result = $this->conn->query($sql);
            $resultList = array();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $user = new User();
                    $user->id = $row["id"];
                    $user->account = $row["account"];
                    $user->password = $row["password"];
                    $user->roleId = $row["roleId"];
                    $user->isScored = $row["isScored"];
                    array_push($resultList, $user);
                }
            }
            return $resultList;
        } finally {
            $this->close();
        }
    }

    function getUserByAccount($account) {
        try {
            $this->connect();
            // FIXME SQL INJECTION...
            $sql = "SELECT * FROM GS_User WHERE account = '$account'";
            $result = $this->conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user = new User();
                $user->id = $row["id"];
                $user->account = $row["account"];
                $user->password = $row["password"];
                $user->roleId = $row["roleId"];
                $user->isScored = $row["isScored"];
                return $user;
            } else {
                return null;
            } 
        } finally {
            $this->close();
        }
    }

    function updateUser($user) {
        try {
            $this->connect();
            // FIXME SQL INJECTION...
            $sql = "UPDATE GS_User SET account = '$user->account', password = '$user->password', roleId = $user->roleId, isScored = $user->isScored WHERE id = $user->id";

            if($this->conn->query($sql) === TRUE) {
                return true;
            }
            return false;
        } finally {
            $this->close();
        }
    }

    // TODO should new a new dao...
    function createScores($scoreList) {
        try {
            $this->connect();
            // FIXME SQL INJECTION...
            $sql = "";
            foreach($scoreList as $scoreObj) {
                $sql .= "INSERT INTO Score(scorer, userId, score) VALUES($scoreObj->scorer, $scoreObj->userId, $scoreObj->score);";
            }

            if($this->conn->multi_query($sql) === TRUE) {
                return true;
            }
            return false;
        } finally {
            $this->close();
        }
    }

    function getAllUserScore() {
        try {
            $this->connect();
            // FIXME SQL INJECTION...
            $sql = "SELECT user.id, user.account, IFNULL(score.score, 0) score, user.isScored FROM GS_User user LEFT JOIN (SELECT userid, sum(score) score FROM score GROUP BY userid) score ON user.id = score.userid WHERE user.roleId <> 1";
            $result = $this->conn->query($sql);
            $resultList = array();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $userDto = new UserDto($row["id"], $row["account"], $row["score"], $row["isScored"]);
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