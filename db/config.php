<?php

// =========== TIME AND SESSION SET ================ //

date_default_timezone_set('asia/dhaka');
session_start();

// =========== DATABASE CONNECTION ================ //
class database{
	public function db()
	{
		$db = new mysqli('localhost', 'root', '', 'attstore');
		if($db->connect_errno > 0){
		    die('Unable to connect to database [' . $db->connect_error . ']');
		}	
		return $db;	
	}	
}

$db = database::db();


// =========== TAD REQUIRE FILE ================ //

require 'lib/TADFactory.php';
require 'lib/TAD.php';
require 'lib/TADResponse.php';
require 'lib/Providers/TADSoap.php';
require 'lib/Providers/TADZKLib.php';
require 'lib/Exceptions/ConnectionError.php';
require 'lib/Exceptions/FilterArgumentError.php';
require 'lib/Exceptions/UnrecognizedArgument.php';
require 'lib/Exceptions/UnrecognizedCommand.php';


// =========== CHECK SESSION FOR SERVER UPLOAD ================ //

if(isset($_SESSION['initialize'])) {
	if(time() - $_SESSION['initialize'] > 1800) {
    	session_destroy();
	}	
}

// ===== CUSTOM FUNCTIONS ===== //

include 'functions.php';
