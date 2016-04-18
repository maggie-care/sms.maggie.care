<?php

class Account {
	
	protected $connect;
	
	private $acct_id = false;
	
	private $opened = false;
	
	private $api_key = false;
	
	private $users = array();
	
	private $acct_owner;
	
	public function __construct(){
		
		require_once 'connect.class.php';
		
		$this->connect = new Connect();
		
	} // end __construct
	
	
	
	public function get_acct_id(){ return $this->acct_id; }
	
	public function get_users(){ return $this->users;}
	
	public function get_acct_owner() { return $this->acct_owner; }
	
	
	public function verify_access( $acct_id , $access_key ){
		
		require_once 'connect.class.php';
		
		$connect = new Connect();
		
		$sql = "SELECT * FROM maggiecare_acct WHERE id='$acct_id'";
		
		$acct = $connect->select( $sql );
		
		if ( $acct[0]['api_key'] == $access_key ){
			
			$this->acct_id = $acct[0]['id'];
			
			$this->opened = $acct[0]['open'];
			
			$this->api_key = $acct[0]['api_key'];
			
			return true;
			
		} else {
			
			return false;
			
		}
		
	} // end verify_access
	
	public function set_account_by_id( $acct_id ){
		
		require_once 'connect.class.php';
		
		$connect = new Connect();
		
		$sql = "SELECT * FROM maggiecare_acct WHERE id='$acct_id'";
		
		$acct = $connect->select( $sql );
		
		if ( $acct ){
			
			$this->acct_id = $acct_id;
			
			$this->set_acct_users();
			
			var_dump( $this->get_users() );
			
			return true;
			
		} else {
			
			return false;
			
		} // end if
		
	} // end set_account_by_id
	
	public function set_acct_users( $acct_id = false ){
		
		if ( ! $acct_id ) $acct_id = $this->get_acct_id();
		
		require_once 'connect.class.php';
		
		$connect = new Connect();
		
		$sql = "SELECT * FROM maggiecare_acct_users WHERE acct_id='$acct_id'";
		
		$users = $connect->select( $sql );
		
		if ( $users ){
			
			require_once 'user.class.php';
			
			foreach( $users as $db_user ){
				
				$user = new User();
				
				$user->set_user_by_id( $db_user['user_id'] , $acct_id );
				
				if ( $db_user['role_id'] === 1 ) $this->acct_owner = $user;
				
				$this->users[ $db_user['user_id'] ] = $user;
				
			} // end foreach;
			
			return true;
			
		} else {
			
			return false;
			
		} // end if
		
	}
	
	public function add_user_to_account( $user_id , $args ){
		
		if ( array_key_exists( $user_id , $this->get_users() ) ) return false;
		
		$acct_id = $this->get_acct_id();
		
		$status = ( ! empty( $args['status'] ) ) ? $args['status'] : 'pending';
		
		$role_id = ( ! empty( $args['role_id'] ) ) ? $args['role_id'] : 4;
		
		$sms_number = $this->get_next_sms( $user_id );
		
		$result = $this->connect->insert( "INSERT INTO maggiecare_acct_users (user_id,acct_id,role_id,status,sms_number) 
				VALUES ('$user_id','$acct_id','$role_id','$status','$sms_number')" );
				
				var_dump( $result );
				
		if ( $result ){
			
			return $sms_number;
			
		} else {
			
			return false;
			
		}
		
	} // end add_user_to_account
	
	private function get_next_sms( $user_id ){
		
		require_once 'mcare-twilio.class.php';
		
		$twilio = new Mcare_Twilio();
		
		$sms_numbers = $twilio->get_numbers();
		
		$user_accts = $this->connect->select( "SELECT * FROM maggiecare_acct_users WHERE user_id='$user_id'" );
		
		$user_sms = $sms_numbers[0];
		
		if ( $user_accts ){
			
			$user_acct_sms = array();
			
			foreach( $user_accts as $user_acct ){
				
				$user_acct_sms[] = $user_acct['sms_number'];
				
			} // end foreach
			
			foreach( $sms_numbers as $sms_number ){
				
				if ( ! in_array( $sms_number , $user_acct_sms ) ){
					
					$user_sms = $sms_number;
					
				} // end if
				
			} // end foreach
			
		} // end if
		
		return $user_sms;
		
	} // end get_next_sms
	
	/*public function invite_user( $user_settings ){
		
		$nums = array();
		
		$name = ( ! empty( $user_settings['name'] ) ) ? $user_settings['name'] : '';
		$role_id = ( ! empty( $user_settings['role_id'] ) ) ?  $user_settings['role_id'] : 4;
		$phone = ( ! empty( $user_settings['phone'] ) ) ? $user_settings['phone'] : '';
		$email = ( ! empty( $user_settings['email'] ) ) ? $user_settings['email'] : '';
		
		$invite_user = $this->connect->select( "SELECT * FROM maggiecare_users WHERE phone='$phone'");
		
		if ( $invite_user ){
			
			$user_id = $invite_user[0]['user_id'];
			
			$user_accts = $this->connect->select( "SELECT * FROM maggiecare_acct_users WHERE user_id='$user_id'");
			
			if ( $user_accts ){
				
				foreach( $user_accts as $user_acct ){
				}// end foreach
				
			} // end if
			
			var_dump( $user_accts );
			
		} else {
			
			echo 'Was not found';
		}

	} // end $user
	
	/*public function add_acct_user( $user_id , $role_id , $acct_id = false ){
		
		if ( ! $acct_id ) $acct_id = $this->get_acct_id();
		
		require_once 'connect.class.php';
		
		$connect = new Connect();
		
		$api_key = md5(microtime().rand());
		
		$sql = "INSERT INTO maggiecare_acct_users (user_id,acct_id,role_id) VALUES ('$user_id','$acct_id','$role_id')";
		
		$result = $connect->insert( $sql );
		
		if ( $result !== false ){
			
			return true;
			
		} else {
			
			return false;
			
		}
		
	}*/
	
} // end Account