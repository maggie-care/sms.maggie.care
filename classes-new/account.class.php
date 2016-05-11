<?php

class Account {
	
	protected $connect;
	
	public $ID;
	
	public $access_key;
	
	public $created;
	
	public $users;
	
	
	public function __construct( $connect ){
		
		$this->connect = $connect;
	 	
	} // end __construct
	
	
	public function add_user( $args , $acct_id ){
		
		$user = new User( $this->connect );
		
		if ( $user_id = $user->create_user( $args ) ){
			
			if ( $this->add_user_to_account( $user_id, $args ) ){
				
				return true;
				
			} else {
				
				return false;
				
			};
			
		} else {
			
			return false;
			
		}
		
		//$this->users[ 
		
	} // end add_user
	
	
	public function add_user_to_account( $user_id , $user_args , $acct_id = false ){
		
		$status = ( ! empty( $user_args['status'] ) )? $user_args['status'] : 'pending';

		$role_id = ( ! empty( $user_args['role_id'] ) )? $user_args['role_id'] : 4;
		
		$acct_id = ( $acct_id ) ? $acct_id : $this->get_id();
		
		$sms_number = $this->get_next_sms( $user_id );
		
		if ( ! array_key_exists( $user_id , $this->get_users() ) ){
		
			$sql = "INSERT INTO maggiecare_acct_users (user_id,acct_id,role_id,status,sms_number) 
					VALUES ('$user_id','$acct_id','$role_id','$status','$sms_number')";
					
			if ( $reslult = $this->connect->insert( $sql ) ){
				
				return true;
				
			} else {
				
				return false;
				
			}// end if
		
		} else {
			
			return false;
			
		}// end if
		
		
	} // end add_user_to_account
	
	
	private function get_next_sms( $user_id ){
		
		require_once 'twilio.class.php';
		
		$twilio = new Twilio();
		
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
	
	
	public function get_id(){
		
		if ( ! isset( $this->id ) ) {
			
			return false;
			
		} else {
			
			return $this->id;
			
		} // end if
		
	} // end get_id
	
	
	public function get_access_key( $id = false ){
		
		if ( $id && ( ! isset( $this->id ) || ( isset( $this->id ) && $this->id != $id ) ) ){
			
			$this->set_account( $id );
			
		} // end if
		
		if ( ! isset( $this->access_key ) ) {
			
			return false;
			
		} else {
			
			return $this->access_key;
			
		} // end if
		
	} // end get_id
	
	
	public function get_users( $id = false ){
		
		if ( $id && ( ! isset( $this->id ) || ( isset( $this->id ) && $this->id != $id ) ) ){
			
			$this->set_account( $id );
			
		} // end if
		
		if ( ! isset( $this->users ) ) {
			
			return false;
			
		} else {
			
			return $this->users;
			
		} // end if
		
	} // end get_id

	
	
	
	
	public function set_account( $id = false , $do_create = false  ){
		
		if ( $id ) {
		
			if ( $this->set_account_data( $id ) ){
				
				$this->set_account_users( $id );
			
				return true;
			
			} else {
			
				return false;
			
			} // end if
			
		} else if ( $do_create ){
			
			// create the account
			
		} else {
			
			return false;
			
		}// end if
	
	} // end set_account
	
	
	public function set_account_data( $id ){
		
		if ( $row = $this->connect->select( "SELECT * FROM maggiecare_acct WHERE id='$id'" ) ){
			
			$this->id = $row[0]['id'];
		
			$this->access_key = $row[0]['api_key'];
		
			$this->created = $row[0]['open'];
			
			return true;
			
		} else {
			
			return false;
			
		} // end if
		
	} // end set_account_data
	
	
	public function set_account_users( $acct_id = false ){
		
		$this->check_set_id( $acct_id );
		
		require_once 'user.class.php';
		
		$acct_users = $this->get_account_user_ids( $acct_id );
		
		foreach( $acct_users as $user_id ){
			
			$user = new User( $this->connect );
			
			if ( $user->set_user( $user_id , $acct_id ) ){
				
				$this->users[ $user_id ] = $user;
				
			} // end if
			
		} // end foreach
		
	} // end set_account_users
	
	
	
	public function get_account_user_ids( $acct_id = false ){
		
		$this->check_set_id( $acct_id );
		
		$user_ids = array();
		
		if ( $rows = $this->connect->select( "SELECT * FROM maggiecare_acct_users WHERE acct_id='$acct_id'" ) ){
			
			foreach( $rows as $row ){
				
				$user_ids[] = $row['user_id'];
				
			} // end foreach
			
		}// end if
		
		return $user_ids;
		
	} // end get_account_user_ids();
	
	
	public function verify( $access_key ){
		
		if ( $this->get_access_key() && $this->get_access_key() == $access_key ){
			
			return true;
			
		} else {
			
			return false;
			
		}// end if
		
	} // end verify
	
	
	public function check_set_id( &$acct_id ){
		
		if ( $acct_id !== false ){
			
			if ( isset( $this->id ) ){
				
				if ( $acct_id != $this->id ) {
					
					$this->set_account( $acct_id );
					
				} // end if
				
			} else {
				
				$this->set_account( $acct_id );
				
			} // end if
			
		} // end if
		
		$acct_id = $this->get_id();
		
	} // check_set
	
	
	/*public function create_user( $user_args ){
		
		$args = array();
		
		$args['role_id'] = ( isset( $user_args['role_id'] ) )? $user_args['role_id'] : 4;
		
		$args['name'] = ( isset( $user_args['name'] ) )? $user_args['name'] : '';
		
		$args['phone'] = ( isset( $user_args['phone'] ) )? $user_args['phone'] : '';
		
		$args['email'] = ( isset( $user_args['email'] ) )? $user_args['email'] : '';
		
		$args['status'] = ( isset( $user_args['status'] ) )? $user_args['status'] : 'pending';
		
		require_once 'user.class.php';
		
		$user = new User();
		
		$user->create_user( $this->get_id() , $args );
		
		return $user_id;
		
	} // end create user*/
	
	
}