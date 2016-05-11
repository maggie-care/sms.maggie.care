<?php

header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');


require_once $_SERVER["DOCUMENT_ROOT"] . '/classes/api.class.php';


class Add_User extends Api {
	
	
	public function __construct(){
		
		parent::__construct();
		
		if ( isset( $_GET['access_key'] ) && isset( $_GET['acct_id'] ) ) {
			
			$acct_id = $_GET['acct_id'];
		
			$access_key = $_GET['access_key'];
			
			require_once $this->require_path . 'classes-new/account.class.php';
			
			require_once $this->require_path . 'classes-new/connect.class.php';
			
			$connect = new Connect();
			
			$account = new Account( $connect );
			
			$account->set_account( $acct_id );
			
			if ( $account->verify( $access_key ) ){
				
				$account->add_user( $this->get_user_args() );
				
				//$this->response( true , 'Success' , $this->get_response_account_users( $account ) );
				
			} else {
				
				$this->response( false , 'Invalid Request' );
				
			};
			
		} else {
			
			$this->response( false , 'Invalid Request' );
			
			die();
			
		} // end if
		
		
	} // end __construct
	
	
	public function get_user_args(){
		
		$args = array();
		
		$args['role_id'] = ( isset( $_GET['role_id'] ) )? $_GET['role_id'] : 4;
		
		$args['name'] = ( isset( $_GET['name'] ) )? $_GET['name'] : '';
		
		$args['phone'] = ( isset( $_GET['phone'] ) )? $_GET['phone'] : '';
		
		$args['email'] = ( isset( $_GET['email'] ) )? $_GET['email'] : '';
		
		$args['status'] = ( isset( $_GET['status'] ) )? $_GET['status'] : '';
		
		return $args;
		
	} // end if
	
	
	
}

$get_users = new Add_User();