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

	$record = array();
	$record["txdate"] = date("ymd");
?>
<? include("header.inc.php");?>
<script language="javascript" src="js/skugrid.js.php"></script>
<script language="javascript" src="js/json2.js"></script>
<script type="text/javascript" src="js/tw-sack.js"></script>
<script language="javascript" src="saledetail.js.php"></script>

<form name="frm1" id="frm1" method="post" enctype="multipart/form-data">
<input type="hidden" name="cmd" value="<?=$_REQUEST["cmd"]?>">
<input type="hidden" name="id" value="<?=$_REQUEST["id"]?>">

<table border="0" cellpadding="2" cellspacing="0" class="detailtable" width="100%">
<tr>
	<td nowrap><div class="functionname">Sale Detail</div></td>
	<td width="100%" align="right">
		<input type="button" name="cmdDel" value="Remove Item(F9)" onclick="delItem()">
		&nbsp;&nbsp;
		<input type="button" name="cmdSave" value="Save(F12)" onclick="doSave()">
		<input type="button" name="cmdCancel" value="Close(ESC)" onclick="doCancel()">
	</td>
</tr>
</table>
<table border="0" cellpadding="2" cellspacing="0" class="detailtable" width="100%">
<tr>
	<td>S.O. No.: </td>
	<td><input type="text" size="20" maxlength="20" name="sono" value="<?=$record["sono"]?>" class="readonly" readonly></td>
	<td>Date : </td>
		<td><input type="text" name="txdate" value="<?=$record["txdate"]?>" id="txdate" size="6" maxlength="6" onkeypress="CheckNumKey(this)">
					<img src="images/calendar_img.gif" border="0" name="btntxdate" id="btntxdate"></td>
</tr>
<tr>
	<td>Customer : </td>
	<td colspan="3"><input type="text" size="80" maxlength="100" name="customer" value="<?=$record["customer"]?>"></td>
</tr>
<tr>
	<td>Total Amount : </td>
	<td><input type="text" size="10" class="readonly" readonly maxlength="10" name="totalamount" value="<?=$record["totalamount"]?>"></td>
	<td>Total Qty : </td>
	<td><input type="text" size="10" class="readonly" readonly maxlength="10" name="totalqty" value="<?=$record["totalqty"]?>"></td>
</tr>
<tr>
	<td>Item No. (F2): </td>
	<td><input type="text" size="20" maxlength="20" name="itemno" value="<?=$_REQUEST["itemno"]?>" onkeypress="onItemKeyPress()" onkeyup="onItemKeyUp()">
		<input type="button" name="cmditemno" value="Add(Enter)" onclick="addItem()">
		<input type="button" name="cmdclearitemno" value="Clear" onclick="clearLastItem()">
		<span id="ActualItemNoLabel"></span>
		<input type="hidden" name="actualitemno" value="">
	</td>
	<td>Unit Pirce: </td>
	<td><input type="text" size="10" maxlength="10" name="unitprice" value="" onkeyup="setUnitPrice()"></td>
</tr>
</table>
</form>

<script type="text/javascript">
SKUGrid.Generate();
</script>
<style type="text/css">@import url(./jscalendar-1.0/calendar-win2k-1.css);</style>
<script type="text/javascript" src="./jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="./jscalendar-1.0/lang/calendar-en.js"></script>
<script type="text/javascript" src="./jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript">
	Calendar.setup({
		inputField	:	"txdate",
		ifFormat	:	"%y%m%d",
		button		:	"btntxdate"
	});
</script>
<? include("footer.inc.php");?>
