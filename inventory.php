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
	
	if ($_REQUEST["itemno"] || $_REQUEST["itemname"]) {
		$sqlw = "";
		if ($_REQUEST["itemno"]) {
			$sqlw = " and itemno like '%".DBQuote($_REQUEST["itemno"])."%' ";
		}
		if ($_REQUEST["itemname"]) {
			$sqlw = " and itemname like '%".DBQuote($_REQUEST["itemname"])."%' ";
		}
		$sql  = "select a.itemid, a.itemno, a.itemname, a.unitprice, a.unitcost, ";
		$sql .= "c.statusname, sum(b.qty) as qoh ";
		$sql .= "from item a ";
		$sql .= "  left join inventory b on a.itemid = b.itemid ";
		$sql .= "  left join itemstatus c on a.status = c.statusid ";
		$sql .= "where ".substr($sqlw, 5)." ";
		$sql .= "group by a.itemid, a.itemno, a.itemname, a.unitprice, a.unitcost, c.statusname ";
		$sql .= "order by a.itemno ";
		$sql .= "limit 0, 500 ";
		
		$items = DBFetchAll($sql);

		if (sizeof($items) == 1) {
			$item = $items[0];
			
			$sql  = "select a.*, b.qty ";
			$sql .= "from sku a left join inventory b ";
			$sql .= "  on a.skuid = b.skuid ";
			$sql .= "where a.itemid = '".$item["itemid"]."' ";
			
			$skus = DBFetchAll($sql);
		
			$colors = split(",", $item["colors"]);
			$sizes = split(",", $item["sizes"]);
			
			$SKUQty = array();
			for ($i=0; $i<sizeof($skus); $i++) {
				$sku = $skus[$i];
				$colors[] = $sku["color"];
				$sizes[] = $sku["size"];
				$SKUQty[$sku["size"]][$sku["color"]] = $sku["qty"];
			}
			$colors = RemoveEmpty($colors);
			$sizes = RemoveEmpty($sizes);
		}
	}
	
?>
<? include("header.inc.php"); ?>
<form name="frm1" method="get">
<input type="hidden" name="postedBack" value="1">

<table border="0" cellpadding="2" cellspacing="0" class="detailtable" width="100%">
<tr>
	<td nowrap><div class="functionname">Inventory</div></td>
	<td width="100%" align="right">
	</td>
</tr>
</table>
<table border="0" cellpadding="2" cellspacing="0" class="detailtable" width="100%">
<tr>
	<td>Item No.: </td>
	<td><input type="text" name="itemno" value="<?=$_REQUEST["itemno"]?>"></td>
	<td>Item Name : </td>
	<td><input type="text" name="itemname" value="<?=$_REQUEST["itemname"]?>"></td>
	<td><input type="submit" name="search" value="Search"></td>
</tr>
</table>
</form>

<? if (sizeof($items) > 1) { ?>
<table border="0" cellpadding="2" cellspacing="0" class="detailtable" width="100%">
<col/>
<col/>
<col align="right"/>
<col align="right"/>
<col align="right"/>
<col/>
<tr>
	<td>Item No.</td>
	<td>Item Name</td>
	<td>Unit Price</td>
	<td>Unit Cost</td>
	<td>Qty On Hand</td>
	<td>Status</td>
</tr>
<? for ($i=0; $i<sizeof($items); $i++) { $item = $items[$i];?>
<tr>
	<td><a href="<?=$_SERVER["SCRIPT_NAME"]."?itemno=".$item["itemno"]?>"><?=$item["itemno"]?>&nbsp;</a></td>
	<td><?=$item["itemname"]?>&nbsp;</td>
	<td><?=$item["unitprice"]?>&nbsp;</td>
	<td><?=$item["unitcost"]?>&nbsp;</td>
	<td><?=$item["qoh"]?>&nbsp;</td>
	<td><?=$item["statusname"]?>&nbsp;</td>
</tr>
<? } ?>
</table>

<? } else { ?>
<table border="0" cellpadding="2" cellspacing="0" class="detailtable" width="100%">
<tr>
	<td width="100">Item No. : &nbsp;</td>
	<td colspan="3"><?=$item["itemno"]?>&nbsp;&nbsp;<?=$item["itemname"]?>&nbsp;</td>
</tr>
<tr>
	<td width="100">Status :</td>
	<td><?=$item["statusname"]?>&nbsp;</td>
	<td width="100">Unit Cost :</td>
	<td><?=$item["unitcost"]?>&nbsp;</td>
</tr>
<tr>
	<td>Qty On Hand :</td>
	<td><?=$item["qoh"]?>&nbsp;</td>
	<td>Unit Price :</td>
	<td><?=$item["unitprice"]?>&nbsp;</td>
</tr>
</table>
<br>

<table border="0" cellpadding="2" cellspacing="0" class="skutable" width="100%">
<col align="center"/>
<? for ($s=0; $s<sizeof($sizes); $s++) {?>
<col align="center"/>
<? } ?>
<col align="center"/>
<thead>
<tr>
	<td width="10%">&nbsp;</td>
	<td colspan='<?=(sizeof($sizes)<6)?6:sizeof($sizes)?>'><b>Size</b></td>
	<td width="10%">&nbsp;</td>
</tr>
<tr>
	<td width="10%"><b>Color</b></td>
<? 
	$colwidth = round(80/((sizeof($sizes)<6)?6:sizeof($sizes)));
	for ($s=0; $s<sizeof($sizes)||$s<6; $s++) {
?>
	<td width='<?=$colwidth?>%'><?=$sizes[$s]?>&nbsp;</td>
<? } ?>
	<td width="10%"><b>Total<b></td>
</tr>
</thead>
<? 
	for ($c=0; $c<sizeof($colors); $c++) {
?>
<tr>
	<td class="skutable_size"><?=$colors[$c]?></td>
<? 
	$colorTotal = 0;
	for ($s=0; $s<sizeof($sizes)||$s<6; $s++) {
		$colorTotal += $SKUQty[$sizes[$s]][$colors[$c]];
?>
	<td><?=$SKUQty[$sizes[$s]][$colors[$c]]?>&nbsp;</td>
<? } ?>
	<td class="skutable_total"><?=$colorTotal?>&nbsp;</td>
</tr>
<? } ?>
<tfoot>
<tr>
	<td><b>Total</b></td>
<? 
	$grandTotal = 0;
	for ($s=0; $s<sizeof($sizes)||$s<6; $s++) {
		$sizeTotal = 0;
		for ($c=0; $c<sizeof($colors); $c++) {
			$sizeTotal += $SKUQty[$sizes[$s]][$colors[$c]];
		}
		$grandTotal += $sizeTotal;
?>
	<td><?=$sizeTotal?>&nbsp;</td>
<? } ?>
	<td><?=$grandTotal?>&nbsp;</td>
</tr>
</tfoot>
</table>
<? } ?>

<script language="javascript">
function init()
{
	var f = document.frm1;
	f.itemno.focus();
	f.itemno.select();
}

window.onload = init;
</script>

<? include("footer.inc.php");?>
