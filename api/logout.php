<?php
session_start();
require "json.php";


//------------Block Description: Log an user in, assigning a new token
//----------Permission Required:
//----Expecting Post Parameters: token, name, password
//-----Optional Post Parameters:
//----------------Return Values: JSON Object(login_result, login_token, permission)
if (isset($_POST["action"]) && $_POST["action"] == "logout")
{
	$_SESSION["uid"] = -1;
	$_SESSION["permission"] = -1;
	json_new();
	$key = "logoutresult";
	$result = "You have been logged out";
	json_add($key,$result);
	json_print();
}
