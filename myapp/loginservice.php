<?php
include "userdao.php";

class LoginService {
	function login($account, $password) {
		$userDao = new UserDao();
		$pwdFromDB = $userDao->getPwdByAccount($account);

		if(empty($pwdFromDB)) {
			return false;
		}
		return strcmp($pwdFromDB, $password) === 0;
	}

	function isManager($account) {
		$userDao = new UserDao();
		$roleId = $userDao->getRoleByAccount($account);

		return $roleId != null && $roleId == 1;
	}
}
?>