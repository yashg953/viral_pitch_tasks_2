<?php 
define('_INSTAGRAM_CLIENT_ID','2e4eb71837664313aba6c2144a2b61d3');
define('_INSTAGRAM_CLIENT_SECRET','c22a4d5deeb241828444b3e9e0bb7cb5');
define('_INSTAGRAM_REDIRECT_URL','https://viralpitch.co/dashboard/callback.php');

class instaClass{

  public $token_array;
  
  // Authentication
  public function authInstagram(){

    $url = "https://api.instagram.com/oauth/authorize/?client_id="._INSTAGRAM_CLIENT_ID."&redirect_uri="._INSTAGRAM_REDIRECT_URL."&response_type=code";
    header('location: '.$url);

  }
  
  // Set Access Token
  public function setAccess_token($code){

    $this->token_array = array("client_id"=>_INSTAGRAM_CLIENT_ID,
        "client_secret"=>_INSTAGRAM_CLIENT_SECRET,
        "grant_type"=>authorization_code,
        "redirect_uri"=>_INSTAGRAM_REDIRECT_URL,
        "code"=>$code);

  }
  
  // Get user details
  public function getUserDetails(){

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,"https://api.instagram.com/oauth/access_token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->token_array );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec ($ch);

    curl_close ($ch);

    return json_decode($result);

  }

  public function getUserPost(){

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,"https://api.instagram.com/v1/self/media/recent?access_token=8802916640.2e4eb71.b200f43db1ba4d4b9867139f5fdcc92a");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->token_array );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec ($ch);

    curl_close ($ch);

    return json_decode($result);

  }

}

 ?>