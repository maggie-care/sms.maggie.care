<?php
class  Api {
	
	public function verify_access( $api_key ){
		
		$config = parse_ini_file('/home/djbleile/appconfig/maggiecare/apiconfig.ini');
		
		if ( $api_key == $config['api_key'] ){
			
			return true;
			
		} else {
			
			return false;
			
		}// end if
		
	} // end verify_access
	
}