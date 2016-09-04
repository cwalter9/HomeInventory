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


$details = json_decode($_REQUEST["details"], true);

$result = array();
if ($_REQUEST["cmd"] == "add") {
	if ($stockadjid = addRec($_REQUEST, $details)) {
		$result["stockadjid"] = $stockadjid;
		$result["Code"] = "OK";
	} else {
		$result["Code"] = "ERROR";
		$result["Message"] = DBGetError();
	}
}

echo json_encode($result);

function GenStockAdjNo()
{
	return date("YmdHis");
}


function addRec($vars, $details)
{
	global $result;
	
	$stockadjno = GenStockAdjNo();
	$result["StockAdjNo"] = $stockadjno;
	$txdate = $vars["txdate"];
	$adjtype = $vars["adjtype"];
	$remark = $vars["remark"];
	
	$totalqty = 0;
	
	for ($i=0; $i<sizeof($details); $i++) {
		$totalqty += $details[$i]["qty"];
	}
	
	DBBeginTrans();
	
	$sql  = "insert into stockadjheader (";
	$sql .= "stockadjno, txdate, remark, totalqty, adjtype, ";
	$sql .= "icreatedby, dcreatedby) values (";
	$sql .= "'".DBQuote($stockadjno)."',";
	$sql .= "'".DBQuote($txdate)."',";
	$sql .= "'".DBQuote($remark)."',";
	$sql .= "'".DBQuote($totalqty)."',";
	$sql .= "'".DBQuote($adjtype)."',";
	$sql .= "'".CurrentUserId()."',";
	$sql .= "now()";
	$sql .= ")";
	
	if (!DBExecute($sql)) {	
		DBRollBack();		
		return false;
	}
	
	$stockadjid = DBInsertId();
	
	for ($i=0; $i<sizeof($details); $i++) {
		$skus = $details[$i]["SKUs"];
		$seq = $i;
		$itemid = $details[$i]["itemid"];

		for ($j=0; $j<sizeof($skus); $j++) {
			$qty = $skus[$j]["qty"];
			$skuid = $skus[$j]["skuid"];
			
			if ($qty != 0) {
				$sql  = "insert into stockadjdetail (";
				$sql .= "stockadjid, skuid, qty, seq, itemid, ";
				$sql .= "icreatedby, dcreatedby) values (";
				$sql .= "'".DBQuote($stockadjid)."',";
				$sql .= "'".DBQuote($skuid)."',";
				$sql .= "'".DBQuote($qty)."',";
				$sql .= "'".DBQuote($seq)."',";
				$sql .= "'".DBQuote($itemid)."',";
				$sql .= "'".CurrentUserId()."',";
				$sql .= "now()";
				$sql .= ")";

				if (!DBExecute($sql)) {	
					DBRollBack();		
					return false;
				}
				
				// Update Inventroy Table
				$sql  = "select * from inventory where skuid = '".DBQuote($skuid)."' ";
				if (DBFetchData($sql)) {
					$sql  = "update inventory set ";
					$sql .= "qty = qty + '".DBQuote($qty)."', ";
					$sql .= "imodifiedby = '".CurrentUserId()."', ";
					$sql .= "dmodifiedby = now() ";
					$sql .= "where skuid = '".DBQuote($skuid)."' ";
					
					if (!DBExecute($sql)) {	
						DBRollBack();		
						return false;
					}
				} else {
					$sql  = "insert into inventory (";
					$sql .= "skuid, itemid, qty, icreatedby, dcreatedat";
					$sql .= ") values (";
					$sql .= "'".DBQuote($skuid)."',";
					$sql .= "'".DBQuote($itemid)."',";
					$sql .= "'".DBQuote($qty)."', ";
					$sql .= "'".CurrentUserId()."',";
					$sql .= "now()";
					$sql .= ")";

					if (!DBExecute($sql)) {	
						DBRollBack();		
						return false;
					}
				}
				
				// Update Transaction Table
				$sql  = "select * from transactions ";
				$sql .= "where skuid = '".DBQuote($skuid)."' ";
				$sql .= "  and txdate = '".DBQuote($txdate)."' ";
				
				if ($thisRow = DBFetchData($sql)) {
					$sql  = "update transactions set ";
					$sql .= "adjqty = adjqty + '".DBQuote($qty)."', ";
					$sql .= "imodifiedby = '".CurrentUserId()."', ";
					$sql .= "dmodifiedby = now() ";
					$sql .= "where skuid = '".DBQuote($skuid)."' ";
					$sql .= "  and txdate = '".DBQuote($txdate)."' ";
					
					if (!DBExecute($sql)) {	
						DBRollBack();		
						return false;
					}
					
					$closingqty = $thisRow["openqty"] + $thisRow["purchaseqty"] - $thisRow["salesqty"] + $thisRow["adjqty"] + $qty;
				} else {
					
					// Get Last Closing Qty
					$sql  = "select * from transactions ";
					$sql .= "where skuid = '".DBQuote($skuid)."' ";
					$sql .= "  and txdate < '".DBQuote($txdate)."' ";
					$sql .= "order by txdate desc ";
					$sql .= "limit 0, 1";
					
					$openqty = 0;
					if ($lastRow = DBFetchData($sql)) {
						$openqty = $lastRow["openqty"] + $lastRow["purchaseqty"] - $lastRow["salesqty"] + $lastRow["adjqty"];
					}					
					
					$sql  = "insert into transactions (";
					$sql .= "txdate, itemid, skuid, openqty, adjqty, ";
					$sql .= "icreatedby, dcreatedat";
					$sql .= ") values (";
					$sql .= "'".DBQuote($txdate)."',";
					$sql .= "'".DBQuote($itemid)."',";
					$sql .= "'".DBQuote($skuid)."',";
					$sql .= "'".DBQuote($openqty)."',";
					$sql .= "'".DBQuote($qty)."',";
					$sql .= "'".CurrentUserId()."',";
					$sql .= "now()";
					$sql .= ")";
					
					if (!DBExecute($sql)) {	
						DBRollBack();		
						return false;
					}

					$closingqty = $openqty + $qty;
				}
				
				// Re-calcuate OpenQty for Back date transaction
				$sql  = "select * from transactions ";
				$sql .= "where skuid = '".DBQuote($skuid)."' ";
				$sql .= "  and txdate > '".DBQuote($txdate)."' ";
				$sql .= "order by txdate ";
				
				$newTxs = DBFetchAll($sql);
				
				for ($k=0; $k<sizeof($newTxs); $k++) {
					$tx = $newTxs[$k];
					
					$sql  = "update transactions set openqty = '".$closingqty."' ";
					$sql .= "where txid = '".$tx["txid"]."' ";
					if (!DBExecute($sql)) {	
						DBRollBack();		
						return false;
					}
					
					$closingqty = $closingqty + $tx["purchaseqty"] - $tx["salesqty"] + $tx["adjqty"];
				}
				
			}
		}
	}	

	DBCommit();
	
	return $stockadjid;
}
?>