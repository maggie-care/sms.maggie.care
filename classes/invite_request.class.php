<?php

require_once 'request.class.php';

class Invite_Request extends Request {
	
	protected $type = 'invite';
	
	public function send( $phone , $sms_number , $settings ){
		
		$msg = $settings['owner'] . ' would like to invite you to his/her caregiver network. Reply "YES" to join or "NO" to decline. Learn more at http://maggie.care';
		
		$this->twilio->send_sms( $phone, $msg, $sms_number );
		
	} // end send;
	
	
	public function do_request( $response ){
		
		$msg = strtolower( $response->get_msg() );
		
		if ( strpos( $msg , 'yes' ) !== false ){
			
			return $this->accepted( $response );
			
		} else if (strpos( $msg , 'no' ) !== false ){
			
			return $this->declined( $response );
			
		} else {
			
			return 'Sorry, I didn\'t catch that. Please respond "YES" or "NO"';
			
		} // end if
		
	} // end do_request
	
	
	public function accepted( $response ){
		
		require_once 'account.class.php';
		
		$account = new Account();
		
		$account->set_account_by_id( $response->get_acct_id() );
		
		$account->update_acct_user_status( $response->get_user_id() , 'accepted' );
		
		$this->delete_open_request( $this->get_request_id() ,  $response->get_user_id() );
		
		$this->update_request_status('complete');
		
		if ( $user = $account->get_user( $response->get_user_id() ) ){
			
			$note = 'PEOPLE LOVE YOU! ' . $user->get_name() . ' has accepted your invite to join your caregiver network. Update caregivers in settings.';
			
			$note_added = $account->insert_notification( $note, 'pending', $this->get_request_id() );
			
		} // end if
		
		$owner = $account->get_acct_owner();
		
		$name = explode( ' ' , $owner->get_name() );
	
		$msg = 'Way to show some love. MaggieCare has notified ' . $name[0] . ' that you\'re a part of his/her network. You\'ll be notified if ' . $name[0] . ' needs help.';
		
		//$this->twilio->send_sms( $response->get_from(), $msg, $response->get_to() );
		
		return $msg;
		
	}
	
	public function declined( $response ){
		
		require_once 'account.class.php';
		
		$account = new Account();
		
		$account->set_account_by_id( $response->get_acct_id() );
		
		
		
		$this->delete_open_request( $this->get_request_id() ,  $response->get_user_id() );
		
		$this->update_request_status('complete');
		
		$owner = $account->get_acct_owner();
		
		$account->remove_acct_user( $response->get_user_id() );
		
		if ( $user = $account->get_user( $response->get_user_id() ) ){
			
			$note = 'MaggieCare notice: ' . $user->get_name() . ' declined to join ' . $owner->get_name() . '\'s caregiver network.';
			
			$note_added = $account->insert_notification( $note, 'pending', 2 , $this->get_request_id() );
			
		} // end if
		
		$name = explode( ' ' , $owner->get_name() );
	
		$msg = 'OK. MaggieCare won\'t add you to ' . $name[0] . '\'s network.';
		
		return $msg;
		
	}
	
	
	
	
	
}
?>