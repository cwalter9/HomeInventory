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
	if ($_REQUEST["stockadjno"]) {
		$sqlw .= " and stockadjno like '%".DBQuote($_REQUEST["stockadjno"])."%' ";
	}
	if ($_REQUEST["startdate"]) {
		$sqlw .= " and txdate >= '".DBQuote($_REQUEST["startdate"])." 00:00:00' ";
	}
	if ($_REQUEST["enddate"]) {
		$sqlw .= " and txdate <= '".DBQuote($_REQUEST["enddate"])." 23:59:59' ";
	}
	if ($_REQUEST["adjtype"]) {
		$sqlw .= " and adjtype = '".DBQuote($_REQUEST["adjtype"])."' ";
	}
	if ($_REQUEST["remark"]) {
		$sqlw .= " and (".DBKeywordSearch("remark", $_REQUEST["remark"]).") ";
	}
	
	
	$sql = " select * ";
	$sql .= "from stockadjheader ";
	if ($sqlw) {
		$sql .= "where ".substr($sqlw, 5)." ";
	}	
	$sql .= "order by stockadjno desc ";
	$sql .= "limit 0, 100 ";
		
	$adjs = DBFetchAll($sql);
?>
<? include("header.inc.php");?>

<form name="frm1" method="get">
<div class="functionname">Stock Adjustments</div>


<?=$msg?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="60">Adj. No.:</td>
	<td width="50"><input type="text" name="stockadjno" value="<?=$_REQUEST["stockadjno"]?>" size="5"></td>
	<td width="50">Date</td>
	<td width="280">: <input type="text" name="startdate" value="<?=$_REQUEST["startdate"]?>" id="startdate" size="10" maxlength="10">
				<img src="images/calendar_img.gif" border="0" name="btnstartdate" id="btnstartdate"> To 
				<input type="text" name="enddate" value="<?=$_REQUEST["enddate"]?>" id="enddate" size="10" maxlength="10">
				<img src="images/calendar_img.gif" border="0" name="btnenddate" id="btnenddate"> 
	</td>
	<td width="80">Type:</td>
	<td width="100">
	<select name="adjtype">
		<option value=""></option>
<?
	$sql = "select * from stockadjtypes order by adjtype";
	$tmp = DBFetchAll($sql);
	$adjtypes = array();
	for ($i=0; $i<sizeof($tmp); $i++) {
		$adjtypes[$tmp[$i]["adjtype"]] = $tmp[$i]["adjtypename"];
		echo "<option value='".$tmp[$i]["adjtype"]."'>".$tmp[$i]["adjtypename"]."</option>";
	}
?>		
	</select>
	</td>
	<td width="80">Remark:</td>
	<td width="100"><input type="text" name="supplier" value="<?=$_REQUEST["remark"]?>" size="10"></td>
	<td width="100"><input type="submit" name="search" value="Search"></td>
	<td align="right"><a href="adjustmentdetail.php?cmd=add" class="actionlink">add new</a></td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tablestyle">
<col width="20%">
<col width="10%">
<col width="10%">
<col width="10%" align="right">
<col width="50%">
	<thead>
	<tr>
		<td>Stock Adj. No.</td>
		<td>Date</td>
		<td>Type</td>
		<td>Qty</td>
		<td>Remark</td>
	</tr>
	</thead>
<? 
	$totalqty = 0;
	for ($i=0; $i<sizeof($adjs); $i++) { 
		$totalqty += $adjs[$i]["totalqty"];
?>
	<tr>
		<td><a href="adjustmentview.php?id=<?=$adjs[$i]["stockadjid"]?>"><?=$adjs[$i]["stockadjno"]?></a>&nbsp;</td>
		<td><?=formatDate($adjs[$i]["txdate"])?>&nbsp;</td>
		<td><?=$adjtypes[$adjs[$i]["adjtype"]]?>&nbsp;</td>
		<td><?=FormatInt($adjs[$i]["totalqty"])?>&nbsp;</td>
		<td><?=$adjs[$i]["remark"]?>&nbsp;</td>
	</tr>
<? } ?>
	<tfoot>
	<tr>
		<td colspan="3" align="right">Total</td>
		<td><?=FormatInt($totalqty)?>&nbsp;</td>
		<td>&nbsp;</td>
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
