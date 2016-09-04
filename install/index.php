<?
include_once("../common.inc.php");

if (strstr($_REQUEST["submit"], "<<")) {
	$step = $_REQUEST["back"];
} else {
	$step = $_REQUEST["step"];
}
if (!$step) $step = 0;

$DBERROR = "";

if ($step == 0) {
	// Welcome
	$title = "Welcome to the Install Wizard for NACL Inventory System";
	$content = "<p>Choose language to be used for the installation process</p><select name='lang'><option value='english' selected='selected'>english</option></select>";
	$thisStepOK = true;
	$nextStep = 1;
	$prevStep = 0;
} else if ($step == 1) {
	// Introduction
	$title = "Introduction";
	$includefile = "step1.php";
	$thisStepOK = true;
	$nextStep = 2;
	$prevStep = 0;
} else if ($step == 2) {
	// Check File
	$title = "Checking file and directory permissions";
	$thisStepOK = true;
	$nextStep = 3;
	$prevStep = 1;

	$files = array("../config.inc.php");
	$results = array();
	$content = "<table cellpadding='2'>";
	for ($i=0; $i<sizeof($files); $i++) {
		$file = realpath($files[$i]);

		if (!file_exists($file)) {
			$results[] = array("file"=>$file, "result"=>"Missing");
			$content .= "<tr><td class='error'>Missing</td><td>$file</td></tr>";
			$thisStepOK = false;
		} else {
			if (is_writable($files[$i])) {
				$results[] = array("file"=>$files[$i], "result"=>"OK");
				$content .= "<tr><td class='ok'>OK</td><td>$file</td></tr>";
			} else {
				$results[] = array("file"=>$files[$i], "result"=>"NOT Writable");
				$content .= "<tr><td class='error'>NOT Writable</td><td>$file</td></tr>";
				$thisStepOK = false;
			}
		}
	}
	$content .= "</table>";

	if (!$thisStepOK) {
		$nextStep = 2;
		$prevStep = 1;
		$content = "<p class='error'>In order for the modules included in the package to work correctly, the following files must be writeable by the server. Please change the permission setting for these files. (i.e. 'chmod 666 file_name' and 'chmod 777 dir_name' on a UNIX/LINUX server, or check the properties of the file and make sure the read-only flag is not set on a Windows server)</p>".$content;
	} else {
		$content .= "<p class='ok'>No errors detected</p>";
	}
} else if ($step == 3) {
	// Database
	$title = "Database Configuration";
	$includefile = "step3.php";
	$thisStepOK = true;
	$nextStep = 4;
	$prevStep = 2;
} else if ($step == 4) {
	// Database
	$title = "Checking Database Configuration";
//	$includefile = "step3.php";
	$thisStepOK = true;
	$nextStep = 5;
	$prevStep = 3;
	$valid = true;
	
	$content = "<table cellpadding='2'>";
	if ($_POST["database"]) {
		$content .= "<tr><td class='ok'>OK</td><td>Database : ".$_POST["database"]."</td></tr>";
	} else {
		$content .= "<tr><td class='error'>Missing</td><td>Database</td></tr>";
		$valid = false;
	}
	if ($_POST["dbhost"]) {
		$content .= "<tr><td class='ok'>OK</td><td>Database Hostname : ".$_POST["dbhost"]."</td></tr>";
	} else {
		$content .= "<tr><td class='error'>Missing</td><td>Database Hostname</td></tr>";
	}
	if ($_POST["dbuser"]) {
		$content .= "<tr><td class='ok'>OK</td><td>Database Username : ".$_POST["dbuser"]."</td></tr>";
	} else {
		$content .= "<tr><td class='error'>Missing</td><td>Database Username</td></tr>";
		$valid = false;
	}
	if ($_POST["dbpass"]) {
		$content .= "<tr><td class='ok'>OK</td><td>Database Password : ".$_POST["dbpass"]."</td></tr>";
	} else {
		$content .= "<tr><td class='error'>Missing</td><td>Database Password</td></tr>";
		$valid = false;
	}
	if ($_POST["dbname"]) {
		$content .= "<tr><td class='ok'>OK</td><td>Database Name : ".$_POST["dbname"]."</td></tr>";
	} else {
		$content .= "<tr><td class='error'>Missing</td><td>Database Name</td></tr>";
		$valid = false;
	}
	$content .= "</table>";
	
	if (!$valid) {
		$content .= "<p>Please input the missing information and try again.</p>";
	} else {
		$link = @mysql_connect($_POST["dbhost"], $_POST["dbuser"], $_POST["dbpass"]);
		if (!$link) {
			$content .= "<p class='error'>Could not connect to database : ".mysql_error()."</p>";
			$content .= "<p class='error'>Please prepare user account and grant the user the access to the database.</p>";
			$valid = false;
		} else {
			$content .= "<p class='ok'>Connected successfully</p>";
			mysql_close($link);
			
			$filename = realpath("../config-dist.inc.php");
			$fd= fopen($filename, "r");
			$configfile = fread($fd, filesize($filename));
			fclose($fd);
			
			$configfile = str_replace("<<database>>", $_POST["database"], $configfile);
			$configfile = str_replace("<<dbhost>>", $_POST["dbhost"], $configfile);
			$configfile = str_replace("<<dbuser>>", $_POST["dbuser"], $configfile);
			$configfile = str_replace("<<dbpass>>", $_POST["dbpass"], $configfile);
			$configfile = str_replace("<<dbname>>", $_POST["dbname"], $configfile);
			
			$filename = realpath("../config.inc.php");
			$fd= fopen($filename, "w");
			fwrite($fd, $configfile);
			fclose($fd);
		}
	}
	$content .= "<input type='hidden' name='database' value='".$_POST["database"]."'>";
	$content .= "<input type='hidden' name='dbhost' value='".$_POST["dbhost"]."'>";
	$content .= "<input type='hidden' name='dbuser' value='".$_POST["dbuser"]."'>";
	$content .= "<input type='hidden' name='dbpass' value='".$_POST["dbpass"]."'>";
	$content .= "<input type='hidden' name='dbname' value='".$_POST["dbname"]."'>";
	
	$thisStepOK = true;
	if (!$valid) {
		$thisStepOK = false;
		$nextStep = 4;
		$prevStep = 3;
	}
} else if ($step == 5) {
	$title = "Creating Tables and Records";
	$content = "";
	$thisStepOK = true;
	$nextStep = 6;
	$prevStep = 4;

	$content .= "<input type='hidden' name='database' value='".$_POST["database"]."'>";
	$content .= "<input type='hidden' name='dbhost' value='".$_POST["dbhost"]."'>";
	$content .= "<input type='hidden' name='dbuser' value='".$_POST["dbuser"]."'>";
	$content .= "<input type='hidden' name='dbpass' value='".$_POST["dbpass"]."'>";
	$content .= "<input type='hidden' name='dbname' value='".$_POST["dbname"]."'>";

	$link = @mysql_connect($_POST["dbhost"], $_POST["dbuser"], $_POST["dbpass"]);
	if (!$link) {
		$content .= "<p class='error'>Could not connect to database : ".mysql_error()."</p>";
		$content .= "<p class='error'>Please prepare user account and grant the user the access to the database.</p>";
		$valid = false;
	} else {
		$content .= "<p class='ok'>Connected successfully</p>";
		$valid = true;
		if (mysql_select_db($_POST["dbname"])) {
			$content .= "<p class='ok'>Database ".$_POST["dbname"]." exists</p>";
		} else {
			$content .= "<p class='error'>Database ".$_POST["dbname"]." not exists</p>";
			$sql = "create database ".$_POST["dbname"];
			if (!DBExecute($sql)) {
				$content .= "<p class='error'>Database ".$_POST["dbname"]." cannot be created</p>";
				$valid = false;
			} else {
				$content .= "<p class='ok'>Database ".$_POST["dbname"]." is created</p>";
				mysql_select_db($_POST["dbname"]);
			}
		}
	}
	
	if ($valid) {		
		$filename = realpath("nacin.sql");
		$fd= fopen($filename, "r");
		$contents = fread($fd, filesize($filename));
		fclose($fd);

		$statements = split(";", $contents);

		$content .= "<table cellpadding='2'>";
		for ($i=0; $i<sizeof($statements); $i++) {
			$sql = trim($statements[$i]);

			if ($sql) {
				if (DBExecute($sql)) {
					$content .= "<tr><td class='ok'>OK</td>";
				} else {
					$content .= "<tr><td class='error'>Error</td>";
					$valid = false;
				}
				$content .= "<td>".FirstLine($sql)."</td></tr>";
			}
		}
		$content .= "</table>";

		if ($valid) {
			$sql = "INSERT INTO `user` VALUES (1,'admin','e10adc3949ba59abbe56e057f20f883e','Administrator','',0,'0000-00-00 00:00:00',0,'0000-00-00 00:00:00')";
			if (!DBExecute($sql)) {
				$content .= "<p class='error'>Cannot import data</p>";
				$valid = false;
			} else {
				$content .= "<p class='ok'>Data imported successfully</p>";
			}
		}

		mysql_close($link);	
	}
	
	if (!$valid) {
		$thisStepOK = false;
		$nextStep = 5;
		$prevStep = 4;
	}
} else {
	$title = "Setup Completed";
	$content = "Congratulations !";
	$content .= "<p>For security reasons, please delete the 'install' folder and set the permission of config.inc.php to readonly.</p>";
	$content .= "<center><p><a href='../index.php?setup=1'><h3>Login</h3></a></p></center>";
	$content .= "<center><p>User ID: admin <br/>Password: 123456</p></center>";
	$completed = true;
}

function DBExecute($sql)
{
	global $DBERROR;

	if (!$stmt = @mysql_query($sql)) {
		$DBERROR .= mysql_error()."\n";
		return false;
	}
	return true;
}

function FirstLine($s)
{
	$pos = strpos($s, "\n");

	if ($pos === false) {
	   return $s;
	} else {
		return substr($s, 0, $pos-1);
	}
}
?><?include("template.php")?>
