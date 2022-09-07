<?php

if ( ! function_exists('password_validate')){
	/**
	 * @param string $password
	 * @param $msg
	 * @return bool|string
	 */
	function password_validate($password = '', &$msg){

		$errors = [];
		$password = trim($password);
		$regex_lowercase = '/[a-z]/';
		$regex_uppercase = '/[A-Z]/';
		$regex_number = '/[0-9]/';
		$regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';
		$msg = 'Your password does not meet these requirements:';
		if (empty($password))
		{
			array_push($errors,1);
		}
		if (preg_match_all($regex_lowercase, $password) < 1)
		{
			array_push($errors,2);
		}
		if (preg_match_all($regex_uppercase, $password) < 1)
		{
			array_push($errors,3);
		}
		if (preg_match_all($regex_number, $password) < 1)
		{
			array_push($errors,4);
		}
		if (strlen($password) < 5)
		{
			array_push($errors,6);
		}
		if (strlen($password) > 32)
		{
			array_push($errors,7);
		}
		if (preg_match_all($regex_special, $password) < 1)
		{
			array_push($errors,5);
		}
		if (count($errors)>0) {
			foreach ($errors as $key => $value) {
				if ($value==6) {$msg .= '- At least 5 characters long.';}
				if ($value==7) {$msg .= '- No more than 32 characters long.';}
				if ($value==2) {$msg .= '- Include one lowercase.';}
				if ($value==3) {$msg .= '- Include one uppercase.';}
				if ($value==4) {$msg .= '- Include one number.';}
				if ($value==5) {$msg .= '- Include one special character (' . htmlentities('.!@#$%&*;:') . ').';}
			}
			$msg .= '</ol>';
			return FALSE;
		}
		return TRUE;

	}
}
