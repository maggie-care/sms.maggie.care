<?php namespace mcaresms;

require_once 'account.class.php';

class Request {
	
	protected $connect;
	
	private $id = false;
	
	private $acct_id = false;
	
	private $created = false;
	
	private $account = false;
		
	public function __construct( $connect ){
		
		$this->connect = $connect;
		
	} // end __construct
	
	public function get_acct_id(){ return $this->acct_id; }
	
	public function set_sql_request( $sql_response_row ){
		
		$this->request_id = $sql_response_row['request_id'];
		
		$this->acct_id = $sql_response_row['acct_id'];
		
		$this->created = $sql_response_row['created'];
		
		$this->account = new Account( $this->connect );
		
		$this->account->set_account( $this->get_acct_id() );
		
	} // end set_sql_request\
	
	public function set_providers(){
		
		$mysqli = $this->connect->get_connect();
		
		$sql = 'SELECT * FROM maggiecare_open_requests';
		
	} // end set_providers
	
	
}