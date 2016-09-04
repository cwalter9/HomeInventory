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
	$output = "";
	
	$id = $_REQUEST["id"];
	$itemno = $_REQUEST["itemno"];

	if ($itemno) {
		$sql = "select * from item where itemno = '" . DBQuote($itemno)."' ";
		$record = DBFetchData($sql);
		$id = $record["itemid"];
	} else {
		$sql = "select * from item where itemid = '" . DBQuote($id)."' ";
		$record = DBFetchData($sql);
	}

	$result["ReturnCode"] = "OK";
	$result["Item"] = $record;
	
	$jsonCode = json_encode($result);
	
	if ($_POST["txt"]) {
		debug(json_decode($_POST["txt"]));
	}
	
/*
	$returnCode = "";
	if (!$record["itemid"]) {
		$returnCode = "NOT FOUND";
	} else if ($record["status"] > 1) {
		$returnCode = $record["status"];
	} else {
		$returnCode = "OK";
	}
	
	if ($returnCode != "OK") {
		echo $returnCode;
		exit;
	}
	
	$seq = $_SESSION[SS]["POSEQ"]++;
	
	$output = $returnCode;
	$output .= "||".$seq;

	if ($cp == 1) {
		$output .= "||".$record["unitprice"];
	} else {
		$output .= "||".$record["unitcost"];
	}

	$cat = $record["catid"];
	
	$sql = "select * from sku where itemid = '" . DBQuote($id)."' ";
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

function RemoveEmpty($arr)
{
	$tmp = array();
	for ($i=0; $i<sizeof($arr); $i++) {
		if ($arr[$i]) $tmp[] = trim($arr[$i]);
	}
	return $tmp;
}
	$output .= "||";
	$output .= "		<table width='100%' cellspacing='0' border='1' class='colorsizetable' id='SKUDetail".$seq."'>";
	$output .= "		<tr>";
	$output .= "			<td>&nbsp;</td>";
	$output .= "			<td colspan='".sizeof($colors)."'><b>Colors</b></td>";
	$output .= "		</tr>";
	$output .= "		<tr>";
	$output .= "			<td><b>Size</b></td>";

	for ($c=0; $c<sizeof($colors); $c++) { 
		$colorCode = $colors[$c];
		if ($colorCode == '') continue;

		$output .= "			<td>".$colorCode."</td>";
	}
	$output .= "		</tr>";

	for ($s=0; $s<sizeof($sizes); $s++) { 
		$sizeCode = $sizes[$s];
		if ($sizeCode == '') continue;

		$output .= "		<tr>";
		$output .= "			<td>".$sizeCode."</td>";

		for ($c=0; $c<sizeof($colors); $c++) { 
			$colorCode = $colors[$c];
			if ($colorCode == '') continue;
			
			$inputName = $seq.'-'.$sizeCode.'-'.$colorCode;

			if ($colorSizeValues[$sizeCode][$colorCode]) {
				$output .= "			<td><input type='textbox' name='".$inputName."' value='' maxlength='3' class='inputQty'></td>";
			} else {
				$output .= "			<td>&nbsp;</td>";
			}
		}
		
		$output .= "		</tr>";
	}
	$output .= "		</table>";

	$output .= "||";
	
	$output .= "		<table width='100%' cellspacing='0' border='1' class='summarytable_current' id='SKURow".$seq."' ";
	$output .= "onmouseover='onMouseOverRow(this)' ";
	$output .= "onmouseout='onMouseOutRow(".$seq.")' ";
	$output .= "onclick='showSKU(".$seq.")' ";
	$output .= ">";
	$output .= "		<tr>";
	$output .= "			<td>Item:<input type='hidden' name='itemid".$seq."' value='".$id."'></td>";
	$output .= "			<td colspan='3'>".$record["itemno"]." ".$record["itemname"]."</td>";
	$output .= "		</tr>";
	$output .= "		<tr>";
	$output .= "			<td>Price:</td>";
	if ($cp == 1) {
		$output .= "			<td><span id='txtunitprice".$seq."'>".$record["unitprice"]."</span><input type='hidden' name='untiprice".$seq."' value='".$record["unitprice"]."'></td>";
	} else {
		$output .= "			<td><span id='txtunitcost".$seq."'>".$record["unitcost"]."</span><input type='hidden' name='unitcost".$seq."' value='".$record["unitcost"]."'></td>";
	}
	$output .= "			<td>Qty:</td>";
	$output .= "			<td><span id='txtqty".$seq."'>0</span><input type='hidden' name='qty".$seq."' value='0'></td>";
	$output .= "		</tr>";
	$output .= "		</table>";

	echo $output;
*/
?>
<?include("header.inc.php")?>
<script language="javascript" src="js/skugrid.js"></script>
<script language="javascript" src="js/json2.js"></script>
<script language="javascript">
function doTest()
{
	var t = document.getElementById("txt");
	var result = JSON.parse(t.value);
	result["ReturnCode"] = "xxxx";
	t.value = JSON.stringify(result);
	document.frm1.submit();
}

var item = new Object();
item.itemno = '0101100';
item.itemname = 'test item';
item.unitprice = 100;
item.qty = 123;
item.Colors = new Array('CBLK', 'CRED', 'CWHT');
item.Sizes = new Array('S01', 'S02', 'S03');
item.SKUIdx = new Object();
item.SKUIdx.S01 = new Object();
item.SKUIdx.S01.CBLK = 1;
item.SKUIdx.S01.CRED = 2;
item.SKUIdx.S01.CWHT = 3;
item.SKUIdx.S02 = new Object();
item.SKUIdx.S02.CRED = 4;
item.SKUIdx.S02.CWHT = 5;
item.SKUIdx.S03 = new Object();
item.SKUIdx.S03.CRED = 6;
item.SKUs = new Array();
item.SKUs[1] = new Object();
item.SKUs[1].qty = 123;
SKUGrid.AddItem(item);

var item = new Object();
item.itemno = '122233';
item.itemname = 'test item';
item.unitprice = 100;
item.qty = 724;
item.Colors = new Array('CBLK', 'CRED', 'CWHT', 'CBLU');
item.Sizes = new Array('S01', 'S02', 'S03');
item.SKUIdx = new Object();
item.SKUIdx.S01 = new Object();
item.SKUIdx.S01.CBLK = 0;
item.SKUIdx.S01.CRED = 2;
item.SKUIdx.S01.CWHT = 3;
item.SKUIdx.S01.CBLU = 3;
item.SKUIdx.S02 = new Object();
item.SKUIdx.S02.CRED = 4;
item.SKUIdx.S02.CWHT = 5;
item.SKUIdx.S03 = new Object();
item.SKUIdx.S03.CRED = 6;
item.SKUs = new Array();
item.SKUs[0] = new Object();
item.SKUs[0].qty = 724;
SKUGrid.AddItem(item);

</script>
<body>
<form method="post" name="frm1">
<textarea id=txt name=txt rows=5 cols=100><?=$jsonCode?></textarea>
</form>
<input type="button" name="cmdtest" value="      Test      " onclick="doTest()">

<script language="javascript">
SKUGrid.Generate();
SKUGrid.MoveNext();

function formKeyUp()
{
	switch (event.keyCode)
	{
		case 27: // ESC
			doCancel();
			break;
		case 38: // Up arrow
			SKUGrid.MovePrevious();
			break;
		case 40: // Down arrow
			SKUGrid.MoveNext();
			break;
		case 113: // F2
			focusItemNo();
			break;
		case 118: // F7
			break;
		case 119: // F8
			break;
		case 120: // F9 Remove
			if (confirm("Are you sure ?")) {
				SKUGrid.RemoveItem();
			}
			break;
		case 123: // F12 Save
			doSave();
			break;
	}
}
document.body.onkeyup= formKeyUp;
</script>

<input type="button" name="SetPrice" value="SetPrice" onclick="SKUGrid.SetPrice(128)">
<input type="button" name="JSON" value="JSON" onclick="Dump(SKUGrid.GetJSONString())">
</body>
<html>