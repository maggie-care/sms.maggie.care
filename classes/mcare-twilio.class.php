<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/lib/twilio-php/Services/Twilio.php';

class Mcare_Twilio {
	
	protected $numbers = array( '385-393-1713' , '385-222-3539' , '385-222-3430' );
	
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
		
		$from = ( $from ) ? $from : $this->get_number();
		
		$connect = $this->connect();
		
		//var_dump( $user_number . ', ' .  $sms_number . ', ' . $message ); 
		
		$sms = $connect->account->messages->sendMessage( $from , $number , $message );
		
	} // end send_sms
	
	
}