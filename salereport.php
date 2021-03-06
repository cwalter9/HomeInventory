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
	
	if ($_REQUEST["type"] == 1) {
		// By year
		$sql  = "select distinct year(txdate) as txyear ";
		$sql .= "from salesheader ";
		$sql .= "order by 1";
		$txyears = DBFetchAll($sql);

		if (!$_REQUEST["txyear"]) {
			$_REQUEST["txyear"] = date("Y");
		}
		
		$sql  = "select concat(year(txdate),'-',month(txdate)) as txmonth, ";
		$sql .= "count(*) as pocount, sum(totalqty) as qty, sum(totalamount) as amount ";
		$sql .= "from salesheader ";
		$sql .= "where year(txdate) = '".$_REQUEST["txyear"]."' ";
		$sql .= "group by 1 ";
		$sql .= "order by 1 ";
		$data = DBFetchAll($sql);
		
	} else if ($_REQUEST["type"] == 2) {
		// By date

		if (!$_REQUEST["startdate"] && !$_REQUEST["enddate"] ) {
			$_REQUEST["startdate"] = date("Y-m")."-01";
			$_REQUEST["enddate"] = LastDateOfMonth(date("Y"), date("m"));
		}

		$sql  = "select txdate, ";
		$sql .= "count(*) as pocount, sum(totalqty) as qty, sum(totalamount) as amount ";
		$sql .= "from salesheader ";
		$sql .= "where txdate between '".$_REQUEST["startdate"]."' and '".$_REQUEST["enddate"]."' ";
		$sql .= "group by 1 ";
		$sql .= "order by 1 ";
		$data = DBFetchAll($sql);

	} else if ($_REQUEST["type"] == 3) {
		// By Customer
		// By date
		if (!$_REQUEST["startdate"] && !$_REQUEST["enddate"]) {
			if (!$_REQUEST["customer"] ) {
				$_REQUEST["startdate"] = date("Y-m-d");
				$_REQUEST["enddate"] = date("Y-m-d");
			} else {
				$_REQUEST["startdate"] = date("Y-m")."-01";
				$_REQUEST["enddate"] = LastDateOfMonth(date("Y"), date("m"));
			}
		}
		$sqlw = "";
		if ($_REQUEST["customer"]) {
			$sqlw .= " and customer = '".$_REQUEST["customer"]."' ";
		}
		if ($_REQUEST["startdate"]) {
			$sqlw .= " and txdate >= '".$_REQUEST["startdate"]."' ";
		} 
		if ($_REQUEST["enddate"]) {
			$sqlw .= " and txdate <= '".$_REQUEST["enddate"]."' ";
		}

		$sql  = "select customer, txdate, ";
		$sql .= "count(*) as pocount, sum(totalqty) as qty, sum(totalamount) as amount ";
		$sql .= "from salesheader ";
		if ($sqlw) {
			$sql .= "where ".substr($sqlw, 5)." ";
		}
		$sql .= "group by 1, 2 ";
		$sql .= "order by 1, 2 ";
		$data = DBFetchAll($sql);
	} else if ($_REQUEST["type"] == 4) {
		// By Item
		// By date
		if (!$_REQUEST["startdate"] && !$_REQUEST["enddate"]) {
			if (!$_REQUEST["itemno"] ) {
				$_REQUEST["startdate"] = date("Y-m-d");
				$_REQUEST["enddate"] = date("Y-m-d");
			} else {
				$_REQUEST["startdate"] = date("Y-m")."-01";
				$_REQUEST["enddate"] = LastDateOfMonth(date("Y"), date("m"));
			}
		}
		$sqlw = "";
		if ($_REQUEST["itemno"]) {
			$sqlw .= " and c.itemno = '".$_REQUEST["itemno"]."' ";
		}
		if ($_REQUEST["startdate"]) {
			$sqlw .= " and a.txdate >= '".$_REQUEST["startdate"]."' ";
		} 
		if ($_REQUEST["enddate"]) {
			$sqlw .= " and a.txdate <= '".$_REQUEST["enddate"]."' ";
		}

		$sql  = "select c.itemno, c.itemname, a.txdate, ";
		$sql .= "count(distinct a.salesid) as pocount, sum(b.qty) as qty, sum(b.qty * b.unitprice) as amount ";
		$sql .= "from salesheader a, salesdetail b, item c ";
		$sql .= "where a.salesid = b.salesid ";
		$sql .= "  and b.itemid = c.itemid ";
		if ($sqlw) $sql .= $sqlw;
		$sql .= "group by 1, 2, 3 ";
		$sql .= "order by 1, 2, 3 ";
		$data = DBFetchAll($sql);
	}
	
?>
<? include("header.inc.php"); ?>
<input type="hidden" name="postedBack" value="1">

<table border="0" cellpadding="2" cellspacing="0" class="detailtable" width="100%">
<tr>
	<td nowrap><div class="functionname">Sales Report</div></td>
	<td width="100%" align="right">
<? if (!$_REQUEST["print"]) { ?>	
		<input type="button" name="cmdprint" value="Print(F7)" onclick="doPrint()">
<? } ?>
	</td>
</tr>
</table>

<? if ($_REQUEST["type"] == 1) { // by year ?>
<p class="reportsubtitle">By year</p>

<? if ($_REQUEST["print"]) { ?>
<table border="0" cellpadding="2" cellspacing="0" class="detailtable">
<tr>
	<td>Year : <?=$_REQUEST["txyear"]?></td>
</tr>
</table>
<? } else { ?>
<form name="frm1" method="get">
<input type="hidden" name="type" value="<?=$_REQUEST["type"]?>">
<table border="0" cellpadding="2" cellspacing="0" class="detailtable">
<tr>
	<td>Year : </td>
	<td>
		<select name="txyear" onchange="this.form.submit()">
			<option value="">-- Please Select --</option>
<? for ($i=0; $i<sizeof($txyears); $i++) {?>
			<option value="<?=$txyears[$i]["txyear"]?>" <?=($_REQUEST["txyear"]==$txyears[$i]["txyear"])?"selected":""?> ><?=$txyears[$i]["txyear"]?></option>
<? } ?>
		</select>
	</td>
	<td><input type="submit" name="search" value="Show"></td>
</tr>
</table>
</form>
<? } ?>

<table border="0" cellpadding="5" cellspacing="0" class="reporttable">
<col width='150'/><col width='150' align='right'/><col width='150' align='right'/><col width='150' align='right'/>
<thead>
	<tr>
		<td>Month</td>
		<td>No. of S.O.</td>
		<td>Total Qty</td>
		<td>Total Amount</td>
	</tr>
</thead>
<? 
	$totalcount = 0; $totalqty = 0; $totalamount = 0;
	for ($i=0; $i<sizeof($data); $i++) {
		$totalcount += $data[$i]["pocount"];
		$totalqty += $data[$i]["qty"];
		$totalamount += $data[$i]["amount"];
?>
	<tr>
		<td><a href="<?=$_SERVER["SCRIPT_NAME"]?>?type=2"><?=$data[$i]["txmonth"]?></a></td>
		<td><?=number_format($data[$i]["pocount"], 0, ".", ",")?></td>
		<td><?=number_format($data[$i]["qty"], 2, ".", ",")?></td>
		<td><?=number_format($data[$i]["amount"], 2, ".", ",")?></td>
	</tr>
<? } ?>
<tfoot>
	<tr>
		<td>Total</td>
		<td><?=number_format($totalcount, 0, ".", ",")?></td>
		<td><?=number_format($totalqty, 2, ".", ",")?></td>
		<td><?=number_format($totalamount, 2, ".", ",")?></td>
	</tr>
</tfoot>
</table>

<? } else if ($_REQUEST["type"] == 2) { // by month ?>
<p class="reportsubtitle">By month</p>

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
<col width='150'/><col width='150' align='right'/><col width='150' align='right'/><col width='150' align='right'/>
<thead>
	<tr>
		<td>Date</td>
		<td>No. of S.O.</td>
		<td>Total Qty</td>
		<td>Total Amount</td>
	</tr>
</thead>
<? 
	$totalcount = 0; $totalqty = 0; $totalamount = 0;
	for ($i=0; $i<sizeof($data); $i++) {
		$totalcount += $data[$i]["pocount"];
		$totalqty += $data[$i]["qty"];
		$totalamount += $data[$i]["amount"];
?>
	<tr>
		<td><a href="<?=$_SERVER["SCRIPT_NAME"]?>?type=3&startdate=<?=FormatDate2($data[$i]["txdate"])?>&enddate=<?=FormatDate2($data[$i]["txdate"])?>"><?=FormatDate($data[$i]["txdate"])?></a></td>
		<td><?=number_format($data[$i]["pocount"], 0, ".", ",")?></td>
		<td><?=number_format($data[$i]["qty"], 2, ".", ",")?></td>
		<td><?=number_format($data[$i]["amount"], 2, ".", ",")?></td>
	</tr>
<? } ?>
<tfoot>
	<tr>
		<td>Total</td>
		<td><?=number_format($totalcount, 0, ".", ",")?></td>
		<td><?=number_format($totalqty, 2, ".", ",")?></td>
		<td><?=number_format($totalamount, 2, ".", ",")?></td>
	</tr>
</tfoot>
</table>

<? } else if ($_REQUEST["type"] == 3) { // by customer ?>

<p class="reportsubtitle">By Customer</p>

<? if ($_REQUEST["print"]) { ?>
<table border="0" cellpadding="2" cellspacing="0" class="detailtable">
<tr>
	<td>Customer : <?=$_REQUEST["customer"]?></td>
</tr>
<? if ($_REQUEST["startdate"] || $_REQUEST["enddate"]) { ?>
<tr>
	<td>Date : <?=$_REQUEST["startdate"]?> To <?=$_REQUEST["enddate"]?></td>
</tr>
<? } ?>
</table>
<? } else { ?>
<form name="frm1" method="get">
<input type="hidden" name="type" value="<?=$_REQUEST["type"]?>">
<table border="0" cellpadding="2" cellspacing="0" class="detailtable">
<tr>
	<td>Customer : </td>
	<td>
		<input type="text" name="customer" value="<?=$_REQUEST["customer"]?>" id="customer" size="40">
	</td>
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
<col width='150'/><col width='150'/><col width='150' align='right'/><col width='150' align='right'/><col width='150' align='right'/>
<thead>
	<tr>
		<td>Customer</td>
		<td>Date</td>
		<td>No. of S.O.</td>
		<td>Total Qty</td>
		<td>Total Amount</td>
	</tr>
</thead>
<? 
	$totalcount = 0; $totalqty = 0; $totalamount = 0;
	for ($i=0; $i<sizeof($data); $i++) {
		$totalcount += $data[$i]["pocount"];
		$totalqty += $data[$i]["qty"];
		$totalamount += $data[$i]["amount"];
?>
	<tr>
		<td><a href="<?=$_SERVER["SCRIPT_NAME"]?>?type=3&customer=<?=htmlspecialchars($data[$i]["customer"])?>"><?=htmlspecialchars($data[$i]["customer"])?>&nbsp;</a></td>
		<td><?=FormatDate($data[$i]["txdate"])?></td>
		<td><?=number_format($data[$i]["pocount"], 0, ".", ",")?></td>
		<td><?=number_format($data[$i]["qty"], 2, ".", ",")?></td>
		<td><?=number_format($data[$i]["amount"], 2, ".", ",")?></td>
	</tr>
<? } ?>
<tfoot>
	<tr>
		<td>&nbsp;</td>
		<td>Total</td>
		<td><?=number_format($totalcount, 0, ".", ",")?></td>
		<td><?=number_format($totalqty, 2, ".", ",")?></td>
		<td><?=number_format($totalamount, 2, ".", ",")?></td>
	</tr>
</tfoot>
</table>

<? } else if ($_REQUEST["type"] == 4) { // by item ?>

<p class="reportsubtitle">By Item</p>

<? if ($_REQUEST["print"]) { ?>
<table border="0" cellpadding="2" cellspacing="0" class="detailtable">
<tr>
	<td>Item No.: <?=$_REQUEST["itemno"]?></td>
</tr>
<? if ($_REQUEST["startdate"] || $_REQUEST["enddate"]) { ?>
<tr>
	<td>Date : <?=$_REQUEST["startdate"]?> To <?=$_REQUEST["enddate"]?></td>
</tr>
<? } ?>
</table>
<? } else { ?>
<form name="frm1" method="get">
<input type="hidden" name="type" value="<?=$_REQUEST["type"]?>">
<table border="0" cellpadding="2" cellspacing="0" class="detailtable">
<tr>
	<td>Item No.: </td>
	<td>
		<input type="text" name="itemno" value="<?=$_REQUEST["itemno"]?>" id="itemno" size="40">
	</td>
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
<col width='150'/><col width='300'/><col width='100'/>
<col width='100' align='right'/><col width='100' align='right'/><col width='100' align='right'/>
<thead>
	<tr>
		<td>Item No.</td>
		<td>Item Name</td>
		<td>Date</td>
		<td>No. of S.O.</td>
		<td>Total Qty</td>
		<td>Total Amount</td>
	</tr>
</thead>
<? 
	$totalcount = 0; $totalqty = 0; $totalamount = 0;
	for ($i=0; $i<sizeof($data); $i++) {
		$totalcount += $data[$i]["pocount"];
		$totalqty += $data[$i]["qty"];
		$totalamount += $data[$i]["amount"];
?>
	<tr>
		<td><?=htmlspecialchars($data[$i]["itemno"])?></td>
		<td><?=htmlspecialchars($data[$i]["itemname"])?></td>
		<td><?=FormatDate($data[$i]["txdate"])?></td>
		<td><?=number_format($data[$i]["pocount"], 0, ".", ",")?></td>
		<td><?=number_format($data[$i]["qty"], 2, ".", ",")?></td>
		<td><?=number_format($data[$i]["amount"], 2, ".", ",")?></td>
	</tr>
<? } ?>
<tfoot>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>Total</td>
		<td><?=number_format($totalcount, 0, ".", ",")?></td>
		<td><?=number_format($totalqty, 2, ".", ",")?></td>
		<td><?=number_format($totalamount, 2, ".", ",")?></td>
	</tr>
</tfoot>
</table>

<? } else { ?>

<br>
<table align="center">
<tr>
	<td><a href="<?=$_SERVER["SCRIPT_NAME"]?>?type=1">By Year</a></td>
</tr>
<tr>
	<td><a href="<?=$_SERVER["SCRIPT_NAME"]?>?type=2">By Month</a></td>
</tr>
<tr>
	<td><a href="<?=$_SERVER["SCRIPT_NAME"]?>?type=3">By Customer</a></td>
</tr>
<tr>
	<td><a href="<?=$_SERVER["SCRIPT_NAME"]?>?type=4">By Item</a></td>
</tr>
<tr>
	<td><a href="profitreport.php">Profit Report</a></td>
</tr>
</table>

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
	window.open("<?=$_SERVER["SCRIPT_NAME"]?>?<?=GetQueryString(array("print"=>1))?>");
}
<? } else {?>
//window.print();
<? } ?>
</script>

<? if (!$_REQUEST["print"] && ($_REQUEST["type"] == 2 || $_REQUEST["type"] == 3 || $_REQUEST["type"] == 4)) { ?>
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
