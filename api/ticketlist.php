<?php
session_start();
require "dbconnection.php";
require "json.php";

if (isset($_POST["action"]) && $_POST["action"] == "getticketlist" && isset($_POST["ticket_num"]))//for checking
{
	$connection = database_connect();
	$sql = sprintf("Select * From sec_price where remain_seats > %d", $_POST["ticket_num"]);
	$result = database_query($connection, $sql);
	json_new();
	json_addrows($result);
	json_printarray();
	database_close($connection);
}

if (isset($_POST["action"]) && $_POST["action"] == "searchbyticket" && isset($_POST["ticket_num"])&& isset($_POST["section"]))//for checking
{
	$connection = database_connect();
	$sql = sprintf("Select * From sec_price where remain_seats > %d AND sec_id = '%s'", $_POST["ticket_num"], $_POST["section"]);
	$result = database_query($connection, $sql);
	json_new();
	json_addrows($result);
	json_printarray();
	database_close($connection);
}