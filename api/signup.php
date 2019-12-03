<?php

session_start();
require "dbconnection.php";
require "json.php";
require "tokencontrol.php";

//------------Block Description: Create a new account, validate username and password, and log into it
//----------Permission Required:
//----Expecting Post Parameters: token, name, password
//-----Optional Post Parameters:
//----------------Return Values: JSON Object(signup_result, login_token)
if (isset($_POST["action"]) && $_POST["action"] == "signup" && isset($_POST["name"]) && isset($_POST["password"]))
{
	if (strlen($_POST["password"]) < 5)
	{
		json_new();
		json_add('signup_result', "Password should have at least 5 characters.");
		json_print();
		exit();
	}
	if (preg_match("/[A-Z]/", $_POST["password"]) == 0)
	{
		json_new();
		json_add('signup_result', "Password should have at least one uppercase letter.");
		json_print();
		exit();
	}
	if (preg_match("/^[A-Za-z0-9]+$/", $_POST["password"]) == 0)
	{
		json_new();
		json_add('signup_result', "Password can only contain uppercase letters, lowercase letters and numbers");
		json_print();
		exit();
	}
	$connection = database_connect();
	$sql = sprintf("Select Count(*) As Number From user Where name='%s'", $_POST["name"]);
	$result = database_query($connection, $sql);
	$number = $result[0]["Number"];
	if ($number != "0")
	{
		json_new();
		json_add('signup_result', "User name has been used, please try another name.");
		json_print();
		exit();
	}

	$sql = "Select UserID As Number From User Order By UserID Desc Limit 1";
	$result = database_query($connection, $sql);
	$number = (int)substr($result[0]["Number"], 1) + 1;
	$sql = sprintf("Insert Into user(name, email_addr, pwd) 
							VALUES ('%s', '%s', '%s')", $_POST["name"],
							$_POST["email"], md5($_POST["password"]));
	//echo $sql;
	database_query($connection, $sql);
	$sql = sprintf("Select uid, permission From user Where name = '%s'", $_POST['name']);
	$result = database_query($connection, $sql);
	json_new();
	json_add('signup_result', true);
	$_SESSION["uid"] = $result[0]["uid"];
	$_SESSION["permission"] = $result[0]["permission"];
	database_close($connection);
	json_new();
	json_add('signup_result', true);
	//json_add("login_token", token_new("U" . str_pad($number, 7, "0", STR_PAD_LEFT)));
	json_printobject();
}