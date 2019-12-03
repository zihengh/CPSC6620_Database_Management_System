<?php
session_start();

require "dbconnection.php";
require "json.php";


//------------Block Description: Log an user in, assigning a new token
//----------Permission Required:
//----Expecting Post Parameters: token, name, password
//-----Optional Post Parameters:
//----------------Return Values: JSON Object(login_result, login_token, permission)
if (isset($_POST["action"]) && $_POST["action"] == "login" && isset($_POST["name"]) && isset($_POST["password"]))
{
	$connection = database_connect();
	$sql = sprintf("Select Count(*) As Result, uid, name, pwd, permission From user Where name = '%s' And pwd = '%s'", $_POST["name"], md5($_POST['password']));
	$result = database_query($connection, $sql);
	//json_new();
	if ($result[0]["Result"] != "0")
	{
		//json_add('login_result', true);
		//json_add("login_token", token_new($result[0]["UserID"]));
		//json_add("permission", $result[0]["Permission"]);
		$_SESSION["name"] = $result[0]["name"];
		$sql = sprintf("Select * From user Where name = '%s'", $_POST["name"]);
		$result = database_query($connection, $sql);
		json_new();
		json_add('login_result', true);
		$_SESSION["uid"] = $result[0]["uid"];
		$_SESSION["permission"] = $result[0]["permission"];
		//$_SESSION["name"] = $_POST["name"];
	}
	else
	{
		json_add('login_result', false);
		$_SESSION["uid"] = -1;
		$_SESSION["permission"] = -1;
	}

	json_printobject();
	database_close($connection);
}
