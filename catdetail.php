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
		if ($_REQUEST["act"] == "") {
			if ($_REQUEST["cmd"] == "add") {
				$sql = "insert into cat (pcatid, catcode, catname, description ";
				$sql .= ") values (";
				$sql .= "'".DBQuote($_POST["pcatid"])."'";
				$sql .= ",'".DBQuote($_POST["catcode"])."'";
				$sql .= ",'".DBQuote($_POST["catname"])."'";
				$sql .= ",'".DBQuote($_POST["description"])."'";
				$sql .= ")";
				if (DBExecute($sql)) {
					$catid = DBInsertId();
	
					if ($catid > 0) {
						if ($_FILES['iconphoto']['name']) {
							$p = pathinfo($_FILES['iconphoto']['name']);
							move_uploaded_file($_FILES['iconphoto']['tmp_name'], CAT_ICON_PATH.$catid.".".strtolower($p["extension"]));
						}
						if ($_FILES['bannerphoto']['name']) {
							$p = pathinfo($_FILES['bannerphoto']['name']);
							move_uploaded_file($_FILES['bannerphoto']['tmp_name'], CAT_BANNER_PATH.$catid.".".strtolower($p["extension"]));
						}
					}
									
					redirect("catlist.php");
					exit;
				}
			} else if ($_REQUEST["cmd"] == "edit") {
				$sql = "update cat set ";
				$sql .= "pcatid = '".DBQuote($_POST["pcatid"])."'";
				$sql .= ", catcode = '".DBQuote($_POST["catcode"])."'";
				$sql .= ", catname = '".DBQuote($_POST["catname"])."'";
				$sql .= ", description = '".DBQuote($_POST["description"])."'";
				$sql .= " where catid = '".DBQuote($_POST["id"])."'";
				
				
				if (DBExecute($sql)) {
					$catid = $_POST["id"];
	
					if ($_FILES['iconphoto']['name']) {
						@unlink(CAT_ICON_PATH.$catid.".jpg");
						@unlink(CAT_ICON_PATH.$catid.".gif");
						@unlink(CAT_ICON_PATH.$catid.".png");
		
						$p = pathinfo($_FILES['iconphoto']['name']);
						move_uploaded_file($_FILES['iconphoto']['tmp_name'], CAT_ICON_PATH.$catid.".".strtolower($p["extension"]));
					}
	
					if ($_FILES['bannerphoto']['name']) {
						@unlink(CAT_BANNER_PATH.$catid.".jpg");
						@unlink(CAT_BANNER_PATH.$catid.".gif");
						@unlink(CAT_BANNER_PATH.$catid.".png");
		
						$p = pathinfo($_FILES['bannerphoto']['name']);
						move_uploaded_file($_FILES['bannerphoto']['tmp_name'], CAT_BANNER_PATH.$catid.".".strtolower($p["extension"]));
					}								
					redirect("catlist.php");
					exit;
				}
			}
		} else {
			if ($_REQUEST["act"] == "cat") {
				$sql = "select * from cat where catid = '" . DBQuote($_REQUEST["pcatid"])."' ";
				$parent = DBFetchData($sql);
			}
		}
		$record = $_REQUEST;
	} else {
		if ($_REQUEST["cmd"] == "edit") {
			$sql = "select * from cat where catid = '" . DBQuote($_REQUEST["id"])."' ";
			$record = DBFetchData($sql);
		}
	}

	$sql = " select catid, catname from cat order by catcode ";
	$cats = DBFetchAll($sql);

function DataType($name, $type)
{
	$arr = array("", "Text", "Date", "Numeric", "Yes/No");

	$html  = "<select name='".$name."'>\n";
	for ($i=0; $i<sizeof($arr); $i++) {
		$html .= "<option value='$i' ";
		if ($type == $i) $html .= "selected";
		$html .= ">".$arr[$i]."</option>\n";
	}
	$html .= "</select>\n";
	return $html;
}

?>
<? include("header.inc.php");?>
<form name="frm1" method="post" onsubmit="validate(this)" enctype="multipart/form-data">
<input type="hidden" name="postedBack" value="1">
<input type="hidden" name="cmd" value="<?=$_REQUEST["cmd"]?>">
<input type="hidden" name="id" value="<?=$_REQUEST["id"]?>">
<input type="hidden" name="act" value="">

<div class="functionname">Category Detail</div>
<br>
<table border="0" cellpadding="2" cellspacing="0" class="detailtable">
<tr>
	<td>Category Code : </td>
	<td><input type="text" size="5" maxlength="5" name="catcode" value="<?=$record["catcode"]?>"></td>
</tr>
<tr>
	<td>Category Name : </td>
	<td><input type="text" size="50" maxlength="80" name="catname" value="<?=$record["catname"]?>"></td>
</tr>
<tr>
	<td>Description : </td>
	<td><input type="text" size="50" maxlength="80" name="description" value="<?=$record["description"]?>"></td>
</tr>
<tr>
	<td>Parent Category : </td>
	<td>
		<select name="pcatid" onchange="loadCat()">
			<option value="0"></option>
<? for ($i=0; $i<sizeof($cats); $i++) { ?>
			<option value="<?=$cats[$i]["catid"]?>" <?=($record["pcatid"]==$cats[$i]["catid"])?"selected":""?> ><?=$cats[$i]["catname"]?>&nbsp;</option>
<? } ?>
		</select>
	</td>
</tr>
<!--
<tr>
	<td>Icon Photo</td>
	<td><input type="file" size="50" name="iconphoto" value=""></td>
</tr>
-->
</table>

<br>

<input type="submit" name="cmdSave" value="Save">
<input type="button" name="cmdCancel" value="Cancel" onclick="doCancel()">
</form>

<script language="javascript">
function validate(f)
{
	return true;
}

function doCancel()
{
	document.location.href="catlist.php";
}

function loadCat()
{
	var f = document.frm1;
	f.act.value = "cat";
	f.submit();
}
</script>
<? include("footer.inc.php");?>
