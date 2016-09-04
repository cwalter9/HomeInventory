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
	include("image.class.php");

	CheckLogin();
	$msg = "";
	
	if ($_REQUEST["postedBack"]) {
		if ($_REQUEST["act"] == "") {
			if ($_REQUEST["cmd"] == "add") {
				if (AddRec()) {
					redirect("itemlist.php?itemno=".$_POST["itemno"]);
				}
			} else if ($_REQUEST["cmd"] == "edit") {
				if (UpdateRec()) {
					redirect("itemlist.php?itemno=".$_POST["itemno"]);
				}
			}

		}

		$colors = split(",", $_POST["colors"]);
		$sizes = split(",", $_POST["sizes"]);
		
		for ($i=0; $i<sizeof($_POST["colorsize"]); $i++) {
			$tmp = split(",", $_POST["colorsize"][$i]);
			$colors[] = $tmp[1];
			$sizes[] = $tmp[0];
		}
		$colors = RemoveEmpty($colors);
		$sizes = RemoveEmpty($sizes);
		
		$colors = array_values(array_unique($colors));
		$sizes = array_values(array_unique($sizes));
		asort($colors);
		asort($sizes);
		
		$_REQUEST["colors"] = implode(",", $colors);
		$_REQUEST["sizes"] = implode(",", $sizes);
		
		$colorSizeValues = array();
		for ($i=0; $i<sizeof($_POST["colorsize"]); $i++) {
			$tmp = split(",", $_POST["colorsize"][$i]);
			$colorSizeValues[trim($tmp[0])][trim($tmp[1])] = 1;
		}		
		
		$record = $_REQUEST;
		$cat = $record["catid"];
	} else {
		if ($_REQUEST["cmd"] == "edit") {
			$sql = "select * from item where itemid = '" . DBQuote($_REQUEST["id"])."' ";
			$record = DBFetchData($sql);
			$cat = $record["catid"];
			
			$sql = "select * from sku where itemid = '" . DBQuote($_REQUEST["id"])."' ";
			$colorsizes = DBFetchAll($sql);

			$colors = split(",", $record["colors"]);
			$sizes = split(",", $record["sizes"]);
			
			$colorSizeValues = array();
			for ($i=0; $i<sizeof($colorsizes); $i++) {
				$tmp = split(",", $colorsizes[$i]);
				$colors[] = $colorsizes[$i]["color"];
				$sizes[] = $colorsizes[$i]["size"];
				$colorSizeValues[$colorsizes[$i]["size"]][$colorsizes[$i]["color"]] = 1;
			}
			$colors = RemoveEmpty($colors);
			$sizes = RemoveEmpty($sizes);
			
			$colors = array_values(array_unique($colors));
			$sizes = array_values(array_unique($sizes));
			asort($colors);
			asort($sizes);
			
			$record["colors"] = implode(",", $colors);
			$record["sizes"] = implode(",", $sizes);
		}
	}

	$sql = " select statusid, statusname from itemstatus order by statusid ";
	$statuslist = DBFetchAll($sql);

	$sql = "select * from cat where catid = '" . DBQuote($cat). "' ";
	$catInfo = DBFetchData($sql);

	$sql = " select catid, catname from cat order by catname ";
	$cats = DBFetchAll($sql);

function AddRec()
{
	global $msg;
	
	DBBeginTrans();

	$sql = "insert into item (itemno, itemname, unitcost, unitprice, sizes, colors, catid, status ";
	$sql .= ") values (";
	$sql .= "'".DBQuote($_POST["itemno"])."'";
	$sql .= ",'".DBQuote($_POST["itemname"])."'";
	$sql .= ",'".DBQuote($_POST["unitcost"])."'";
	$sql .= ",'".DBQuote($_POST["unitprice"])."'";
	$sql .= ",'".DBQuote($_POST["sizes"])."'";
	$sql .= ",'".DBQuote($_POST["colors"])."'";
	$sql .= ",'".DBQuote($_POST["catid"])."'";
	$sql .= ",'".DBQuote($_POST["status"])."'";
	$sql .= ")";

	if (!DBExecute($sql)) {
		DBRollBack();
		$msg .= "Cannot insert item.<br>";
		return false;
	}

	$itemid = DBInsertId();

	if ($itemid > 0) {
		for ($i=1; $i<=MAX_PHOTO; $i++) {
			ProcessUploadedFile($itemid, $i, $_FILES[("file".$i)]);
		}
	}

	for ($i=0; $i<sizeof($_POST["colorsize"]); $i++) {
		$tmp = split(",", $_POST["colorsize"][$i]);
		$color = DBQuote($tmp[1]);
		$size = DBQuote($tmp[0]);
		
		$sql  = "insert into sku (itemid, size, color) ";
		$sql .= "values ('".$itemid."','".$size."','".$color."')";
		
		if (!DBExecute($sql)) {
			DBRollBack();
			$msg .= "Cannot insert SKU<br>";
			return false;
		}
	}

	DBCommit();
	
	return true;
}

function UpdateRec()
{
	global $msg;
	
	DBBeginTrans();

	$sql = "update item set ";
	$sql .= "itemno = '".DBQuote($_POST["itemno"])."'";
	$sql .= ", itemname = '".DBQuote($_POST["itemname"])."'";
	$sql .= ", unitcost = '".DBQuote($_POST["unitcost"])."'";
	$sql .= ", unitprice = '".DBQuote($_POST["unitprice"])."'";
	$sql .= ", sizes = '".DBQuote($_POST["sizes"])."'";
	$sql .= ", colors = '".DBQuote($_POST["colors"])."'";
	$sql .= ", catid = '".DBQuote($_POST["catid"])."'";
	$sql .= ", status = '".DBQuote($_POST["status"])."'";
	$sql .= "where itemid = '".DBQuote($_POST["id"])."'";
	
	if (!DBExecute($sql)) {
		DBRollBack();
		$msg .= "Cannot update item.<br>";
		return false;
	}
	
	$itemid = DBQuote($_POST["id"]);

	if ($itemid > 0) {
		for ($i=1; $i<=MAX_PHOTO; $i++) {
			ProcessUploadedFile($itemid, $i, $_FILES[("file".$i)]);
		}
	}

	$oldSKUs = array();
	$sql = "select * from sku where itemid = '" . DBQuote($_REQUEST["id"])."' ";
	$tmp = DBFetchAll($sql);
	for ($i=0; $i<sizeof($tmp); $i++) {
		$oldSKUs[$tmp[$i]["size"].",".$tmp[$i]["color"]] = $tmp[$i]["skuid"];
	}

	for ($i=0; $i<sizeof($_POST["colorsize"]); $i++) {
		$tmp = split(",", $_POST["colorsize"][$i]);
		$color = DBQuote($tmp[1]);
		$size = DBQuote($tmp[0]);
		
		if (!$oldSKUs[$_POST["colorsize"][$i]]) {
			$sql  = "insert into sku (itemid, size, color) ";
			$sql .= "values ('".$itemid."','".$size."','".$color."')";

			if (!DBExecute($sql)) {
				DBRollBack();
				$msg .= "Cannot insert SKU.<br>";
				return false;
			}
		} else {
			$oldSKUs[$_POST["colorsize"][$i]] *= -1; // mark as deleted
		}
	}

	reset($oldSKUs);
	while(list($cs, $skuid) = each($oldSKUs))
	{
		if ($skuid > 0) {
			$sql  = "select * from inventory where skuid = '".$skuid."' ";
			if (DBFetchData($sql)) {
				DBRollBack();
				$msg = "SKU cannot be deleted, there are some records associated with this SKU.<br>";
				return false;
			}
			
			$sql  = "delete from sku ";
			$sql .= "where skuid = '".$skuid."' ";
			if (!DBExecute($sql)) {
				DBRollBack();
				$msg .= "Cannot delete SKU.<br>";
				return false;
			}
		}
	}
	
	DBCommit();

	return true;
}

function DeleteRec()
{
}
	
function ProcessUploadedFile($itemid, $fileno, $file)
{
	if (!$file["name"]) return;

	$p = pathinfo($file["name"]);	
	$ext = strtolower($p["extension"]);
	
	if ($ext != "jpg" && $ext != "gif" && $ext != "png") {
		echo "Only support jpg, gif or png.<br>";
		return;
	}

	@unlink(ITEM_ICON_PATH.$itemid."-".$fileno.".jpg");
	@unlink(ITEM_ICON_PATH.$itemid."-".$fileno.".gif");
	@unlink(ITEM_ICON_PATH.$itemid."-".$fileno.".png");
	@unlink(ITEM_ICON_PATH.$itemid."-".$fileno."-1.jpg");
	@unlink(ITEM_ICON_PATH.$itemid."-".$fileno."-1.gif");
	@unlink(ITEM_ICON_PATH.$itemid."-".$fileno."-1.png");
	@unlink(ITEM_ICON_PATH.$itemid."-".$fileno."-2.jpg");
	@unlink(ITEM_ICON_PATH.$itemid."-".$fileno."-2.gif");
	@unlink(ITEM_ICON_PATH.$itemid."-".$fileno."-2.png");
	@unlink(ITEM_ICON_PATH.$itemid."-".$fileno."-3.jpg");
	@unlink(ITEM_ICON_PATH.$itemid."-".$fileno."-3.gif");
	@unlink(ITEM_ICON_PATH.$itemid."-".$fileno."-3.png");

	$outfile = ITEM_ICON_PATH.$itemid."-".$fileno.".".$ext;
	move_uploaded_file($file['tmp_name'], $outfile);

	
	$objImage = new ImageUtils();
	$iconfile = ITEM_ICON_PATH.$itemid."-".$fileno."-1.".$ext;
	$objImage->Resize($outfile, 190, "", $iconfile);
	$iconfile = ITEM_ICON_PATH.$itemid."-".$fileno."-2.".$ext;
	$objImage->Resize($outfile, 335, "", $iconfile);
	$iconfile = ITEM_ICON_PATH.$itemid."-".$fileno."-3.".$ext;
	$objImage->Resize($outfile, 30, "", $iconfile);
	
//	resize($outfile, 200, 99999, $iconfile);
}
?>
<? include("header.inc.php");?>
<form name="frm1" method="post" onsubmit="validate(this)" enctype="multipart/form-data">
<input type="hidden" name="postedBack" value="1">
<input type="hidden" name="cmd" value="<?=$_REQUEST["cmd"]?>">
<input type="hidden" name="id" value="<?=$_REQUEST["id"]?>">
<input type="hidden" name="act" value="">

<div class="functionname">Product Detail</div>
<br>

<?=$msg?>

<table border="0" cellspacing="0" cellpadding="2" class="detailtable">
<tr>
	<td>Item No. : </td>
	<td><input type="text" size="20" maxlength="20" name="itemno" value="<?=$record["itemno"]?>"></td>
	<td>Status : </td>
	<td>
		<select name="status">
<? for ($i=0; $i<sizeof($statuslist); $i++) { ?>
			<option value="<?=$statuslist[$i]["statusid"]?>" <?=($record["status"]==$statuslist[$i]["statusid"])?"selected":""?> ><?=$statuslist[$i]["statusname"]?>&nbsp;</option>
<? } ?>
		</select>
	</td>
</tr>
<tr>
	<td>Item Name : </td>
	<td colspan="3"><input type="text" size="80" maxlength="100" name="itemname" value="<?=$record["itemname"]?>"></td>
</tr>
<tr>
	<td>Category : </td>
	<td colspan="3">
		<select name="catid" onchange="loadCat()">
			<option value="0"></option>
<? for ($i=0; $i<sizeof($cats); $i++) { ?>
			<option value="<?=$cats[$i]["catid"]?>" <?=($record["catid"]==$cats[$i]["catid"])?"selected":""?> ><?=$cats[$i]["catname"]?>&nbsp;</option>
<? } ?>
		</select>
	</td>
</tr>
<tr>
	<td>Unit Cost : </td>
	<td><input type="text" size="20" maxlength="20" name="unitcost" value="<?=$record["unitcost"]?>"></td>
	<td>Unit Price : </td>
	<td><input type="text" size="20" maxlength="20" name="unitprice" value="<?=$record["unitprice"]?>"></td>
</tr>
<!--
<tr>
	<td valign="top">Item Photo : </td>
	<td colspan="3">
		<input type="file" name="file1" value="" size="80"><br>
	</td>
</tr>
//-->
<tr>
	<td>Sizes : </td>
	<td colspan="3"><input type="text" size="80" maxlength="250" name="sizes" value="<?=$record["sizes"]?>" title="Use commas(,) to seperate"></td>
</tr>
<tr>
	<td>Colors : </td>
	<td colspan="3"><input type="text" size="80" maxlength="250" name="colors" value="<?=$record["colors"]?>" title="Use commas(,) to seperate"></td>
</tr>
<tr>
	<td colspan="4">Color-Size Details : </td>
</tr>
<tr>
	<td colspan="4">
		<table width="100%" cellspacing="0" border="1" class="colorsizetable">
		<tr>
			<td>(<input type="checkbox" name="chkselectall" onclick="SelectAll(this)">Select All)</td>
			<td colspan="<?=sizeof($sizes)?>"><b>Size</b></td>
		</tr>
		<tr>
			<td><b>Color</b></td>
<? 
	for ($s=0; $s<sizeof($sizes); $s++) { 
		$sizeCode = $sizes[$s];
		if ($sizeCode == "") continue;
?>
			<td><?=$sizeCode?></td>
<? } ?>
		</tr>
<? 
	for ($c=0; $c<sizeof($colors); $c++) { 
		$colorCode = $colors[$c];
		if ($colorCode == "") continue;
?>
		<tr>
			<td><?=$colorCode?></td>
<? 
	for ($s=0; $s<sizeof($sizes); $s++) { 
		$sizeCode = $sizes[$s];
		if ($sizeCode == "") continue;
		
		$checked = ($colorSizeValues[$sizeCode][$colorCode])?"checked":"";		
?>
			<td><input type="checkbox" name="colorsize[]" value="<?=$sizeCode.",".$colorCode?>" <?=$checked?>></td>
<? } ?>
		</tr>
<? } ?>
		</table>
	</td>
</tr>
</table>

<br>

<input type="submit" name="cmdSave" value="Save">
<input type="button" name="cmdRefresh" value="Refresh Color Size" onclick="doRefresh()">
<input type="button" name="cmdCancel" value="Cancel" onclick="doCancel()">
</form>

<script language="javascript">
function validate(f)
{
	return true;
}

function doCancel()
{
	document.location.href="itemlist.php?cat=<?=$cat?>";
}

function doRefresh()
{
	var f = document.frm1;
	f.act.value = "refresh";
	f.submit();
}
function loadCat()
{
	var f = document.frm1;
	f.act.value = "cat";
	f.submit();
}

function SelectAll(chk)
{
	var chks = document.all["colorsize[]"];
	if (chks == undefined) return;
	
	if (chks.length != undefined) {
		for (var i=0; i<chks.length; i++) {
			chks[i].checked = chk.checked;
		}
	} else {
		chks.checked = chk.checked;
	}
}
</script>
<? include("footer.inc.php");?>
