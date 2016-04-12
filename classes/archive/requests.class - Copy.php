<?php namespace mcaresms;

require_once 'request.class.php';

class Requests {
	
	protected $connect;
	
	protected $requests = array();
	
	public function __construct( $connect ){
		
		$this->connect = $connect;
		
	} // end __construct
	
	public function get_requests(){ return $this->requests;}
	
	
	public function set_requests(){
		
		$mysqli = $this->connect->get_connect();
		
		$sql = 'SELECT * FROM maggiecare_open_requests';
		
		$query = $mysqli->query( $sql );
		
		while( $row = $query->fetch_assoc() ){
			
			$request = new Request( $this->connect );
			
			$request->set_sql_request( $row );
			
			$this->requests[ $row['request_id'] ] = $request;
			
		};	
		
	} // end query
	
	
}