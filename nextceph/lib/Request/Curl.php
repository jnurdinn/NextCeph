<?php
namespace OCA\NextCeph\Request;

class Curl {

  public function __construct($AppName){
    parent::__construct($AppName, $request);
    $this->userId = $UserId;
  }

  public function post($url,$login,$pass,$jsondata){
		$jsondata = json_encode($jsondata);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
		$result = curl_exec($ch);
		$obj = json_decode($result);
		curl_close($ch);
		return $obj;
	}

  public function get($url,$login,$pass,$jsondata){
		$jsondata = json_encode($jsondata);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
		$result = curl_exec($ch);
		$obj = json_decode($result);
		curl_close($ch);
		return $obj;
	}
}
