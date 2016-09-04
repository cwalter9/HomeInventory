/* This script and many more are available free online at
The JavaScript Source!! http://javascript.internet.com
Created by: Konstantin Jagello | http://javascript-array.com/ */
var TimeOut         = 300;
var currentMenu     = null;
var currentItem     = null;
var currentIDs       = new Array();
var noClose         = 0;
var closeTimer      = null;

function mclosetime(td) {
  closeTimer = window.setTimeout(closemenu, TimeOut);
  if (td) {
  	td.className = "menuItem";
  }
}

function mcancelclosetime() {
  if(closeTimer) {
    window.clearTimeout(closeTimer);
    closeTimer = null;
  }
}

function openmenu(menuItem, parentId, menuId)
{
  var m  = document.getElementById("menu"+menuId);
  var pm = document.getElementById("menu"+parentId);
  var mm = document.getElementById("menumain");

  mcancelclosetime();
  menuItem.className = "menuitem_hover";

  if (m) {
	  if (parentId == "main") {
		  m.style.left = posLib.getLeft(menuItem) - 3;
//			m.style.top = posLib.getTop(menuItem) - m.clientHeight - 2; 
			m.style.top = posLib.getTop(menuItem) + menuItem.clientHeight + 2; 
			m.style.width = menuItem.clientWidth + 6;
		} else {
		  m.style.left = posLib.getLeft(pm) + pm.clientWidth - 2;
		  if (posLib.ie) {
				m.style.top = posLib.getTop(menuItem) - 2; 
			} else if (posLib.moz) {
				m.style.top = posLib.getTop(menuItem) - 3; 
			} else {
				m.style.top = posLib.getTop(menuItem) - 2; 
			}
			
			if (posLib.getTop(m) + m.clientHeight > posLib.getTop(mm)) {
				m.style.top = posLib.getTop(mm) - m.clientHeight - 2; 
			}
		}
    m.style.visibility='visible';

		while (currentIDs.length > 0 && 
		       currentIDs[(currentIDs.length-1)] != parentId && 
		       currentIDs[(currentIDs.length-1)] != menuId)
		{
			mId = currentIDs.pop();
			menuObj = document.getElementById("menu"+mId);
			
			if (menuObj) {
      	menuObj.style.visibility='hidden';
			}
		}

    currentIDs[currentIDs.length] = menuId;

  } else {
		while (currentIDs.length > 0 && 
		       currentIDs[(currentIDs.length-1)] != parentId && 
		       currentIDs[(currentIDs.length-1)] != menuId)
		{
			mId = currentIDs.pop();
			menuObj = document.getElementById("menu"+mId);
			
			if (menuObj) {
      	menuObj.style.visibility='hidden';
			}
		}
  }
}

function closemenu()
{
	while (currentIDs.length > 0)
	{
		mId = currentIDs.pop();
		menuObj = document.getElementById("menu"+mId);
		
		if (menuObj) {
    	menuObj.style.visibility='hidden';
		}
	}
}

function openlink(URL)
{
	document.location.href = URL;
}

document.onclick = closemenu; 
