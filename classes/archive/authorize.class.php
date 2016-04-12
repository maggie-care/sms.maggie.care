<?php namespace mcaresms;

//require_once 'connect.class.php';

class Authorize {
	
	private $connect;
	
	public function __construct( $connect = false ){
		
		$this->connect = $connect;
		
	} // end __construct
	
	public function authorize_requests( $api_key ){
		
		return true;
		
	} // end query
	
	
}