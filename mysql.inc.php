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

$DBCONN = "";
$DBERROR = "";

function DBLogOn()
{
	global $DBCONN;
	$DBCONN = mysql_connect(DBHOST, DBUSER, DBPASSWORD);
	mysql_select_db(DBNAME);
}

function DBLogOff()
{
	global $DBCONN;
	mysql_close($DBCONN);
}

function DBGetError()
{
	global $DBERROR;
 return $DBERROR;
}

function DBQuery($sql)
{
	global $DBCONN, $DBERROR;
	if (!$stmt = @mysql_query($sql)) {
		$DBERROR .= mysql_error()."\n";
		return false;
	}
	return $stmt;
}

function DBCloseQuery($stmt)
{
	mysql_free_result($stmt);
}

function DBFetch($stmt)
{
	$row = mysql_fetch_assoc($stmt);
	return $row;
}

function DBFetchData($sql)
{
	$stmt = DBQuery($sql);
	if ($stmt) {
		$row = mysql_fetch_assoc($stmt);
		DBCloseQuery($stmt);
	}
	return $row;
}

function DBFetchAll($sql)
{
	$stmt = DBQuery($sql);

	if ($stmt) {
		unset($result);
		while($row = mysql_fetch_assoc($stmt))
		{
			$result[] = $row;
		}
		DBCloseQuery($stmt);
	}
	return $result;
}

function DBFetchArray($sql, $keyfield, $valuefield="")
{
	$stmt = DBQuery($sql);

	if ($stmt) {
		unset($result);
		while($row = mysql_fetch_assoc($stmt))
		{
			if ($valuefield) {
				$result[$row[$keyfield]] = $row[$valuefield];
			} else {
				$result[$row[$keyfield]] = $row;
			}
		}
		DBCloseQuery($stmt);
	}
	return $result;
}

function DBFetchRange($sql, $start, $numrow)
{
	$stmt = DBQuery($sql);
	
	if ($stmt) {
		unset($result);
		$i = 0;
		while($row = mysql_fetch_assoc($stmt))
		{
			if ($i >= $start && $i < ($start + $numrow)) {
				$result[] = $row;
			}
			$i++;
		}
		DBCloseQuery($stmt);
	}
	return $result;
}

function DBExecute($sql)
{
	if (!$stmt = DBQuery($sql)) return false;	
	return true;
}

function DBQuote($value)
{
	$value = str_replace("\\", "\\\\", $value);
//	$value = addcslashes($value, "\0..\37!@\177..\377");
	$value = ereg_replace ("'", "''", $value);
	return $value;
}

function DBToSysDate($d, $f="d")
{
	$tmp = split(" ", $d);
	if ($f == "d") {
		$tmpd = split("-", $tmp[0]);
		$d = date("j M, y", mktime(0,0,0,$tmpd[1],$tmpd[2],$tmpd[0]));
	} else {
		$tmpd = split("-", $tmp[0]);
		$tmpt = split(":", $tmp[1]);
		$d = date("j M, y G:i:s", mktime($tmpd[0],$tmpd[1],$tmpd[2],$tmpd[1],$tmpd[2],$tmpd[0]));
	}
	return $d;
}

function DBToDBDate($d, $f="d")
{
	if ($f == "d") {
	} else {
	}
	return $d;
}

function DBNow()
{
	return "now()";
}

function DBNum($v)
{
	if ($v == "" || empty($v)) {
		return "0";
	} else {
		return $v;
	}
}

function DBToday()
{
	return "curdate()";
}

function DBDateDiff($field1, $field2)
{
	// field2 - field1
	return "TO_DAYS($field2) - TO_DAYS($field1)";
}

function DBInsertId()
{
	return mysql_insert_id();
}

function DBBeginTrans()
{
	$sql = "set autocommit = 0";
	DBExecute($sql);
}

function DBCommit()
{
	$sql = "commit";
	DBExecute($sql);
}

function DBRollBack()
{
	$sql = "rollback";
	DBExecute($sql);
}

function DBKeywordSearch($field, $search)
{
	$words = preg_split("/\s+/", $search);

	$sql = "";		
	for ($i=0; $i<sizeof($words); $i++) {
		$sql .= "and $field like '%".DBQuote($words[$i])."%' ";
	}
	if (!empty($sql)) {
		$sql = substr($sql, 4);
	}		
	return $sql;
}
?>
