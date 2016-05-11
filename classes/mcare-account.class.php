<?php

class Mcare_Account {
	
	protected $connect;
	protected $acct_id;
	private $opened;
	private $api_key;
	protected $users = array();
	
	
	public function get_acct_id(){ return $this->acct_id; }
	public function get_api_key() { return $this->api_key; }
	public function get_users() { return $this->users; }
	
	
	public function __construct(){
		
		require_once 'connect.class.php';
		
		$this->connect = new Connect();
		
	} // end __construct
	
	
	public function the_account( $acct_id , $basic = false ){
		
		if ( $row = $this->get_account( $acct_id ) ){
			
			$this->set_account_from_db( $row );
			
		} // end if
		
		if ( ! $basic ){
			
			$this->set_account_users( $acct_id );
			
		} // end if
		
	} // end if
	
	
	public function get_account( $acct_id ){
		
		if ( $row = $this->connect->select( "SELECT * FROM maggiecare_acct WHERE id='$acct_id'" ) ){
			
			return $row[0];
			
		} else {
			
			return false;
			
		} // end if
		
	} // end get_account
	
	
	public function set_account_from_db( $row ){
		
		$this->acct_id = $row['id'];
		
		$this->api_key = $row['api_key'];
		
		$this->open = $row['open'];
		
	} // end set_account_from_db
	
	
	public function create_account(){
		
		$api_key = md5(microtime().rand());
		
		$salt = uniqid( mt_rand() , true );
			
		$sql = "INSERT INTO maggiecare_acct (api_key,salt,open) VALUES ('$api_key','$salt',now())";
		
		if ( $acct_id = $this->connect->insert( $sql ) ){
			
			return $acct_id;
			
		} else {
			
			return false;
			
		} // end if
		
	} // end create_account
	
	
	public function set_account_users( $acct_id = false ){
		
		require_once 'mcare-user.class.php';
		
		if ( ! $acct_id ) $acct_id = $this->get_acct_id();
		
		if ( $acct_users = $this->get_account_users( $acct_id ) ){
			
			foreach( $acct_users as $acct_user ){
				
				$user = new Mcare_User();
				
				$user->the_user( $acct_user['user_id'] );
				
				$user->the_user_acct( $acct_id , $acct_user );
				
				$this->users[ $acct_user['user_id'] ] = $user;
				
			} // end foreach
			
			return true;
			
		} else {
			
			return false;
			
		}// end if 
		
	} // end set_account_users
	
	
	public function get_account_users( $acct_id = false ){
		
		if ( ! $acct_id ) $acct_id = $this->get_acct_id();
		
		if ( $users = $this->connect->select( "SELECT * FROM maggiecare_acct_users WHERE acct_id='$acct_id'" ) ){
			
			return $users;
			
		} else {
			
			return array();
			
		} // end if
		
	} // end get_account_users
	
	
	public function add_user_to_account( $user_id , $args , $acct_id = false ){
		
		if ( ! $acct_id ) $acct_id = $this->get_acct_id();
		
		$users = $this->get_users();
		
		if ( $users ) $this->set_account_users( $acct_id );
		
		if ( array_key_exists( $user_id , $this->get_users() ) ) {
			
			return true;
			
		} else {
			
			$status = ( ! empty( $args['status'] ) ) ? $args['status'] : 'pending';
			
			$role_id = ( ! empty( $args['role_id'] ) ) ? $args['role_id'] : 4;
			
			$sms_number = $this->get_next_sms( $user_id );
			
			$result = $this->connect->insert( "INSERT INTO maggiecare_acct_users (user_id,acct_id,role_id,status,sms_number) 
					VALUES ('$user_id','$acct_id','$role_id','$status','$sms_number')" );
					
			if ( $result ){
				
				return $sms_number;
				
			} else {
				
				return false;
				
			}
		
		} // end if 
		
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
	
	
	public function get_account_array(){
		
		$account = array();
		
		$account['acct_id'] = $this->get_acct_id();
		
		foreach( $this->get_users() as $user ){
			
			$account['users'][ $user->get_user_id() ]['ID'] = $user->get_user_id();
			
			$account['users'][ $user->get_user_id() ]['role_id'] = $user->get_role_id();
			
			$account['users'][ $user->get_user_id() ]['name'] = $user->get_name();
			
			
			
		} // end foreach
		
		return $account;
		
	}
	
	
}