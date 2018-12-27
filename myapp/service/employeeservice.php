<?php
include "dao/userdao.php";

class EmployeeService {
    private $userDao;

	function __construct() {
		$this->userDao = new UserDao();
    }
    
	function saveScore($account, $userScoreList) {
        $scorerId = $this->getScorerId($account);

        $scoreList = array();
        foreach($userScoreList as $userId => $score) {
            $scoreObj = new Score();
            $scoreObj->scorer = $scorerId;
            $scoreObj->userId = $userId;
            $scoreObj->score = $score;
            array_push($scoreList, $scoreObj);
        }
        $this->userDao->createScores($scoreList);

        $this->changeUserIsScored($account);
    }

    private function getScorerId($account) {
        $user = $this->userDao->getUserByAccount($account);
        return $user->id;
    }

    private function changeUserIsScored($account) {
        $user = $this->userDao->getUserByAccount($account);
        $user->isScored = true;
        $this->userDao->updateUser($user);
    }

    function isScored($account) {
        return $this->userDao->isScored($account);
    }

    function getOtherEmployees($account) {
        return $this->userDao->getAllUserExceptSpecificAccount($account);
    }
}
?>