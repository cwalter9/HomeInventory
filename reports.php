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
	
?>
<? include("header.inc.php"); ?>

<center>
<p>
<div align="left" style='width:250;'>
<ul>
	<li><a href="purchasereport.php">Purchase Report</a>
		<ul>
			<li><a href="purchasereport.php?type=1">By Year</a></li>
			<li><a href="purchasereport.php?type=2">By Month</a></li>
			<li><a href="purchasereport.php?type=3">By Supplier</a></li>
		</ul>
	</li>
	<li><a href="salereport.php">Sales Report</a>
		<ul>
			<li><a href="salereport.php?type=1">By Year</a></li>
			<li><a href="salereport.php?type=2">By Month</a></li>
			<li><a href="salereport.php?type=3">By Customer</a></li>
			<li><a href="salereport.php?type=4">By Item</a></li>
			<li><a href="profitreport.php">Profit Report</a></li>
		</ul>
	</li>
	<li><a href="inventoryreport.php">Inventory Report</a>
	</li>
	<li><a href="transactionreport.php">Transaction Report</a>
		<ul>
			<li><a href="transactionsummaryreport.php">Transaction Summary</a></li>
			<li><a href="transactiondetailreport.php">Transaction Details</a></li>
		</ul>
	</li>
</ul>
</div>
</p>
</center>

<? include("footer.inc.php");?>
