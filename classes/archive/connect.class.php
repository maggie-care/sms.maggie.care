<?php 
/**
 * Abstract class for DB connects\
 * @author Danial Bleile
 * @version 0.0.1
 */
class Connect {
	
	//@var DB name
	private $name = 'maggiecare';
	
	//@var DB host
	private $host = 'localhost';
	
	//@var DB all priv user
	private $mcare_api_user = 'mcare_api';
	
	//@var DB all priv pwd
	private $mcare_api_pwd = 'eL}{-u$?Mo*;k&I}oc';
	
	//@var DB SELECT only user
	private $mcare_api_read_user = 'mcare_api_read';
	
	//@var DB SELECT only pwd
	private $mcare_api_read_pwd = 'PLf2;5aJ)cV2wNy=Nw';
	
	//@var DB Connection
	private $conn = false;
	
	public function get_connect(){ return $this->conn; }
	
	/**
	 * Connect to Database
	 */
	public function connect( $type = false ){
		
		$user = 'mcare_api';//( $type == 'api' )? $this->mcare_api_user : $this->mcare_api_read_user;
		
		$pwd = 'eL}{-u$?Mo*;k&I}oc';//( $type == 'api' )? $this->mcare_api_pwd : $this->mcare_api_read_pwd;
		
		$conn = new \mysqli( $this->host, $user, $pwd, $this->name );
		
		if ( $conn->connect_error ) {
			
			echo 'Error: No connection';
			
            die ();
			
        } else {
			
			$this->conn = $conn;

        } // end if
		
	} // end connect
	
	public function close(){
		
		$this->conn->close();
		
	} // end close
	
}