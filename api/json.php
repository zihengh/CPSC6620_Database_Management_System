<?php


$json_string = "";
$json_row = array();

function json_new()
{
	global $json_string, $json_row;
	$json_string = "";
	$json_row = array();
}

function json_isempty()
{
	global $json_string;
	return $json_string == "";
}

function json_printobject()
{
	global $json_string, $json_row;
	if (count($json_row) > 0)
	{
		if ($json_string != "")
		{
			$json_string .= ",";
		}
		$json_string .= json_encode(utf8fixer($json_row));
	}
	if (substr($json_string, 0, 1) == "[")
	{
		$json_string = substr($json_string, 1);
	}
	header('Content-type: application/json');
	echo $json_string;
}

function json_printarray()
{
	global $json_string, $json_row;
	if (count($json_row) > 0)
	{
		if ($json_string != "")
		{
			$json_string .= ",";
		}
		$json_string .= json_encode(utf8fixer($json_row));
	}
	if (substr($json_string, 0, 1) != "[")
	{
		$json_string = "[" . $json_string;
	}
	$json_string .= "]";
	header('Content-type: application/json');
	echo $json_string;
}

function json_print()
{
	global $json_string, $json_row;
	if (count($json_row) > 0)
	{
		if ($json_string != "")
		{
			$json_string .= ",";
		}
		$json_string .= json_encode(utf8fixer($json_row));
	}
	if (substr($json_string, 0, 1) == "[")
	{
		$json_string .= "]";
	}
	header('Content-type: application/json');
	echo $json_string;
}

function json_add($key, $value)
{
	global $json_row;
	$json_row[$key] = $value;
}

function json_addarray($array)
{
	global $json_row;
	$json_row = array_merge($json_row, $array);
}

function json_addrow($row)
{
	global $json_string, $json_row;
	if (count($json_row) > 0)
	{
		json_newrow();
	}
	if ($json_string != "")
	{
		$json_string .= ",";
		if (substr($json_string, 0, 1) != "[")
		{
			$json_string = "[" . $json_string;
		}
	}
	$json_string .= json_encode(utf8fixer($row));
}

function json_addrows($rows)
{
	for ($i = 0; $i < count($rows); $i++)
	{
		json_addrow($rows[$i]);
	}

}

function json_newrow()
{
	global $json_string, $json_row;
	if ($json_string != "")
	{
		$json_string .= ",";
		if (substr($json_string, 0, 1) != "[")
		{
			$json_string = "[" . $json_string;
		}
	}
	$json_string .= json_encode(utf8fixer($json_row));
	$json_row = array();
}

function utf8fixer($input)
{
	if (is_array($input))
	{
		$rtn = array();
		foreach ($input as $key => $value)
		{
			$rtn[$key] = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($value));
		}
		return $rtn;
	}
	else
	{
		return iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($input));
	}
}