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

	if ($_REQUEST["cmd"] == "delete") {
		$ids = $_REQUEST["del"];
		
		for ($i=0; $i<sizeof($ids); $i++) {
			$id = $ids[$i];
			$sql ="update user set sstatus = 'D' where iuserid = '".DBQuote($id)."' ";
			DBExecute($sql);
		}
		
		redirect($SCRIPT_NAME);
	}
?>
<?include("header.inc.php")?>
<script language="JavaScript">
<!--
function addrec()
{
	document.location.href = "userdetail.php?cmd=add";
}

function editrec(id)
{
	document.location.href = "userdetail.php?cmd=edit&id=" + id;
}

function deleterec()
{
	var f = document.frm1;
	var checked = false;

	if (f.elements["del[]"] == undefined) return;

	if (f.elements["del[]"].length != undefined) {
		for (var i=0; i<f.elements["del[]"].length; i++) {
			if (f.elements["del[]"][i].checked) {
				checked = true;
			}
		}
	} else {
		if (f.elements["del[]"].checked) {
			checked = true;
		}
	}	
	if (!checked) {
		alert("Please select record to delete.");
		return;
	}
	
	if (confirm("Are you sure ?")) {
		f.cmd.value = "delete";
		f.submit();
	}
}

//-->
</script>

<form name="frm1" method="post" action="">
<input type="hidden" name="cmd" value="">
<div class="functionname">User Maintenance</div>
<div align="right"><a href="javascript:addrec()" class="actionlink">add new</a>&nbsp;&nbsp;<a href="javascript:deleterec()" class="actionlink">delete</a></div>

<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tablestyle">
	<thead>
		<tr>
		<td>&nbsp;</td>
		<td>User Id</td>
		<td>User Name</td>
		<td>Status</td>
		</tr>
	</thead>
<?
$sql = "select * from user where sstatus <> 'D' order by sloginid";
$users = DBFetchAll($sql);

for ($i=0; $i<sizeof($users); $i++) {
$userid = $users[$i]["iuserid"];

if ($users[$i]["sstatus"] == "D") {
$status = "Disabled";
} else {
$status = "Active";
}
?>							
	<tr> 
		<td><? if ($users[$i]["sloginid"] != "admin") {?><input type="checkbox" name="del[]" value="<?=$userid?>"><? } else { echo "&nbsp;";}?></td>
		<td><a href="javascript:editrec(<?=$userid?>)"><?=$users[$i]["sloginid"]?></a></td>
		<td><?=$users[$i]["susername"]?></td>
		<td><?=$status?></td>
	</tr> 
<? } ?>
</table>

</form>
<?include("footer.inc.php")?>
