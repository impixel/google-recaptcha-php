<?php
/**
 * 	Wrapper class for new version of reCAPTCHA (v2.0) by Google Inc
 * 	@author 	Hadi Tajallaei <hadi@impixel.com>
 * 	@since 		2nd Feburary 2016
 * 	@version  	1.0
 * 	@copyright 	IMPIXEL (c) 2016 <http://www.impixel.com>
 * 	@license  	MIT License
 */

Class GoogleRecaptcha {
	
	const URL_PATH 			= "https://www.google.com/recaptcha/api/siteverify";
	private static $_secret_key = "YOUR SECRET KEY HERE";


	/**
	 * Verifys the post field `g-recaptcha-response`
	 * @param  <STRING> 	$captcha_code is a post value `$_POST['g-recaptcha-response']` generated by reCAPTCHA
	 * @return <BOOLEAN>	success
	 */
	public function verifyCaptcha($captcha_code) {
		
		$fields_string = "";
		$usr_ip = $this->getIp();
		$fields = array(
			'secret' 	=> urlencode(self::$_secret_key),
			'response' 	=> urlencode($captcha_code),
			'remoteip' 	=> urlencode($usr_ip)
		);
		
		//urlify the data for the POST
		foreach($fields as $key => $value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		
		//open connection
		$ch = curl_init();
		
		//set the url, number of POST vars
		curl_setopt($ch,CURLOPT_URL, URL_PATH);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		
		//execute post
		$result = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($result);
		return $result->success;
	}


	/**
	 * Gets user's IP address
	 * @return <STRING> user's IP
	 */
	private function getIp() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		    $usr_ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		    $usr_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		    $usr_ip = $_SERVER['REMOTE_ADDR'];
		}
		return $usr_ip;
	}
}