<?php
/**
 * Abstract class for DB connects\
 * @author Danial Bleile
 * @version 0.0.1
 */
class Connect {

	//@var database connection
	protected static $connection;
	
	public function connect(){
		
		if ( ! isset( self::$connection ) ) { 
			
			$config = parse_ini_file('/home/djbleile/appconfig/maggiecare/dbconnect.ini');
			
			self::$connection = new mysqli( 'localhost', $config['username'], $config['password'], $config['dbname'] );
			
		} // end if
		
		// If connection was not successful, handle the error
        if( self::$connection === false ) {
			
            // Handle error - notify administrator, log to a file, show an error screen, etc.
            return false;
			
        } // end if
		
		return self::$connection;  
		
	} // end __construct 
	
	public function query($query) {
		
        // Connect to the database
        $connection = $this->connect();

        // Query the database
        $result = $connection->query( $query );

        return $result;
		
    }
	
	public function insert( $query ){
		
		// Connect to the database
        $connection = $this->connect();
		
		// Query the database
        $result = $connection->query( $query );
		
		if ( $result ){
			
			return $connection->insert_id;
			
		} else {
			
			return false;
			
		} // end if
		
	} // end insert
	
	
	public function select( $query ) {
		
        $rows = array();
		
        $result = $this->query( $query );
		
        if( $result === false ) { 
		
            return false;
			
        }
		
        while ( $row = $result -> fetch_assoc() ) {
			
            $rows[] = $row;
			
        } // end while
		
        return $rows;
		
    } // end select
	
}