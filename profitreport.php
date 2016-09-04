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
	
	// By date

	if (!$_REQUEST["startdate"] && !$_REQUEST["enddate"] ) {
		$_REQUEST["startdate"] = date("Y-m")."-01";
		$_REQUEST["enddate"] = LastDateOfMonth(date("Y"), date("m"));
	}

	$sql  = "select c.itemno, c.itemname, c.unitcost, ";
	$sql .= "sum(b.qty) as qty, sum(b.unitprice*b.qty)/sum(b.qty) as unitprice, sum(b.unitprice*b.qty) as amountatprice, ";
	$sql .= "sum(c.unitcost*b.qty) as amountatcost ";
	$sql .= "from salesheader a, salesdetail b, item c ";
	$sql .= "where a.txdate between '".$_REQUEST["startdate"]."' and '".$_REQUEST["enddate"]."' ";
	$sql .= "  and a.salesid = b.salesid ";
	$sql .= "  and b.itemid = c.itemid ";
	$sql .= "group by 1,2,3 ";
	$sql .= "order by 1,2,3 ";
	$data = DBFetchAll($sql);

?>
<? include("header.inc.php"); ?>
<input type="hidden" name="postedBack" value="1">

<table border="0" cellpadding="2" cellspacing="0" class="detailtable" width="100%">
<tr>
	<td nowrap><div class="functionname">Profit Report</div></td>
	<td width="100%" align="right">
<? if (!$_REQUEST["print"]) { ?>	
		<input type="button" name="cmdprint" value="Print(F7)" onclick="doPrint()">
<? } ?>
	</td>
</tr>
</table>

<? if ($_REQUEST["print"]) { ?>
<table border="0" cellpadding="2" cellspacing="0" class="detailtable">
<tr>
	<td>Date : <?=$_REQUEST["startdate"]?> To <?=$_REQUEST["enddate"]?></td>
</tr>
</table>
<? } else { ?>
<form name="frm1" method="get">
<input type="hidden" name="type" value="<?=$_REQUEST["type"]?>">
<table border="0" cellpadding="2" cellspacing="0" class="detailtable">
<tr>
	<td>Date : </td>
	<td>
		<input type="text" name="startdate" value="<?=$_REQUEST["startdate"]?>" id="startdate" size="10" maxlength="10">
		<img src="images/calendar_img.gif" border="0" name="btnstartdate" id="btnstartdate"> To 
		<input type="text" name="enddate" value="<?=$_REQUEST["enddate"]?>" id="enddate" size="10" maxlength="10">
		<img src="images/calendar_img.gif" border="0" name="btnenddate" id="btnenddate"> 
	</td>
	<td><input type="submit" name="search" value="Show"></td>
</tr>
</table>
</form>
<? } ?>

<table border="0" cellpadding="5" cellspacing="0" class="reporttable">
<col width='100'/><col width='200'/><col width='100' align='right'/><col width='100' align='right'/>
<col width='100' align='right'/><col width='100' align='right'/><col width='100' align='right'/><col width='100' align='right'/>
<col width='50' align='right'/>
<thead>
	<tr>
		<td>Item No.</td>
		<td>Item Name</td>
		<td>Sales Qty</td>
		<td>Unit Cost</td>
		<td>Amount at Cost</td>
		<td>Average Unit Price</td>
		<td>Amount at Price</td>
		<td>Gross Profit</td>
		<td>Gross Profit %</td>
	</tr>
</thead>
<? 
	$totalcount = 0; $totalqty = 0; $totalamount = 0;
	for ($i=0; $i<sizeof($data); $i++) {
		$totalqty += $data[$i]["qty"];
		$totalcostamount += $data[$i]["amountatcost"];
		$totalpriceamount += $data[$i]["amountatprice"];
?>
	<tr>
		<td><?=$data[$i]["itemno"]?></td>
		<td><?=$data[$i]["itemname"]?></td>
		<td><?=number_format($data[$i]["qty"], 0, ".", ",")?></td>
		<td><?=number_format($data[$i]["unitcost"], 2, ".", ",")?></td>
		<td><?=number_format($data[$i]["amountatcost"], 2, ".", ",")?></td>
		<td><?=number_format($data[$i]["unitprice"], 2, ".", ",")?></td>
		<td><?=number_format($data[$i]["amountatprice"], 2, ".", ",")?></td>
		<td><?=number_format($data[$i]["amountatprice"]-$data[$i]["amountatcost"], 2, ".", ",")?></td>
<? if ($data[$i]["amountatcost"] != 0) { ?>
		<td><?=number_format(($data[$i]["amountatprice"]-$data[$i]["amountatcost"])/$data[$i]["amountatcost"]*100, 2, ".", ",")?></td>
<? } else { ?>
		<td><?=number_format(0, 2, ".", ",")?></td>
<? } ?>
	</tr>
<? } ?>
<tfoot>
	<tr>
		<td>&nbsp;</td>
		<td align="right">Total</td>
		<td><?=number_format($totalqty, 0, ".", ",")?></td>
		<td>&nbsp;</td>
		<td><?=number_format($totalcostamount, 2, ".", ",")?></td>
		<td>&nbsp;</td>
		<td><?=number_format($totalpriceamount, 2, ".", ",")?></td>
		<td><?=number_format($totalpriceamount-$totalcostamount, 2, ".", ",")?></td>
<? if ($totalcostamount != 0) { ?>
		<td><?=number_format(($totalpriceamount-$totalcostamount)/$totalcostamount*100, 2, ".", ",")?></td>
<? } else { ?>
		<td><?=number_format(0, 2, ".", ",")?></td>
<? } ?>
	</tr>
</tfoot>
</table>

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
	window.open("<?=$_SERVER["SCRIPT_NAME"]?>?<?=GetQueryString(array("print"=>1))?>");
}
<? } else {?>
//window.print();
<? } ?>
</script>

<? if (!$_REQUEST["print"]) { ?>
<style type="text/css">@import url(./jscalendar-1.0/calendar-win2k-1.css);</style>
<script type="text/javascript" src="./jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="./jscalendar-1.0/lang/calendar-en.js"></script>
<script type="text/javascript" src="./jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript">
	Calendar.setup({
		inputField	:	"startdate",
		ifFormat	:	"%Y-%m-%d",
		button		:	"btnstartdate"
	});
	Calendar.setup({
		inputField	:	"enddate",
		ifFormat	:	"%Y-%m-%d",
		button		:	"btnenddate"
	});
</script>
<? } ?>

<? include("footer.inc.php");?>
