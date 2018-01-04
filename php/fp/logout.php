<?php
//log out the user by destroying session variables and returning to home page

//initiate the session (must be the first statement in the document)
session_start();

$_SESSION['custID'] = "";

session_unset(); 

session_destroy();

header( "Location: ../../" ); 

?>