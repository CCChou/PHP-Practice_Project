<?php
class UserList {
    private $id;
    private $account;
    private $password;
    private $isScored;
    private $roleId;

    function __construct($id, $account, $password, $isScored, $roleId) {
        $this->id = $id;
        $this->account = $account;
        $this->password = $password;
        $this->isScored = $isScored;
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