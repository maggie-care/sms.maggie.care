<?php

header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

require_once $_SERVER["DOCUMENT_ROOT"] . '/classes/api.class.php';

class Account_Get extends Api{
	
	protected $show_errors = true;
	
	
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
				
				$account_array = array( 
					'acct_id'    => $account->get_id(),
					'access_key' => $account->get_access_key(),
					'users'      => $this->get_response_account_users( $account ),
				);
				
				
				$this->response( true , 'Success' , $account_array );
				
			} else {
				
				$this->response( false , 'Invalid Access Key' , array( $acct_id , $access_key ) );
				
			}// end if
			
		} else {
			
			$this->response( false , 'Invalid Request' );
			
			die();
			
		} // end if
		
	}
	
}

$account_get = new Account_Get();