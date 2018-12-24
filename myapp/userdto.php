<?php
class UserDto {
    private $id;
    private $account;
    private $score;

    function __construct($id, $account, $score) {
        $this->id = $id;
        $this->account = $account;
        $this->score = $score;
    }

    function __get($name) {
        if(!empty($this->$name)) {
            return $this->$name;
        }
        return null;
    }
}
?>