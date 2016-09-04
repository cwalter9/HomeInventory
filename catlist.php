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
			$msg = "";

			$sql = "select * from cat where pcatid = '".$_POST["id"]."' ";
			if (DBFetchData($sql)) {
				$msg = "<b>Category cannot be deleted. It contains sub-categories.</b><br>";
			}
			
			$sql = "select * from item where catid = '".$_POST["id"]."' ";
			if (DBFetchData($sql)) {
				$msg = "<b>Category cannot be deleted. It contains items.</b><br>";
			}

			if ($msg == "") {
				$sql = "delete from cat where catid = '".$_POST["id"]."' ";
				if (DBExecute($sql)) {
					@unlink(CAT_ICON_PATH.$_POST["id"].".jpg");
					@unlink(CAT_ICON_PATH.$_POST["id"].".gif");
					@unlink(CAT_ICON_PATH.$_POST["id"].".png");

					@unlink(CAT_BANNER_PATH.$_POST["id"].".jpg");
					@unlink(CAT_BANNER_PATH.$_POST["id"].".gif");
					@unlink(CAT_BANNER_PATH.$_POST["id"].".png");
					
					redirect("catlist.php");
					exit;
				}
			}
		}
	}
	
	$sql = " select a.*, b.catname as pcatname ";
	$sql .= "from cat a ";
	$sql .= "  left join cat b on a.pcatid = b.catid ";
	$sql .= "order by b.catname, a.catname ";
		
	$cats = DBFetchAll($sql);
?>
<? include("header.inc.php");?>

<form name="frm1" method="post">
<div class="functionname">Category Maintenance</div>

<div align="right"><a href="catdetail.php?cmd=add" class="actionlink">add</a></div>

<?=$msg?>

<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tablestyle">
	<thead>
	<tr>
		<td>Parent Category</td>
		<td>Code</td>
		<td>Category</td>
		<td>&nbsp;</td>
	</tr>
	</thead>
<? for ($i=0; $i<sizeof($cats); $i++) { ?>
	<tr>
		<td><?=$cats[$i]["pcatname"]?>&nbsp;</td>
		<td><?=$cats[$i]["catcode"]?>&nbsp;</td>
		<td><a href="catdetail.php?cmd=edit&id=<?=$cats[$i]["catid"]?>"><?=$cats[$i]["catname"]?></a>&nbsp;</td>
		<td align="right">
			<a class="actionlink" href="javascript:doDelete(<?=$cats[$i]["catid"]?>)">delete</a>&nbsp;&nbsp;
			<a class="actionlink" href="itemlist.php?cat=<?=$cats[$i]["catid"]?>">items</a>
		</td>
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
</script>

<? include("footer.inc.php");?>
