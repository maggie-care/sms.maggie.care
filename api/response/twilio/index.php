<?php 

class API_Response_Text {
	
	protected $require_path;
	
	public function get_require_path(){ return $this->require_path;}
	
	
	public function __construct(){
		
		ini_set('display_errors', 1);
		
		ini_set('display_startup_errors', 1);

		error_reporting(E_ALL);
		
		$this->require_path = $_SERVER["DOCUMENT_ROOT"] . '/';
		
		header("content-type: text/xml");
		
    	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		
		$this->do_response();
		
	}
	
	public function do_response(){
		
		require_once $this->get_require_path() . 'classes/response.class.php';
		
		require_once $this->get_require_path() . 'classes/request-factory.class.php';
		
		$response = new Response();
		
		$response->set_text_response();
		
		$request_factory = new Request_Factory();
		
		$request = $request_factory->get_request_by_id( $response->get_request_id() );
		
		if ( $request ){
			
			$response_msg = $request->do_request( $response );
			
			//$response_msg = '';
			
		} else {
			
			$response_msg = 'You\'re fast, but someone is faster. Maggie found someone to help already!';
			
		} // end if
		
		//$request->update_request( $response );
		
		$this->confirm( $response_msg );
		
		//$response = new Response();
		
		//$request_response = $response->get_response_request();
		
		//$this->confirm( $response );
		
		//$response->set_text_response();
		
		
		
		
	}
	
	public function confirm( $response ){
		
		echo '<Response><Message>';
		
		echo $response;
		
		echo '</Message></Response>';
		
	} // end
	
}

$api_response_text = new API_Response_Text();