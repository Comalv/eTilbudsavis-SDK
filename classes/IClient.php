<?php


interface IClient
{
	// Obtain a valid API Session
	//
	// @param		String			$key			The API Key
	// @param		Array			$v1				Optional array with API v1 Auth details
	// @return		Object							The JSON-Decoded API Response
	// @return		Boolean			false			If errors occur before the API can give a response
    public function initialize($key, array $v1 = NULL);

	// Sign In a User
	//
	// @param		Array			$credentials	User Credentials
	// @return		Object							The JSON-Decoded API Response
	// @return		Boolean			false			If errors occur before the API can give a response
    public function signIn($credentials);

	// Sign Out
	//
	// @param		Array			$credentials	User Credentials
	// @return		Object							The JSON-Decoded API Response
	// @return		Boolean			false			If errors occur before the API can give a response
	// Notes: this simply empties the email field and calls signIn()
    public function signOut($credentials);

	// Destroys the current Session
	//
	// @param		String			$token			The current token for the session
	// @param		String			$secret			The API Secret
	// @return		Object							The JSON-Decoded API Response
	// @return		Boolean			false			If errors occur before the API can give a response
    public function destroy($token, $secret);

	
	// Gets a catalog list
	//
	// @param		String			$token			The current token for the session
	// @param		String			$secret			The API Secret
	// @param		Array			$options		The List options
	// @return		Array (of Objects)				The JSON-Decoded API Response
	// @return		Boolean			false			If errors occur before the API can give a response
	//
	// Notes: Structure of $options
	//
	// @key			@value			Required/Optional
	// lat			Float			required		The Latitude value of the request
	// lng			Float			required		The Longitude value of the request
	// radius		Int				required		The Radius in meters of the search area
	// catalog_ids	String			optional		The comma-separated ids of catalogs to search
	//												Note: this overrides other filters and radius
	// dealer_ids	String			optional		The comma-separated ids of dealers to search
	// store_ids	String			optional		The comma-separated ids of stores to search
	// limit		Int				optional		The maximum numbers of results to return
	// offset		Int				optional		The offset at which the returned results start
	// sort			String or Array optional		One or more comma-separated sorting words
	//												Valid values are (prepend '-' for Descending orded)
	//												distance, dealer, created, expiration_date,
	//												publication_date, popularity
    public function getCatalogList($token, $secret, array $options);

	// Gets a catalog
	//
	// @param		String			$token			The current token for the session
	// @param		String			$secret			The API Secret
	// @param		Array			$options		The List options
	// @return		Array (of Objects)				The JSON-Decoded API Response
	// @return		Boolean			false			If errors occur before the API can give a response
	//
	// Notes: Structure of $options
	//
	// @key			@value			Required/Optional
	// lat			Float			required		The Latitude value of the request
	// lng			Float			required		The Longitude value of the request
	// radius		Int				required		The Radius in meters of the search area
	// catalog_ids	String			required		The id of the catalog
    public function getCatalog($token, $secret, array $options);



    /**
     */
    public function getStoreList($options);

    /**
     */
    public function getStore($options);



    /**
     */
    public function getOfferList($options);

    /**
     */
    public function getOffer($options);



    /**
     */
    public function getDealerList($options);

    /**
     */
    public function getDealer($options);

}
