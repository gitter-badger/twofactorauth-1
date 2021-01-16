<?php
/*
 * TwoFactorAuth
 *
 * Copyright (C) 2021-2022 e107 Inc. (https://www.e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

class twofactorauth
{
	
	public $tfa_debug = false; 

	public function __construct() 
	{
		// Check debug mode (not used yet)
		if(e107::getPlugPref('twofactorauth', 'tfa_debug') == true) 
		{
			$this->tfa_debug = true;
		}
	}
	

	public function init($user_id)
	{
		// Check if 2FA is activated
		if($this->tfaActivated($user_id) == false)
		{
			// 2FA is NOT activated, return false to proceed with core login process.
			error_log("2FA NOT ACTIVATED FOR THIS USER ID: ".$user_id); 
			return false; 
		}

		// 2FA is enabled for this user. Continue verification process. Service page to enter TOTP digits, generated by user's authenthicator app.
		error_log("2FA IS ACTIVATED, NEED TO ENTER DIGITS");
		// Store user id in a session so we can retrieve it again
		e107::getSession('2fa')->set('user_id', $user_id);
		e107::redirect(e_PLUGIN."twofactorauth/login.php"); 
		return true; 
	}


	private function tfaActivated($user_id)
	{
		$count = e107::getDb()->count('twofactorauth', '(*)', "user_id='{$user_id}'");
		error_log("Count: ".$count);
		
		/*
		if($this->tfa_debug)
		{
			e107::getAdminLog()->addDebug("(".__LINE__.") 2FA active: ".$count);
			e107::getAdminLog()->toFile('twofactorauth', 'TwoFactorAuth Debug Information', true);
		}*/
		
		return $count;
	}

}