<?php

//autoloading classes
spl_autoload_register(function ($class) {
    include '../classes/' . $class . '.php';
});


//debug variable, if set to 0 a blank page means success
//only errors are displayed
//if set to 1 every step is displayed
$verbose = 1;

//testing credentials
$test = array(
	"key" => "",
	"secret" => "",
	"email" => "",
	"password" => "",
	"token" => "",
	"date" => "".date('c')		//ISO 8601 current datetime
);

//testing catalog required info
$catalog_info = array(
	"lat" => 55.55,
	"lng" => 12.12,
	"radius" => 10000
);


try {
	if($verbose == 1) echo("Instatiating Credentials...<br />");
	$credentials = new Credentials();
} catch (Exception $e) {
	echo $e->getMessage(), "\n";
}

try {
	if($verbose == 1) echo("Setting Test Credentials...<br />");
	$credentials->setKey($test['key']);
	$credentials->setSecret($test['secret']);
	$credentials->setEmail($test['email']);
	$credentials->setPassword($test['password']);
	$credentials->setToken($test['token']);
	$credentials->setDate($test['date']);	
} catch (Exception $e) {
	echo $e->getMessage(), "\n";
}

try {
	if($verbose == 1) echo("Instatiating Client...<br />");
	$session = new Client();
} catch (Exception $e) {
	echo $e->getMessage(), "\n";
}

if($verbose == 1) echo("Initializing API Session...<br />");
$init_info = $session->initialize($credentials->getKey());

if(!$init_info)
{
	echo("Failed to Initialize API Session, closing...<br />");
	exit();
}

if(isset($init_info->{'token'}))
{
	if($verbose == 1) echo("API Session Initialized, Current token is: ".$init_info->{'token'}."<br />");
	$credentials->setToken($init_info->{'token'});
}

if($verbose == 1) echo("Signing In...<br />");
$si_info = $session->signIn($credentials->getCredentials());

if(!$si_info)
{
	echo("Login failed...<br />");
}

if(isset($si_info->{'token'}))
{
	if($verbose == 1) echo("Login Successful, Current token is: ".$si_info->{'token'}."<br />");
	$credentials->setToken($si_info->{'token'});
}

if($verbose == 1) echo("Getting Unfiltered Catalog List...<br />");
$list_info = $session->getCatalogList($credentials->getToken(), $credentials->getSecret(), $catalog_info);

if(!$list_info)
{
	echo("Failed to retrieve Catalog list...<br />");
}

if($list_info && isset($list_info[0]->{'id'}))
{
	if($verbose == 1) echo("Catalog List retrieved, First Catalog ID is: ".$list_info[0]->{'id'}."<br />");
}

//setting sorting parameters for more specific catalog list
$catalog_info['limit'] = 3;
$catalog_info['offset'] = 0;
$catalog_info['sort'] = array('distance', '-popularity');

if($verbose == 1) echo("Getting Sorted Catalog List (3 results by distance,-popularity)...<br />");
$sorted_info = $session->getCatalogList($credentials->getToken(), $credentials->getSecret(), $catalog_info);

if(!$sorted_info)
{
	echo("Failed to retrieve Catalog list...<br />");
}

if($sorted_info && isset($sorted_info[0]->{'id'}))
{
	if($verbose == 1) echo("Catalog List retrieved, First Catalog ID is: ".$sorted_info[0]->{'id'}."<br />");
}

//setting filters for more specific catalog list
$catalog_info['catalog_ids'] = '2978YuL';
$catalog_info['dealer_ids'] = '2d6dxg';
$catalog_info['store_ids'] = '8c78b6L';

if($verbose == 1) echo("Getting Filtered Catalog List (id = ".$catalog_info['catalog_ids'].")...<br />");
$filtered_info = $session->getCatalogList($credentials->getToken(), $credentials->getSecret(), $catalog_info);

if(!$filtered_info)
{
	echo("Failed to retrieve Catalog list...<br />");
}

if($filtered_info && isset($filtered_info[0]->{'id'}))
{
	if($verbose == 1) echo("Catalog List retrieved, First Catalog ID is: ".$filtered_info[0]->{'id'}.", expected: ".$catalog_info['catalog_ids']."<br />");
}

//setting id for just 1 catalog
$catalog_info['catalog_ids'] = 'fddfkuL';

if($verbose == 1) echo("Getting Catalog with id = ".$catalog_info['catalog_ids'].")...<br />");
$just_one_info = $session->getCatalog($credentials->getToken(), $credentials->getSecret(), $catalog_info);

if(!$just_one_info)
{
	echo("Failed to retrieve Catalog...<br />");
}

if($just_one_info && isset($just_one_info[0]->{'id'}))
{
	if($verbose == 1) echo("Catalog retrieved, ID is: ".$just_one_info[0]->{'id'}.", expected: ".$catalog_info['catalog_ids']."<br />");
}

if($verbose == 1) echo("Signing Out...<br />");
$so_info = $session->signOut($credentials->getCredentials());

if(!$so_info)
{
	echo("Logout failed...<br />");
}

if(isset($so_info->{'token'}))
{
	if($verbose == 1) echo("Logout Successful, Current token is: ".$so_info->{'token'}."<br />");
	$credentials->setToken($so_info->{'token'});
}

if($verbose == 1) echo("Destroying Session...<br />");
$close = $session->destroy($credentials->getToken(), $credentials->getSecret());

if(!$close)
{
	echo("Failed to destroy session...<br />");
}
else if($verbose == 1) echo("Session destroyed, Goodbye...<br />");

exit();

//end of test/index.php