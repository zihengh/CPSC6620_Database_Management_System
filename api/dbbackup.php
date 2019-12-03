<?php
session_start();

require "dbconnection.php";
require "json.php";


if (isset($_POST["action"]) && $_POST["action"] == "backuptable" && isset($_SESSION["permission"])&&$_SESSION["permission"]>0)
{
	
	$host = "mysql1.cs.clemson.edu";
	$username = "zihengh";
	$password = "zihengh_chongm";
	$database_name = "Ticket_management";

	// Get connection object and set the charset
	$conn = mysqli_connect($host, $username, $password, $database_name);
	$conn->set_charset("utf8");


	// Get All Table Names From the Database
	$tables = array();
	$sql = "SHOW TABLES";
	$result = mysqli_query($conn, $sql);

	while ($row = mysqli_fetch_row($result)) {
		$tables[] = $row[0];
	}
		
		$sqlScript = "";
	foreach ($tables as $table) {
		
		// Prepare SQLscript for creating table structure
		$query = "SHOW CREATE TABLE $table";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_row($result);
		
		$sqlScript .= "\n\n" . $row[1] . ";\n\n";
		
		
		$query = "SELECT * FROM $table";
		$result = mysqli_query($conn, $query);
		
		$columnCount = mysqli_num_fields($result);
		
		// Prepare SQLscript for dumping data for each table
		for ($i = 0; $i < $columnCount; $i ++) {
			while ($row = mysqli_fetch_row($result)) {
				$sqlScript .= "INSERT INTO $table VALUES(";
				for ($j = 0; $j < $columnCount; $j ++) {
					$row[$j] = $row[$j];
					
					if (isset($row[$j])) {
						$sqlScript .= '"' . $row[$j] . '"';
					} else {
						$sqlScript .= '""';
					}
					if ($j < ($columnCount - 1)) {
						$sqlScript .= ',';
					}
				}
				$sqlScript .= ");\n";
			}
		}
		
		$sqlScript .= "\n"; 
	}


	if(!empty($sqlScript))
	{
		// Save the SQL script to a backup file
		$backup_file_name = $database_name . '_backup_' . '.sql';
		$fileHandler = fopen($backup_file_name, 'w+');
		$number_of_lines = fwrite($fileHandler, $sqlScript);
		fclose($fileHandler); 

		// Download the SQL backup file to the browser
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($backup_file_name));
		ob_clean();
		flush();
		readfile($backup_file_name);
		exec('rm ' . $backup_file_name); 
	}
}
//------------Block Description: Restore a table by its backup file(current data will be wiped out)
//----------Permission Required: Administrator
//----Expecting Post Parameters: token, table
//-----Optional Post Parameters:
//----------------Return Values:
if (isset($_POST["action"]) && $_POST["action"] == "restoretable" && isset($_SESSION["permission"])&&$_SESSION["permission"]>0)//for checking
{
	set_time_limit(500);
	$host = "mysql1.cs.clemson.edu";
	$username = "zihengh";
	$password = "zihengh_chongm";
	$database_name = "Ticket_management";
	$db = new mysqli($host, $username, $password, $database_name); 

	$filePath = $database_name . '_backup_' . '.sql';
    // Temporary variable, used to store current query
    $templine = '';
    
    // Read in entire file
    $lines = file($filePath);
    
    $error = '';
    
    // Loop through each line
    foreach ($lines as $line){
        // Skip it if it's a comment
        if(substr($line, 0, 2) == '--' || $line == ''){
            continue;
        }
        
        // Add this line to the current segment
        $templine .= $line;
        
        // If it has a semicolon at the end, it's the end of the query
        if (substr(trim($line), -1, 1) == ';'){
            // Perform the query
            if(!$db->query($templine)){
                $error .= 'Error performing query "<b>' . $templine . '</b>": ' . $db->error . '<br /><br />';
            }
            
            // Reset temp variable to empty
            $templine = '';
        }
    }
	
}

function getfile($dir)
{
	if (false != ($handler = opendir($dir)))
	{
		while (false !== ($file = readdir($handler)))
		{
			if ($file != "." && $file != ".." && strpos($file, "."))
			{
				$filearray[] = $file;
			}
		}
		closedir($handler);
	}
	return $filearray;
}