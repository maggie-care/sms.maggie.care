<?php

class User {
	
	protected $connect;
	
	protected $user_id = false;
	
	protected $acct_id = false;
	
	protected $status = false;
	
	protected $role_id = false;
	
	protected $phone = false;
	
	protected $sms_number = false;
	
	public function __construct( ){
		
		require_once 'connect.class.php';
		
		$this->connect = new Connect();
		
	} // end __construct
	
	
	public function get_user_id(){ return $this->user_id; } 
	public function get_role_id(){ return $this->role_id; } 
	public function get_name(){ return $this->name; } 
	public function get_phone(){ return $this->phone; }
	
	
	public function set_user_db( $user ){
		
		if ( ! empty( $user['user_id'] ) ) $this->user_id = $user['user_id'];
		
		if ( ! empty( $user['user_name'] ) ) $this->name = $user['user_name'];
				
		if ( ! empty( $user['phone'] ) ) $this->phone = $user['phone'];
			
		if ( ! empty( $user['created'] ) ) $this->created  = $user['created'];
		
		if ( ! empty( $user['acct_id'] ) ) $this->acct_id  = $user['acct_id'];
		
		if ( ! empty( $user['role_id'] ) ) $this->role_id  = $user['role_id'];
		
		if ( ! empty( $user['status'] ) ) $this->status  = $user['status'];
		
		if ( ! empty( $user['sms_number'] ) ) $this->sms_number  = $user['sms_number'];
		
	} // end set_user_db
	
	public function set_user_by_id( $user_id ){
		
		$user = $this->connect->select( "SELECT * FROM maggiecare_users WHERE user_id='$user_id'" );
		
		if ( $user ){
			
			$this->set_user_db( $user[0] );
			
			return true;
			
		} else {
			
			return false;
			
		}// end if
		
	} // end set_user_by_id
	
	
	
	public function create_set_user( $user_settings ){
		
		$phone = ( ! empty( $user_settings['phone'] ) ) ? $user_settings['phone'] : '';
		
		$this->get_clean_phone( $phone );
		
		if ( $user = $this->get_user_by_phone( $phone ) ){
			
			$this->set_user_db( $user );
			
			return true;	
			
		} else {
			
			$name = ( ! empty( $user_settings['name'] ) ) ? $user_settings['name'] : '';
			
			if ( $user_id = $this->insert_user( $name , $phone ) ){
				
				if ( $this->set_user_by_id( $user_id ) ){
					
					return true;
					
				} else {
					
					return false;
					
				} // end if
				
			} else {
				
				return false;
				
			} // end if
			
		} // end if
		
	}  // end create_set_user
	
	
	public function get_user_by_phone( $phone ){
		
		$this->get_clean_phone( $phone );
	
		$user = $this->connect->select( "SELECT * FROM maggiecare_users WHERE phone='$phone'" );
		
		if ( $user ){
			
			return $user[0];
			
		} else {
			
			return false;
			
		} // end if
		
	} // end get_user_by_phone
	
	
	public function get_clean_phone( &$phone ){
		
		$phone = str_replace( array('-',' ','(',')' ) , '' , $phone );
		
		return $phone;
		
	} // end get_clean_phone
	
	
	public function insert_user( $name , $phone ){
		
		$this->get_clean_phone( $phone );
		
		$api_key = md5(microtime().rand());
		
		$sql = "INSERT INTO maggiecare_users (user_name,phone,api_key,created) VALUES ('$name','$phone','$api_key',now())";
		
		$result = $this->connect->insert( $sql );
		
		if ( $result !== false ){
			
			return $result;
			
		} else {
			
			return false;
			
		} // end if
		
	} // end
	
	
	
	
	/*private $user_id = false;
	
	private $acct_id = false;
	
	private $role_id = false;
	
	private $name = false;
	
	private $phone = false;
	
	public function get_user_id(){ return $this->user_id; } 
	public function get_role_id(){ return $this->role_id; } 
	public function get_name(){ return $this->name; } 
	public function get_phone(){ return $this->phone; }
	*/
	
	/*public function set_user_by_phone( $phone , $acct_id = false ){
		
		$phone = $this->get_clean_phone( $phone );
		
		require_once 'connect.class.php';
			
		$connect = new Connect();
	
		$user = $connect->select( "SELECT * FROM maggiecare_users WHERE phone='$phone'" );
		
		if ( $user[0] ){
			
			$this->set_user_from_db( $user[0] );
			
			if ( $acct_id ){
			
				$this->set_user_acct( $acct_id );
				
			} // end if
			
			return true;
			
		} else {
			
			return false;
			
		} // end if
		
	} // end set_user_by_phone
	
	
	public function set_user_from_db( $row ){
		
		$this->name = $row['user_name'];
				
		$this->phone = $row['phone'];
			
		$this->created  = $row['created'];
		
	} // end
	
	public function set_user_acct( $acct_id ){
	
		$user = $this->connect->select( "SELECT * FROM maggiecare_acct_users WHERE acct_id='$acct_id' AND user_id='$this->user_id'" );
		
		var_dump( $user );
		
	}
	
	
	public function get_clean_phone( $phone ){
		
		$phone = str_replace( array('-',' ','(',')' ) , '' , $phone );
		
		return $phone;
		
	} // end get_clean_phone
	
	
	
	/*public function set_db_user( $row , $profile_type = 'basic' ){
		
		$this->user_id = $row['user_id'];
		
		$this->acct_id = $row['acct_id'];
		
		$this->role_id = $row['role_id'];
		
	} // end set_sql_request\
	
	public function set_user_by_id( $id , $acct_id = false , $role_id = false ){
		
		require_once 'connect.class.php';
			
		$connect = new Connect();
	
		$db_user = $connect->select( "SELECT * FROM maggiecare_users WHERE user_id='$id'" );
		
		if( $db_user[0] ){
			
			$this->name = $db_user[0]['user_name'];
			
			$this->phone = $db_user[0]['phone'];
			
			if ( $acct_id ) $this->acct_id = $acct_id;
			
			if ( $role_id ) $this->role_id = $role_id;
			
			return true;
			
		} else {
			
			return false;
			
		}// end if
		
	} // end set_user
	
	public function set_user_by_phone( $phone ){
		
		$phone = $this->get_clean_phone( $phone );
		
		require_once 'connect.class.php';
			
		$connect = new Connect();
	
		$user = $connect->select( "SELECT * FROM maggiecare_users WHERE phone='$phone'" );
		
		if ( $user[0] ){
			
			$this->set_user_from_db( $user[0] );
			
			return true;
			
		} else {
			
			return false;
			
		} // end if
		
	}
	
	public function set_user_acct(
	
	public function set_user_from_db( $row , $full_user = true ){
		
		$this->name = $row['user_name'];
				
		$this->phone = $row['phone'];
			
		$this->created  = $row['created'];
		
	} // end $row
	
	public function get_clean_phone( $phone ){
		
		$phone = str_replace( array('-',' ','(',')' ) , '' , $phone );
		
		return $phone;
		
	} // end get_clean_phone
	
	
	/*public function set_user( $id = false ){
		
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
	
	public function invite_user( $account , $user_settings , $send_invite = true ){
		
		$user_id = $this->insert_user_invite( $account->get_acct_id(), $user_settings['name'] , $user_settings['phone'],$user_settings['role_id'] );
		
		if ( $user_id !== false ){
			
			$this->user_id = $user_id;
			
			$this->name = $user_settings['name'];
			
			$this->phone = $user_settings['phone'];
			
			$this->role_id = $user_settings['role_id'];
			
			if ( $send_invite ){
				
				require_once 'mcare-twilio.class.php';
				
				$twilio = new Mcare_Twilio();
				
				$twilio->send_user_invite( $account , $this );
				
				$msg = array(
					'status' => 1,
					'response' => $this->get_name() . ' has been invited to your caregiver network!',  
				);
				
				echo json_encode( $msg );
				
			} else {
				
				return true;
				
			} // end if
			
		} else {
			
			return false;
			
		} // end if
		
		
	} // end create_user
	
	public function insert_user( $name , $phone ){
		
		require_once 'connect.class.php';
		
		$connect = new Connect();
		
		$api_key = md5(microtime().rand());
		
		$sql = "INSERT INTO maggiecare_users (user_name,phone,api_key,created) VALUES ('$name','$phone','$api_key',now())";
		
		$result = $connect->insert( $sql );
		
		if ( $result !== false ){
			
			return $result;
			
		} else {
			
			return false;
			
		} // end if
		
	} // end
	
	private function insert_user_invite( $acct_id , $name , $phone, $role_id ){
		
		require_once 'connect.class.php';
		
		$connect = new Connect();
		
		$sql = "INSERT INTO maggiecare_user_invite (acct_id,role_id,name,phone,created) VALUES ('$acct_id','$role_id','$name','$phone',now())";
		
		$result = $connect->insert( $sql );
		
		if ( $result !== false ){
			
			return $result;
			
		} else {
			
			return false;
			
		} // end if
		
	} // end
	
	public function user_get_settings(){
		
		$user_settings = array();
		
		if ( isset( $_GET['name'] ) && ( isset( $_GET['phone'] ) || isset( $_GET['email'] ) ) ){
			
			$user_settings['name'] = $_GET['name'];
				
			$user_settings['phone'] = ( isset( $_GET['phone'] ) ) ? $_GET['phone'] : '';
			
			$user_settings['email'] = ( isset( $_GET['email'] ) ) ? $_GET['email'] : '';
			
			$user_settings['role_id'] = ( isset( $_GET['role_id'] ) ) ? $_GET['role_id'] : 4;
			
		} // end if
		
		return $user_settings;
		
	} // end user_get_settings
	
	public function get_user_invite( $phone ){
		
		require_once 'connect.class.php';
		
		$connect = new Connect();
		
		$sql = "SELECT * FROM maggiecare_user_invite WHERE phone='$phone'";
		
		$invite = $connect->select( $sql );
		
		if ( $invite ){
			
			return $invite[0];
			
		} else {
			
			return false;
			
		} // end if
		
	} // end get_user_invite*/
	
}