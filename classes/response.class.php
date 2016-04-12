<?php namespace mcaresms;

class Response {
	
	private $id;
	
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
		
	}
	
}