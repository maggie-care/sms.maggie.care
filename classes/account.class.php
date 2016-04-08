<?php namespace mcaresms;

class Account {
	
	protected $connect;
	
	protected $id;
	
	protected $api_key;
	
	protected $users = array();
	
	protected $settings = array();
	
	public function __construct( $connect ){
		
		$this->connect = $connect;
		
	} // end __construct
	
	public function set_account( $acct_id ){
		
		$this->id = $acct_id;
		
		var_dump( $acct_id );
		
		$mysqli = $this->connect->get_connect();
		
		$sql = "SELECT * FROM maggiecare_acct WHERE id='$acct_id'";
		
		//$sql = "SELECT * FROM maggiecare_acct LEFT JOIN maggiecare_acct_users ON maggiecare_acct.id = maggiecare_acct_users.acct_id AND maggiecare_acct.id='$acct_id'";
		
		//$sql = "SELECT *, GROUP_CONCAT(maggiecare_acct_users.id) as 'users' FROM maggiecare_acct LEFT JOIN maggiecare_acct_users ON maggiecare_acct.id = maggiecare_acct_users.acct_id GROUP BY maggiecare_acct.id";
		
		$query = $mysqli->query( $sql );
		
		if ( $row = $query->fetch_assoc() ){
		
			var_dump( $row );
		
		} // end if
		
		/*if ( $row = $query->fetch_assoc() ){
			
			$this->api_key = $row['api_key'];
			
			$this->set_users();
			
		} // end if*/
		
	} // end set_account
	
	public function set_users( $acct_id ){
		
		$mysqli = $this->connect->get_connect();
		
		$sql = "SELECT * FROM maggiecare_acct_users WHERE id='$acct_id'";
		
		
	} // end set_users
	
	
}