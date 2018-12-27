<?php
class UserDto {
    private $id;
    private $account;
    private $score;
    private $isScored;

    function __construct($id, $account, $score, $isScored) {
        $this->id = $id;
        $this->account = $account;
        $this->score = $score;
        $this->isScored = $isScored;
    }

    function __get($name) {
        if(!empty($this->$name)) {
            return $this->$name;
        }
        return null;
    }
}
?>