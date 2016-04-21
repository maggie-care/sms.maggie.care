<?php

class Response {
	
	protected $to;
	
	protected $from;
	
	protected $msg;
	
	protected $request_id;
	
	protected $user_id;
	
	protected $acct_id;
	
	protected $connect;
	
	
	public function __construct(){
		
		require_once 'connect.class.php';
		
		$this->connect = new Connect();
		
	} // end __construct
	
	
	public function get_to(){ return $this->to; }
	public function get_from(){ return $this->from; }
	public function get_msg(){ return $this->msg; }
	public function get_request_id(){ return $this->request_id;}
	public function get_user_id(){ return $this->user_id; }
	public function get_acct_id(){ return $this->acct_id; }
	
	
	public function set_text_response(){
		
		/*$this->msg = $_GET['Body'];
		
		$this->from = substr( str_replace( array(' ','+' ), '', $_GET['From'] )  , 1);
		
		$this->to = substr( str_replace( array(' ','+' ), '', $_GET['To'] ) , 1 );*/
		
		$this->msg = $_POST['Body'];
		
		$this->from = substr( str_replace( array(' ','+' ), '', $_POST['From'] )  , 1);
		
		$this->to = substr( str_replace( array(' ','+' ), '', $_POST['To'] ) , 1 );
		
		if ( $request_data = $this->get_open_request( $this->get_to() , $this->get_from() ) ){
			
			$this->user_id = $request_data['user_id'];
			
			$this->acct_id = $request_data['acct_id'];
			
			$this->request_id = $request_data['request_id'];
			
			return true;
			
		} else {
			
			return false;
			
		}
		
	}
	
	
	public function get_open_request( $to = false , $from = false ){
		
		if ( ! $to ) $to = $this->get_to();
		
		if ( ! $from ) $from = $this->get_from();
		
		$sql = "SELECT * From maggiecare_open_requests WHERE ( user_number='$from' AND sms_number='$to' )";
		
		if ( $result = $this->connect->select( $sql ) ){
			
			return $result[0];
			
		} else {
			
			return false;
			
		};
		
	} // end get_open_response
	
	
	
	
	/*private $id;
	
	private $request_id;
	
	private $user_id;
	
	private $response_type_id;
	
	private $response_text;
	
	private $created;
	
	public function get_id(){ return $this->id; } 
	public function get_user_id(){ return $this->user_id; } 
	public function get_response_type_id(){ return $this->response_type_id; }
	
	public function set_from_db( $row ){
		
		$this->id = $row['response_id'];
		
		$this->request_id = $row['request_id'];
		
		$this->user_id = $row['user_id'];
		
		$this->response_type_id = $row['response_type_id'];
		
		$this->response_text =  $row['response_text'];
		
		$this->created =  $row['created'];
		
	} // end set_sql_request\
	
	
	public function is_accepted(){
		
		if ( $this->get_response_type_id() == 3 ){
			
			return true;
			
		} else {
			
			return false;
			
		} // end if
		
	}*/
	
}