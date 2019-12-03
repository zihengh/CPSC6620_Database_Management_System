<?php

session_start();
require "dbconnection.php";
require "json.php";

if (isset($_POST["action"]) && $_POST["action"] == "check")
{
	if(isset($_SESSION["uid"])&&($_SESSION["uid"]>=0))
	{
		$connection = database_connect();
		$sql = sprintf("Select * From user Where uid = %d", $_SESSION["uid"]);
		$result = database_query($connection, $sql);
		json_new();
		json_add('login_result', true);
		json_add('name', $result[0]["name"]);
		json_add('email', $result[0]["email_addr"]);
		json_printobject();
		database_close($connection);
	}
	else
	{
		json_add('login_result', false);
		$_SESSION["uid"] = -1;
		$_SESSION["permission"] = -1;
		json_printobject();
	}
}



if (isset($_POST["action"]) && $_POST["action"] == "save" && isset($_POST["name"]) && isset($_POST["password"]) && isset($_POST["email"]))
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
	$sql = sprintf("Select name From user Where uid=%d", $_SESSION["uid"]);
	$name = database_query($connection, $sql);
	$sql = sprintf("Select Count(*) As Number From user Where name='%s'", $_POST["name"]);
	$result = database_query($connection, $sql);
	$number = $result[0]["Number"];
	if ($number != "0" && $_POST["name"]!=$name[0]["name"])
	{
		json_new();
		json_add('signup_result', "User name has been used, please try another name.");
		json_print();
		exit();
	}

	
	$sql = sprintf("update user set name='%s', email_addr='%s', pwd='%s'
							WHERE uid=%d", $_POST["name"],
							$_POST["email"], md5($_POST["password"]), $_SESSION["uid"]);
	//echo $sql;
	database_query($connection, $sql);
	
	json_new();
	json_add('signup_result', true);
	
	database_close($connection);
	
	//json_add("login_token", token_new("U" . str_pad($number, 7, "0", STR_PAD_LEFT)));
	json_printobject();
}