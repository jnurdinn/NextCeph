<?php
namespace OCA\NextCeph\Request;

class Curl {

  public function __construct($AppName){
    parent::__construct($AppName, $request);
    $this->userId = $UserId;
  }

  public function post() {
    include 'config.php';
    $login = $nc_config['user'];
    $pass = $nc_config['psswd'];
    $url = 'https://'. $nc_config['mgr_host'] . ':' . $nc_config['mgr_port'] . .'/mon';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
  }
}
