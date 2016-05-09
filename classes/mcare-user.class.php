<?php

class Mcare_User {
	
	protected $connect;
	
	protected $user_id = false;
	
	protected $acct_id = false;
	
	protected $status = false;
	
	protected $role_id = false;
	
	protected $phone = false;
	
	protected $created = false;
	
	protected $sms_number = false;
	
	
	public function get_user_id(){ return $this->user_id; } 
	public function get_role_id(){ return $this->role_id; } 
	public function get_name(){ return $this->name; } 
	public function get_phone(){ return $this->phone; }
	
	
	public function __construct(){
		
		require_once 'connect.class.php';
		
		$this->connect = new Connect();
		
	} // end __construct
	
	public function the_user( $user_id ){
		
		if ( $row = $this->get_user_by_id( $user_id ) ){
			
			$this->set_user_from_db( $row );
			
			return true;
			
		} else {
			
			return false;
			
		} // end if
		
	} // end the_user
	
	
	public function the_user_acct( $acct_id , $row = array() ){
		
		if ( $row ){
			
			$this->set_user_acct_from_db( $row );
			
			return true;
			
		} else {
			
			if ( $row = $this->get_user_acct( $acct_id ) ){
				
				$this->set_user_acct_from_db( $row );
				
				return true;
				
			} else {
				
				return false;
				
			} // end if
			
		} // end if
		
	} // end the_user_acct
	
	
	public function create_user( $user_args ){
		
		$name = ( isset( $user_args['name'] ) ) ? $user_args['name'] : '';
		
		$phone = ( isset( $user_args['phone'] ) ) ? $this->get_clean_phone( $user_args['phone'] ) : '';
		
		$email = ( isset( $user_args['email'] ) ) ? $user_args['email'] : '';
		
		if ( $phone ){
			
			if ( $row = $this->get_user_by_phone( $phone ) ){
				
				return $row['user_id'];
				
			} else {
				
				if ( $user_id = $this->insert_user( $name , $phone ) ){
					
					return $user_id;
					
				} else {
					
					return false;
					
				} // end if
				
			} // end if
			
		} else {
			
			if ( $user_id = $this->insert_user( $name ) ){
				
				return $user_id;
				
			} else {
				
				return false;
				
			};
			
		} // end if
		
	} // end create_user
	
	
	public function set_user_from_db( $row ){
		
		if ( isset( $row['user_id'] ) ) $this->user_id = $row['user_id'];
		
		if ( isset( $row['user_name'] ) ) $this->name = $row['user_name'];
				
		if ( isset( $row['phone'] ) ) $this->phone = $row['phone'];
			
		if ( isset( $row['created'] ) ) $this->created  = $row['created'];
		
	} // end set_user_db
	
	
	public function set_user_acct_from_db( $db_user ){
		
		if ( isset( $row['acct_id'] ) ) $this->acct_id  = $row['acct_id'];
		
		if ( isset( $row['role_id'] ) ) $this->role_id  = $row['role_id'];
		
		if ( isset( $row['status'] ) ) $this->status  = $row['status'];
		
		if ( isset( $row['sms_number'] ) ) $this->sms_number  = $row['sms_number'];
		
	} // end set_user_db
	
	
	public function get_user_by_phone( $phone ){
		
		$this->get_clean_phone( $phone );
	
		$user = $this->connect->select( "SELECT * FROM maggiecare_users WHERE phone='$phone'" );
		
		if ( $user ){
			
			return $user[0];
			
		} else {
			
			return false;
			
		} // end if
		
	} // end get_user_by_phone
	
	
	public function get_user_by_id( $user_id ){
		
		$row = $this->connect->select( "SELECT * FROM maggiecare_users WHERE user_id='$user_id'" );
		
		if ( $row ){
			
			return $row[0];
			
		} else {
			
			return false;
			
		}// end if
		
	} // end get_user_by_id
	
	
	
	public function get_clean_phone( &$phone ){
		
		$phone = str_replace( array('-',' ','(',')' ) , '' , $phone );
		
		return $phone;
		
	} // end get_clean_phone
	
	
	public function insert_user( $name , $phone = false ){
		
		$this->get_clean_phone( $phone );
		
		$api_key = md5(microtime().rand());
		
		if ( $phone ){
			
			$sql = "INSERT INTO maggiecare_users (user_name,phone,api_key,created) VALUES ('$name','$phone','$api_key',now())";
			
		} else {
			
			$sql = "INSERT INTO maggiecare_users (user_name,api_key,created) VALUES ('$name','$api_key',now())";
			
		} // end if
		
		$result = $this->connect->insert( $sql );
		
		if ( $result !== false ){
			
			return $result;
			
		} else {
			
			return false;
			
		} // end if
		
	} // end
	
	
	public function get_user_settings(){
		
		$settings = array();
		
		if( isset( $_GET['name'] ) ) $settings['name'] = $_GET['name'];
		
		if( isset( $_GET['phone'] ) ) $settings['phone'] = $_GET['phone'];
		
		return $settings;
		
	} // end get_user_settings
	
	
	public function get_user_acct( $acct_id , $user_id = false ){
		
		if ( ! $user_id ) $user_id = $this->get_user_id();
		
		$sql = "SELECT * FROM maggiecare_acct_users WHERE ( acct_id='$acct_id' AND user_id='$user_id')";
		
		if ( $row = $this->connect->select( $sql ) ){
			
			return $row[0];
			
		} else {
			
			return false;
			
		};
		
	} // end get_user_acct
	
	
}