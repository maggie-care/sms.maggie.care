<?php namespace mcaresms;

class Request {
	
	/*private $request_id = false;
	
	private $acct_id = false;
	
	private $created = false;
	
	private $request_sent = false;
	
	private $responses = false;
	
	private $providers = false;
	
	private $request_provider = false;
	
	private $accepted_response = false;
	
	private $open_responses = false;
	
	
	public function get_acct_id(){ return $this->acct_id; }
	public function get_request_id(){ return $this->request_id; }
	public function get_request_sent(){ return $this->request_sent;}
	public function get_responses() { return $this->responses; }
	public function get_providers() { return $this->providers; }
	public function get_request_provider(){ return $this->request_provider; }
	public function get_accepted_response() { return $this->accepted_response;}
	public function get_open_responses(){ return $this->open_responses;}
	
	
	public function set_sql_request( $row ){
		
		$this->request_id = $row['request_id'];
		
		$this->acct_id = $row['acct_id'];
		
		$this->created = $row['created'];
		
		$this->request_sent = $row['request_sent'];
		
	} // end set_sql_request\
	
	
	public function set_responses(){
		
		require_once 'response.class.php';
		
		$this->responses = array();
		
		$connect = new Connect();
		
		$request_id = $this->get_request_id();
		
		$db_responses = $connect->select( "SELECT * FROM maggiecare_responses WHERE request_id='$request_id'" );
		
		if ( $db_responses ){
			
			foreach( $db_responses as $db_response ){
				
				$response = new Response();
				
				$response->set_from_db( $db_response );
				
				$this->responses[ $response->get_user_id() ] = $response;
				
			} // end foreach
			
		} // end if
		
	} // 
	
	
	public function set_providers(){
		
		require_once 'user.class.php';
		
		$this->providers = array();
		
		$connect = new Connect();
		
		$db_users = $connect->select( "SELECT * FROM maggiecare_acct_users WHERE acct_id='$this->acct_id'" );
		
		if ( $db_users ){
			
			foreach( $db_users as $db_user ){
				
				$user = new User();
				
				$user->set_db_user( $db_user );
				
				$this->providers[ $user->get_user_id() ] = $user;
				
			} // end foreach
			
		} // end if
		
	} // end set_providers
	
	public function is_accepted_request(){
		
		if ( $this->responses === false ) $this->set_responses();
		
		foreach( $this->get_responses() as $response ){
			
			if ( $response->is_accepted() ){
				
				$this->accepted_response = $response;
				
				return true;
				
			} // end if
			
		} // end foreach
		
		return false;
		
	} // end is_accepted_request
	
	
	public function set_request_provider(){
		
		if ( $this->providers === false ) $this->set_providers();
		
		if ( $this->responses === false ) $this->set_responses();
		
		if ( $this->open_responses === false ) $this->set_open_responses();
		
		foreach( $this->get_providers() as $provider_id => $provider ){
			
			if ( $provider->get_role_id() == 1 ){
				
				echo 'User ' . $provider->get_user_id() . ' is owner<br>';
				
			} else if ( array_key_exists( $provider->get_user_id() , $this->get_responses() ) ){
				
				echo 'User ' . $provider->get_user_id() . ' Responded';
				
			} else if( array_key_exists( $provider->get_user_id() , $this->get_open_responses() ) ) {
				
				echo 'User ' . $provider->get_user_id() . ' already has a pending request<br>';
				
			} else {
				
				$this->request_provider = $provider;
				
				echo 'User ' . $provider->get_user_id() . ' selected for request request<br>';
				
				break;
				
			} // end if
			
		} // endforeach
		
	} // end set_request_provider
	
	
	public function send_request(){
		
		$provider = $this->get_request_provider();
		
		$provider->set_user();
		
		if ( $this->insert_open_response( $provider ) ){
			
			if ( $this->update_request_sent() ){
				
				echo 'Request updated<br>';
				
				require_once 'mcare-twilio.class.php';
				
				$twilio = new Mcare_Twilio();
				
				$twilio->send_sms( $provider->get_phone() , 'Hello World' );
				
			} else {
				
				echo 'Could not update request<br>';
				
			}
			
			echo 'Open response created<br>';
			
		} else {
			
			echo 'Could not create open response<br>';
			
		}// end if
		
	} // end send_request
	
	
	public function insert_open_response( $provider ){
		
		require_once 'connect.class.php';
		
		$connect = new Connect();
		
		$user_id = $provider->get_user_id();
		
		$request_id = $this->get_request_id();
		
		$sql = "INSERT INTO maggiecare_open_responses (request_id,user_id,created) 
				VALUES ('$request_id','$user_id',now())";
		
		if ( $connect->query( $sql ) ){
			
			return true;
			
		} else {
			
			return false;
			
		};
		
		return true;
		
	} // insert_open_response
	
	
	public function set_open_responses(){
		
		$this->open_responses = array();
		
		$connect = new Connect();
		
		$db_responses = $connect->select( "SELECT * FROM maggiecare_open_responses" );
		
		if ( $db_responses ){
			
			foreach( $db_responses as $db_response ){
				
				$this->open_responses[ $db_response['user_id'] ] = $db_response['request_id'];
				
			} // end foreach
			
		} // end if
		
	} // 
	
	
	public function update_request_sent(){
		
		require_once 'connect.class.php';
		
		$connect = new Connect();
		
		$request_id = $this->get_request_id();
		
		//$sql = "INSERT INTO maggiecare_requests (request_sent) 
				//VALUES (now()) WHERE request_id='$request_id'";
				
		$sql = "UPDATE maggiecare_open_requests SET request_sent=now() WHERE request_id='$request_id'";
		
		if ( $connect->query( $sql ) ){
			
			return true;
			
		} else {
			
			return false;
			
		};
		
	}*/
	
}