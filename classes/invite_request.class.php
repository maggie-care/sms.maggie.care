<?php

require_once 'request.class.php';

class Invite_Request extends Request {
	
	protected $type = 'invite';
	
	public function send( $phone ,$sms_number , $settings ){
		
		$msg = $settings['owner'] . ' would like to invite you to their caregiver network. Reply "YES" to join or "NO" to decline. Learn more at https://maggie.care';
		
		
	} // end send;
	
	
	
}
?>