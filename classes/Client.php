<?php

//autoloading classes
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

class Client implements IClient
{
	private $vars = Array();
	
    public function initialize($key, array $v1 = NULL)
	{
	
		//basic check on key existing and being plausible
		if(!$key || $key == "")
		{
			echo("Invalid API Key");
			return false;
		}
		
		//http headers required
		$request_options = array(
			'Host: api.etilbudsavis.dk',
			'Origin: api.etilbudsavis.dk',
			'Content-Type: application/json',
			'Accept: */*'
		);
	
		//construct the body as JSON key-value pairs
		//"token_ttl": "time_in_seconds" can be added if
		//a specific token duration is desired
		$request_body = '{
			"api_key": "'. $key .'"
		}';
		
		if(!empty($v1)) //untested, constructs the body in case of v1 API credentials
		{
			if(!$v1['v1_auth_id'] || !$v1['v1_auth_time'] || !$v1['v1_auth_hash'])
			{
				echo("Invalid or Incomplete API V1 Authentication Credentials, trying with V2");
			}
			else
			{
				$request_body = '{
					"api_key": "'. $key .'",
					"v1_auth_id": "'.$v1['v1_auth_id'].'",
					"v1_auth_name": "'.$v1['v1_auth_name'].'",
					"v1_auth_hash": "'.$v1['v1_auth_hash'].'",
				)';
			}
		}
		
		//HTTP Request-Response cycle
		try 
		{
			$init_request = new CRequest();
			$response = $init_request->curl_post("https://api.etilbudsavis.dk/v2/sessions", $request_body, $request_options);
		} catch (Exception $e) {
			echo $e->getMessage(), "\n";
		}
		return $this->responseHandler($response);
		
	}

    public function signIn($credentials)
	{
		//basic check on credentials existing
		if(!$credentials['token'])
		{
			echo("Invalid Token");
			return false;
		}
				
		if(!$credentials['secret'] || $credentials['secret'] == "")
		{
			echo("Invalid Secret Key");
			return false;
		}
		
		//generating the valid signature
		$signature = hash('sha256', $credentials['secret']. $credentials['token']);
		
		//HTTP Headers
		$request_options = array(
			'Host: api.etilbudsavis.dk',
			'Origin: api.etilbudsavis.dk',
			'Content-Type: application/json',
			'Accept: */*',
			'X-Token: '.$credentials['token'],
			'X-Signature: '.$signature
		);
		
		//HTTP Body in JSON
		$request_body = '{
			"email": "'.$credentials['email'].'".
			"password": "'.$credentials['password'].'"
		}';	
		
		//HTTP Request-Response
		try 
		{
			$init_request = new CRequest();
			$response = $init_request->curl_put("https://api.etilbudsavis.dk/v2/sessions", $request_body, $request_options);
		} catch (Exception $e) {
			echo $e->getMessage(), "\n";
		}
		return $this->responseHandler($response);
	}


	//Sign out = Sign in with empty email
    public function signOut($credentials)
	{
		$credentials['email'] = "";
		$return = $this->signIn($credentials);
		return $return;
	}

    public function destroy($token, $secret)
	{
		//basic check on credentials existing
		if(!$token)
		{
			echo("Invalid Token");
			return false;
		}
				
		if(!$secret || $secret == "")
		{
			echo("Invalid Secret Key");
			return false;
		}
		
		//generating the valid signature
		$signature = hash('sha256', $secret. $token);
		
		//HTTP Headers
		$request_options = array(
			'Host: api.etilbudsavis.dk',
			'Origin: api.etilbudsavis.dk',
			'Content-Type: application/json',
			'Accept: */*',
			'X-Token: '.$token,
			'X-Signature: '.$signature
		);		

		$request_body = '';	 //empty body, call fails without one.
		
		//HTTP Request-Response
		try 
		{
			$init_request = new CRequest();
			$response = $init_request->curl_delete("https://api.etilbudsavis.dk/v2/sessions", $request_body, $request_options);
		} catch (Exception $e) {
			echo $e->getMessage(), "\n";
		}
		return $this->responseHandler($response);
	}



    //optionals:

    /**
     */
    public function getCatalogList($token, $secret, array $options)
	{
		//basic check on credentials existing
		if(!$token)
		{
			echo("Invalid Token");
			return false;
		}
				
		if(!$secret || $secret == "")
		{
			echo("Invalid Secret Key");
			return false;
		}
		
		//generating the valid signature
		$signature = hash('sha256', $secret. $token);
		
		//basic check on other required fields
		if(!is_float($options['lat']))
		{
			echo("Invalid Latitude");
			return false;
		}
		
		if(!is_float($options['lng']))
		{
			echo("Invalid Longitude");
			return false;
		}
		
		if(!is_int($options['radius']))
		{
			echo("Invalid Radius");
			return false;
		}
		
		//HTTP Headers
		$request_options = array(
			'Host: api.etilbudsavis.dk',
			'Origin: api.etilbudsavis.dk',
			'Accept: */*',
			'X-Token: '.$token,
			'X-Signature: '.$signature
		);
		
		//HTTP Request minimum body
		$request_body = array(
			"r_lat" => $options['lat'],
			"r_lng" => $options['lng'],
			"r_radius" => $options['radius']
		);
		
		//add options to the body if they exist
		if(array_key_exists('catalog_ids', $options))
		{		
			$request_body['catalog_ids'] = $options['catalog_ids'];
		}
		
		if(array_key_exists('dealer_ids', $options))
		{
			$request_body['dealer_ids'] = $options['dealer_ids'];
		}
		
		if(array_key_exists('store_ids', $options))
		{
			$request_body['store_ids'] = $options['store_ids'];
		}
		
		if(array_key_exists('limit', $options) && is_int($options['limit']))
		{
			$request_body['limit'] = $options['limit'];
		}

		if(array_key_exists('offset', $options) && is_int($options['offset']))
		{
			$request_body['offset'] = $options['offset'];
		}
		
		//valid sorting attributes
		$valid_sort = array("popularity", "-popularity", "dealer", "-dealer",
						"created", "-created", "expiration_date", "-expiration_date",
						"publication_date", "-publication_date", "distance", "-distance");
		
		//add sorting attributes if one (string) or more (array) exist
		if(array_key_exists('sort', $options) && is_string($options['sort']))
		{
			if(in_array($options['sort'], $valid_sort))
			{
				$request_body['order_by'] = $options['sort'];
			}
		} 
		else if(array_key_exists('sort', $options) && is_array($options['sort']))
		{
			$sort_string = "";
			for($i = 0; $i < count($options['sort']); $i++)
			{
				if(in_array($options['sort'][$i], $valid_sort))
				{
					$sort_string .= $options['sort'][$i];
					if($i !== count($options['sort'])-1)
					{
						$sort_string .= ',';   //separate different sorting strings with a comma
					}
				}
			}
			if($sort_string !== "")
			{
				$request_body['order_by'] = $sort_string;
			}
		}
		
		//HTTP Request-Response
		try 
		{
			$init_request = new CRequest();
			$response = $init_request->curl_get("https://api.etilbudsavis.dk/v2/catalogs", $request_body, $request_options);
		} catch (Exception $e) {
			echo $e->getMessage(), "\n";
		}
		return $this->responseHandler($response);		
	}
	
	//strip $options of anything not required to get only 1 catalog
	//and call getCatalogList with that. 1 catalogs_id is required
    public function getCatalog($token, $secret, array $options)
	{
		// Variable checking will be done in getCatalogList() again
		// but it is better to catch errors as early as possible
		if(!$token)
		{
			echo("Invalid Token");
			return false;
		}
				
		if(!$secret || $secret == "")
		{
			echo("Invalid Secret Key");
			return false;
		}

		if(!is_float($options['lat']))
		{
			echo("Invalid Latitude");
			return false;
		}
		
		if(!is_float($options['lng']))
		{
			echo("Invalid Longitude");
			return false;
		}
		
		if(!is_int($options['radius']))
		{
			echo("Invalid Radius");
			return false;
		}
		
		//this is required for this call
		if(!array_key_exists('catalog_ids', $options))
		{
			echo("Invalid Catalog ID");
			return false;
		}
		
		//if there are multiple IDs, get only the first one
		//as this should not return a list
		if(strstr($options['catalog_ids'], ','))
		{
			$ids = explode(',', $options['catalog_ids']);
			$options['catalog_ids'] = $ids[0];
		}
		
		//copy only the necessary information to the new call
		$catalog = array(
			'lat' => $options['lat'],
			'lng' => $options['lng'],
			'radius' => $options['radius'],
			'catalog_ids' => $options['catalog_ids']
		);
		
		//one catalog is a special case of a catalogList request
		//with just 1 id
		return $this->getCatalogList($token, $secret, $catalog);
	}



    /**
     */
    public function getStoreList($options)
	{
	
	}

    /**
     */
    public function getStore($options)
	{
	
	}



    /**
     */
    public function getOfferList($options)
	{
	
	}

    /**
     */
    public function getOffer($options)
	{
	
	}



    /**
     */
    public function getDealerList($options)
	{
	
	}

    /**
     */
    public function getDealer($options)
	{
	
	}
	
	// Decodes HTTP Response's body from JSON to PHP
	//
	// @param		String			$response		The HTTP Response encoded in JSON
	// @return		Object			$php_response	The JSON-Decoded HTTP Response
	// @return		Boolean			false			In case of errors
	private function responseHandler($response)
	{
		$php_response = json_decode($response);
		
		if(json_last_error() == JSON_ERROR_NONE)
		{
			if(property_exists((object)$php_response, 'code'))
			{
				echo("Error code: ". $php_response->{'code'}.". Error Message: ".$php_response->{'message'});
				return false;
			}
			else
			{
				return($php_response);
			}
		} else return false;
	}
	
}

//end of classes/Client.php