<?php namespace mcaresms;

class Request_Job {
	
	private $connect;
	
	public function __construct(){
		
		$this->do_requests();
		
	} // end __construct
	
	
	private function do_requests(){
		
		$requests = $this->get_requests();
		
		if ( $requests ){
			
			foreach( $requests as $request ){
				
				$this->the_request( $request );
				
			} // end foreach
			
		} // end if
		
	} // end do_request
	
	
	private function the_request( $request ){
		
		if ( $request->is_accepted_request() ){
			
			echo 'Yeah Cool, This request is accepted';
			
		} else {
			
			$request->set_request_provider();
			
			if ( $request->get_request_provider() ){
				
				$request->send_request();
				
			} else {
				
				echo 'Looks like there arent any available providers<br>';
				
			} // end if
			
		} // end if
		
		echo '<hr>';
		
	}
	
	
	
	private function get_requests(){
		
		require_once dirname( dirname( __FILE__ ) ) . '/classes/requests.class.php';
		
		require_once dirname( dirname( __FILE__ ) ) . '/classes/connect.class.php';
		
		$requests = array();
		
		$connect = new Connect();
		
		$db_requests = $connect->select( 'SELECT * FROM maggiecare_open_requests' );
		
		if ( $db_requests ){
			
			foreach( $db_requests as $db_request ){
				
				$request = new Request();
				
				$request->set_sql_request( $db_request );
				
				$requests[  $request->get_request_id()  ] = $request;
				
			} // end foreach
			
		} // end if
		
		return $requests;
		
	} // end 
	
	

	
}
$request_job = new Request_Job();