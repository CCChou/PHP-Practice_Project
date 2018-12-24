<?php
include "userlistdao.php";

class LoginService {
	function login($account, $password) {
		$userListDao = new UserListDao();
		$pwdFromDB = $userListDao->getPwdByAccount($account);

		if(empty($pwdFromDB)) {
			return false;
		}
		return strcmp($pwdFromDB, $password) == 0;
	}

	function isManager($account) {
		$userListDao = new UserListDao();
		$roleId = $userListDao->getRoleByAccount($account);

		return $roleId != null && $roleId == 1;
	}
}
?>