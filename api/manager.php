<?php

require "dbconnection.php";
require "json.php";
session_start();

if (isset($_POST["action"]) && $_POST["action"] == "getdatabaseinfo")//for checking
{

	$connection = database_connect();
	$sql = "Select * From sec_price";
	$result = database_query($connection, $sql);
	
	json_new();
	json_addrows($result);
	
	json_printarray();
	database_close($connection);
}

if (isset($_POST["action"]) && $_POST["action"] == "gettableinfo")//for checking
{

	$connection = database_connect();
	$sql = "SHOW TABLES";
	$result = database_query($connection, $sql);
	
	json_new();
	json_addrows($result);
	
	json_printarray();
	database_close($connection);
}

if (isset($_POST["action"]) && $_POST["action"] == "Save" && isset($_POST["updateinfo"]))//for checking
{
	
	if(isset($_SESSION["permission"])&&$_SESSION["permission"]>0)
	{
		$connection = database_connect();

		$info = explode(';',$_POST["updateinfo"]);
		//echo count($info);
		for($index=0;$index<count($info)-1;++$index)
		{
			$subinfo = explode(':',$info[$index]);
			
			$sql = sprintf("update sec_price set price = %f where sec_id = '%s'", $subinfo[1], $subinfo[0]);
			database_query($connection, $sql);
			$sql = sprintf("update seats set price = %f where district = '%s'", $subinfo[1], $subinfo[0]);
			database_query($connection, $sql);
		}
	
		database_close($connection);
		json_new();
		json_add("permission", 1);
		json_printobject();
	}
	else
	{
		json_new();
		json_add("permission",0);
		json_printobject();
	}
}

