<?
//*****************************************************************************************
//Simple Inventory System 
//is a small inventory system which is designed for fashion 
//and clothing industry. It is a browser based applications which utilized AJAX to let 
//user input color/size on a two dimension table. Currenty, it contains purchase, sales, 
//inventory inquiry and inventory reports.
//
//Copyright (C) 2008  New Associates Consultant Ltd. http://www.nacl.hk
//
//This program is free software: you can redistribute it and/or modify
//it under the terms of the GNU General Public License as published by
//the Free Software Foundation, either version 3 of the License, or
//(at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.
//
//You should have received a copy of the GNU General Public License
//along with this program.  If not, see <http://www.gnu.org/licenses/>.
//*****************************************************************************************

	include("mysql.inc.php");
	include("common.inc.php");

	define("DBHOST", "<<dbhost>>");
	define("DBNAME", "<<dbname>>");
	define("DBUSER", "<<dbuser>>");
	define("DBPASSWORD", "<<dbpass>>");

	define("SS", "NACIN");

	session_start();
	session_register(SS);

	$msg = "";
	if (is_dir("install")) {
		if ($_GET["setup"]) {
			$msg .= "<h3>Please remove the 'install' folder</h3>";
		} else {
			Redirect("install/index.php");
		}
	}
	if (is_writable("config.inc.php")) {
		$msg .= "<h3>Please change the permission of config.inc.php to readonly</h3>";
	}
	if ($msg) {
		echo $msg;
		exit;
	}
	DBLogOn();
?>
