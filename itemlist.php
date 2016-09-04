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

	CheckLogin();

	if ($_REQUEST["postedBack"]) {
		if ($_REQUEST["cmd"] == "del") {
			$sql = "select * from inventory where itemid = '".$_POST["id"]."' limit 0, 1 ";
			if (DBFetchData($sql)) {
				$msg = "Item cannot be deleted. Please change the status instead.<br>";
			}

			if ($msg == "") {
				$sql = "delete from item where itemid = '".$_POST["id"]."' ";
				if (DBExecute($sql)) {
					@unlink(ITEM_ICON_PATH.$_POST["id"]."-1.jpg");
					@unlink(ITEM_ICON_PATH.$_POST["id"]."-1.gif");
					@unlink(ITEM_ICON_PATH.$_POST["id"]."-1.png");
	
					@unlink(ITEM_ICON_PATH.$_POST["id"]."-2.jpg");
					@unlink(ITEM_ICON_PATH.$_POST["id"]."-2.gif");
					@unlink(ITEM_ICON_PATH.$_POST["id"]."-2.png");
	
					redirect("itemlist.php");
					exit;
				}
			}
		}
	}

	$sql = " select catid, catname from cat order by catname ";
	$cats = DBFetchAll($sql);
	$catnames = array();
	for ($i=0; $i<sizeof($cats); $i++) {
		$catnames[$cats[$i]["catid"]] = $cats[$i]["catname"];
	}

	$sql = " select statusid, statusname from itemstatus order by statusid";
	$tmp = DBFetchAll($sql);
	$statusnames = array();
	for ($i=0; $i<sizeof($tmp); $i++) {
		$statusnames[$tmp[$i]["statusid"]] = $tmp[$i]["statusname"];
	}
	
	$sql = "select * from cat where catid = '".$_REQUEST["cat"]."' ";
	$cat = DBFetchData($sql);
	
	$sql = " select * ";
	$sql .= "from item ";
	if ($_REQUEST["itemno"]) {
		$sqlw .= "and itemno like '%".$_REQUEST["itemno"]."%' ";
	}
	if ($_REQUEST["cat"]) {
		$sqlw .= "and catid = '".$_REQUEST["cat"]."' ";
	}
	if ($sqlw) $sql .= " where ".substr($sqlw, 3);
	$sql .= "order by itemname ";		
	$items = DBFetchAll($sql);
?>
<? include("header.inc.php");?>
<form name="frm1" method="post">

<div class="functionname">Products</div>

<?=$msg?>


<table border="0" width="100%">
<tr>
<td width="80">Item No. : </td>
<td width="100"><input type="text" name="itemno" value="<?=$_REQUEST["itemno"]?>" size="10"></td>
<td width="80">Category : </td>
<td width="150">
		<select name="cat" onchange="loadCat(this)">
			<option value="0"></option>
<? for ($i=0; $i<sizeof($cats); $i++) { ?>
			<option value="<?=$cats[$i]["catid"]?>" <?=($_REQUEST["cat"]==$cats[$i]["catid"])?"selected":""?> ><?=$cats[$i]["catname"]?>&nbsp;</option>
<? } ?>
		</select>
</td>
<td width="100"><input type="submit" name="search" value="Search"></td>
<td align="right">
	<a href="itemdetail.php?cmd=add" class="actionlink">add item</a>
</td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tablestyle">
	<thead>
		<tr>
		<td>Item No.</td>
		<td>Item Name</td>
		<td>Category</td>
		<td>Status</td>
		<td>&nbsp;</td>
	</tr>
	</thead>
<? for ($i=0; $i<sizeof($items); $i++) { ?>
	<tr>
		<td><a href="itemdetail.php?cmd=edit&id=<?=$items[$i]["itemid"]?>"><?=$items[$i]["itemno"]?></a>&nbsp;</td>
		<td><?=$items[$i]["itemname"]?>&nbsp;</td>
		<td><?=$catnames[$items[$i]["catid"]]?>&nbsp;</td>
		<td><?=$statusnames[$items[$i]["status"]]?>&nbsp;</td>
		<td align="right"><a href="javascript:doDelete(<?=$items[$i]["itemid"]?>)">delete</a></td>
	</tr>
<? } ?>
</table>

<input type="hidden" name="cmd" value="">
<input type="hidden" name="id" value="">
<input type="hidden" name="postedBack" value="1">
</form>

<script language="javascript">
function doDelete(id)
{
	var f = document.frm1;
	
	if (confirm("Are you sure ?")) {
		f.cmd.value = "del";
		f.id.value = id;
		f.submit();
	}
}

function loadCat(s)
{
	var f = document.frm1;
	f.submit();
}

</script>

<? include("footer.inc.php");?>