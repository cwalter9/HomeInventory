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

	if ($_REQUEST["postedBack"]) {
		if ($_REQUEST["cmd"] == "add") {
			$currentUserId = $_SESSION[SS]["user"]["iuserid"];
			$sql  = "insert into user (";
			$sql .= "sloginid, susername, spassword, sstatus, ";
			$sql .= "icreatedby, dcreatedby, imodifiedby, dmodifiedby ";
			$sql .= ") values (";
			$sql .= "'".DBQuote($_REQUEST["sloginid"])."', ";
			$sql .= "'".DBQuote($_REQUEST["susername"])."', ";
			$sql .= "md5('".DBQuote($_REQUEST["spassword"])."'), ";
			$sql .= "'".DBQuote($_REQUEST["sstatus"])."', ";
			$sql .= "'".$currentUserId."', ";
			$sql .= "now(), ";
			$sql .= "'".$currentUserId."', ";
			$sql .= "now()) ";

			if (DBExecute($sql)) {
				redirect("userlist.php");
			}
			
		} else if ($_REQUEST["cmd"] == "edit") {
			$currentUserId = $_SESSION[SS]["user"]["iuserid"];

			$sql  = "update user set ";
			$sql .= "sloginid = '".DBQuote($_REQUEST["sloginid"])."', ";
			$sql .= "susername = '".DBQuote($_REQUEST["susername"])."' ";
			if ($_REQUEST["spassword"]) {
				$sql .= ", spassword = md5('".DBQuote($_REQUEST["spassword"])."') ";
			}
			$sql .= "where iuserid = '".DBQuote($_REQUEST["iuserid"])."' ";

			if (DBExecute($sql)) {
				redirect("userlist.php");
			}
			
		}
		$iuserid = $_REQUEST["iuserid"];
		$sloginid = $_REQUEST["sloginid"];
		$susername = $_REQUEST["susername"];
	} else {
		if ($_REQUEST["cmd"] == "edit") {
			$sql = "select * from user where iuserid = '".DBQuote($_REQUEST["id"])."' ";
			$data = DBFetchData($sql);
			$iuserid = $data["iuserid"];
			$sloginid = $data["sloginid"];
			$susername = $data["susername"];
		}
	}
?>
<?include("header.inc.php")?>
<script language="JavaScript">
<!--
function validate()
{
	var f = document.frm1;
	var msg = "";
	
	if (f.sloginid.value == "") {
		msg += "Please input User Id.\n";
	}
	
	if (f.susername.value == "") {
		msg += "Please input User Name.\n";
	}
	
	if (msg != "") {
		alert(msg);
		return;
	}
	f.submit();
}

function cancel()
{
	document.location.href = "userlist.php";
}

//-->
</script>
<form name="frm1" method="post" action="" onsubmit="validate(); return false;">
	<input type="hidden" name="cmd" value="<?=$_REQUEST["cmd"]?>">
	<input type="hidden" name="postedBack" value="1">
	<input type="hidden" name="iuserid" value="<?=$iuserid?>">

<div class="functionname">User Detail</div>
<br>
<table border="0" cellspacing="0" cellpadding="2" class="detailtable">
	<tr>
		<td class="bodytxt">User Id : </td>
		<td class="bodytxt"><input type="text" size="10" maxlength="30" name="sloginid" value="<?=$sloginid?>"></td>
	</tr>
	<tr>
		<td class="bodytxt">User Name : </td>
		<td class="bodytxt"><input type="text" size="30" maxlength="100" name="susername" value="<?=$susername?>"></td>
	</tr>
	<tr>
		<td class="bodytxt">Password : </td>
		<td class="bodytxt"><input type="password" size="10" maxlength="30" name="spassword" value=""></td>
	</tr>
</table>
<br>
<input type="submit" name="cmdsubmit" value="Save">
<input type="button" name="cmdcancel" value="Cancel" onclick="cancel()">

</form>

<?include("footer.inc.php")?>
