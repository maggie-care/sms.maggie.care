<?php namespace mcaresms;

require_once dirname( dirname( __FILE__ ) ) . '/classes/authorize.class.php';

require_once dirname( dirname( __FILE__ ) ) . '/classes/requests.class.php';

class Request_Job {
	
	public function __construct(){
		
		$authorize = new Authorize();
		
		if( $authorize->authorize_requests( 1 ) ){
		
			$this->do_request();
		
		} else {
			
			die('Invalid Request');
			
		} // end if
		
	} // end __construct
	
	private function do_request(){
		
		$requests = new Requests();
		
		$requests->query();
		
	} // end do_request
	
	
}
$request_job = new Request_Job();