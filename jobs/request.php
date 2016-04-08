<?php namespace mcaresms;

//require_once dirname( dirname( __FILE__ ) ) . '/classes/authorize.class.php';

require_once dirname( dirname( __FILE__ ) ) . '/classes/requests.class.php';

require_once dirname( dirname( __FILE__ ) ) . '/classes/connect.class.php';

class Request_Job {
	
	private $connect;
	
	public function __construct(){
		
		ini_set('display_errors', 1);
		
		ini_set('display_startup_errors', 1);

		error_reporting(E_ALL);
		
		$this->connect = new Connect();
		
		$this->connect->connect();
		
		$this->do_request();
		
		//$authorize = new Authorize( $this->connect );
		
		//if( $authorize->authorize_requests( 1 ) ){
		
			//$this->do_request();
		
		//} else {
			
			//die('Invalid Request');
			
		//} // end if
		
	} // end __construct
	
	private function do_request(){
		
		$requests = new Requests( $this->connect );
		
		$requests->set_requests();
		
	} // end do_request
	
	
}
$request_job = new Request_Job();