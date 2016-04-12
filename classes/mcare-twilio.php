<?php namespace mcaresms;

require_once dirname( dirname( __FILE__ ) ) . '/lib/twilio-php/Services/Twilio.php';

class Mcare_Twilio {
	
	protected $number = '385-393-1713';
	
	protected static $client;
	
	public function get_number(){ return $this->number; }
	
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
	
	public function send_sms( $number, $message, $from = false ){
		
		$from = ( $from ) ? $from : $this->get_number();
		
		$connect = $this->connect();
		
		$sms = $connect->account->messages->sendMessage( $from , $number , $message );
		
		echo 'Message Set<br>';
		
	} // end send_sms
	
	
}