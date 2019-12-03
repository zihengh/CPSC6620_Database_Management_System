<?php
session_start();
require "dbconnection.php";
require "json.php";


if (isset($_POST["action"]) && $_POST["action"] == "buytickets" && isset($_POST["ticket_num"])&& isset($_POST["sec_id"]))//for checking
{
	if (isset($_SESSION['uid'])&&($_SESSION['uid']>0))
	{
		$connection = database_connect();
		$sql = sprintf("Select * From seats where district = '%s' AND status = 'A' order by sid asc limit %d", $_POST["sec_id"], $_POST["ticket_num"]);
		//$sql = sprintf("Select * From seats where district = '%s' AND status = 'A' order by sid asc", $_POST["sec_id"]);
		$result = database_query($connection, $sql);

		json_new();
		json_addrows($result);
		json_printarray();
		//database_close($connection);
		$arraylen = count($result);
		if($arraylen<=0)
		{
		
		}
		else
		{
			while($arraylen>0) 
			{
				$row = $result[$arraylen-1];
				$sql = sprintf("update seats set status = 'O' where sid = '%s'", $row["sid"]);
				database_query($connection, $sql);
				$sql = sprintf("Insert Into seats_user(sid, uid, price) 
							VALUES ('%s', %d, %f)", $row["sid"], $_SESSION["uid"],
							 $row["price"]);
				database_query($connection, $sql);
				$arraylen = $arraylen-1;
			}

			$sql = sprintf("Select row From seats where district = '%s' AND status = 'A' order by sid asc limit 1", $_POST["sec_id"]);
			$row_result = database_query($connection, $sql);
			$sql = sprintf("update sec_price set start_row = '%s', remain_seats = remain_seats-%d where sec_id = '%s'", $row_result[0]["row"], $_POST["ticket_num"], $_POST["sec_id"]);
			database_query($connection, $sql);
		}
		database_close($connection);
	}
	else
	{
		json_new();
		json_add("permission", -1);
		json_printobject();
	}
	
}