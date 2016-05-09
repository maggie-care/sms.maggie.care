<?php
class API_Users_Add {
	
	protected $require_path;
	
	public function get_require_path(){ return $this->require_path;}
	
	
	public function __construct(){
		
		$this->require_path = $_SERVER["DOCUMENT_ROOT"] . '/';
		
		if ( isset( $_GET['access_key'] ) && isset( $_GET['acct_id'] ) ) {
			
			$this->do_request( $_GET['acct_id'] , $_GET['access_key'] );
			
		} else {
			
			die('Invalid Request');
			
		} // end if
		
	}
	
	private function do_request( $acct_id , $access_key ){
		
		require_once $this->get_require_path() . 'classes/account.class.php';
		
		$account = new Account();
		
		if ( $account->verify_access( $acct_id , $access_key ) ){
			
			require_once $this->get_require_path() . 'classes/user.class.php';
			
				
			$user = new User();
			
			$user_settings = $user->user_get_settings();
			
			if ( $user_settings ){
				
				$user->invite_user( $account, $user_settings );
				
			} else {
				
				$msg = array(
					'status' => 0,
					'response' => 'Sorry, we need more info on this user',  
				);
				
				echo json_encode( $msg );
				
			}// end if
			
		} else {
			
			die('Invalid Request');
			
		}
		
	} // do_request
	
} 

$api_users_add = new API_Users_Add();