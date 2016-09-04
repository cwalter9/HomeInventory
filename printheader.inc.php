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
?>
<html>
<head>
	<title><?=($pageTitle)?$pageTitle:"Inventory System"?></title>
	<meta http-equiv=Content-Type content="text/html; charset=utf-8">
	<meta http-equiv="Content-Language" content="en-us">
	<meta name="robots" content="all">
	<meta name="Description" content="<?=$pageDescription?>">
	<meta name="Keywords" content="<?=$pageKeywords?>">
	<meta name="author" content="NACL">
	<meta name="Copyright" content="Copyright (c) 2008 NACL">
	<meta name="document-type" content="Public">
	<meta name="document-rating" content="Safe for Kids">
	<meta name="document-distribution" content="Global">
	<meta name="revisit-after" content="7 days">
	
	<style>
	body { margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; }
	* {font-family:arial;}
	.menuitem {border:1px solid #999999; font-size:10pt; color:#999999; background-color:#FFFFFF; padding:2; cursor:default;}
	.menuitem_hover {border:1px solid #999999; font-size:10pt; color:#000000; background-color:#EEEEEE; padding:2; cursor:default;}
	.copyright {font-size:8pt; color:#999999;}
	.content_div {padding:20px; height:expression(document.body.offsetHeight-50); width:expression(document.body.offsetWidth-4); overflow:auto;}
	.menu_div {padding-left:20px; padding-right:10px; height:50px; width:expression(document.body.offsetWidth-4);}
	.functionname {font-size:14pt; color:#333333}
	.actionlink {text-decoration:none; color:#993333}
	.actionlink:hover {text-decoration:none; color:#000000}
	.actionlink:visited {text-decoration:none; color:#993333}
	.toplink {text-decoration:none; color:#666666}
	.toplink :hover {text-decoration:none; color:#000000}
	.toplink :visited {text-decoration:none; color:#993333}
	.tablestyle {border:1px solid #666666; font-size:10pt;}
	.tablestyle thead td {border-bottom:1px solid #666666; filter: progid:DXImageTransform.Microsoft.Gradient(gradientType=0,startColorStr=#999999,endColorStr=#EEEEEE);}
	.tablestyle td {border-bottom:1px solid #DDDDDD;}
	
	.important {color:red;}
	.colorsizetable td {text-align:center;}
	.inputQty {width:100%; text-align:right;}
	.readonly {background-color:#CCC}
	

	.skugrid {table-layout:fixed; height:300px;}
	.skugrid_disabled_cell {background-color:#999;}
	#leftbox {border:1px solid #999999; width:100%; height:100%; overflow:auto;}
	#rightbox {border:1px solid #999999; width:100%; height:100%; overflow:auto;}
	.skutable_size {text-align:center; border-left:1px solid #666666; border-bottom:1px solid #666666; filter: progid:DXImageTransform.Microsoft.Gradient(gradientType=0,startColorStr=#999999,endColorStr=#EEEEEE);}
	.skutable td {vertical-align: top; font-size:10pt; text-align:center; border-left:1px solid #666666; border-bottom:1px solid #666666; height:25px;}
	.skutable thead td {text-align:center; border-left:1px solid #666666; border-bottom:1px solid #666666; filter: progid:DXImageTransform.Microsoft.Gradient(gradientType=0,startColorStr=#999999,endColorStr=#EEEEEE);}
	.skutable input {border:0px; width:100%; text-align:center;}

	.summarytable {border-bottom:1px solid #666666;}
	.summarytable_head td {border-left:1px solid #666666; border-bottom:1px solid #666666; filter: progid:DXImageTransform.Microsoft.Gradient(gradientType=0,startColorStr=#999999,endColorStr=#EEEEEE);}
	.summarytable td {vertical-align: top; font-size:10pt; }
	.summarytable_current {background-color:#FFCCCC; border-bottom:1px solid #666666;}	
	.summarytable_current td {vertical-align: top; font-size:10pt; }
	.summarytable_over {background-color:#CCCCFF; border-bottom:1px solid #666666; cursor:default;}
	.summarytable_over td {vertical-align: top; font-size:10pt; }
	.detailtable {font-size:10pt; }

	.reportdetailtable {vertical-align: top; font-size:10pt; text-align:center; border-right:1px solid #666666; border-top:1px solid #666666; height:25px;}
	.reportdetailtable td {vertical-align: top; font-size:10pt; text-align:center; border-left:1px solid #666666; border-bottom:1px solid #666666; height:25px;}
	.reportdetailtable thead td {text-align:center; border-left:1px solid #666666; border-bottom:1px solid #666666; background-color:#CCCCCC;}

	.reporttable {vertical-align: top; font-size:10pt; border-right:1px solid #666666; border-top:1px solid #666666; height:25px;}
	.reporttable td {vertical-align: top; font-size:10pt; border-left:1px solid #666666; border-bottom:1px solid #666666; height:25px;}
	.reporttable thead td {border-left:1px solid #666666; border-bottom:1px solid #666666; background-color:#CCCCCC;}
	.reporttable tfoot td {border-left:1px solid #666666; border-bottom:1px solid #666666; background-color:#000000; color:#FFFFFF;}
	.reportsubtitle {text-align:center; font-size:14pt; }
	
	.openclose {background-color:#CCCCCC;}

	</style>
	<script type="text/javascript" src="js/debug.js"></script>
	<script type="text/javascript" src="js/common.js"></script>
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" width="700" align="center"><tr><td>

