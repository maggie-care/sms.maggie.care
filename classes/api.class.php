<?php
class  Api {
	
	protected $show_errors = false;
	

	
	protected $require_path;
	
	
	public function __construct(){
		
		$this->require_path = $_SERVER["DOCUMENT_ROOT"] . '/';
		
		if ( $this->show_errors ){
			
			$this->report_errors();
			
		} // end if
		
	} // end __construct
	
	
	protected function verify_access( $api_key ){
		
		$config = parse_ini_file('/home/djbleile/appconfig/maggiecare/apiconfig.ini');
		
		if ( $api_key == $config['api_key'] ){
			
			return true;
			
		} else {
			
			return false;
			
		}// end if
		
	} // end verify_access
	
	
	public function report_errors(){
		
		ini_set('display_errors', 1);
		
		ini_set('display_startup_errors', 1);

		error_reporting(E_ALL);
		
	} // end report_errors
	
	
	public function response( $status, $msg , $data = array() ){
		
		$response = array();
		
		$response['status'] = $status;
		
		$response['msg'] = $msg;
		
		$response['data'] = $data;
		
		echo json_encode( $response );
		
	} // end response
	
	
	public function get_response_account_users( $account ){
		
		$users = $account->get_users();
		
		$users_array = array();
		
		foreach ( $users as $user ){
			
			$users_array[] = array(
				'id'     => $user->get_id(),
				'name'   => $user->get_name(),
				'phone'  => $user->get_phone(),
				'role'   => $user->get_role_id(),
				'status' => $user->get_status(),
				'created' => $user->get_created(),
				'updated' => $user->get_updated(),
			);
			
		} // end foreach
		
		return $users_array;
		
	} // end api_users_array
	
}