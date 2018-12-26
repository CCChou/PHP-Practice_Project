<?php
include "userdao.php";

class LoginService {
	private $userDao;

	function __construct() {
		$this->userDao = new UserDao();
	}

	function login($account, $password) {
		$pwdFromDB = $this->userDao->getPwdByAccount($account);

		if(empty($pwdFromDB)) {
			return false;
		}
		return strcmp($pwdFromDB, $password) === 0;
	}

	function isManager($account) {
		$roleId = $this->userDao->getRoleByAccount($account);

		return $roleId != null && $roleId == 1;
	}
}
?>