<?php


interface ICRequest
{

	// Perform a cUrl GET request
	//
	// @param		String			$url			The full URL for the request
	// @param		Array			$get			The body of the request
	// @param		Array			$options		The HTTP Headers of the request
	// @return		String							The HTTP Response
	public function curl_get($url, array $get = NULL, array $options = array());
	
	// Perform a cUrl POST request
	//
	// @param		String			$url			The full URL for the request
	// @param		String			$post			The body of the request
	// @param		Array			$options		The HTTP Headers of the request
	// @return		String							The HTTP Response
	public function curl_post($url, $post = NULL, array $options = array());

	// Perform a cUrl PUT request
	//
	// @param		String			$url			The full URL for the request
	// @param		String			$put			The body of the request
	// @param		Array			$options		The HTTP Headers of the request
	// @return		String							The HTTP Response
	public function curl_put($url, $put = NULL, array $options = array());

	// Perform a cUrl DELETE request
	//
	// @param		String			$url			The full URL for the request
	// @param		String			$post			The body of the request
	// @param		Array			$options		The HTTP Headers of the request
	// @return		String							The HTTP Response	
	public function curl_delete($url, $delete = NULL, array $options = array());
}
//end of file classes/ICRequest.php