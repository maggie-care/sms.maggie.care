<?php namespace mcaresms;

class Request {
	
	private $request_id = false;
	
	private $acct_id = false;
	
	private $created = false;
	
	private $providers = array();
	
	public function set_sql_request( $sql_response_row ){
		
		$this->request_id = $sql_response_row['request_id'];
		
		$this->acct_id = $sql_response_row['acct_id'];
		
		$this->created = $sql_response_row['created'];
		
	} // end set_sql_request
	
	
}