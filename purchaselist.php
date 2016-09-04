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
	
	$sqlw = "";
	if ($_REQUEST["pono"]) {
		$sqlw .= " and pono like '%".DBQuote($_REQUEST["pono"])."%' ";
	}
	if ($_REQUEST["startdate"]) {
		$sqlw .= " and txdate >= '".DBQuote($_REQUEST["startdate"])." 00:00:00' ";
	}
	if ($_REQUEST["enddate"]) {
		$sqlw .= " and txdate <= '".DBQuote($_REQUEST["enddate"])." 23:59:59' ";
	}
	if ($_REQUEST["supplier"]) {
		$sqlw .= " and (".DBKeywordSearch("supplier", $_REQUEST["supplier"]).") ";
	}
	
	
	$sql = " select *";
	$sql .= "from purchaseheader ";
	if ($sqlw) {
		$sql .= "where ".substr($sqlw, 5)." ";
	}	
	$sql .= "order by pono desc ";
	$sql .= "limit 0, 100 ";
		
	$purchases = DBFetchAll($sql);
?>
<? include("header.inc.php");?>

<form name="frm1" method="get">
<div class="functionname">Purchases</div>


<?=$msg?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="80">P.O. No.:</td>
	<td width="50"><input type="text" name="pono" value="<?=$_REQUEST["pono"]?>" size="5"></td>
	<td width="50">Date</td>
	<td width="280">: <input type="text" name="startdate" value="<?=$_REQUEST["startdate"]?>" id="startdate" size="10" maxlength="10">
				<img src="images/calendar_img.gif" border="0" name="btnstartdate" id="btnstartdate"> To 
				<input type="text" name="enddate" value="<?=$_REQUEST["enddate"]?>" id="enddate" size="10" maxlength="10">
				<img src="images/calendar_img.gif" border="0" name="btnenddate" id="btnenddate"> 
	</td>
	<td width="80">Supplier:</td>
	<td width="100"><input type="text" name="supplier" value="<?=$_REQUEST["supplier"]?>" size="10"></td>
	<td width="100"><input type="submit" name="search" value="Search"></td>
	<td align="right"><a href="purchasedetail.php?cmd=add" class="actionlink">add new</a></td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tablestyle">
<col width="20%">
<col width="10%">
<col width="50%">
<col width="10%" align="right">
<col width="10%" align="right">
	<thead>
	<tr>
		<td>P.O. No.</td>
		<td>Date</td>
		<td>Supplier</td>
		<td>Amount</td>
		<td>Qty</td>
	</tr>
	</thead>
<? 
	$totalamount = 0; $totalqty = 0;
	for ($i=0; $i<sizeof($purchases); $i++) { 
		$totalamount += $purchases[$i]["totalamount"];
		$totalqty += $purchases[$i]["totalqty"];
?>
	<tr>
		<td><a href="purchaseview.php?id=<?=$purchases[$i]["purchaseid"]?>"><?=$purchases[$i]["pono"]?></a>&nbsp;</td>
		<td><?=formatDate($purchases[$i]["txdate"])?>&nbsp;</td>
		<td><?=$purchases[$i]["supplier"]?>&nbsp;</td>
		<td><?=FormatNum($purchases[$i]["totalamount"])?>&nbsp;</td>
		<td><?=FormatInt($purchases[$i]["totalqty"])?>&nbsp;</td>
	</tr>
<? } ?>
	<tfoot>
	<tr>
		<td colspan="3" align="right">Total</td>
		<td><?=FormatNum($totalamount)?>&nbsp;</td>
		<td><?=FormatInt($totalqty)?>&nbsp;</td>
	</tr>
	</tfoot>
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

function formKeyUp()
{
	switch (event.keyCode)
	{
		case 113: // F2
			break;
		case 118: // F7
			break;
		case 119: // F8
			break;
		case 120: // F9
			break;
		case 123: // F12
			break;
	}
}
document.body.onkeyup= formKeyUp;
</script>

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

<? include("footer.inc.php");?>
