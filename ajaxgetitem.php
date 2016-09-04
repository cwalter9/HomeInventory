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

/*
  Items[]
    |-itemid
    |-itemname
    |- ...
    |-Colors[i]
    |-Sizes[i]
    |-SKUIdx['size']['color']=idx
    |-SKUs[i]
       |-SKU{Qty,Id}
    
*/
	include("config.inc.php");
	include("image.class.php");

	CheckLogin();
	
	$id = $_REQUEST["id"];
	$cp = $_REQUEST["cp"];
	$itemno = $_REQUEST["itemno"];

	$result = array();
	
	if ($itemno) {
		$sql = "select * from item where itemno = '" . DBQuote($itemno)."' ";
		$record = DBFetchData($sql);
		$id = $record["itemid"];
	} else {
		$sql = "select * from item where itemid = '" . DBQuote($id)."' ";
		$record = DBFetchData($sql);
	}

	$result["Code"] = "";
	if (!$record["itemid"]) {
		$result["Code"] = "NOT FOUND";
	} else if ($record["status"] > 1) {
		$result["Code"] = $record["status"];
	} else {
		$result["Code"] = "OK";
	}

	if ($cp != 1) {
		$record["unitprice"] = $record["unitcost"];
	}
	$record["qty"] = 0;
	$record["amount"] = 0;

	$sql = "select * from sku where itemid = '" . DBQuote($id)."' ";
	$tmp = DBFetchAll($sql);

	$colors = split(",", $record["colors"]);
	$sizes = split(",", $record["sizes"]);
	
	$SKUIdx = array();
	$SKUs = array();
	for ($i=0; $i<sizeof($tmp); $i++) {
		$colors[] = $tmp[$i]["color"];
		$sizes[] = $tmp[$i]["size"];
		$SKUIdx["S".$tmp[$i]["size"]]["C".$tmp[$i]["color"]] = $i;
		$SKUs[] = array("qty"=>"", "skuid"=>$tmp[$i]["skuid"]);
	}
	$colors = RemoveEmpty($colors, "C");
	$sizes = RemoveEmpty($sizes, "S");
		
	$record["Colors"] = $colors;
	$record["Sizes"] = $sizes;
	$record["SKUIdx"] = $SKUIdx;
	$record["SKUs"] = $SKUs;

	$result["Data"] = $record;

	echo json_encode($result);

?>