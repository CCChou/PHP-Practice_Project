<?php
class User {
    private $id;
    private $account;
    private $password;
    private $roleId;
    private $isScored;

    function __get($name) {
        if(!empty($this->$name)) {
            return $this->$name;
        }
        return null;
    }

    function __set($name, $value) {
        $this->$name = $value;
    }
}
?>