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

	include("config.inc.php");

	if ($_REQUEST["postedBack"]) {
		$sql  = "select * from user where sloginid = '".DBQuote($_REQUEST["userid"])."' ";
		$sql .= "and spassword = md5('".DBQuote($_REQUEST["password"])."') ";
		$sql .= "and sstatus <> 'D' ";
		
		$user = DBFetchData($sql);
		if ($user["iuserid"]) {
			$_SESSION[SS]["user"] = $user;
			redirect("index.php");
		} else {
			$msg = "Incorrect User Id or Password.";
		}
	}
?>
<? include("header.inc.php");?>

<form name="frm1" method="post" action="">
<input type="hidden" name="cmd" value="">
<input type="hidden" name="postedBack" value="1">

<br><br><br>

<?=($msg)?"<font color=red>".$msg."</font>":""?>
<table width="300" border="0" cellspacing="0" cellpadding="2" class="tablestyle" align="center">
	<thead>
		<tr>
		<td colspan="2">Login</td>
		</tr>
	</thead>
	<tr>
		<td>User Id : </td>
		<td><input type="text" name="userid" value="<?=$userid?>" size="10"></td>
	</tr>
	<tr>
		<td>Password : </td>
		<td><input type="password" name="password" value="" size="10"></td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" name="cmdok" value="Login">
		</td>
	</tr>
</table>

</form>
<? include("footer.inc.php");?>