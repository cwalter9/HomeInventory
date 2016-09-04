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
include("config.inc.php"); ProtectJS(); 
?>

var lastItemNo = "";
var FormSaved = false;

function AjaxGetItem()
{
	var output = new String(ajax.response);	
	var result = JSON.parse(output);

	if (result.Code == "NOT FOUND") {
		alert("Not Found");
	} else if (result.Code != "OK") {
		if (result.Code == 2) {
			alert("Out of Stock");
		} else if (result.Code == 3) {
			alert("Discontinued");
		} else {
			alert("Other error");
		}
	} else {
		if (result.Data) {
			lastItemNo = document.frm1.actualitemno.value;
			SKUGrid.AddItem(result.Data);
		}
	}
}

function clearLastItem()
{
	var f = document.frm1;
	f.actualitemno.value = "";
	lastItemNo = "";
	var label = document.getElementById("ActualItemNoLabel");
	label.innerHTML = "";
}

function ClearForm()
{
	var f = document.frm1;
	f.supplier.value = "";
	f.totalamount.value = "0";
	f.totalqty.value = "0";
	f.itemno.value = "";
	f.unitprice.value = "";
	f.actualitemno.value = "";
	f.pono.value = "";
	
	SKUGrid.Clear();
	lastItemNo = "";
	FormSaved = false;
	f.txdate.focus();
}

function AjaxSave()
{
	try {
		var output = new String(ajax.response);	
		var result = JSON.parse(output);
	
		if (result.Code == "OK") {
			var f = document.frm1;
			f.pono.value = result.PONo;

			if (confirm("Record is saved successfully. P.O. No. = " + result.PONo + ".\nPrint out ?")) {
				var id = result.poid;
				window.open("purchaseview.php?id="+id+"&print=1");
			}

			ClearForm();
		} else {
			alert("Error : " + result.Message);
			FormSaved = false;
		}
	} catch (e) {
		alert(e + ajax.response);
	}
}

function doSave()
{
	if (FormSaved) return;
	
	var form = document.getElementById('frm1');
	
	var msg = "";
	if (form.txdate.value == "") {
		msg += "Please input date.\n";
	}
	if (form.supplier.value == "") {
		msg += "Please input supplier.\n";
	}
	if (SKUGrid.ItemCount() < 1) {
		msg += "Please input items.\n";
	}
	
	if (msg != "") {
		alert(msg);
		return;
	}

	FormSaved = true;
	
	ajax.setVar("cmd", "add");
	ajax.setVar("pono", form.pono.value);
	ajax.setVar("txdate", form.txdate.value);
	ajax.setVar("supplier", form.supplier.value);
	
	ajax.setVar("details", SKUGrid.GetJSONString());
	
	ajax.requestFile = "purchasedetail.ajax.php";
	ajax.method = "post";
	ajax.onCompletion = AjaxSave;
	ajax.runAJAX();
}

function addItem()
{
	var form = document.getElementById('frm1');
	var itemno = form.actualitemno.value;
	
	ajax.setVar("itemno", itemno);
	ajax.requestFile = "ajaxgetitem.php";
	ajax.method = "post";
	ajax.onCompletion = AjaxGetItem;
	ajax.runAJAX();
}

function onItemKeyPress()
{
	var keycode = event.keyCode;
	if (keycode == 13) {
		addItem();
		event.cancelBubble = true;
	}
}

function onItemKeyUp()
{
	if (event.keyCode != 13) {
		var form = document.getElementById('frm1');
		var actual = form.actualitemno;
		var thisItemNo = form.itemno.value;
		var label = document.getElementById("ActualItemNoLabel");

		if (thisItemNo.length < lastItemNo.length)
		{
			thisItemNo = "" + lastItemNo.substring(0, lastItemNo.length - thisItemNo.length) + thisItemNo;
		}
		
		if (label) {
			label.innerHTML = "(" + thisItemNo + ")";
		}
		actual.value = thisItemNo;
	}
}

function setUnitCost()
{
	var f = document.frm1;
	SKUGrid.SetPrice(f.unitprice.value);
}

function doCancel()
{
	document.location.href="purchaselist.php";
}

function focusItemNo()
{
	var f = document.frm1;
	f.itemno.focus();
	f.itemno.select();
	
}
function formKeyUp()
{
	switch (event.keyCode)
	{
		case 27: // ESC
			doCancel();
			break;
		case 37: // Left arrow			
			SKUGrid.FocusLeftCell();
			break;
		case 38: // Up arrow
			if (event.shiftKey) {
				SKUGrid.MovePrevious();
			} else {
				SKUGrid.FocusUpCell();
			}
			break;
		case 39: // Right arrow
			SKUGrid.FocusRightCell();
			break;
		case 40: // Down arrow
			if (event.shiftKey) {
				SKUGrid.MoveNext();
			} else {
				SKUGrid.FocusDownCell();
			}
			break;
		case 113: // F2
			focusItemNo();
			break;
		case 118: // F7
			break;
		case 119: // F8
			break;
		case 120: // F9 Remove
			if (confirm("Are you sure ?")) {
				SKUGrid.RemoveItem();
			}
			break;
		case 123: // F12 Save
			doSave();
			break;
	}
}

function SKUChanged()
{
	var f = document.frm1;
	f.totalamount.value = SKUGrid.TotalAmount;
	f.totalqty.value = SKUGrid.TotalQty;
	f.unitprice.value = SKUGrid.CurrentUnitPrice();
}

function FormInit()
{
	var f = document.frm1;
	f.txdate.focus();
	f.txdate.select();
}

SKUGrid.OnChanged = SKUChanged;
SKUGrid.OnInit = SKUChanged;
SKUGrid.OnRowChanged = SKUChanged;

document.body.onload = FormInit;
document.body.onkeyup= formKeyUp;
