<?php
class User {
    private $account;
    private $password;
    private $roleId;

    function __construct($account, $password, $roleId) {
        $this->account = $account;
        $this->password = $password;
        $this->roleId = $roleId;
    }

    function __get($name) {
        if(!empty($this->$name)) {
            return $this->$name;
        }
        return null;
    }
}
?>