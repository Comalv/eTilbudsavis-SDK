<?php


class Credentials implements ICredentials
{	
	private $credentials = array(
		"key" => "",
		"secret" => "",
		"email" => "",
		"password" => "",
		"token" => "",
		"date" => "" 		//ISO 8601 datetime
	);
	
	public function getCredentials()
	{
		return $this->credentials;
	}
	
	public function getKey()
	{
		return $this->credentials['key'];
	}
	
	public function setKey($key)
	{
		return $this->credentials['key'] = $key;
	}
	
	public function getSecret()
	{
		return $this->credentials['secret'];
	}
	
	public function setSecret($secret)
	{
		return $this->credentials['secret'] = $secret;
	}
	
	public function getEmail()
	{
		return $this->credentials['email'];
	}
	
	public function setEmail($email)
	{
		return $this->credentials['email'] = $email;
	}
	
	public function getPassword()
	{
		return $this->credentials['password'];
	}
	
	public function setPassword($password)
	{
		return $this->credentials['password'] = $password;
	}
	
	public function getToken()
	{
		return $this->credentials['token'];
	}
	
	public function setToken($token)
	{
		return $this->credentials['token'] = $token;
	}

	public function getDate()
	{
		return $this->credentials['date'];
	}
	
	public function setDate($date)
	{
		return $this->credentials['date'] = $date;
	}
}
//end of file classes/Credentials.php