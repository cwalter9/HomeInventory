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

if ($_SESSION[SS]["user"]) {
	
if (CurrentUserId() == 1) {
	$menuItems[0][] = array("id"=>1, "name"=>"Users", "link"=>"userlist.php");
	$menuItems[0][] = array("id"=>2, "name"=>"Settings", "link"=>"setting.php");
}
	$menuItems[0][] = array("id"=>3, "name"=>"Categories", "link"=>"catlist.php");
	$menuItems[0][] = array("id"=>4, "name"=>"Products", "link"=>"itemlist.php");
	$menuItems[0][] = array("id"=>5, "name"=>"Purchases", "link"=>"purchaselist.php");
	$menuItems[0][] = array("id"=>6, "name"=>"Sales", "link"=>"salelist.php");
	$menuItems[0][] = array("id"=>7, "name"=>"Adjustments", "link"=>"adjustmentlist.php");
	$menuItems[0][] = array("id"=>8, "name"=>"Inventory", "link"=>"inventory.php");
	$menuItems[0][] = array("id"=>9, "name"=>"Reports", "link"=>"reports.php");
	$menuItems[0][] = array("id"=>10, "name"=>"Logout", "link"=>"logout.php");

	$menuItems[9][] = array("id"=>11, "name"=>"Purchase Report", "link"=>"purchasereport.php");
	$menuItems[9][] = array("id"=>12, "name"=>"Sales Report", "link"=>"salereport.php");
	$menuItems[9][] = array("id"=>13, "name"=>"Inventory Report", "link"=>"inventoryreport.php");
	$menuItems[9][] = array("id"=>14, "name"=>"Transaction Report", "link"=>"transactionreport.php");
	$menuItems[9][] = array("id"=>15, "name"=>"Inactive Items Report", "link"=>"inactiveitemreport.php");


	reset($menuItems);
	
	while(list($pid, $subItems)=each($menuItems))
	{
		if (!$pid) {
			$pid = "main";
			$html .= "<table id='menu".$pid."' border='0' cellspacing='2' cellpadding='2' width='100%'>\n";
			$html .= "<tr>\n";
			if (sizeof($subItems) > 0) {
				$width = number_format(100/sizeof($subItems), 0);
			}
			for ($i=0; $i<sizeof($subItems); $i++) {
				$html .= "<td class='menuitem' width='".$width."%'";
				$html .= "onclick=\"openlink('".$subItems[$i]["link"]."')\" ";
				$html .= "onmouseover=\"openmenu(this, '".$pid."', '".$subItems[$i]["id"]."')\" ";
				$html .= "onmouseout=\"mclosetime(this)\"";
				$html .= "><table border='0' cellspacing='0' cellpadding='0' width='100%'><tr>";
				$html .= "<td align='center' width='100%'>".$subItems[$i]["name"]."</td>";
				if ($menuItems[$subItems[$i]["id"]]) { // has sub-menu
					$html .= "<td><img src=\"images/downarrow.gif\" border=\"0\" align=\"absmiddle\"></td>";
				}
				$html .= "</tr></table>";
				$html .= "</td>\n";
			}
			$html .= "</tr>\n";
			$html .= "</table>\n";
		} else {
			$html .= "<table id='menu".$pid."' border='0' class='submenu' cellspacing='2' cellpadding='2'>\n";
			for ($i=0; $i<sizeof($subItems); $i++) {
				$html .= "<tr><td class='menuitem'";
				$html .= "onclick=\"openlink('".$subItems[$i]["link"]."')\" ";
				$html .= "onmouseover=\"openmenu(this, '".$pid."', '".$subItems[$i]["id"]."')\" ";
				$html .= "onmouseout=\"mclosetime(this)\"";
				$html .= "><table border='0' cellspacing='0' cellpadding='0' width='100%'><tr>";
				$html .= "<td align='center' width='100%' nowrap >".$subItems[$i]["name"]."</td>";
				if ($menuItems[$subItems[$i]["id"]]) { // has sub-menu
					$html .= "<td>&nbsp;<img src=\"images/rightarrow.gif\" border=\"0\" align=\"absmiddle\"></td>";
				}
				$html .= "</tr></table>";
			}
			$html .= "</tr>\n";
			$html .= "</table>\n";
		}
	}
	
	$menuhtml = $html;
	echo $menuhtml;
}
?>
