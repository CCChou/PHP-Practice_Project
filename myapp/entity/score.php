<?php
class Score {
    private $id;
    private $scorer;
    private $userId;
    private $score;

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