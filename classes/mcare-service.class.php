<?php

class Mcare_Service {
	
	public function respond( $status , $msg , $settings = array() ){
		
		$response = array(
			'status'   => $status,
			'message'  => $msg,
			'settings' => $settings,
		);
		
		echo json_encode( $response );
		
	} // end respond
	
	public function verify_access( $access_key = false ){
		
		if ( ! $access_key ){
			
			 if ( isset( $_GET['access_key'] ) ) { 
			 	
				$access_key = $_GET['access_key'];
				
			 } else if ( isset( $_POST['access_key'] ) )  {
				 
				$access_key = $_POST['access_key'];
				 
			 } else {
				 
				 return false;
				 
			 }// end if
			 
		} // end if
		
		$config = parse_ini_file('/home/djbleile/appconfig/maggiecare/apiconfig.ini');
		
		if ( $access_key == $config['api_key'] ){
			
			return true;
			
		} else {
			
			return false;
			
		} // end if
		
	} // end verify_access
	
}