<?php namespace mcaresms;

require_once 'connect.class.php';

require_once 'request.class.php';

class Requests extends Connect {
	
	protected $requests = array();
	
	public function get_requests(){ return $this->requests;}
	
	
	public function query(){
		
		$this->connect('api');
		
		$mysqli = $this->get_connect();
		
		$sql = 'SELECT * FROM maggiecare_open_requests';
		
		$query = $mysqli->query( $sql );
		
		while( $row = $query->fetch_assoc() ){
			
			$request = new Request();
			
			$request->set_sql_request( $row );
			
			$this->requests[] = $request;
			
		};	
		
	} // end query
	
	
}