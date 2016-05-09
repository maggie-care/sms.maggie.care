<?php

header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

require_once $_SERVER["DOCUMENT_ROOT"] . '/classes/mcare-service.class.php';

class Create_Account extends Mcare_Service {
	
	
	protected $require_path;
	
	protected $service;
	
	
	public function __construct(){
		
		ini_set('display_errors', 1);
		
		ini_set('display_startup_errors', 1);

		error_reporting(E_ALL);
		
		$this->require_path = $_SERVER["DOCUMENT_ROOT"] . '/';
		
		if ( $this->verify_access() ){
			
			$this->create_account();
			
		} else {
			
			$this->respond( false , 'ERROR: Invalid access key provided' );
			
		}// end if
		
	} // end __construct
	
	protected function create_account(){
		
		require_once $this->require_path . 'classes/mcare-account.class.php';
		
		$account = new Mcare_Account();
		
		if ( $acct_id = $account->create_account() ){
			
			$account->the_account( $acct_id );
			
			require_once $this->require_path . 'classes/mcare-user.class.php';
			
			$user = new Mcare_User();
			
			if ( $user_id = $user->create_user( $user->get_user_settings() ) ){
				
				$account->add_user_to_account( $user_id , array( 'role_id' => 1 , 'status' => 'accepted' ) );
				
				$this->respond( true , 'Account Created' , array( 'acct_id' => $account->get_acct_id() , 'access_key' => $account->get_api_key() ) );
				
			} else {
				
				$this->respond( false , 'ERROR: Could not create user' );
				
			} // end if
			
		} else {
			
			$this->respond( false , 'ERROR: Could not create account' );
			
		} // end if
		
	} // end create_account
	
	
	
	
}
$create_account = new Create_Account();