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

function CurrentUserId()
{
	if (empty($_SESSION[SS]["user"])) {
		return "";
	}
	return $_SESSION[SS]["user"]["iuserid"];
}

function CheckLogin($redirectlogin=true)
{
	if (empty($_SESSION[SS]["user"])) {
		if ($redirectlogin) redirect("login.php");
		exit;
	}
}

function ProtectJS()
{
return;
	if (empty($_SESSION[SS]["user"])) {
		exit;
	}

	$referer = strtolower($_SERVER["HTTP_REFERER"]);
	$host = strtolower("http://".$_SERVER["SERVER_NAME"]);
	if (!strstr($referer, $host)) {
		exit;
	}
	
	$tmp = pathinfo($_SERVER["SCRIPT_NAME"]);
	$path = str_replace("/js", "", strtolower($tmp["dirname"]));
	if (!strstr($referer, $path)) {
		exit;
	}
}

function Debug($values, $title="")
{
	echo "<table border=\"1\">\n";
	if (!empty($title)) echo "<tr><td bgcolor=\"yellow\" colspan=\"3\">$title</td></tr>";
	echo "<tr><td bgcolor=\"#6666FF\">Key</td><td bgcolor=\"#6666FF\">Value</td><td bgcolor=\"#6666FF\"></td></tr>\n";
	$i = 0;
	if (is_array($values) || is_object($values)) {
		reset($values);
		while (list($key, $value) = each($values))
		{
			if ($i % 2 == 0)  {
				$color = "bgcolor=\"#CCCFF\"";
			} else {
				$color = "bgcolor=\"#EEEEE\"";
			}
		
			if ($value == "") $value = " ";
			if (!is_array($key)) $key = htmlspecialchars($key);
			if (!is_array($value)) {
				$v = htmlspecialchars($value);
			} else {
				$v = $value;
			}
		
			echo "<tr $color ><td>$key</td><td>$v</td><td>";
			if (is_array($value) || is_object($value)) {
				Debug($value);
			}
			echo "</td></tr>";
		
			$i++;
		}
	} else {
		echo "<tr><td bgcolor=\"#CCCFF\" colspan=\"3\">" . htmlspecialchars($values) . "<td></tr>\n";
	}
	echo "</table>\n";
}

function Debug2($values, $title="")
{
	$html = "<table border=\"1\">\n";
	if (!empty($title)) $html .= "<tr><td bgcolor=\"yellow\" colspan=\"3\">$title</td></tr>";
	$html .= "<tr><td bgcolor=\"#6666FF\">Key</td><td bgcolor=\"#6666FF\">Value</td><td bgcolor=\"#6666FF\"></td></tr>\n";
	$i = 0;
	if (is_array($values) || is_object($values)) {
		reset($values);
		while (list($key, $value) = each($values))
		{
			if ($i % 2 == 0)  {
				$color = "bgcolor=\"#CCCFF\"";
			} else {
				$color = "bgcolor=\"#EEEEE\"";
			}
		
			if ($value == "") $value = " ";
			if (!is_array($key)) $key = htmlspecialchars($key);
			if (!is_array($value)) {
				$v = htmlspecialchars($value);
			} else {
				$v = $value;
			}
		
			$html .= "<tr $color ><td>$key</td><td>$v</td><td>";
			if (is_array($value) || is_object($value)) {
				$html .= Debug2($value);
			}
			$html .= "</td></tr>";
		
			$i++;
		}
	} else {
		$html .= "<tr><td bgcolor=\"#CCCFF\" colspan=\"3\">" . htmlspecialchars($values) . "<td></tr>\n";
	}
	$html .= "</table>\n";
	return $html;
}

function DebugToFile($filename, $values, $title="")
{
	if (!$fd = fopen($filename, "w")) return;
	fwrite($fd, Debug2($values, $title));
	fclose($fd);
}

function Redirect($url)
{
	header("Location: $url");
	exit;
}

function GetReferer()
{
	global $HTTP_REFERER;
	$reg = "/^http:\/\/([a-zA-Z0-9\.]+)/";
	if (preg_match($reg, $HTTP_REFERER, $match)) {
		return $match[1];
	}	
}

function QueryString($otherVars="")
{
	global $HTTP_GET_VARS;

	$vars = $HTTP_GET_VARS;
	
	if (is_array($otherVars)) {
		reset($otherVars);
		while(list($key, $value) = each($otherVars))
		{
			$vars[$key] = $value;
		}
	}
	if (is_array($vars)) {
		reset($vars);
		$url = "";
		while(list($key, $value) = each($vars))
		{
			if ($value != "") {
				$url .= "&".$key."=".urlencode($value);
			}
		}
		if ($url) $url = substr($url, 1);
	}
	return $url;
}

function FormatDate($d)
{
	if ($d == "") return "";
	if ($d == "0000-00-00 00:00:00") return "N/A";
	
	$tmp = split(" ", $d);
	$tmp = split("-", $tmp[0]);
	$out = date("d M, y", mktime(0, 0, 0, $tmp[1], $tmp[2], $tmp[0]));

	return $out;
}

function FormatInt($i)
{
	if ($i == 0) return "";
	return number_format($i, 0, ".", ",");
}

function FormatNum($i)
{
	if ($i == 0) return "";
	return number_format($i, 2, ".", ",");
}

function FormatDate2($d)
{
	if ($d == "") return "";
	if ($d == "0000-00-00 00:00:00") return "N/A";
	
	$tmp = split(" ", $d);
	$tmp = split("-", $tmp[0]);
	$out = date("Y-m-d", mktime(0, 0, 0, $tmp[1], $tmp[2], $tmp[0]));

	return $out;
}

function FormatDateTime($dt)
{
	if ($dt == "") return "";
	$tmp1 = split(" ", $dt);
	$d = split("-", $tmp1[0]);
	$t = split(":", $tmp1[1]);
	
	return date("d M H:i:s", mktime($t[0], $t[1], $t[2], $d[1], $d[2], $d[0]));
}

function FormatText($Msg, $RemoveSpace=true)
{
	global $user, $sess;

	if ($RemoveSpace) {
		$pattern = array(
	                   "/\s+/",
	                   "/\.([^\d])/",
	                   "/,/",
	                   "/\[(\d+)\]/",
	                   "/\((\d+)\)/");
		
		$replace = array(
	                   "",
	                   ". &nbsp;\\1",
	                   ", ",
	                   "<a href='index.php?user=$user&sess=$sess&cmdfind=1&code=\\1'>[\\1]</a>",
	                   "<a href='index.php?user=$user&sess=$sess&cmdfind=1&code=\\1'>[\\1]</a>");
	} else {
		$pattern = array(
	                   "/\.([^\d])/",
	                   "/,/",
	                   "/\[(\d+)\]/",
	                   "/\((\d+)\)/");
		
		$replace = array(
	                   ". &nbsp;\\1",
	                   ", ",
	                   "<a href='index.php?user=$user&sess=$sess&cmdfind=1&code=\\1'>[\\1]</a>",
	                   "<a href='index.php?user=$user&sess=$sess&cmdfind=1&code=\\1'>[\\1]</a>");
	}
	
	return preg_replace($pattern, $replace, $Msg);
}

function TrimValue($v)
{
	return str_replace(" ", "", str_replace(",", "", $v));
}

function resize($file, $width, $height, $outfile="")
{
	if (!is_float($width*1.0)) return false;
	if (!is_float($height*1.0)) return false;
	if (!file_exists($file)) return false;
	
	$file= escapeshellarg($file);
	$width = escapeshellarg($width);
	$height = escapeshellarg($height);
	if ($outfile) {
		$outfile = escapeshellarg($outfile);
	} else {
		$outfile = $file;
	}
	
//	$cmd = "/usr/bin/convert -geometry ".$width."x".$height." $file $outfile ";
//	return exec($cmd);
}

function RemoveEmpty($arr, $prefix="")
{
	$tmp = array();
	for ($i=0; $i<sizeof($arr); $i++) {
		if ($arr[$i]) $tmp[] = $prefix.trim($arr[$i]);
	}
	$tmp = array_values(array_unique($tmp));
	sort($tmp);
	
	return $tmp;
}

function LastDateOfMonth($year, $month)
{
	$t = mktime(0,0,0, $month+1,0,$year);
	return date("Y-m-d", $t);
}

function GetQueryString($otherVars="")
{
	$getVars = $_GET;
	
	if (is_array($otherVars)) {
		reset($otherVars);
		while(list($key, $value) = each($otherVars))
		{
			$getVars[$key] = $value;
		}
	}
	if (is_array($getVars)) {
		reset($getVars);
		$url = "";
		while(list($key, $value) = each($getVars))
		{
			if ($url != "") $url .= "&";
			$url .= $key . "=" . urlencode($value);
		}
	}
	return $url;
}

function DateDiff($d)
{
	$t = substr($d, 0, 10);	
	$today = strtotime(date("Y-m-d"));
	return ($today - strtotime($t))/3600/24;
}

?>
