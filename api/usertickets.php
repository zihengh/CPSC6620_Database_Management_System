<?php
session_start();

require "dbconnection.php";
require "json.php";

if (isset($_POST["action"]) && $_POST["action"] == "getUserTickets")//for checking
{
	$connection = database_connect();
	//$sql = sprintf("Select * From seats_user where uid = %d", $_SESSION["uid"]);
	$sql = sprintf("Select * From seats_user where uid = %d", $_SESSION["uid"]);
	$result = database_query($connection, $sql);
	//echo $result;
	json_new();
	json_addrows($result);
	json_printarray();
	database_close($connection);
}

if (isset($_POST["action"]) && $_POST["action"] == "refund" && isset($_POST["sid"]))//for checking
{
	$connection = database_connect();
	$sql = sprintf("delete From seats_user where sid = '%s'", $_POST["sid"]);
	database_query($connection, $sql);
	$sql = sprintf("update seats set status = 'A' where sid = '%s'", $_POST["sid"]);
	database_query($connection, $sql);
	$secendpos = (strpos($_POST["sid"],"-"));
	$section_id = substr($_POST["sid"],0,$secendpos);
	//echo $section_id;
	$sql = sprintf("Select row From seats where district = '%s' AND status = 'A' order by sid asc limit 1", $section_id);
	$row_result = database_query($connection, $sql);
	$sql = sprintf("update sec_price set start_row = '%s', remain_seats = remain_seats+1 where sec_id = '%s'", $row_result[0]["row"], $section_id);
	database_query($connection, $sql);
	
}