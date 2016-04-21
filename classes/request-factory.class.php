<?php

class Request_Factory {
	
	protected $connect;
	
	
	public function __construct(){
		
		require_once 'connect.class.php';
		
		$this->connect = new Connect();
		
	} // end __construct
	
	
	
	public function get_request_by_id( $request_id ){
		
		$sql = "SELECT * From maggiecare_requests WHERE request_id='$request_id'";
		
		if ( $result = $this->connect->select( $sql ) ){
			
			$request = false;
			
			switch( $result[0]['type'] ){
					
				case 'invite':
					require_once 'invite_request.class.php';
					$request = new Invite_Request();
					break;
			} // end switch
			
			if ( $request ) $request->set_request_from_db( $result[0] );
			
			return $request;
			
		} else {
			
			return false;
			
		}
		
	} // end get_open_response
	
	
	
	
	
}