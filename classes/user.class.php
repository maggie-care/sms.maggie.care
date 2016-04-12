<?php namespace mcaresms;

class User {
	
	private $user_id = false;
	
	private $acct_id = false;
	
	private $role_id = false;
	
	private $name = false;
	
	private $phone = false;
	
	public function get_user_id(){ return $this->user_id; } 
	public function get_role_id(){ return $this->role_id; } 
	public function get_name(){ return $this->name; } 
	public function get_phone(){ return $this->phone; }
	
	public function set_db_user( $row , $profile_type = 'basic' ){
		
		$this->user_id = $row['user_id'];
		
		$this->acct_id = $row['acct_id'];
		
		$this->role_id = $row['role_id'];
		
	} // end set_sql_request\
	
	
	public function set_user( $id = false ){
		
		require_once 'connect.class.php';
		
		$id = ( $id ) ? $id : $this->get_user_id();
		
		if ( isset( $id ) && $id !== false ) {
			
			$connect = new Connect();
		
			$db_user = $connect->select( "SELECT * FROM maggiecare_users WHERE user_id='$id'" );
			
			if( $db_user[0] ){
				
				$this->name = $db_user[0]['user_name'];
				
				$this->phone = $db_user[0]['phone'];
				
				return true;
				
			} else {
				
				return false;
				
			}// end if
			
		} // end if
		
	} // end set_user
	
}