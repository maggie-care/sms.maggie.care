<?php

class User {
	
	protected $connect;
	
	protected $id;
		
	protected $name;
				
	protected $phone;
			
	protected $created;
	
	protected $acct_id;
		
	protected $role_id;
		
	protected $status;
		
	protected $sms_number;
	
	protected $updated;
	
	
	public function __construct( $connect ){
		
		$this->connect = $connect;
		
	}
	
	public function check_set( $id ){
		
		if ( $id !== false ){
			
			if ( isset( $this->id ) ){
				
				if ( $id != $this->id ) {
					
					$this->set_user( $id );
					
				} // end if
				
			} else {
				
				$this->set_user( $id );
				
			} // end if
			
		} // end if
		
	} // check_set
	
	
	
	public function get_id( $id = false ){
		
		$this->check_set( $id );
		
		if ( ! isset( $this->id ) ) {
		
			return false;
		
		} else {
		
			return $this->id;
		
		} // end if
		
	} // end get_id
	
	
	public function get_name( $id = false ){
		
		$this->check_set( $id );
		
		if ( isset( $this->name ) ) {
			
			return $this->name;
		
		} else {
		
			return false;
		
		} // end if
		
	} // end get_id
	
	
	public function get_phone( $id = false ){
		
		$this->check_set( $id );
		
		if ( isset( $this->phone ) ) {
			
			return $this->phone;
		
		} else {
		
			return false;
		
		} // end if
		
	} // end get_id
	
	public function get_role_id( $id = false ){
		
		$this->check_set( $id );
		
		if ( isset( $this->role_id ) ) {
			
			return $this->role_id;
		
		} else {
		
			return false;
		
		} // end if
		
	} // end get_id
	
	
	public function get_status( $id = false ){
		
		$this->check_set( $id );
		
		if ( isset( $this->status ) ) {
			
			return $this->status;
		
		} else {
		
			return false;
		
		} // end if
		
	} // end get_id
	
	
	public function get_created( $user_id = false ){
		
		$this->check_set_id( $user_id );
		
		if ( isset( $this->created ) ) {
			
			return $this->created;
		
		} else {
		
			return false;
		
		} // end if
		
	} // end get_id
	
	
	public function get_updated( $user_id = false ){
		
		$this->check_set_id( $user_id );
		
		if ( isset( $this->updated ) ) {
			
			return $this->updated;
		
		} else {
		
			return false;
		
		} // end if
		
	} // end get_id
	
	
	public function set_user( $user_id , $acct_id = false , $do_create = false ){
		
		if ( $do_create ){
			
			// Create User
			
		} else {
			
			if ( $this->set_user_data( $user_id ) ){;
			
				if ( $acct_id ){
					
					if ( $this->set_acct_data( $user_id , $acct_id ) ){
					
						return true;
						
					} else {
						
						return false;
						
					}// end if
					
				} // end if
				
				return true;
			
			} else {
				
				return false;
				
			} // end if
			
		} // end if
		
	} // end set_user
	
	
	public function set_user_data( $user_id ){
		
		if ( $row = $this->connect->select( "SELECT * FROM maggiecare_users WHERE user_id='$user_id'" ) ){
			
			$this->id = ( isset( $row[0]['user_id'] ) ) ? $row[0]['user_id'] : '';
		
			$this->name = ( isset( $row[0]['user_name'] ) ) ?$row[0]['user_name'] : '';
				
			$this->phone = ( isset( $row[0]['phone'] ) ) ? $row[0]['phone'] : '';
			
			$this->created = ( isset( $row[0]['created'] ) ) ? $row[0]['created'] : '';
			
			$this->updated = ( isset( $row[0]['updated'] ) ) ?  $row[0]['updated'] : '';  
			
			return true;
			
		} else {
			
			return false;
			
		} // end if
		
	} // end set_user_data
	
	
	public function set_acct_data( $user_id , $acct_id ){
		
		if ( $rows = $this->connect->select( "SELECT * FROM maggiecare_acct_users WHERE acct_id='$acct_id' AND user_id='$user_id'" ) ){
				
			if ( isset( $rows[0]['acct_id'] ) ) $this->acct_id  = $rows[0]['acct_id'];
			
			if ( isset( $rows[0]['role_id'] ) ) $this->role_id  = $rows[0]['role_id'];
			
			if ( isset( $rows[0]['status'] ) ) $this->status  = $rows[0]['status'];
			
			if ( isset( $rows[0]['sms_number'] ) ) $this->sms_number  = $rows[0]['sms_number'];
			
			return true;
			
		} // end if
		
		return false;
		
	} // end set_acct_data
	
	
	public function create_user( $user_args ){
		
		$name = ( ! empty( $user_args['name'] ) )? $user_args['name'] : '';
		
		$phone = ( ! empty( $user_args['phone'] ) )? $user_args['phone'] : '';
		
		$email = ( ! empty( $user_args['email'] ) )? $user_args['email'] : '';
		
		$this->get_clean_phone( $phone );
		
		if ( $user_id = $this->get_id_from_phone( $phone ) ){
			
			return $user_id;
			
		} else {
			
			$api_key = md5(microtime().rand());
			
			$sql = "INSERT INTO maggiecare_users (user_name,phone,api_key,created,updated) VALUES ('$name','$phone','$api_key',now(),now())";
			
			$user_id = $this->connect->insert( $sql );
			
			return $user_id;
			
		} // end if
		
	} // end create_user
	
	
	public function check_set_id( &$user_id ){
		
		if ( $user_id !== false ){
			
			if ( isset( $this->id ) ){
				
				if ( $user_id != $this->id ) {
					
					$this->set_user( $id );
					
				} // end if
				
			} else {
				
				$this->set_user( $id );
				
			} // end if
			
		} // end if
		
		$user_id = $this->get_id();
		
	} // check_set
	
	public function get_id_from_phone( $phone ){
		
		if ( $phone && $row = $this->connect->select( "SELECT * FROM maggiecare_users WHERE phone='$phone'" ) ){
			
			return $row[0]['user_id'];
			
		} else {
			
			return false;
			
		}// end if
		
	} // end get_id_from_phone
	
	
	public function get_clean_phone( &$phone ){
		
		$phone = str_replace( array('-',' ','(',')' ) , '' , $phone );
		
		return $phone;
		
	} // end get_clean_phone
	
	
	
}