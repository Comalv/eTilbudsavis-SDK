<?php


interface ICredentials
{

//Getters and Setters for Session/User credentials
//getCredentials() allows to work on the full array
//other methods access one particular key->value at a time
//
//Available items in $Credentials are
//'key' 		The API Key
//'secret'		The API Secret
//'email'		The User E-mail Address
//'password'	The User Password
//'token'		The Session Token
//'date'		The Token's Expiration date

	public function getCredentials();
	
	public function getKey();
	
	public function setKey($key);
	
	public function getSecret();
	
	public function setSecret($secret);
	
	public function getEmail();
	
	public function setEmail($email);
	
	public function getPassword();
	
	public function setPassword($password);
	
	public function getToken();
	
	public function setToken($token);
	
	public function getDate();
	
	public function setDate($date);
}
//end of file classes/ICredentials.php