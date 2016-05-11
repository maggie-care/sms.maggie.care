<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/lib/twilio-php/Services/Twilio.php';

class Twilio {
	
	protected $numbers = array( '3853931713' , '3852223539' , '3852223430' );
	
	protected static $client;
	
	
	public function get_numbers(){ return $this->numbers; }
	
	
	public function connect(){
		
		if ( ! isset( self::$client) ) { 
			
			$config = parse_ini_file('/home/djbleile/appconfig/maggiecare/twilio.ini');
			
			
			self::$client = new \Services_Twilio( $config['AccountSid'], $config['AuthToken'] );
			
		} // end if
		
		// If connection was not successful, handle the error
        if( self::$client === false ) {
			
            // Handle error - notify administrator, log to a file, show an error screen, etc.
            return false;
			
        } // end if
		
		return self::$client;  
		
	} // end __construct
	
	
	public function send_sms( $user_number, $message, $sms_number ){
		
		$connect = $this->connect();
		
		$sms = $connect->account->messages->sendMessage( $sms_number , $user_number , $message );
		
	} // end send_sms
	
	
}