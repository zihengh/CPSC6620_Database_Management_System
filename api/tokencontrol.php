<?php
/**
 * Created by PhpStorm.
 * User: Wennx
 * Date: 2018/11/30
 * Time: 3:43
 */
error_reporting(E_ERROR | E_PARSE);

require_once "dbconnection.php";
require_once "json.php";

//Login Token assign, renew and validation

if (isset($_POST["action"]) && $_POST["action"] == "validatetoken" && isset($_POST["token"]))
{
	if (isset($_POST["permission"]))
	{
		$permission = $_POST["permission"];
	}
	else
	{
		$permission = "";
	}
	json_new();
	json_add("validate_result", token_validate($_POST["token"], $permission));
	json_printobject();
}

function token_new($userid)
{
	$connection = database_connect();
	$sql = sprintf("Select LoginName, pwd, Token As Result From user Where uid = %d", $userid);
	$result = database_query($connection, $sql)[0];
	$expiretime = date(DATE_COOKIE, time() + 900);
	$key = md5($result["LoginName"] . substr($result["Password"], 0, strlen($result["Password"]) / 2));
	$tokenpack = base64_encode(implode(";", array($userid, $expiretime, $key)));
	$sql = sprintf("Update User Set Token = '%s' Where UserID = '%s'", $tokenpack, $userid);
	database_query($connection, $sql);
	database_close($connection);
	return $tokenpack;
}

function token_renew($userid)
{
	$connection = database_connect();
	$sql = sprintf("Select LoginName, Password, Permission, Token From User Where UserID = '%s'", $userid);
	$result = database_query($connection, $sql)[0];
	$expandtime = 0;
	$storedtoken = explode(";", $result["Token"]);
	if (count($storedtoken) > 1)
	{
		$expandtime = (int)$storedtoken[1];
	}
	$pack = explode(";", base64_decode($storedtoken[0]));
	$expiretime = strtotime($pack[1]);
	$expandtime = min(time() + 900, $expiretime + $expandtime + 180) - $expiretime;
	$token = $storedtoken[0] . ";" . $expandtime;
	$sql = sprintf("Update User Set Token = '%s' Where UserID = '%s'", $token, $userid);
	database_query($connection, $sql);
	database_close($connection);
}

function token_validate($token, $permission = "")
{
	$pack = explode(";", base64_decode($token));
	if (!is_array($pack) || count($pack) != 3)
	{
		return false;
	}
	$userid = $pack[0];
	$expiretime = $pack[1];
	$key = $pack[2];
	$connection = database_connect();
	$sql = sprintf("Select LoginName, Password, Permission, Token From User Where UserID = '%s'", $userid);
	$result = database_query($connection, $sql)[0];
	$expandtime = 0;
	$storedtoken = explode(";", $result["Token"]);

	if (count($storedtoken) > 1)
	{
		$expandtime = (int)$storedtoken[1];
	}
	if ($storedtoken[0] != $token)
	{
		database_close($connection);
		return false;
	}
	if ($key != md5($result["LoginName"] . substr($result["Password"], 0, strlen($result["Password"]) / 2)))
	{
		database_close($connection);
		return false;
	}
	if (time() > strtotime($expiretime) + $expandtime)
	{
		database_close($connection);
		return false;
	}
	if ($permission != "" && strpos($result["Permission"], $permission) === false)
	{
		database_close($connection);
		return false;
	}
	token_renew($userid);
	database_close($connection);
	return true;
}

function token_getid($token)
{
	$pack = explode(";", base64_decode($token));
	return $pack[0];
}

function token_getpermission($token)
{

}