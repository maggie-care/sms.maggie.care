<?php
class API_Confirm_User {
	
	protected $require_path;
	
	
	public function get_require_path(){ return $this->require_path;}
	
	
	public function __construct(){
		
		$this->require_path = $_SERVER["DOCUMENT_ROOT"] . '/';
		
		if ( isset( $_GET['api_key'] ) ){
			
			require_once $this->get_require_path() . 'classes/api.class.php';
			
			$api = new Api();
			
			if ( $api->verify_access( $_GET['api_key'] ) ){
			
				$this->do_request();
				
			} else {
				
				die( 'Invalid Request' );
				
			} // end if
			
			
		} else {
			
			die( 'Invalid Request' );
			
		}// end if
		
	} // end construct
	
	
	private function do_request(){
		
		header("content-type: text/xml");
		
    	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		
		if ( strpos( $_POST['Body'] , 'YES' ) !== false) {
		
		require_once $this->get_require_path() . 'classes/user.class.php';
		
		require_once $this->get_require_path() . 'classes/account.class.php';
		
		$user = new User();
		
		//$invite = $user->get_user_invite( $_REQUEST['From'] );
		
		$invite = $user->get_user_invite( $_POST['From'] );
		
		$user_id = $user->insert_user( $invite['name'] , $invite['phone'] );
		
		if ( $user_id ){
			
			$account = new Account();
			
			if ( $account->set_account_by_id( $invite['acct_id'] ) ){
				
				if( $account->add_acct_user( $user_id , $invite['role_id']) ){
					
					
					echo '<Response><Message>Way to show some love. MaggieCare has notified [OWNER] that you\'re part of their
						network. You\'ll be notified if [name] has a need and be given a chance to help.</Message></Response>';
					
				} else {
					
					echo '<Response><Message>Sorry we can\'t seem to add you to the account!</Message></Response>';
					
				};
				
			} else {
				
				echo '<Response><Message>Sorry we can\'t seem to find the account!</Message></Response>';
				
			};
			
		} else {
			
			echo '<Response><Message>Sorry we can\'t seem to find your invite!</Message></Response>';
			
		}
		
		} else {
			
			echo '<Response><Message>Error</Message></Response>';
			
		}// end if
		
		
	} // end do_request
	
	
}
$api_confirm_user = new API_Confirm_User();