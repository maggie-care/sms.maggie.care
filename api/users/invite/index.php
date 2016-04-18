<?php
class API_Invite_User {
	
	protected $require_path;
	
	public function get_require_path(){ return $this->require_path;}
	
	
	public function __construct(){
		
		ini_set('display_errors', 1);
		
		ini_set('display_startup_errors', 1);

		error_reporting(E_ALL);
		
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
			
			$account->set_account_by_id( $acct_id );
			
			require_once $this->get_require_path() . 'classes/user.class.php';
		
			require_once $this->get_require_path() . 'classes/invite_request.class.php';
			
			var_dump( $account->get_acct_owner() );
			
			$user = new User();
			
			// Creates or sets a user
			/*if ( $user->create_set_user( $this->get_user_settings() ) ){
				
				if ( $sms_number = $account->add_user_to_account( $user->get_user_id() , array( 'role_id' => $user->get_role_id() ) ) ){
					
					$request = new Invite_Request();
					
					$request_settings = array(
						'acct_id' => $account->get_acct_id(),
						'user_id' => $user->get_user_id(), 
					);
					
					if ( $request->create_set( 'invite' , $request_settings ) ){
						
						$owner = $account->get_acct_owner();
						
						//$request->send( $user->get_phone() , $sms_number , array( 'owner' => $owner->get_name() ) );
						
					} else {
						
						echo 'Could not create request';
						
					};
					
				} else {
					
					echo 'Could not add user to account';
				}
				
				//if ( $request->invite_user( $account , $user ) ){
				//} else {
				//}
				
			} else {
				
				echo 'user not created';
				
			}*/
			
			//require_once $this->get_require_path() . 'classes/user.class.php';
				
			//$user = new User();
			
			//if ( ! $user->set_user_by_phone( $_GET['phone'] ) ){
				
				//echo json_encode( array( 'status' => 1, 'response' => 'We\'ve invited ' . $_GET['name'] . ' to your network' ) );
				
			//} else {
				
				//$account->invite_user( $user );
				
				//echo json_encode( array( 'status' => 1, 'response' => 'We\'ve found ' . $user->get_name() . ' and invited them to your network' ) );
				
			//} // end if
			
			/*$user_settings = $user->user_get_settings();
			
			if ( $user_settings ){
				
				$user->invite_user( $account, $user_settings );
				
			} else {
				
				$msg = array(
					'status' => 0,
					'response' => 'Sorry, we need more info on this user',  
				);
				
				echo json_encode( $msg );
				
			}// end if*/
			
		} else {
			
			die('Invalid Request');
			
		}
		
	} // do_request
	
	private function get_user_settings(){
		
		$settings = array();
		
		$settings['name'] = ( ! empty( $_GET['name'] ) ) ? $_GET['name'] : '';
			
		$settings['role_id'] = ( ! empty( $_GET['role_id'] ) ) ?  $_GET['role_id'] : 4;
		
		$settings['phone'] = ( ! empty( $_GET['phone'] ) ) ? $_GET['phone'] : '';
			
		$settings['email'] = ( ! empty( $_GET['email'] ) ) ? $_GET['email'] : '';
		
		return $settings;
		
	} // end get_user_settings
	
} 

$api_invite_users = new API_Invite_User();