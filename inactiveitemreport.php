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
		}

		if (!$_REQUEST["period"]) $_REQUEST["period"] = 1;
		$refdate = date("Y-m-d", mktime(0,0,0, date("m") - $_REQUEST["period"], date("d"), date("Y")));

		$sql  = "select b.catid, b.itemid, b.itemno, b.itemname, sum(a.qty) as qty ";
		$sql .= "from inventory a join item b on a.itemid = b.itemid ";
		$sql .= "  left join transactions c on a.skuid = c.skuid and c.txdate > '".$refdate."' ";
		$sql .= "where c.txid is null ";
		if ($sqlw) {
			$sql .= $sqlw;
		}
		$sql .= "group by 1, 2, 3, 4 ";
		$sql .= "order by 1, 2, 3, 4 ";
		$data = DBFetchAll($sql);
		
		if (sizeof($data) > 0) {
			$tmp = array();
			for ($i=0; $i<sizeof($data); $i++) {
				$tmp[] = $data[$i]["itemid"];
			}
			
			$inactiveItemIds = implode(",", $tmp);

			$sql = "select itemid, max(txdate) as txdate ";
			$sql .= "from transactions ";
			$sql .= "where itemid in (".$inactiveItemIds.") ";
			$sql .= "group by itemid ";
			$tmp = DBFetchAll($sql);
			
			for ($i=0; $i<sizeof($tmp); $i++) {
				$inactivePeriods[$tmp[$i]["itemid"]] = DateDiff($tmp[$i]["txdate"]);
			}
		}		

		$sql = "select * from cat order by catname ";
		$allcats = DBFetchAll($sql);
		for ($i=0; $i<sizeof($allcats); $i++) {
			$catnames[$allcats[$i]["catid"]] = $allcats[$i]["catname"];
		}

?>
<? include("header.inc.php"); ?>
<table border="0" cellpadding="2" cellspacing="0" class="detailtable" width="100%">
<tr>
	<td nowrap><div class="functionname">Inactive Items Report</div></td>
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
	<td>Category : <?=$catnames[$_REQUEST["catid"]]?></td>
</tr>
<tr>
	<td>Inactive Period : <?=$_REQUEST["period"]?> Months</td>
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
	<td>Inactive Period : </td>
	<td>
		<select name="period">
<? 
	$periods = array(1, 3, 6, 9, 12, 24, 36, ">36");
	
	for ($i=0; $i<sizeof($periods); $i++) {
?>
		<option value="<?=$periods[$i]?>" <?=($periods[$i] == $_REQUEST["period"])?"selected":""?>><?=$periods[$i]?></option>
<? } ?>
		</select>
		(Months)
	</td>
	<td><input type="submit" name="search" value="Show"></td>
</tr>
</table>
</form>
<? } ?>


<table border="0" cellpadding="5" cellspacing="0" class="reporttable">
<col width='150'/><col width='150'/><col width='300'/><col width='150' align='right'/><col width='150' align='right'/>
<thead>
	<tr>
		<td>Category</td>
		<td>Item No.</td>
		<td>Item Name</td>
		<td>Inactive Period (Days)</td>
		<td>Qty On Hand</td>
	</tr>
</thead>
<? 
	$totalqty = 0; 
	for ($i=0; $i<sizeof($data); $i++) {
		$totalqty += $data[$i]["qty"];
?>
	<tr>
		<td><?=htmlspecialchars($catnames[$data[$i]["catid"]])?>&nbsp;</td>
		<td><?=htmlspecialchars($data[$i]["itemno"])?>&nbsp;</td>
		<td><?=htmlspecialchars($data[$i]["itemname"])?>&nbsp;</td>
		<td><?=number_format($inactivePeriods[$data[$i]["itemid"]], 0, ".", ",")?>&nbsp;</td>
		<td><?=number_format($data[$i]["qty"], 2, ".", ",")?>&nbsp;</td>
	</tr>
<? } ?>
<tfoot>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>Total</td>
		<td><?=number_format($totalqty, 2, ".", ",")?></td>
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

<? include("footer.inc.php");?>
