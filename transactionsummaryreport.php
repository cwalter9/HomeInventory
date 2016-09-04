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
	if ($_REQUEST["catid"]) {
		$sqlw .= " and b.catid = '".$_REQUEST["catid"]."' ";
		$sqlw2 .= " and b.catid = '".$_REQUEST["catid"]."' ";
	}
	if ($_REQUEST["itemno"]) {
		$sqlw .= " and b.itemno like '".$_REQUEST["itemno"]."%' ";
		$sqlw2 .= " and b.itemno like '".$_REQUEST["itemno"]."%' ";
	} 
	if ($_REQUEST["itemname"]) {
		$sqlw .= " and b.itemname like '%".$_REQUEST["itemname"]."%' ";
		$sqlw2 .= " and b.itemname like '%".$_REQUEST["itemname"]."%' ";
	} 
	if ($_REQUEST["enddate"]) {
		$sqlw .= " and txdate <= '".$_REQUEST["enddate"]."' ";
	}
	if ($_REQUEST["startdate"]) {
		$sqlw .= " and txdate >= '".$_REQUEST["startdate"]."' ";
	} 

	// Get Opening
	$sql = "delete from tmp_closing where userid = '".CurrentUserId()."' ";
	if (!DBExecute($sql)) {
		$msg .= DBGetError()."<br>";
	}
	$sql = "delete from tmp_transactions where userid = '".CurrentUserId()."' ";
	if (!DBExecute($sql)) {
		$msg .= DBGetError()."<br>";
	}
	
	$sd = $_REQUEST["startdate"];
	if (!$sd) {
		$sql = "select min(txdate) as txdate from transactions ";
		$tmp = DBFetchData($sql);
		$sd = formatDate2($tmp["txdate"]);
	} 

	$sql  = "insert into tmp_closing (userid, skuid, catid, itemid, itemno, itemname, lasttxid) ";
	$sql .= "select '".CurrentUserId()."', a.skuid, b.catid, b.itemid, b.itemno, b.itemname, max(c.txid) ";
	$sql .= "from item b join sku a on a.itemid = b.itemid ";
	$sql .= "  join transactions c on a.skuid = c.skuid ";
	$sql .= "    and c.txdate <= '".$sd."' ";
	if ($sqlw2) $sql .= "where ".substr($sqlw2, 5);
	$sql .= "group by 1,2,3,4,5,6";
	if (!DBExecute($sql)) {
		$msg .= DBGetError()."<br>";
	}

	$sql  = "insert into tmp_transactions (userid, itemid, skuid, catid, itemno, itemname, txdate,";
	$sql .= "openqty, purchaseqty, salesqty, adjqty) ";
	$sql .= "select '".CurrentUserId()."', a.itemid, a.skuid, b.catid, b.itemno, b.itemname, a.txdate, ";
	$sql .= "a.openqty, a.purchaseqty, a.salesqty, a.adjqty ";
	$sql .= "from transactions a inner join item b on a.itemid = b.itemid ";
	$sql .= "  inner join sku c on a.skuid = c.skuid ";
	if ($sqlw) {
		$sql .= "where ".substr($sqlw, 5);
	}
	DBExecute($sql);

	$sql  = "select a.itemid, a.skuid, c.catid, c.itemno, c.itemname, b.txdate, ";
	$sql .= "b.openqty+b.purchaseqty-b.salesqty+b.adjqty as closing ";
	$sql .= "from tmp_closing a, transactions b, item c ";
	$sql .= "where a.lasttxid = b.txid ";
	$sql .= "  and a.userid = '".CurrentUserId()."' ";
	$sql .= "  and b.itemid = c.itemid ";
	$sql .= "  and not exists ";
	$sql .= "(select * from tmp_transactions c where b.skuid = c.skuid and b.txdate = c.txdate) ";
	$closing = DBFetchAll($sql);

	for ($i=0; $i<sizeof($closing); $i++) {
		$skuid = $closing[$i]["skuid"];
		$d = $closing[$i]["txdate"];

		$sql  = "insert into tmp_transactions (";
		$sql .= "userid, itemid, skuid, itemno, itemname, catid, txdate, ";
		$sql .= "openqty, purchaseqty, salesqty, adjqty) values (";
		$sql .= "'".CurrentUserId()."',";
		$sql .= "'".$closing[$i]["itemid"]."',";
		$sql .= "'".$closing[$i]["skuid"]."',";
		$sql .= "'".$closing[$i]["itemno"]."',";
		$sql .= "'".$closing[$i]["itemname"]."',";
		$sql .= "'".$closing[$i]["catid"]."',";
		$sql .= "'".$sd."',";
		$sql .= "'".$closing[$i]["closing"]."',";
		$sql .= "0,0,0)";
		DBExecute($sql);
	}
	
	// Sum to Item Level
	$sql  = "select catid, itemid, itemno, itemname, txdate, ";
	$sql .= "sum(openqty) as openqty, sum(purchaseqty) as purchaseqty, ";
	$sql .= "sum(salesqty) as salesqty, sum(adjqty) as adjqty ";
	$sql .= "from tmp_transactions ";
	$sql .= "where userid = '".CurrentUserId()."' ";
	$sql .= "group by 1,2,3,4,5 ";
	$sql .= "order by 1,2,3,4,5 ";
	$tmp = DBFetchAll($sql);

	$closing = 0; $lastItemId = 0;
	$tmp2 = array();
	$data = array();
	for ($i=0; $i<sizeof($tmp); $i++) {
		$itemid = $tmp[$i]["itemid"];
		if ($lastItemId == $itemid) {
			$tmp[$i]["openqty"] = $closing;
		}
		
		if (!isset($tmp2[$itemid])) {
			$tmp2[$itemid]["catid"] = $tmp[$i]["catid"];
			$tmp2[$itemid]["itemid"] = $tmp[$i]["itemid"];
			$tmp2[$itemid]["itemno"] = $tmp[$i]["itemno"];
			$tmp2[$itemid]["itemname"] = $tmp[$i]["itemname"];
			$tmp2[$itemid]["openqty"] = $tmp[$i]["openqty"];
		}
		$tmp2[$itemid]["purchaseqty"] += $tmp[$i]["purchaseqty"];
		$tmp2[$itemid]["salesqty"] += $tmp[$i]["salesqty"];
		$tmp2[$itemid]["adjqty"] += $tmp[$i]["adjqty"];
		
		$closing = $tmp[$i]["openqty"] + $tmp[$i]["purchaseqty"] - $tmp[$i]["salesqty"] + $tmp[$i]["adjqty"];
		$lastItemId = $itemid;
	}
	while(list($itemid, $tmp)=each($tmp2))
	{
		if ($tmp["purchaseqty"]!=0 || $tmp["salesqty"]!=0 || $tmp["adjqty"]!=0 || $_REQUEST["inactive"]) {
			$data[] = $tmp;
		}
	}

	$sql = "select * from cat order by catid";
	$allcats = DBFetchAll($sql);
	for ($i=0; $i<sizeof($allcats); $i++) {
		$catnames[$allcats[$i]["catid"]] = $allcats[$i]["catname"];
	}
?>
<? include("header.inc.php"); ?>
<table border="0" cellpadding="2" cellspacing="0" class="detailtable" width="100%">
<tr>
	<td nowrap><div class="functionname">Transaction Report</div></td>
	<td width="100%" align="right">
<? if (!$_REQUEST["print"]) { ?>	
		<input type="button" name="cmdprint" value="Print(F7)" onclick="doPrint()">
<? } ?>
	</td>
</tr>
</table>
<p class="reportsubtitle">Summary</p>

<? if ($_REQUEST["print"]) { ?>
<table border="0" cellpadding="2" cellspacing="0" class="detailtable">
<tr>
	<td width="300">Category : <?=$catnames[$_REQUEST["catid"]]?></td>
	<td width="300">Date : <?=$_REQUEST["startdate"]?> To <?=$_REQUEST["enddate"]?></td>
</tr>
<tr>
	<td>Item No. : <?=$_REQUEST["itemno"]?></td>
	<td>Item Name : <?=$_REQUEST["itemname"]?></td>
</tr>
</table>
<? } else { ?>
<form name="frm1" method="get">
<table border="0" cellpadding="2" cellspacing="0" class="detailtable">
<tr>
	<td>Category : </td>
	<td>
		<select name="catid">
			<option value=""></option>
<? for ($i=0; $i<sizeof($allcats); $i++) { ?>
			<option value="<?=$allcats[$i]["catid"]?>" <?=($allcats[$i]["catid"]==$_REQUEST["catid"])?"selected":""?>><?=$allcats[$i]["catname"]?></option>
<? } ?>
		</select>
	</td>
	<td>Item No. : </td>
	<td>
		<input type="text" name="itemno" value="<?=$_REQUEST["itemno"]?>" id="itemno" size="15">
	</td>
	<td>Item Name : </td>
	<td>
		<input type="text" name="itemname" value="<?=$_REQUEST["itemname"]?>" id="itemname" size="15">
	</td>
</tr>
<tr>
	<td>Date: </td>
	<td colspan="3">
		<input type="text" name="startdate" value="<?=$_REQUEST["startdate"]?>" id="startdate" size="10" maxlength="10">
		<img src="images/calendar_img.gif" border="0" name="btnstartdate" id="btnstartdate"> To 
		<input type="text" name="enddate" value="<?=$_REQUEST["enddate"]?>" id="enddate" size="10" maxlength="10">
		<img src="images/calendar_img.gif" border="0" name="btnenddate" id="btnenddate"> 
	</td>
	<td colspan="2">
			<input type="checkbox" name="inactive" value="1" id="inactive" <?=($_REQUEST["inactive"])?"checked":""?>>Include Inactive Item 
	</td>
	<td><input type="submit" name="search" value="Show"></td>
</tr>
</table>
</form>
<? } ?>


<table border="0" cellpadding="5" cellspacing="0" class="reporttable">
<col width='100'/><col width='100'/><col width='200'/>
<col width='100' align='right'/><col width='100' align='right'/><col width='100' align='right'/>
<col width='100' align='right'/><col width='100' align='right'/>
<thead>
	<tr>
		<td>Category</td>
		<td>Item No.</td>
		<td>Item Name</td>
		<td>Opening Qty</td>
		<td>Purchase Qty</td>
		<td>Sales Qty</td>
		<td>Adjustment Qty</td>
		<td>Closing Qty</td>
	</tr>
</thead>
<? 
	$totalopenqty = 0; 
	$totalpurchaseqty = 0; 
	$totalsalesqty = 0; 
	$totaladjqty = 0; 
	$totalclosingqty = 0; 

	$lastItemId = 0;
	for ($i=0; $i<sizeof($data); $i++) {
		$totalopenqty += $data[$i]["openqty"];
		$totalpurchaseqty += $data[$i]["purchaseqty"];
		$totalsalesqty += $data[$i]["salesqty"];
		$totaladjqty += $data[$i]["adjqty"];

		$closingqty = $data[$i]["openqty"] + $data[$i]["purchaseqty"] - $data[$i]["salesqty"] + $data[$i]["adjqty"];
		$totalclosingqty += $closingqty;

		$lastItemId = $data[$i]["itemid"];
?>
	<tr>
		<td><?=htmlspecialchars($catnames[$data[$i]["catid"]])?>&nbsp;</td>
		<td><?=htmlspecialchars($data[$i]["itemno"])?>&nbsp;</td>
		<td><?=htmlspecialchars($data[$i]["itemname"])?>&nbsp;</td>
		<td <?=$openstyle?>><?=FormatInt($data[$i]["openqty"])?>&nbsp;</td>
		<td><?=FormatInt($data[$i]["purchaseqty"])?>&nbsp;</td>
		<td><?=FormatInt($data[$i]["salesqty"])?>&nbsp;</td>
		<td><?=FormatInt($data[$i]["adjqty"])?>&nbsp;</td>
		<td <?=$closestyle?>><?=FormatInt($closingqty)?>&nbsp;</td>
	</tr>
<? } ?>
<tfoot>
	<tr>
		<td colspan="3" align="right">Total <?=($_REQUEST["inactive"]?"":"(Excluded inactive items)")?></td>
		<td><?=FormatInt($totalopenqty, 2, ".", ",")?></td>
		<td><?=FormatInt($totalpurchaseqty, 2, ".", ",")?></td>
		<td><?=FormatInt($totalsalesqty, 2, ".", ",")?></td>
		<td><?=FormatInt($totaladjqty, 2, ".", ",")?></td>
		<td><?=FormatInt($totalclosingqty, 2, ".", ",")?></td>
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
window.print();
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
