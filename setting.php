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

	require_once("config.inc.php");

	CheckLogin();
	if ($_SESSION[SS]["user"]["sloginid"] != "admin") {
		echo "<font color=red>This function can only be used by administrator.</font>";
		exit;
	}
	if (isset($_REQUEST["postedBack"]) && $_REQUEST["postedBack"]) {
		$currentUserId = $_SESSION[SS]["user"]["iuserid"];

		for ($i=0; $i<sizeof($_REQUEST["ids"]); $i++) {
			$sql = "update settings set ";
			$sql .= "svalue = '".DBQuote($_REQUEST["svalues"][$i])."', ";
			$sql .= "imodifiedby = '".$currentUserId."' ";
			$sql .= "where isettingid = '".DBQuote($_REQUEST["ids"][$i])."' ";
			
			DBExecute($sql);
		}
		$_SESSION[SS]["saved"] = 1;

		Redirect($_SERVER["SCRIPT_NAME"]);
	} else {
		if (!isset($_SESSION[SS]["saved"])) $_SESSION[SS]["saved"] = 0;
		$sql = "select * from settings order by sname ";
		$data = DBFetchAll($sql);
	}
?>
<?include("header.inc.php")?>
<script language="JavaScript">
<!--
function validate()
{
	var f = document.frm1;
	var msg = "";
	
	if (msg != "") {
		alert(msg);
		return;
	}
	f.submit();
}
//-->
</script>

<form name="frm1" method="post" action="" onsubmit="validate(); return false;">

<div class="functionname">Settings</div>
<br>


<table border="0" cellspacing="0" cellpadding="2" class="detailtable">
<?
for ($i=0; $i<sizeof($data); $i++) {
$id = $data[$i]["isettingid"];
$name = htmlspecialchars($data[$i]["sname"]);
$value = htmlspecialchars($data[$i]["svalue"]);
?>
	<tr>
		<td class="bodytxt"><?=$name?> : <input type="hidden" name="ids[]" value="<?=$id?>"></td>
		<td class="bodytxt"><input type="text" size="80" name="svalues[]" value="<?=$value?>"></td>
	</tr>
<? } ?>											
</table>
<br>
<input type="hidden" name="postedBack" value="1">
<input type="submit" name="cmdsubmit" value="Save">
</form>

<script language="javascript">
<? if ($_SESSION[SS]["saved"] == 1) { ?>
alert("Settings are updated.");
<? $_SESSION[SS]["saved"] = 0;} ?>
</script>

<?include("footer.inc.php")?>
