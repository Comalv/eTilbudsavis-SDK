<?php


class CRequest implements ICRequest
{
	public function curl_get($url, array $get = NULL, array $options = array())
	{
		$defaults = array( 
				CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get), 
				CURLOPT_HEADER => 0, 				//do not return headers in the response
				CURLOPT_RETURNTRANSFER => TRUE, 	//return the body of the response
				CURLOPT_TIMEOUT => 4,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_AUTOREFERER    => true,
				CURLOPT_SSL_VERIFYPEER => false,    //for localhost purposes only
				CURLOPT_CONNECTTIMEOUT => 120,
				CURLOPT_TIMEOUT        => 120,
				CURLOPT_MAXREDIRS      => 10,		//number of allowed redirects
				CURLOPT_HEADERFUNCTION => array($this, 'header_callback'),	//let another function handle the response headers
				CURLOPT_HTTPHEADER => $options				
		); 

		$init = curl_init(); 
		curl_setopt_array($init, $defaults); 		//build the full request
		if( ! $result = curl_exec($init)) 
		{ 
			trigger_error(curl_error($init)); 
		} 
		curl_close($init); 
		return $result; 
	}
	
	public function curl_post($url, $post = NULL, array $options = array())
	{
		$defaults = array( 
			CURLOPT_POST => 1, 
			CURLOPT_HEADER => 0, 
			CURLOPT_URL => $url, 
			CURLOPT_FRESH_CONNECT => 1, 
			CURLOPT_RETURNTRANSFER => 1, 
			CURLOPT_FORBID_REUSE => 1, 
			CURLOPT_TIMEOUT => 4, 
			CURLOPT_POSTFIELDS => $post,		//request body
			CURLOPT_SSL_VERIFYPEER => false,    //for localhost purposes only
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_AUTOREFERER    => true,
			CURLOPT_CONNECTTIMEOUT => 120,
			CURLOPT_TIMEOUT        => 120,
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_HTTPHEADER => $options,
			CURLOPT_HEADERFUNCTION => array($this, 'header_callback')
		); 

		$init = curl_init(); 
		curl_setopt_array($init, $defaults); 
		if( ! $result = curl_exec($init)) 
		{ 
			trigger_error(curl_error($init)); 
		} 
		curl_close($init); 
		return $result;
	}
	
	public function curl_put($url, $json_data = NULL, array $options = array())
	{
		$defaults = array( 
			CURLOPT_PUT => 1, 
			CURLOPT_HEADER => 0, 
			CURLOPT_URL => $url, 
			CURLOPT_FRESH_CONNECT => 1, 
			CURLOPT_RETURNTRANSFER => 1, 
			CURLOPT_FORBID_REUSE => 1, 
			CURLOPT_TIMEOUT => 4, 
			CURLOPT_VERBOSE => 1,
			CURLOPT_CUSTOMREQUEST => "PUT",
			CURLOPT_SSL_VERIFYPEER => false,    //for localhost purposes only
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_AUTOREFERER    => true,
			CURLOPT_CONNECTTIMEOUT => 120,
			CURLOPT_TIMEOUT        => 120,
			CURLOPT_MAXREDIRS      => 10,	
			CURLOPT_HTTPHEADER => $options,
			CURLOPT_HEADERFUNCTION => array($this, 'header_callback'),
			CURLOPT_POSTFIELDS => $json_data			
		); 
		
		$init = curl_init(); 
		curl_setopt_array($init, $defaults); 
		if( ! $result = curl_exec($init)) 
		{ 
			trigger_error(curl_error($init)); 
		} 
		curl_close($init); 
		return $result;
	}
	public function curl_delete($url, $delete = NULL, array $options = array())
	{
		$defaults = array( 
			CURLOPT_HEADER => 0, 
			CURLOPT_URL => $url, 
			CURLOPT_FRESH_CONNECT => 1, 
			CURLOPT_RETURNTRANSFER => 1, 
			CURLOPT_FORBID_REUSE => 1, 
			CURLOPT_TIMEOUT => 4, 
			CURLOPT_VERBOSE => 1,
			CURLOPT_CUSTOMREQUEST => "DELETE",
			CURLOPT_POSTFIELDS => $delete,
			CURLOPT_SSL_VERIFYPEER => false,    //for localhost purposes only
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_AUTOREFERER    => true,
			CURLOPT_CONNECTTIMEOUT => 120,
			CURLOPT_TIMEOUT        => 120,
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_HEADERFUNCTION => array($this, 'header_callback'),
			CURLOPT_HTTPHEADER => $options
		
		); 
		
		$init = curl_init(); 
		curl_setopt_array($init, $defaults); 
		
		if( ! $result = curl_exec($init)) 
		{ 
			trigger_error(curl_error($init)); 
		} 
		curl_close($init); 
		return $result;
	}
	
	// Check the status of the HTTP Response
	//
	// @param		Resource		$ch				The cURL resource used for the request/response
	// @param		String			$header_line	The current header line parsed
	// @return		Int								The current header line length (required)
	// Notes: in case of a redirect (HTTP/1.1 100 Continue) It will also validate the next header
	// unlike cURL own function which sometimes fails when errors 401 or 407 follow a 100 Status
	private function header_callback($ch, $header_line)
	{
		//only interested in the lines pertaining the status of the response
		if("HTTP" == strtoupper(substr($header_line, 0, 4)))
		{
			//if the response code is an error, display it
			if(intval(trim(substr($header_line, 9, 4))) >= 400)
			{	
				echo("The HTTP Request has returned an error: ". substr($header_line, 9));
			}
		}
		return strlen($header_line);
	}
}
//end of file classes/CRequest.php