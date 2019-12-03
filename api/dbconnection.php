<?php


//Database Connection header

$database_server = "mysql1.cs.clemson.edu";
$database_username = "zihengh";
$database_password = "zihengh_chongm";
$database_name = "Ticket_management";


function database_connect()
{
	global $database_server, $database_username, $database_password, $database_name;
	$connection = mysqli_connect($database_server, $database_username, $database_password, $database_name);
	return $connection;
}

function database_close($connection)
{
	mysqli_close($connection);
}

function database_query($connection, $sqlcommand)
{
	if (!$connection)
	{
		return "Unconnected database";
	}
	$result = mysqli_query($connection, $sqlcommand);
	if (!$result)
	{
		return mysqli_error($connection);
	}
	else if (!is_bool($result))
	{
		$rows = array();
		while ($row = mysqli_fetch_assoc($result))
		{
			array_push($rows, $row);
		}
		return $rows;
	}
}