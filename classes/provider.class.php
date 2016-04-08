<?php namespace mcaresms;

require_once 'connect.class.php';

class Provider extends Connect {
	
	protected $id = false;
	
	protected $role_id = false;
	
	protected $responses = array();
	
	public function set_provider_by_id( $id ){
	} // end set_provider_by_id
	
	
}