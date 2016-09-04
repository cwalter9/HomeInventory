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
	
	$sql  = "select * ";
	$sql .= "from stockadjheader ";
	$sql .= "where stockadjid = '".DBQuote($_REQUEST["id"])."' ";
	$header = DBFetchData($sql);

	$sql  = "select a.*, b.size, b.color, c.itemno, c.itemname ";
	$sql .= "from stockadjdetail a, sku b, item c ";
	$sql .= "where a.stockadjid = '".DBQuote($_REQUEST["id"])."' ";
	$sql .= "  and a.skuid = b.skuid and b.itemid = c.itemid ";
	$sql .= "order by a.seq, a.itemid, b.size, b.color ";
	$tmp = DBFetchAll($sql);

	
	$details = array();

	for ($i=0; $i<sizeof($tmp); $i++) {
		$row = $tmp[$i];
		$seq = $row["seq"];
		if (!isset($details[$seq])) {
			$details[$seq]["itemno"] = $row["itemno"];
			$details[$seq]["itemname"] = $row["itemname"];
			$details[$seq]["qty"] = 0;
		}
		$details[$seq]["colors"][] = $row["color"];
		$details[$seq]["sizes"][] = $row["size"];
		$details[$seq]["skus"][$row["size"]][$row["color"]] = $row["qty"];
		$details[$seq]["qty"] += $row["qty"];
	}

	$sql = "select * from stockadjtypes order by adjtype";
	$tmp = DBFetchAll($sql);
	$adjtypes = array();
	for ($i=0; $i<sizeof($tmp); $i++) {
		$adjtypes[$tmp[$i]["adjtype"]] = $tmp[$i]["adjtypename"];
	}
	
?>
<? include("header.inc.php"); ?>
<input type="hidden" name="postedBack" value="1">

<table border="0" cellpadding="2" cellspacing="0" class="detailtable" width="100%">
<tr>
	<td nowrap><div class="functionname">Stock Adjustment Detail</div></td>
	<td width="100%" align="right">
<? if (!$_REQUEST["print"]) { ?>	
		<input type="button" name="cmdprint" value="Print(F7)" onclick="doPrint()">
		<input type="button" name="cmdback" value="Back(ESC)" onclick="history.back()">
<? } ?>
	</td>
</tr>
</table>
<table border="0" cellpadding="2" cellspacing="0" class="detailtable" width="100%">
<tr>
	<td>Adj. No.: <?=$header["stockadjno"]?></td>
	<td>Type : <?=$adjtypes[$header["adjtype"]]?></td>
	<td>Date : <?=formatDate($header["txdate"])?></td>
</tr>
<tr>
	<td colspan="2">Remark : <?=$header["remark"]?></td>
	<td>Total Qty : <?=$header["totalqty"]?></td>
</tr>
</table>
<br>
<?
	for ($i=0; $i<sizeof($details); $i++) {
		$detail = $details[$i];

		$colors = RemoveEmpty($detail["colors"]);
		$sizes = RemoveEmpty($detail["sizes"]);
?>
<table border="0" cellpadding="2" cellspacing="5" class="detailtable" width="100%">
<tr>
	<td>Item : <?=$detail["itemno"]?>&nbsp;&nbsp;<?=$detail["itemname"]?></td>
	<td>Qty : <?=$detail["qty"]?></td>
</tr>
</table>

<table border="0" cellpadding="2" cellspacing="0" class="reportdetailtable" width="100%">
<thead>
<tr>
	<td width="10%">&nbsp;</td>
<? 
	$colwidth = number_format(90/(sizeof($sizes)<6?6:sizeof($sizes)),0)."%";
	for ($s=0; $s<sizeof($sizes)||$s<6; $s++) {
?>
	<td width="<?=$colwidth?>"><?=$sizes[$s]?>&nbsp;</td>
<? } ?>
</tr>
</thead>
<? 
	for ($c=0; $c<sizeof($colors); $c++) {
?>
<tr>
	<td><b><?=$colors[$c]?></b></td>
<? 
	for ($s=0; $s<sizeof($colors)||$s<6; $s++) {
		$qty = $detail["skus"][$sizes[$s]][$colors[$c]];
?>
	<td><?=$qty?>&nbsp;</td>
<? } ?>
</tr>
<? } ?>
</table>
<br>
<? } ?>

<script language="javascript">
<? if (!$_REQUEST["print"]) { ?>	
function onKeyUp()
{
	switch (event.keyCode)
	{
		case 27: // Escape
			history.back();
			break;		
		case 118: // F7
			doPrint();
			break;		
	}
}
document.body.onkeyup= onKeyUp;

function doPrint()
{
	window.open("adjustmentview.php?id=<?=$_REQUEST["id"]?>&print=1");
}
<? } else {?>
window.print();
<? } ?>
</script>

<? include("footer.inc.php");?>
